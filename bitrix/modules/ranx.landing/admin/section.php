<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

use Bitrix\Iblock;
use Bitrix\Main\Loader;
use Ranx\Landing\Config;
use Bitrix\Main\Web\Json;
use Ranx\Landing\Helpers;
use Bitrix\Main\Application;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Section\Manager as SectionManager;


defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'ranx.landing');

global $USER, $APPLICATION;

CJSCore::Init(['jquery3', 'translit']);
Loader::includeModule(ADMIN_MODULE_NAME);
Loader::includeModule('iblock');

$converter = \CBXPunycode::GetConverter();
$request = Application::getInstance()->getContext()->getRequest();

if (!Helpers\Iblock::isModuleTypeExists()) {
    CAdminMessage::showMessage(Loc::getMessage('RX_LANDING_SECTION_IBLOCK_TYPE_DOESNT_EXIST'));
    echo Loc::getMessage('RX_LANDING_SECTION_IBLOCK_TYPE_DOESNT_EXIST_NOTE');
    return;
}

$bAddNew = false;
$sectionId = $request->get('SECTION_ID');
if (intval($sectionId) <= 0) {
    $bAddNew = true;
}
if (!$bAddNew) {
    $arSection = SectionTable::getByPrimary($sectionId)->fetchObject();

    if (empty($arSection)) {
        unset($sectionId);

        $arSection = [];
        $bAddNew = true;
    }
}

if (!$USER->CanDoOperation('rx_landing_section_edit')) {
    $APPLICATION->authForm('Access denied');
}

$arMap = SectionTable::getMap();
$arTypes = SectionTable::getTypes();
$arRootModes = SectionTable::getRootModes();

$arIblocks = [];
$iblocks = Iblock\IblockTable::getList([
    'filter' => [
        'ACTIVE' => 'Y',
        'IBLOCK_TYPE_ID' => 'ranx_landing',
        'CODE' => 'ranx_landing_list_%',
    ],
])->fetchAll();
foreach ($iblocks as $iblock) {
    $arIblocks[$iblock['ID']] = '[' . $iblock['ID'] . '] ' . $iblock['NAME'];
}

if ($bAddNew) {
    $iblock = Iblock\IblockTable::getList([
        'filter' => [
            'IBLOCK_TYPE_ID' => 'ranx_landing',
            'CODE' => 'ranx_landing_blocks',
        ],
    ])->fetch();
    $iblockSites = Iblock\IblockSiteTable::getList([
        'filter' => [
            'IBLOCK_ID' => $iblock['ID'],
        ],
    ])->fetchAll();

    $arSites = \Bitrix\Main\SiteTable::getList([
        'filter' => [
            'ACTIVE' => 'Y',
            'LID' => array_column($iblockSites, 'SITE_ID'), // get only these sites
        ],
    ])->fetchAll();
}

// delete section
if (!$bAddNew && !empty($arSection['ID']) && $request->get('del') == 'Y') {
    SectionManager::delete($arSection['ID']);

    CAdminMessage::showMessage([
        'MESSAGE' => Loc::getMessage('RX_LANDING_SECTION_DELETED'),
        'TYPE'    => 'OK',
    ]);

    LocalRedirect(sprintf('%s?lang=%s', $request->getRequestedPage(), LANGUAGE_ID));
}

if ($request->getPost('save') && $request->isPost() && check_bitrix_sessid()) {
    try {
        $arNewFields = [];
        $arOptions = [
            'PATH_FORCE_REPLACE' => !empty($request->getPost('PATH_FORCE_REPLACE'))
        ];
        foreach (array_keys($arMap) as $field) {
            $value = $request->getPost($field);

            if ($bAddNew && $arMap[$field]->isRequired() || $arSection[$field] != $value) {
                $arNewFields[$field] = $value;
            }
        }

        if ($bAddNew) {
            $sectionId = SectionManager::add($arNewFields, $arOptions);
            Config::enterEditMode();

            if (!empty($request->getPost('ADD_TO_MENU'))) {
                Helpers\Menu::append($sectionId);
            }

            LocalRedirect(sprintf('%s?SECTION_ID=%d&mid=%s&lang=%s', $request->getRequestedPage(), $sectionId, urlencode($mid), LANGUAGE_ID));
        } else {
            SectionManager::update($sectionId, $arNewFields, $arOptions);
            $arSection = SectionTable::getByPrimary($sectionId)->fetchObject();
        }

        CAdminMessage::showMessage([
            'MESSAGE' => Loc::getMessage('RX_LANDING_SECTION_SAVED'),
            'TYPE'    => 'OK',
        ]);
    } catch (Exception $e) {
        CAdminMessage::showMessage($e->getMessage());
    }
}

if (!$bAddNew) {
    $arSite = \Bitrix\Main\SiteTable::getList([
        'filter' => [
            'LID' => $arSection['SITE_ID'],
        ],
    ])->fetch();
}

if ($bAddNew) {
    $GLOBALS['APPLICATION']->SetTitle(Loc::getMessage('RX_LANDING_SECTION_ADD_NEW_TITLE'));
} else {
    $GLOBALS['APPLICATION']->SetTitle(htmlspecialcharsBack($arSection['TITLE']) . ' (' . $arSection['PATH'] . ')');
}
?>

<?if(!$bAddNew):?>
    <?= BeginNote(); ?>
        <? $protocol = $request->isHttps() ? 'https://' : 'http://'; ?>
        <? $host = !empty($arSite['SERVER_NAME']) ? $protocol.$arSite['SERVER_NAME'] : ''; ?>
        <?= Loc::getMessage('RX_LANDING_SECTION_NOTE_GO_PUBLIC', [
                '#LINK_TEXT#' => ($arSection['DOMAIN'] ? $converter->Decode($arSection['DOMAIN']) : $arSection['PATH']),
                '#LINK#' => ($arSection['DOMAIN'] ? $protocol.$arSection['DOMAIN'] : $host.$arSection['PATH']),
    ]); ?>
    <?= EndNote(); ?>
<?endif?>

<?php
// TAB CONTROL
$tabControl = new CAdminTabControl('tabControl', [
    [
        'DIV' => 'edit1',
        'TAB' => Loc::getMessage('RX_LANDING_SECTION_TAB_TITLE'),
        'TITLE' => Loc::getMessage('RX_LANDING_SECTION_TAB_TITLE'),
    ],
]);
$tabControl->begin();
?>

<form method="POST" action="<?=sprintf('%s?SECTION_ID=%d&mid=%s&lang=%s', $request->getRequestedPage(), $sectionId, urlencode($mid), LANGUAGE_ID)?>">
    <?php
    echo bitrix_sessid_post();
    $tabControl->beginNextTab();
    ?>

    <tr>
        <td class="adm-detail-content-cell-l" style="width: 50%;">
            <label><?=$arMap['SITE_ID']->getTitle()?>:</label>
        </td>
        <td class="adm-detail-content-cell-r" style="width: 50%;">
            <?if($bAddNew):
                $selectedSiteId = $request->getPost('SITE_ID');
                ?>
                <select name="SITE_ID">

                    <?foreach($arSites as $site):?>
                    <option value="<?=$site['LID']?>" <?if($selectedSiteId && $selectedSiteId == $site['LID']):?>selected<?endif?>>[<?=$site['LID']?>] <?=$site['NAME']?></option>
                    <?endforeach?>

                </select>
            <?else:?>
                <?=$arSection['SITE_ID']?>
            <?endif?>
        </td>
    </tr>
    <tr>
        <td class="adm-detail-content-cell-l" style="width: 50%;">
            <label><?=$arMap['TYPE']->getTitle()?>:</label>
        </td>
        <td class="adm-detail-content-cell-r" style="width: 50%;">
            <?php
            $selectedType = $request->getPost('TYPE');
            ?>

            <?if($bAddNew):?>

                <select name="TYPE">

                    <?foreach($arTypes as $typeId => $typeName):
                        $isSelected = $selectedType == $typeId;
                    ?>
                        <option value="<?=$typeId?>" <?if($isSelected):?>selected<?endif?>><?=$typeName?></option>
                    <?endforeach?>

                </select>

            <?else:?>
                <input type="hidden" name="TYPE" value="<?=$arSection['TYPE']?>">
                <?=$arTypes[$arSection['TYPE']]?>
            <?endif?>
        </td>
    </tr>
    <? $selectedType = $bAddNew ? $request->getPost('TYPE') : $arSection['TYPE']; ?>
    <? $isSectionType = SectionManager::isSectionType($selectedType ?? array_keys($arTypes)[0]); ?>
    <tr <?if(!$isSectionType):?>style="display: none;"<?endif?>>
        <td class="adm-detail-content-cell-l" style="width: 50%;">
            <label><?=$arMap['ROOT_MODE']->getTitle()?>:</label>
        </td>
        <td class="adm-detail-content-cell-r" style="width: 50%;">
            <? $selectedRootMode = $bAddNew  ? $request->getPost('ROOT_MODE') : $arSection['ROOT_MODE']; ?>
            <select name="ROOT_MODE" <?if(!$isSectionType):?>style="display: none;" disabled<?endif?>>
                <?foreach($arRootModes as $rootModeId => $rootModeName):?>
                    <? if ($rootModeId == SectionTable::ROOT_MODE_ELEMENT) continue; ?>

                    <? $isSelected = $selectedRootMode == $rootModeId; ?>
                    <option value="<?=$rootModeId?>" <?if($isSelected):?>selected<?endif?>><?=$rootModeName?></option>
                <?endforeach?>
            </select>
            <input type="hidden" name="ROOT_MODE" value="<?=$bAddNew ? SectionTable::ROOT_MODE_ELEMENT : $arSection['ROOT_MODE']?>"
                   <?if($isSectionType):?>disabled<?endif?>>
        </td>
    </tr>
    <tr>
        <td class="adm-detail-content-cell-l" style="width: 50%;">
            <label><?=$arMap['TITLE']->getTitle()?>:</label>
        </td>
        <td class="adm-detail-content-cell-r" style="width: 50%;">
            <input type="text"
                name="TITLE"
                data-translit-target="#pathInput"
                class="js-auto-translit"
                value="<?=($bAddNew ? $request->getPost('TITLE') : $arSection['TITLE'])?>"
                />
        </td>
    </tr>
    <? $isLandingType = $arSection['TYPE'] == SectionTable::TYPE_LANDING || $request->getPost('TYPE') == SectionTable::TYPE_LANDING;?>
    <? $domain = $bAddNew && empty($arSection['DOMAIN']) ? $request->getPost('DOMAIN') : $arSection['DOMAIN'];?>
    <tr <?if(!$isLandingType):?>style="display:none;"<?endif?>>
        <td class="adm-detail-content-cell-l" style="width: 50%;">
            <label><?=Loc::getMessage('RX_LANDING_SECTION_USE_DOMAIN')?>:</label>
        </td>
        <td class="adm-detail-content-cell-r" style="width: 50%;">
            <input type="checkbox" class="js-toggle-domain" <?if(!empty($domain)):?>checked<?endif?> />
        </td>
    </tr>
    <tr <?if(empty($domain) || !$isLandingType):?>style="display:none;"<?endif?>>
        <td class="adm-detail-content-cell-l" style="width: 50%;">
            <label><?=$arMap['DOMAIN']->getTitle()?>:</label>
        </td>
        <td class="adm-detail-content-cell-r" style="width: 50%;">
            <input type="text"
                name="DOMAIN"
                value="<?=$converter->Decode($domain)?>"/>
        </td>
    </tr>
    <tr <?if(empty($domain) || !$isLandingType):?>style="display:none;"<?endif?>>
        <td class="adm-detail-content-cell-l" style="width: 50%;">
            <label><?=$arMap['OWN_SETTINGS']->getTitle()?>:</label>
        </td>
        <td class="adm-detail-content-cell-r" style="width: 50%;">
            <input type="checkbox"
                name="OWN_SETTINGS"
                <?if($arSection['OWN_SETTINGS'] == 'Y' || !empty($request->getPost('OWN_SETTINGS'))):?>checked<?endif?> />
        </td>
    </tr>
    <? $isMainType = $arSection['TYPE'] == SectionTable::TYPE_MAIN || $request->getPost('TYPE') == SectionTable::TYPE_MAIN;?>
    <tr <?if($isMainType):?>style="display:none;"<?endif?>>
        <td class="adm-detail-content-cell-l" style="width: 50%;">
            <label><?=$arMap['PATH']->getTitle()?>:</label>
        </td>
        <?
        $path = '/example/';
        if ($isMainType) {
            $path = '/';
        }
        elseif (!$bAddNew) {
            $path = $arSection['PATH'];
            if ($arSite['DIR'] != '/') {
                $path = str_replace($arSite['DIR'], '', $path);
            }
            if (mb_strpos($path, '/') !== 0) {
                $path = '/'.$path;
            }
        }
        elseif ($selectedPath = $request->getPost('PATH')) {
            $path = $selectedPath;
        }
        ?>
        <td class="adm-detail-content-cell-r" style="width: 50%;">
            <input type="text"
                name="PATH"
                id="pathInput"
                value="<?=$path?>"
                />
        </td>
    </tr>
    <tr>
        <td class="adm-detail-content-cell-l" style="width: 50%;">
            <label><?=Loc::getMessage('RX_LANDING_SECTION_PATH_FORCE_REPLACE')?>:</label>
        </td>
        <td class="adm-detail-content-cell-r" style="width: 50%;">
            <input type="checkbox"
                   name="PATH_FORCE_REPLACE" <?if(!empty($request->getPost('PATH_FORCE_REPLACE'))):?>checked<?endif?>/>
        </td>
    </tr>
    <?if ($bAddNew):?>
        <tr>
            <td class="adm-detail-content-cell-l" style="width: 50%;">
                <label><?=Loc::getMessage('RX_LANDING_SECTION_ADD_TO_MENU')?>:</label>
            </td>
            <td class="adm-detail-content-cell-r" style="width: 50%;">
                <input type="checkbox"
                       name="ADD_TO_MENU" <?if(!empty($request->getPost('ADD_TO_MENU'))):?>checked<?endif?>/>
            </td>
        </tr>
    <?endif?>

    <?php
    $tabControl->buttons();
    ?>
    <input type="submit"
           name="save"
           value="<?=Loc::getMessage("MAIN_SAVE") ?>"
           title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>"
           class="adm-btn-save"
           />
    <?if(!$bAddNew):?>
    <a class="adm-btn rx-section-del" href="<?=sprintf('%s?SECTION_ID=%d&mid=%s&lang=%s&del=Y', $request->getRequestedPage(), $sectionId, urlencode($mid), LANGUAGE_ID)?>"
       style="float: right;" onclick="if (!confirm('<?=Loc::getMessage('RX_LANDING_SECTION_DELETE_CONFIRM')?>')) return false;"><?=Loc::getMessage('RX_LANDING_SECTION_DELETE_SECTION')?></a>
    <?endif?>
    <?php
    $tabControl->end();
    ?>
</form>

<script>
    $(document).ready(function(){
        $('select[name="TYPE"]').on('change', function(){
            const defaultPaths = <?= Json::encode(SectionTable::getDefaultPath()) ?>;
            const defaultTitles = <?= Json::encode(SectionTable::getDefaultTitle()) ?>;

            const defaultPath = defaultPaths[$(this).val()] ?? '';
            $('[name="PATH"]').val(defaultPath);

            const defaultTitle = defaultTitles[$(this).val()] ?? '';
            $('[name="TITLE"]').val(defaultTitle);

            if ($(this).val() == <?=SectionTable::TYPE_MAIN?>) {
                $('[name="PATH"]').closest('tr').hide();
                $('[name="PATH"]').val('/');
                $('[name="PATH_FORCE_REPLACE"]').prop('checked', true);
            } else {
                $('[name="PATH"]').closest('tr').show();
                $('[name="PATH_FORCE_REPLACE"]').prop('checked', false);
            }

            if ($(this).val() == <?=SectionTable::TYPE_LANDING?>) {
                $('.js-toggle-domain').closest('tr').show();
                if ($('.js-toggle-domain').prop('checked')) {
                    $('[name="DOMAIN"]').closest('tr').show();
                    $('[name="OWN_SETTINGS"]').closest('tr').show();
                }
            } else {
                $('.js-toggle-domain').closest('tr').hide();
                $('[name="DOMAIN"]').closest('tr').hide();
                $('[name="OWN_SETTINGS"]').closest('tr').hide();
            }

            let isSectionType = $(this).val() != <?=SectionTable::TYPE_LANDING?> &&
                                $(this).val() != <?=SectionTable::TYPE_MAIN?> &&
                                $(this).val() != <?=SectionTable::TYPE_SEARCH?> &&
                                $(this).val() != <?=SectionTable::TYPE_ORDER?>;

            $('[name="ROOT_MODE"]').closest('tr').toggle(isSectionType);
            $('select[name="ROOT_MODE"]').toggle(isSectionType);

            $('select[name="ROOT_MODE"]').prop('disabled', !isSectionType);
            $('input[name="ROOT_MODE"]').prop('disabled', isSectionType);
        });
        $('input.js-auto-translit').on('change keyup paste', function(e){
            if ($('[name="TYPE"]').val() == <?=SectionTable::TYPE_MAIN?>) {
                return;
            }

            const options = {'replace_space': '-', 'replace_other': '-'};

            let input  = $(this);
            let target = $(input.data('translit-target'));

            if(BX.translit) {
                target.val(BX.translit(input.val(), options));
            }
        });
        $('.js-toggle-domain').on('change', function(e){
            const $domain = $('[name="DOMAIN"]').closest('tr');
            const $ownSettings = $('[name="OWN_SETTINGS"]').closest('tr');

            $domain.toggle();
            $ownSettings.toggle();
        });
        $('form').on('submit', function (e) {
            if (!$('.js-toggle-domain').prop('checked')) {
                $('[name="DOMAIN"]').val('');
                $('[name="OWN_SETTINGS"]').prop('checked', false);
            }
        });
    });
</script>
