<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

global $APPLICATION;

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'ranx.landing');

if (!$USER->isAdmin()) {
    $APPLICATION->authForm('Nope');
}

Loader::includeModule(ADMIN_MODULE_NAME);
Loader::includeModule('iblock');
$request = Application::getInstance()->getContext()->getRequest();
$GLOBALS['APPLICATION']->SetTitle(Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_TITLE'));
$GLOBALS['APPLICATION']->SetAdditionalCss('/bitrix/css/'.ADMIN_MODULE_NAME.'/menu.css');
CJSCore::Init('jquery3');


$by = 'id';
$sort = 'asc';

$arSites = [];
$dbRes = CSite::GetList($by, $sort, ['ACTIVE'=>'Y']);
while($res = $dbRes->Fetch()){
    $arSites[] = $res;
}

$arTabs = [];
$bShowGenerate = false;
foreach($arSites as $key => $arSite){
    $arItems = [];
    $arSite['DIR'] = str_replace('//', '/', '/'.$arSite['DIR']);
    if(!strlen($arSite['DOC_ROOT'])){
        $arSite['DOC_ROOT'] = $_SERVER['DOCUMENT_ROOT'];
    }
    $arSite['DOC_ROOT'] = str_replace('//', '/', $arSite['DOC_ROOT'].'/');
    $arSite['DIR_FORMAT'] = str_replace('//', '/', $arSite['DOC_ROOT'].$arSite['DIR']);
    $optionsSiteID = $arSite['ID'];

    $rsItems = CIBlockElement::GetList(
        ['NAME' => 'ASC'],
        ['ACTIVE' => 'Y', 'LID' => $optionsSiteID, 'IBLOCK_CODE' => 'ranx_landing_regions', '!PROPERTY_MAIN_DOMAIN' => false],
        false,
        false,
        ['ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_MAIN_DOMAIN']
    );
    while($arItem = $rsItems->Fetch())
    {
        $arItems[] = $arItem;
    }

    $bGenerate = ((Option::get(ADMIN_MODULE_NAME, 'REGION_TYPE', 'ONE_DOMAIN', $optionsSiteID) === 'SUBDOMAINS')
                && Option::get(ADMIN_MODULE_NAME, 'USE_REGION', 'N', $optionsSiteID));
    if($bGenerate)
        $bShowGenerate = true;

    $arTabs[] = [
        'DIV' => 'edit'.($key+1),
        'TAB' => Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_SITE_TITLE',
            ['#SITE_NAME#' => $arSite['NAME'], '#SITE_ID#' => $arSite['ID']]),
        'ICON' => 'settings',
        'PAGE_TYPE' => 'site_settings',
        'SITE_ID' => $arSite['ID'],
        'SITE_DIR' => $arSite['DIR'],
        'SITE_DOC_ROOT' => $arSite['DOC_ROOT'],
        'SITE_DIR_FORMAT' => $arSite['DIR_FORMAT'],
        'ITEMS' => $arItems,
        'HAS_REGIONS' => $bGenerate,
    ];
}

$tabControl = new CAdminTabControl('tabControl', $arTabs);

if($request->isPost() && check_bitrix_sessid()) {
    $siteId = $request->getPost('SITE_ID');
    if($request->getPost('Apply') || $request->getPost('ID')) {
        foreach($arTabs as $key => $arTab) {
            if ($arTab['SITE_ID'] != $siteId || empty($arTab['ITEMS'])) {
                continue;
            }

            $fileAccess = $arTab['SITE_DIR_FORMAT'].'.htaccess';
            $file = file_get_contents($fileAccess);

            if(strpos($file, 'RANX_ROBOTS') === false && strpos($file, 'ASPRO_ROBOTS') === false &&
                strpos($file, 'RewriteEngine On') !== false) {
                if(!file_exists($fileAccess.'_back'.time()))
                    copy($fileAccess, $fileAccess.'_back'.time());

                $file = str_replace('RewriteEngine On', "RewriteEngine On
                \r\n\t# RANX_ROBOTS Serve robots.txt with robots.php only if the latter exists
\tRewriteCond %{REQUEST_FILENAME} robots.txt
\tRewriteCond %{DOCUMENT_ROOT}/robots.php -f
\tRewriteRule ^(.*)$ /robots.php [L]", $file);
                file_put_contents($fileAccess, $file);
            }

            $elementId = $request->getPost('ID');
            foreach ($arTab['ITEMS'] as $arItem) {
                if (!empty($elementId) && $elementId != $arItem['ID']) {
                    continue;
                }

                $siteDir = $arTab['SITE_DIR_FORMAT'];
                if($arItem['PROPERTY_MAIN_DOMAIN_VALUE'])
                {
                    if (!file_exists($siteDir.'robots.txt')) {
                        continue;
                    }
                    if (!file_exists($siteDir.'robots.php')) {
                        if (!file_exists($arTab['SITE_DOC_ROOT'].'bitrix/wizards/ranx/landing/site/public/ru/robots.php')) {
                            continue;
                        }
                        copy($arTab['SITE_DOC_ROOT'].'bitrix/wizards/ranx/landing/site/public/ru/robots.php', $siteDir.'robots.php');
                    }

                    if (!file_exists($siteDir.'ranx_regions')) {
                        mkdir($siteDir.'ranx_regions');
                    }
                    if (!file_exists($siteDir.'ranx_regions/robots')) {
                        mkdir($siteDir.'ranx_regions/robots');
                    }

                    copy($siteDir.'robots.txt', $siteDir.'ranx_regions/robots/robots_'.$arItem['PROPERTY_MAIN_DOMAIN_VALUE'].'.txt');
                    $arFile = file($siteDir.'ranx_regions/robots/robots_'.$arItem['PROPERTY_MAIN_DOMAIN_VALUE'].'.txt');
                    foreach($arFile as $key => $str)
                    {
                        if(strpos($str, 'Host' ) !== false)
                            $arFile[$key] = 'Host: '.(\CMain::isHTTPS() ? 'https://' : 'http://').$arItem['PROPERTY_MAIN_DOMAIN_VALUE']."\r\n";
                        if(strpos($str, 'Sitemap' ) !== false)
                            $arFile[$key] = 'Sitemap: '.(\CMain::isHTTPS() ? 'https://' : 'http://').$arItem['PROPERTY_MAIN_DOMAIN_VALUE'].'/sitemap.xml'."\r\n";
                    }
                    $strr = implode('', $arFile);
                    file_put_contents($siteDir.'ranx_regions/robots/robots_'.$arItem['PROPERTY_MAIN_DOMAIN_VALUE'].'.txt', $strr);
                }
            }
        }
    }

    $APPLICATION->RestartBuffer();
}
?>

<?if(empty($arTabs)):?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_NO_SITE')?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
<?else:?>
    <?if($bShowGenerate):?>
        <div class="adm-info-message"><?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_INFO');?></div>
        <br>
        <br>
    <?endif;?>
    <?$tabControl->Begin();?>
    <?$bShowBtn = true;?>
    <form method="post" class="max_options" enctype="multipart/form-data" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
        <?=bitrix_sessid_post();?>
        <input type="hidden" name="SITE_ID">
        <?
        foreach($arTabs as $key => $arTab)
        {
            $tabControl->BeginNextTab();
            if($arTab['SITE_ID'])
            {
                $optionsSiteID = $arTab['SITE_ID'];?>
                <div class="tab-site_id" style="display: none;" data-site_id="<?=$optionsSiteID?>"></div>
                <tr>
                    <td>
                        <?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_MAIN');?>
                    </td>
                    <td style="width:50%;">
                        <?$href = "javascript: new BX.CAdminDialog({'content_url':'/bitrix/admin/public_file_edit.php?site=".$optionsSiteID."&bxpublic=Y&from=includefile&path=".$arTab["SITE_DIR"]."robots.txt&lang=".LANGUAGE_ID."&noeditor=Y&template=include_area.php&subdialog=Y','width':'1009','height':'503'}).Show();";?>
                        <a class="adm-btn" href="<?=$href?>" name="edit" title="<?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_EDIT')?>"><?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_EDIT')?></a>

                    </td>
                </tr>
                <?if($arTab['HAS_REGIONS']):?>
                    <tr class="heading"><td colspan="2"><?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_DOMAINS')?></td></tr>

                    <?if($arTab['ITEMS'])
                    {
                        foreach($arTab['ITEMS'] as $arItem):?>
                            <tr>
                                <td>
                                    <?=$arItem['NAME'].' ('.$arItem['PROPERTY_MAIN_DOMAIN_VALUE'].')';?>
                                </td>
                                <td style="width:50%;">
                                    <?$href = "javascript: new BX.CAdminDialog({'content_url':'/bitrix/admin/public_file_edit.php?site=".$optionsSiteID."&bxpublic=Y&from=includefile&path=".$arTab["SITE_DIR"]."ranx_regions/robots/robots_".$arItem["PROPERTY_MAIN_DOMAIN_VALUE"].".txt&lang=".LANGUAGE_ID."&noeditor=Y&template=include_area.php&subdialog=Y','width':'1009','height':'503'}).Show();"; ?>
                                    <a class="adm-btn" href="<?=$href?>" name="edit" title="<?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_EDIT')?>"><?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_EDIT')?></a>
                                    <input type="button" name="generate" data-element_id="<?=$arItem['ID'];?>" data-site_id="<?=$optionsSiteID?>" class="submit-btn adm-btn-save" value="<?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_SHORT')?>" title="<?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_SHORT')?>">
                                    <?$href = ($request->isHttps() ? 'https://' : 'http://').$arItem['PROPERTY_MAIN_DOMAIN_VALUE'].'/robots.txt';?>
                                    <a href="<?=$href;?>" target="_blank"><?=$href;?></a>
                                </td>
                            </tr>
                        <?endforeach;?>
                    <?}?>
                <?else:?>
                    <tr>
                        <td style="width:100%;text-align:center;" colspan="2">
                            <div class="adm-info-message"><?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_ERROR');?></div>
                        </td>
                    </tr>
                <?endif;?>
            <?}
        }?>
        <?
        if($request->isPost() && strlen($Update.$Apply.$RestoreDefaults) && check_bitrix_sessid())
        {
            if(strlen($Update) && strlen($request->get('back_url_settings')))
                LocalRedirect($request->get('back_url_settings'));
            else
                LocalRedirect($APPLICATION->GetCurPage().'?mid='.urlencode($mid).'&lang='.urlencode(LANGUAGE_ID).'&back_url_settings='.urlencode($request->get('back_url_settings')).'&'.$tabControl->ActiveTabParam());
        }?>
        <?$tabControl->Buttons();?>
        <?if($bShowGenerate):?>
            <input type="submit" name="Apply" class="submit-btn adm-btn-save" value="<?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_REPLACE')?>" title="<?=Loc::getMessage('RX_LANDING_GENERATE_ROBOTS_REPLACE')?>">
        <?endif;?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('input[name=generate]').on('click', function(){
                    var _this = $(this);
                    _this.attr('disabled', 'disabled');
                    $.ajax({
                        type: 'POST',
                        dataType: 'html',
                        data: {'sessid': $('input[name=sessid]').val(), 'ID': _this.data('element_id'), 'SITE_ID': _this.data('site_id')},
                        success: function(html){
                            _this.removeAttr('disabled');
                        },
                        error: function(data){
                            window.console&&console.log(data);
                        }
                    });
                });

                $('input[name=Apply]').on('click', function(){
                    const activeTabName = $('#tabControl_active_tab').val();
                    const siteId = $('#' + activeTabName).find('.tab-site_id').data('site_id');
                    $('input[name=SITE_ID]').val(siteId);
                });

                $(document).on('click', '#btn_popup_save', function () {
                   $('#wait_window_div').remove();
                });
            });
        </script>
    </form>
    <?$tabControl->End();?>
<?endif;?>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');?>
