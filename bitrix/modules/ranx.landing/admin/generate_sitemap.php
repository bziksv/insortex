<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

global $APPLICATION;

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'ranx.landing');

if (!$USER->isAdmin()) {
    $APPLICATION->authForm('Nope');
}

Loader::includeModule(ADMIN_MODULE_NAME);
Loader::includeModule('iblock');
$request = Application::getInstance()->getContext()->getRequest();
$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".ADMIN_MODULE_NAME."/style.css");
$GLOBALS['APPLICATION']->SetTitle(Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_TITLE'));
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
    $siteDir = str_replace('//', '/', $arSite['DOC_ROOT'].$arSite['DIR']);
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
        'TAB' => Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_SITE_TITLE',
            ['#SITE_NAME#' => $arSite['NAME'], '#SITE_ID#' => $arSite['ID']]),
        'ICON' => 'settings',
        'PAGE_TYPE' => 'site_settings',
        'SITE_ID' => $arSite['ID'],
        'SITE_DIR' => $arSite['DIR'],
        'SITE_DIR_FORMAT' => $siteDir,
        'HAS_REGIONS' => $bGenerate,
        'ITEMS' => $arItems,
        'OPTIONS' => [
            'SITEMAP_URL' => [
                'TITLE' => Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_URL_TITLE'),
                'DEFAULT' => ($arSite['SERVER_NAME'] ? $arSite['SERVER_NAME'] : $_SERVER['SERVER_NAME']),
                'TYPE' => 'text',
                'REQUIRED' => 'Y',
            ],
            'SITEMAP_NAME' => [
                'TITLE' => Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_NAME_TITLE'),
                'DEFAULT' => 'sitemap.xml',
                'TYPE' => 'text',
                'REQUIRED' => 'Y',
            ],
        ],
    ];
}

$tabControl = new CAdminTabControl('tabControl', $arTabs);

$arErrors = $arOK = [];
if($request->isPost() && check_bitrix_sessid())
{
    if($request->getPost('Generate'))
    {
        foreach($arTabs as $key => $arTab)
        {
            $optionsSiteID = $arTab['SITE_ID'];
            if(isset($arTab['OPTIONS']) && $arTab['OPTIONS'])
            {
                foreach($arTab['OPTIONS'] as $optionCode => $arOption)
                {
                    if(isset($arOption['REQUIRED']) && $arOption['REQUIRED'] == 'Y')
                    {
                        if(!$request->getPost($optionCode.'_'.$optionsSiteID))
                            $arErrors[$optionsSiteID][] = Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_FIELD_NO_VALUE',
                                ['#FIELD#' => Loc::getMessage('RX_LANDING_GENERATE_'.$optionCode.'_TITLE'), '#SITE_ID#' => $optionsSiteID]);
                    }
                    if($request->getPost($optionCode.'_'.$optionsSiteID))
                    {
                        Option::set(ADMIN_MODULE_NAME, $optionCode, $_POST[$optionCode."_".$optionsSiteID], $optionsSiteID);
                    }
                }
                $siteMapName =  Option::get(ADMIN_MODULE_NAME, 'SITEMAP_NAME', $arTab['OPTIONS']['SITEMAP_NAME']['DEFAULT'], $optionsSiteID);
                $siteMapUrl =   Option::get(ADMIN_MODULE_NAME, 'SITEMAP_URL',  $arTab['OPTIONS']['SITEMAP_URL']['DEFAULT'], $optionsSiteID);
                $bExistSiteMap = (file_exists($arTab['SITE_DIR_FORMAT'].$siteMapName));
                if($arTab['HAS_REGIONS'])
                {
                    if(!$bExistSiteMap)
                        $arErrors[$optionsSiteID][] = Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_FILENAME_FULL',
                            ['#FILE#' => $siteMapName, '#SITE_ID#' => $optionsSiteID]);
                }

                if(!$arErrors[$optionsSiteID])
                {
                    if($arTab['ITEMS'])
                    {
                        $docRoot = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/');
                        if (!file_exists($arTab['SITE_DIR_FORMAT'].'sitemap.php')) {
                            if (!file_exists($docRoot.'bitrix/wizards/ranx/landing/site/public/ru/sitemap.php')) {
                                continue;
                            }
                            copy($docRoot.'bitrix/wizards/ranx/landing/site/public/ru/sitemap.php', $arTab['SITE_DIR_FORMAT'].'sitemap.php');
                        }

                        $arName = explode('.xml', $siteMapName);
                        $siteMapNameTmp = reset($arName);

                        $arFiles = [];
                        foreach(glob($arTab['SITE_DIR_FORMAT'].$siteMapNameTmp.'*.xml', 0) as $dir){
                            $dir = str_replace($arTab['SITE_DIR_FORMAT'], '', basename($dir));
                            $arFiles[] = $dir;
                        }

                        if($arFiles)
                        {
                            foreach($arFiles as $xmlfile)
                            {
                                $arName = [];
                                $siteMapNameTmp = '';

                                $arName = explode('.xml', $xmlfile);
                                $siteMapNameTmp = reset($arName);
                                if($siteMapNameTmp)
                                {
                                    foreach($arTab['ITEMS'] as $arItem)
                                    {
                                        if(!file_exists($arTab['SITE_DIR_FORMAT'].$siteMapNameTmp.'.php'))
                                        {
                                            CopyDirFiles($arTab['SITE_DIR_FORMAT'].'sitemap.php', $arTab['SITE_DIR_FORMAT'].$siteMapNameTmp.'.php', true, true);
                                            $file = file_get_contents($arTab['SITE_DIR_FORMAT'].$siteMapNameTmp.'.php');
                                            $file = str_replace(['sitemap_', 'sitemap.'], [$siteMapNameTmp.'_', $siteMapNameTmp.'.'], $file);
                                            file_put_contents($arTab['SITE_DIR_FORMAT'].$siteMapNameTmp.'.php', $file);
                                        }

                                        if (!file_exists($arTab['SITE_DIR_FORMAT'].'ranx_regions')) {
                                            mkdir($arTab['SITE_DIR_FORMAT'].'ranx_regions');
                                        }
                                        if (!file_exists($arTab['SITE_DIR_FORMAT'].'ranx_regions/sitemap')) {
                                            mkdir($arTab['SITE_DIR_FORMAT'].'ranx_regions/sitemap');
                                        }
                                        CopyDirFiles($arTab['SITE_DIR_FORMAT'].$xmlfile, $arTab['SITE_DIR_FORMAT'].'ranx_regions/sitemap/'.$siteMapNameTmp.'_'.$arItem['PROPERTY_MAIN_DOMAIN_VALUE'].'.xml', true, true);

                                        $file = file_get_contents($arTab['SITE_DIR_FORMAT'].'ranx_regions/sitemap/'.$siteMapNameTmp.'_'.$arItem["PROPERTY_MAIN_DOMAIN_VALUE"].'.xml');
                                        $file = str_replace($siteMapUrl, $arItem['PROPERTY_MAIN_DOMAIN_VALUE'], $file);
                                        file_put_contents($arTab['SITE_DIR_FORMAT'].'ranx_regions/sitemap/'.$siteMapNameTmp.'_'.$arItem['PROPERTY_MAIN_DOMAIN_VALUE'].'.xml', $file);

                                        $fileAccess = $arTab['SITE_DIR_FORMAT'].'.htaccess';
                                        $file = file_get_contents($fileAccess);

                                        if(strpos($file, 'RANX_SITEMAP_'.$siteMapNameTmp.' ' ) === false &&
                                           strpos($file, 'ASPRO_SITEMAP_'.$siteMapNameTmp.' ' ) === false &&
                                           strpos($file, 'RewriteEngine On') !== false)
                                        {
                                            if(!file_exists($fileAccess.'_back'.time()))
                                                copy($fileAccess, $fileAccess.'_back'.time());

                                            $file = str_replace('RewriteEngine On', "RewriteEngine On
                                            \r\n\t# RANX_SITEMAP_".$siteMapNameTmp." Serve sitemap.xml with sitemap.php only if the latter exists
\tRewriteCond %{REQUEST_FILENAME} ".$siteMapNameTmp.".xml
\tRewriteCond %{DOCUMENT_ROOT}/".$siteMapNameTmp.".php -f
\tRewriteRule ^(.*)$ /".$siteMapNameTmp.".php [L]", $file);
                                            file_put_contents($fileAccess, $file);
                                        }
                                    }
                                }
                            }
                            $arOK[$optionsSiteID][] = Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_FILENAME_GENERATE',
                                ['#FILE#' => $siteMapName, '#SITE_ID#' => $optionsSiteID]);
                        }
                    }
                }
            }
        }
    }
    if(!$arErrors && !$arOK)
        $APPLICATION->RestartBuffer();
}

if(!empty($arErrors))
{
    foreach($arErrors as $siteID => $arError)
        CAdminMessage::ShowMessage(join("\n", $arError));
}

if(!empty($arOK))
{
    foreach($arOK as $siteID => $arError)
        CAdminMessage::ShowMessage(['MESSAGE' => join("\n", $arError), 'TYPE' => 'OK']);
}
?>

<?if(!count($arTabs)):?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?=Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_NO_SITE')?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
<?else:?>
    <?if($bShowGenerate):?>
        <div class="adm-info-message"><?=Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_INFO');?></div>
        <br>
        <br>
    <?endif;?>
    <?$tabControl->Begin();?>
    <form method="post" class="max_options" enctype="multipart/form-data" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
        <?=bitrix_sessid_post();?>
        <?
        foreach($arTabs as $key => $arTab)
        {
            $tabControl->BeginNextTab();
            if($arTab['SITE_ID'])
            {
                $optionsSiteID = $arTab['SITE_ID'];?>
                <?if(isset($arTab['OPTIONS']) && $arTab['OPTIONS']):?>
                <tr class="heading"><td colspan="2"><?=Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_CONFIG')?></td></tr>
                <?foreach($arTab['OPTIONS'] as $optionCode => $arOption):?>
                    <?$val = Option::get(ADMIN_MODULE_NAME, $optionCode, $arOption['DEFAULT'], $optionsSiteID);?>
                    <tr>
                        <td>
                            <?=$arOption['TITLE'];?>
                        </td>
                        <td style="width:50%;">
                            <input type="<?=$arOption['TYPE'];?>" size="" maxlength="255" value="<?=$val;?>" name="<?=$optionCode;?>_<?=$optionsSiteID;?>">
                        </td>
                    </tr>
                <?endforeach;?>
            <?endif;?>
                <?
                $siteMapName =  Option::get(ADMIN_MODULE_NAME, 'SITEMAP_NAME', $arTab['OPTIONS']['SITEMAP_NAME']['DEFAULT'], $optionsSiteID);
                $bExistSiteMap = (file_exists($arTab['SITE_DIR_FORMAT'].$siteMapName));
                if(!$bExistSiteMap)
                {?>
                    <td><?=Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_FILENAME', ['#FILE#' => $siteMapName])?></td>
                    <td><?=Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_NOT_EXISTS')?></td>
                <?}?>
                <?
                if($arTab['HAS_REGIONS'])
                {
                    if($arTab['ITEMS'])
                    {
                        foreach($arTab['ITEMS'] as $arItem):?>
                            <tr>
                                <td><?=Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_DOMAIN', ['#DOMAIN#' => $arItem['PROPERTY_MAIN_DOMAIN_VALUE']])?></td>
                                <?$href = ($request->isHttps() ? 'https://' : 'http://').$arItem['PROPERTY_MAIN_DOMAIN_VALUE'].'/'.$siteMapName;?>
                                <td style="width:50%;"><a href="<?=$href;?>" target="_blank"><?=$href;?></a></td>
                            </tr>
                        <?endforeach;
                    }
                }
                else
                {?>
                    <tr>
                        <td style="width:100%;text-align:center;" colspan="2">
                            <div class="adm-info-message"><?=Loc::getMessage('RX_LANDING_GENERATE_SITEMAP_ERROR');?></div>
                        </td>
                    </tr>
                <?}?>
            <?}
        }?>
        <?
        if($request->isPost() && strlen($Generate.$Apply.$RestoreDefaults) && check_bitrix_sessid())
        {
            if(strlen($Update) && strlen($_REQUEST['back_url_settings']))
                LocalRedirect($_REQUEST['back_url_settings']);
            elseif(!$arErrors)
                LocalRedirect($APPLICATION->GetCurPage().'?mid='.urlencode($mid).'&lang='.urlencode(LANGUAGE_ID).'&back_url_settings='.urlencode($_REQUEST['back_url_settings']).'&'.$tabControl->ActiveTabParam());
        }?>
        <?$tabControl->Buttons();?>
        <?if($bShowGenerate):?>
            <input type="submit" name="Generate" class="submit-btn adm-btn-save" value="<?=Loc::getMessage('RX_LANDING_GENERATE_SITEMAP')?>" title="<?=Loc::getMessage('RX_LANDING_GENERATE_SITEMAP')?>">
        <?endif;?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('input[name=generate]').on('click', function(){
                    var _this = $(this);
                    _this.attr('disabled', 'disabled');
                    $.ajax({
                        type: 'POST',
                        dataType: 'html',
                        data: {'sessid': $('input[name=sessid]').val(), 'ID': _this.data('element_id')},
                        success: function(html){
                            _this.removeAttr('disabled');
                        },
                        error: function(data){
                            window.console&&console.log(data);
                        }
                    });
                })
            });
        </script>
    </form>
    <?$tabControl->End();?>
<?endif;?>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');?>
