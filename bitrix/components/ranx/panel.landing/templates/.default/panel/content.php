<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

/**
 * @var array $arResult
 */

use Ranx\Landing\Block;
use Ranx\Landing\Config;
use Ranx\Landing\Landing;
use Ranx\Landing\Helpers\Iblock;
use Ranx\Landing\Helpers\Helper;
use Ranx\Landing\Helpers\FormHelper;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Panel\Content\Filter as ContentFilter;

Loc::loadMessages(__FILE__);
?>

<?if(Config::isAnchorsEnabled()):?>
<div id="panelContentAnchorTitle">
    <div class="form-group has-field-cleaner">
        <label>
            <?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_ANCHOR_TITLE') ?>
            (<a href="https://help.landing-demo.ru/articles/299-308--kak-sozdat-bystruyu-navigaciyu-po-stranice-menyu-yakorej/"
                target="_blank" title="<?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_DOCS') ?>">?</a>)
        </label>
        <input type="text" name="ANCHOR_TITLE" class="form-control" value="<?=$arResult['PROPS']['ANCHOR_TITLE']?>" />
        <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
            <?=Helper::svg('panel', 'remove')?>
        </div>
    </div>
</div>
<?endif?>

<?if(in_array('CONTENT_TITLE', $arResult['SECTIONS'])):?>
<?php
    $isTabChecked  = $arResult['PROPS']['HIDE_TITLE'] != 'Y';
    $isTitleTag = in_array('CONTENT_TITLE_TAG', $arResult['SECTIONS']);
    $fieldsOptions = $arResult['FIELDS_OPTIONS'];
?>
<div class="panel-acc <?if($isTabChecked):?>open<?endif?>">
    <div class="panel-acc-header theme-color-hover-parent">

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="panelContentTab1" name="SHOW_TITLE" <?if($isTabChecked):?>checked<?endif?>>
            <label class="custom-control-label" for="panelContentTab1"></label>
        </div>

        <div class="panel-acc-title"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_TITLE_ACC') ?></div>

        <div class="panel-acc-opened theme-color-hover"><?= Helper::svg('panel', 'acc_up') ?></div>
        <div class="panel-acc-closed theme-color-hover"><?= Helper::svg('panel', 'acc_down') ?></div>

    </div>
    <div class="panel-acc-body">
        <div class="form-group has-field-cleaner">
            <label><?= ($arResult['FIELDS_MESS']['CATITLE'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_TITLE_CATTITLE')) ?></label>
            <input type="text" name="CATTITLE" class="form-control" value="<?=$arResult['PROPS']['CATTITLE']?>" />
            <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                <?=Helper::svg('panel', 'remove')?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-<?= $isTitleTag ? '10' : '12' ?> has-field-cleaner">
                <label><?= ($arResult['FIELDS_MESS']['TITLE'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_TITLE_TITLE')) ?></label>
                <input type="text" name="TITLE" class="form-control" value="<?=$arResult['NAME']?>" />
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>
            <?if ($isTitleTag) {
                $field = [
                    'CLASSES' => 'col-2',
                    'TITLE' => $arResult['FIELDS_MESS']['TITLE_TAG'] ?? $arResult['PROPERTIES']['TITLE_TAG']['NAME'],
                    'NAME' => 'TITLE_TAG',
                    'LIST_VALUES' => $arResult['TITLE_TAG_VALUES'],
                    'VALUE' => $arResult['PROPS']['TITLE_TAG'],
                ];

                include __DIR__.'/../include/field/select.php';
            } ?>
        </div>
        <div class="form-group">
            <label><?= ($arResult['FIELDS_MESS']['SUBTITLE'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_TITLE_SUBTITLE')) ?></label>
            <? FormHelper::showTextField(
                'SUBTITLE', 'panelContentSUBTITLE', $arResult['PROPS']['SUBTITLE'],
                $fieldsOptions['SUBTITLE']['SHOW_EDITOR'] ?? true);
            ?>
        </div>
        <div class="form-group">
            <label><?= ($arResult['FIELDS_MESS']['DESC'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_TITLE_DESC')) ?></label>
            <? FormHelper::showTextField(
                'DESC', 'panelContentDESC', $arResult['PROPS']['DESC']['TEXT'] ?? '',
                $fieldsOptions['DESC']['SHOW_EDITOR'] ?? true);
            ?>
        </div>

        <?if (in_array('CONTENT_PREVIEW_PICTURE', $arResult['SECTIONS'])) {
            $field = [
                'TITLE' => $arResult['FIELDS_MESS']['PREVIEW_PICTURE'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_TITLE_PREVIEW_PICTURE'),
                'NAME' => 'PREVIEW_PICTURE',
                'ID' => 'panelContentPreviewPicture',
                'VALUE' => $arResult['PREVIEW_PICTURE'],
                'MIME_TYPE' => 'image',
                'BTN_TEXT' => Loc::getMessage('RX_PANEL_LANDING_CONTENT_TITLE_PREVIEW_PICTURE_UPLOAD'),
            ];

            include __DIR__.'/../include/field/file.php';
        } ?>

        <?if(in_array('CONTENT_DETAIL_PICTURE', $arResult['SECTIONS'])) {
            $field = [
                'TITLE' => $arResult['FIELDS_MESS']['DETAIL_PICTURE'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_TITLE_DETAIL_PICTURE'),
                'NAME' => 'DETAIL_PICTURE',
                'ID' => 'panelContentDetailPicture',
                'VALUE' => $arResult['DETAIL_PICTURE'],
                'MIME_TYPE' => 'image',
                'BTN_TEXT' => Loc::getMessage('RX_PANEL_LANDING_CONTENT_TITLE_DETAIL_PICTURE_UPLOAD'),
            ];

            include __DIR__.'/../include/field/file.php';
        }?>

    </div>
</div>
<?else:?>
    <?if(in_array('CONTENT_TITLE_TAG', $arResult['SECTIONS'])) {
        $field = [
            'CLASSES' => 'one-form-group',
            'TITLE' => $arResult['FIELDS_MESS']['TITLE_TAG'] ?? $arResult['PROPERTIES']['TITLE_TAG']['NAME'],
            'NAME' => 'TITLE_TAG',
            'LIST_VALUES' => $arResult['TITLE_TAG_VALUES'],
            'VALUE' => $arResult['PROPS']['TITLE_TAG'],
        ];

        include __DIR__ . '/../include/field/select.php';
    } ?>
<?endif?>

<?if(in_array('CONTENT_IMPORT', $arResult['SECTIONS']) && !Config::isDemoLanding()):?>
<?php
    $isTabChecked = $arResult['PROPS']['IMPORT_ELEMENTS'] === 'Y';
    $importDataType = $arResult['INFO']['IMPORT_DATA_TYPE'] ?? '';
    $isImportElementChecked = $isTabChecked;
    $iblocks = Iblock::getExternalIblocksForSelect($importDataType);
    if (!empty($arResult['PROPS']['IMPORT_ID'])) {
        $sections = Iblock::getIblockSectionsForSelect($arResult['PROPS']['IMPORT_ID']);
    }
?>
    <?if(!empty($iblocks)):?>
    <div class="panel-acc <?if($isTabChecked):?>open<?endif?>">
        <div class="panel-acc-header theme-color-hover-parent">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="panelContentTab4" name="SHOW_IMPORT_ELEMENTS" <?if($isTabChecked):?>checked<?endif?>>
                <label class="custom-control-label" for="panelContentTab4"></label>
            </div>
            <div class="panel-acc-title"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_IMPORT_TITLE') ?></div>
            <div class="panel-acc-opened theme-color-hover"><?= Helper::svg('panel', 'acc_up') ?></div>
            <div class="panel-acc-closed theme-color-hover"><?= Helper::svg('panel', 'acc_down') ?></div>
        </div>
        <div class="panel-acc-body">

            <div class="form-row">
                <div class="form-group <?if(empty($sections)):?>col-12<?else:?>col-6<?endif?>">
                    <label><?= ($arResult['FIELDS_MESS']['IMPORT_ID'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_IMPORT_ID_TITLE'))?></label>
                    <select name="IMPORT_ID" class="form-control">
                        <option value="" <?if(!$arResult['PROPS']['IMPORT_ID']):?>selected<?endif?>>
                            <?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_IMPORT_DEFAULT') ?>
                        </option>
                        <?foreach($iblocks as $iblockType):?>
                            <optgroup label="<?=$iblockType['NAME']?>">
                                <?foreach($iblockType['IBLOCKS'] as $iblockId => $iblockName):
                                    $isSelected = $iblockId == $arResult['PROPS']['IMPORT_ID'];    
                                ?>
                                    <option value="<?=$iblockId?>" <?if($isSelected):?>selected<?endif?>>[<?=$iblockId?>] <?=$iblockName?></option>
                                <?endforeach?>    
                            </optgroup>
                        <?endforeach?>
                    </select>
                </div>

                <div class="form-group col-6" <?if(empty($sections)):?>style="display:none;"<?endif?>>
                    <label><?= ($arResult['FIELDS_MESS']['IMPORT_SECTION_ID'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_IMPORT_SECTION_ID_TITLE'))?></label>
                    <select name="IMPORT_SECTION_ID" class="form-control">
                        <option value="" <?if(!$arResult['PROPS']['IMPORT_SECTION_ID']):?>selected<?endif?>>
                            <?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_IMPORT_DEFAULT') ?>
                        </option>
                        <?if(!empty($sections)):?>
                            <?foreach($sections as $section):
                                $isSelected = $section['ID'] == $arResult['PROPS']['IMPORT_SECTION_ID'];
                            ?>
                                <option value="<?=$section['ID']?>" <?if($isSelected):?>selected<?endif?>>
                                    <?=str_repeat("&nbsp;&nbsp;", $section['DEPTH_LEVEL'] - 1)?>[<?=$section['ID']?>]&nbsp;<?=$section['NAME']?>
                                </option>
                            <?endforeach?>
                        <?endif?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label><?=($arResult['FIELDS_MESS']['IMPORT_SORT'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_IMPORT_SORT'))?></label>
                    <select name="IMPORT_SORT" class="form-control">

                        <?foreach($arResult['IMPORT_SORT_VALUES'] as $i => $type):
                            $isSelected = ($arResult['PROPERTIES']['IMPORT_SORT']['VALUE_ENUM_ID'] == $type['ID']);
                            if ($importDataType !== 'PRODUCTS' && in_array($type['XML_ID'], ['PRICE', 'AVAILABLE']))
                                continue;
                            if ($importDataType !== 'NEWS' && $type['XML_ID'] == 'ACTIVE_FROM')
                                continue;
                        ?>
                        <option value="<?=$type['ID']?>" data-code="<?=$type['XML_ID']?>" data-index="<?=$i?>"
                                <?if($isSelected):?>selected<?endif?>><?=$type['VALUE']?></option>
                        <?endforeach?>

                    </select>
                </div>

                <div class="form-group col-6">
                    <label><?=($arResult['FIELDS_MESS']['IMPORT_SORT_ORDER'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_IMPORT_SORT_ORDER'))?></label>
                    <select name="IMPORT_SORT_ORDER" class="form-control">

                        <?foreach($arResult['IMPORT_SORT_ORDER_VALUES'] as $i => $type):
                            $isSelected = ($arResult['PROPERTIES']['IMPORT_SORT_ORDER']['VALUE_ENUM_ID'] == $type['ID']);
                        ?>
                        <option value="<?=$type['ID']?>" data-code="<?=$type['XML_ID']?>" data-index="<?=$i?>"
                                <?if($isSelected):?>selected<?endif?>><?=$type['VALUE']?></option>
                        <?endforeach?>

                    </select>
                </div>
            </div>

            <?if(!empty($arResult['PRICES_INFO'])):?>
                <div class="form-group">
                    <label><?=($arResult['FIELDS_MESS']['IMPORT_PRICE_ID'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_IMPORT_PRICE_ID'))?></label>
                    <select name="IMPORT_PRICE_ID" class="form-control">
                        <?foreach ($arResult['PRICES_INFO'] as $i => $arPrice):?>
                            <?$isSelected = $arResult['PROPS']['IMPORT_PRICE_ID'] == $arPrice['ID'] ||
                                empty($arResult['PROPS']['IMPORT_PRICE_ID']) && $arPrice['BASE'] == 'Y';?>
                            <option value="<?=$arPrice['ID']?>" <?if($isSelected):?>selected<?endif?>><?=$arPrice['NAME_LANG']?></option>
                        <?endforeach?>
                    </select>
                </div>
            <?endif?>

            <div class="form-group">
                <label><?=($arResult['FIELDS_MESS']['IMPORT_LINK_TYPE'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_IMPORT_LINK_TYPE'))?></label>
                <select name="IMPORT_LINK_TYPE" class="form-control">
                    <? $selectId = $arResult['PROPERTIES']['IMPORT_LINK_TYPE']['VALUE_ENUM_ID'] ??
                        reset($arResult['IMPORT_LINK_TYPE_VALUES'])['ID'];?>
                    <?foreach ($arResult['IMPORT_LINK_TYPE_VALUES'] as $arLinkType):
                        $isSelected = $selectId == $arLinkType['ID'];
                        if ($importDataType === 'NEWS' && $arLinkType['XML_ID'] == 'form')
                          continue;
                    ?>
                        <option value="<?=$arLinkType['ID']?>" <?if($isSelected):?>selected<?endif?>><?=$arLinkType['VALUE']?></option>
                    <?endforeach?>
                </select>
            </div>

            <? $selectedCode = 'ALL_ELEMENTS';?>
            <div class="form-group">
                <label><?=($arResult['FIELDS_MESS']['IMPORT_FILTERS'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_IMPORT_FILTERS'))?></label>
                <select name="IMPORT_FILTERS" class="form-control">

                    <? $index = 0 ?>
                    <?foreach($arResult['IMPORT_FILTERS_VALUES'] as $type):
                        $isSelected = false;
                        if ($arResult['PROPERTIES']['IMPORT_FILTERS']['VALUE_ENUM_ID'] == $type['ID']) {
                            $isSelected = true;
                            $selectedCode = $type['XML_ID'];
                        }

                        if ($importDataType === 'PRODUCTS' && $type['XML_ID'] === 'LAST_ACTIVE')
                            continue;
                    ?>
                    <option value="<?=$type['ID']?>" data-code="<?=$type['XML_ID']?>" data-index="<?=$index++?>"
                            <?if($isSelected):?>selected<?endif?>><?=$type['VALUE']?></option>
                    <?endforeach?>

                </select>
            </div>

            <div class="form-group" <?if($selectedCode != 'LAST_ACTIVE'):?>style="display: none;"<?endif?> data-code="LAST_ACTIVE">
                <label><?=($arResult['FIELDS_MESS']['ELEMENTS_COUNT'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_ELEMENTS_COUNT'))?></label>
                <input type="number" name="ELEMENTS_COUNT" class="form-control" value="<?=$arResult['PROPS']['ELEMENTS_COUNT']?>" />
            </div>

            <div class="form-group" <?if($selectedCode != 'ELEMS_SPEC_ID'):?>style="display: none;"<?endif?> data-code="ELEMS_SPEC_ID">
                <label><?=($arResult['FIELDS_MESS']['IMPORT_ELEM_IDS'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_IMPORT_ELEM_IDS'))?></label>
                
                <?php
                    $acItems = $arResult['IMPORT_ELEM_IDS'];
                    $acName = 'IMPORT_ELEM_IDS';
                    $acAction = 'searchElements';
                    $acAdditional = ['iblock' => $arResult['PROPS']['IMPORT_ID'], 'section' => $arResult['PROPS']['IMPORT_SECTION_ID']];

                    include __DIR__ . '/../include/ac.php';
                ?>
            </div>
        </div>
    </div>
    <?endif?>

<?endif?>

<?if(in_array('CONTENT_AUTO', $arResult['SECTIONS']) && $arResult['PROPS']['MODE'] !== Landing::MODE_ELEMENT):?>
    <?php
    $isTabChecked = $arResult['PROPS']['AUTO_BLOCK'] == 'Y';
    $isAutoChecked = $isTabChecked;
    ?>
    <div class="panel-acc <?if($isTabChecked):?>open<?endif?>">
        <div class="panel-acc-header theme-color-hover-parent">

            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="panelContentTab6" name="AUTO_BLOCK" <?if($isTabChecked):?>checked<?endif?>>
                <label class="custom-control-label" for="panelContentTab6"></label>
            </div>

            <div class="panel-acc-title"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_AUTO_ACC') ?></div>

            <div class="panel-acc-opened theme-color-hover"><?= Helper::svg('panel', 'acc_up') ?></div>
            <div class="panel-acc-closed theme-color-hover"><?= Helper::svg('panel', 'acc_down') ?></div>

        </div>
        <div class="panel-acc-body">

            <input type="hidden" name="CONTENT_AUTO" value="true">

            <div class="form-group">
                <label><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_AUTO_TYPE') ?></label>
                <select name="AUTO_TYPE" class="form-control">
                    <option value="element" <?if($arResult['PROPS']['AUTO_TYPE'] == 'element'):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_AUTO_TYPE_ELEMENT') ?></option>
                    <option value="section" <?if($arResult['PROPS']['AUTO_TYPE'] == 'section'):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_AUTO_TYPE_SECTION') ?></option>
                </select>
            </div>

            <div class="form-group has-field-cleaner">
                <label><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_AUTO_COUNT') ?></label>
                <input type="text" name="AUTO_COUNT" class="form-control" value="<?=$arResult['PROPS']['AUTO_COUNT']?>" />
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>

        </div>
    </div>
<?endif?>

<?if(in_array('CONTENT_TABS', $arResult['SECTIONS'])):?>
    <? $isUseTab = $arResult['PROPS']['USE_TABS'] === 'Y';?>
    <div class="panel-row" <?if($isImportElementChecked || $isAutoChecked):?>style="display:none;"<?endif?>>
        <input type="hidden" name="CONTENT_TABS" value="true">
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="panelContentUseTabs" name="USE_TABS" <?if($isUseTab):?>checked<?endif?>>
                <label class="custom-control-label" for="panelContentUseTabs">
                    <?= ($arResult['FIELDS_MESS']['USE_TABS'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_TABS_USE')) ?>
                    (<a href="https://help.landing-demo.ru/articles/299-523--ispolzovanie-vkladok-tabov-v-blokah/"
                        target="_blank" title="<?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_DOCS') ?>">?</a>)
                </label>
            </div>
        </div>
    </div>
<?endif?>

<?if(ContentFilter::isInclude($arResult['CODE'], $arResult['SECTIONS'])):?>
<?
    $isTabActive = $arResult['FILTER']['INCLUDE'];
?>
    <div class="panel-acc <?if($isTabActive):?>open<?endif;?> panel-acc--filter">
        <div class="panel-acc-header theme-color-hover-parent">

            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="panelContentTab7" name="FILTER[INCLUDE]" <?if($isTabActive):?>checked<?endif?>>
                <label class="custom-control-label" for="panelContentTab7"></label>
            </div>

            <div class="panel-acc-title"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_FILTER_ACC') ?></div>

            <div class="panel-acc-opened theme-color-hover"><?= Helper::svg('panel', 'acc_up') ?></div>
            <div class="panel-acc-closed theme-color-hover"><?= Helper::svg('panel', 'acc_down') ?></div>

        </div>
        <div class="panel-acc-body">
            <input type="hidden" name="CONTENT_FILTER" value="true">
            <?if (!empty($arResult['FILTER']['INCLUDE_FIELDS'])):?>
                <?foreach ($arResult['FILTER']['INCLUDE_FIELDS'] as $propCode => $isActive):?>
                    <? if (!isset($arResult['FILTER']['TITLE'][$propCode])) continue; ?>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox custom-checkbox--toggle">
                            <input type="checkbox" class="custom-control-input" id="FILTER_FIELD_<?=$propCode?>"
                                   name="FILTER[INCLUDE_FIELDS][<?=$propCode?>]" <?if($isActive):?>checked<?endif;?>>
                            <label class="custom-control-label" for="FILTER_FIELD_<?=$propCode?>">
                                <?=$arResult['FILTER']['TITLE'][$propCode]?>
                            </label>
                        </div>
                    </div>
                <?endforeach;?>
            <?endif;?>

        </div>
    </div>
<?endif;?>

<? $isWeekDaysSection = in_array('CONTENT_WEEK_DAYS', $arResult['SECTIONS']);?>
<?if(in_array('CONTENT_CARDS', $arResult['SECTIONS']) || $isWeekDaysSection):?>
    <?php
    $maxCardCount = $arResult['INFO']['MAX_CARD_COUNT'];
    $isTabChecked = $arResult['PROPS']['HIDE_ELEMENTS'] != 'Y';
    $isUseTab = in_array('CONTENT_TABS', $arResult['SECTIONS']) && $arResult['PROPS']['USE_TABS'] === 'Y';
    $isHiddenAddBtn = isset($maxCardCount) && !$isWeekDaysSection && count($arResult['CARDS']) >= $maxCardCount;
    ?>
    <div class="panel-acc <?if($isTabChecked):?>open<?endif?>" <?if($isImportElementChecked || $isAutoChecked):?>style="display:none;"<?endif?>>
        <div class="panel-acc-header theme-color-hover-parent">

            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="panelContentTab2" name="SHOW_ELEMENTS" <?if($isTabChecked):?>checked<?endif?>>
                <label class="custom-control-label" for="panelContentTab2"></label>
            </div>

            <div class="panel-acc-title">
                <?if ($isUseTab):?>
                    <?= Loc::getMessage('RX_PANEL_LANDING_TAB_CONTENT_CARDS_ACC', ['#TAB_NAME#' => $arResult['TAB_NAME']]) ?>
                <?else:?>
                    <?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_ACC') ?>
                <?endif?>
            </div>

            <div class="panel-acc-opened theme-color-hover"><?= Helper::svg('panel', 'acc_up') ?></div>
            <div class="panel-acc-closed theme-color-hover"><?= Helper::svg('panel', 'acc_down') ?></div>

        </div>
        <div class="panel-acc-body">
            <input type="hidden" name="CONTENT_CARDS" value="true">

            <div class="panel-cards-sort">
                <div class="panel-cards-sort-title"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_SORT_TITLE') ?></div>
                <select name="CARDS_SORT">
                    <?foreach(Block::getCardsSorts() as $sort => $sortName):
                        $isSelected = $sort === 'SORT|ASC' && !$arResult['CARDS_SORT'] || $arResult['CARDS_SORT'] && $arResult['CARDS_SORT'] === $sort;
                        ?>
                        <option value="<?= $sort ?>" <?if($isSelected):?>selected<?endif?>><?= $sortName ?></option>
                    <?endforeach?>
                </select>
            </div>

            <? $cardTemplates = []; ?>
            <?if(!empty($arResult['CARDS'])):?>
                <?foreach($arResult['CARDS'] as $key => $card):?>
                    <? ob_start(); ?>
                    <div class="panel-card <?if($card['ACTIVE'] == 'Y'):?>active<?endif?> <?=$card['TAB_CLASS']?>" data-id="<?=$card['ID']?>">

                        <input type="hidden" name="ELEMENT_<?=$card['ID']?>_ACTIVE" value="<?=$card['ACTIVE']?>">
                        <input type="hidden" name="ELEMENT_<?=$card['ID']?>_SORT" value="<?=$card['SORT']?>">

                        <div class="panel-card-header">

                            <div class="panel-card-title js-panel-card theme-color-hover theme-border-hover">
                                <?if(!empty($card['NAME'])):
                                    $card['NAME'] = trim(strip_tags($card['NAME']));
                                    ?>
                                    <?= Helper::cutName($card['NAME'], 35) ?>
                                <?elseif(!empty($card['PREVIEW_TEXT'])):?>
                                    <?= Helper::cutName($card['PREVIEW_TEXT'], 35) ?>
                                <?else:?>
                                    <?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_CARD_TITLE_DEFAULT', ['#ID#' => $card['ID']]) ?>
                                <?endif?>
                            </div>
                            <div class="panel-card-actions">
                                <a href="#" class="panel-card-action-deact js-panel-card-deact"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_ACTION_DEACT') ?></a>
                                <a href="#" class="panel-card-action-act js-panel-card-act"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_ACTION_ACT') ?></a>
                                <a href="#" class="js-panel-card-remove"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_ACTION_REMOVE') ?></a>
                                <div class="panel-card-drag"><?= Helper::svg('panel', 'drag_drop') ?></div>
                            </div>
                        </div>

                    </div>
                    <? $cardTemplates[$key] = ob_get_clean(); ?>
                <?endforeach?>
            <?endif?>

            <?if ($isWeekDaysSection):?>
                <?foreach ($arResult['WEEK_GAY_GROUPS'] as $dayName => $cardKeys):?>
                    <div class="panel-acc panel-acc--weekdays">
                        <div class="panel-acc-header theme-color-hover-parent">
                            <div class="panel-acc-title"><?=$dayName?></div>
                            <div class="panel-acc-opened theme-color-hover"><?= Helper::svg('panel', 'acc_up') ?></div>
                            <div class="panel-acc-closed theme-color-hover"><?= Helper::svg('panel', 'acc_down') ?></div>
                        </div>
                        <div class="panel-acc-body">
                            <div class="panel-cards <?if(!$arResult['CARDS_SORT'] || $arResult['CARDS_SORT'] === 'SORT|ASC'):?>js-sortable<?endif?>">
                                <?foreach ($cardKeys as $cardKey):?>
                                    <?= $cardTemplates[$cardKey]; ?>
                                <?endforeach;?>
                            </div>
                        </div>
                    </div>
                <?endforeach;?>
            <?else:?>
                <div class="panel-cards <?if(!$arResult['CARDS_SORT'] || $arResult['CARDS_SORT'] === 'SORT|ASC'):?>js-sortable<?endif?>"
                     data-max-count="<?=$maxCardCount?>">
                    <?foreach ($cardTemplates as $cardTemplate):?>
                        <?= $cardTemplate ?>
                    <?endforeach;?>
                </div>
            <?endif;?>

            <button class="btn btn-primary btn-block js-panel-card-add" <?if($isHiddenAddBtn):?>style="display: none;"<?endif;?>>
                <?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_CARD_ADD') ?>
            </button>

        </div>
    </div>
<?endif?>

<?if(in_array('CONTENT_GALLERY_CARDS', $arResult['SECTIONS'])):?>
<?
    $isTabChecked = $arResult['PROPS']['HIDE_ELEMENTS'] != 'Y';
?>
<div class="panel-acc <?if($isTabChecked):?>open<?endif?>" <?if($isImportElementChecked || $isAutoChecked):?>style="display:none;"<?endif?>>
    <div class="panel-acc-header theme-color-hover-parent">

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="panelGalleryCardsContentTab"
                   name="SHOW_ELEMENTS" <?if($isTabChecked):?>checked<?endif?>>
            <label class="custom-control-label" for="panelGalleryCardsContentTab"></label>
        </div>

        <div class="panel-acc-title"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_ACC') ?></div>

        <div class="panel-acc-opened theme-color-hover"><?= Helper::svg('panel', 'acc_up') ?></div>
        <div class="panel-acc-closed theme-color-hover"><?= Helper::svg('panel', 'acc_down') ?></div>

    </div>
    <div class="panel-acc-body panel-gallery-acc-body">
        <input type="hidden" name="CONTENT_GALLERY_CARDS" value="true">

        <label class="panel-gallery-upload theme-border theme-border-hover theme-color-hover-parent">
            <input type="file" class="panel-gallery-upload-input" accept=".jpg, .jpeg, .gif, .png" multiple>
            <div class="panel-gallery-upload-svg theme-color"><?=Helper::svg('panel', 'image')?></div>
            <div class="panel-gallery-upload-note">
                <span class="bold theme-color theme-color-hover">
                    <?=Loc::getMessage('RX_PANEL_LANDING_CONTENT_GALLERY_UPLOAD_NOTE_TITLE')?>
                </span>
                <span>
                    <?=Loc::getMessage('RX_PANEL_LANDING_CONTENT_GALLERY_UPLOAD_NOTE_TEXT')?>
                </span>
            </div>
        </label>

        <div class="panel-cards-sort">
            <div class="panel-cards-sort-title"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_SORT_TITLE') ?></div>
            <select name="CARDS_SORT">
                <?foreach(Block::getCardsSorts() as $sort => $sortName):
                    $isSelected = $sort === 'SORT|ASC' && !$arResult['CARDS_SORT'] || $arResult['CARDS_SORT'] && $arResult['CARDS_SORT'] === $sort;   
                ?>
                <option value="<?= $sort ?>" <?if($isSelected):?>selected<?endif?>><?= $sortName ?></option>
                <?endforeach?>
            </select>
        </div>

        <div class="panel-gallery-cards <?if(!$arResult['CARDS_SORT'] || $arResult['CARDS_SORT'] === 'SORT|ASC'):?>js-gallery-sortable<?endif?>">
        <?foreach($arResult['GALLERY_CARDS'] as $card):?>
            <div class="panel-gallery-card <?if($card['ACTIVE'] == 'Y'):?>active<?endif?>" data-id="<?=$card['ID']?>"
                 data-order="<?=$card['SORT']?>">

                <?foreach ($arResult['CARD_PROPS_CODE'] as $propCode):?>
                <input type="hidden" name="ELEMENT_<?=$card['ID']?>_<?=$propCode?>" value="<?=$card[$propCode]?>">
                <?endforeach?>

                <div class="panel-gallery-card-action">
                    <a href="#" class="panel-gallery-card-action-remove js-panel-gallery-card-remove">
                        <?=Helper::svg('panel', 'trash')?>
                    </a>
                    <a href="#" class="panel-gallery-card-action-deact js-panel-gallery-card-deact">
                        <?=Helper::svg('panel', 'hide')?>
                    </a>
                    <a href="#" class="panel-gallery-card-action-act js-panel-gallery-card-act">
                        <?=Helper::svg('panel', 'show')?>
                    </a>
                    <a href="#" class="panel-gallery-card-action-edit js-panel-gallery-card-edit theme-border-hover">
                        <?=Loc::getMessage('RX_PANEL_LANDING_CONTENT_GALLERY_CARD_EDIT')?>
                    </a>
                </div>

                <?if (empty($card['IMG_SRC'])):?>
                    <div class="panel-gallery-card-img"></div>
                <?else:?>
                    <img src="<?=$card['IMG_SRC']?>" class="panel-gallery-card-img">
                <?endif?>
                <div class="panel-gallery-card-info">
                    <div class="panel-gallery-card-name"><?=$card['NAME']?></div>
                    <div class="panel-gallery-card-size"><?=$card['IMG_SIZE']?></div>
                </div>
            </div>
        <?endforeach?>

            <div class="panel-gallery-card-edit hidden">
                <a href="#" class="panel-gallery-card-edit-close js-panel-gallery-edit-close">
                    <?=Helper::svg('panel', 'close')?>
                </a>
                <div class="form-group has-field-cleaner">
                    <label class="panel-gallery-card-edit-label">
                        <?=Loc::getMessage('RX_PANEL_LANDING_CONTENT_GALLERY_CARD_EDIT_TITLE')?>
                    </label>
                    <input type="text" class="form-control" name="ELEMENT_PROPERTY_PICTURE_TITLE"/>
                    <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                        <?=Helper::svg('panel', 'remove')?>
                    </div>
                </div>
                <div class="form-group has-field-cleaner">
                    <label class="panel-gallery-card-edit-label">
                        <?=Loc::getMessage('RX_PANEL_LANDING_CONTENT_GALLERY_CARD_EDIT_ALT')?>
                    </label>
                    <input type="text" class="form-control" name="ELEMENT_PROPERTY_PICTURE_ALT"/>
                    <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                        <?=Helper::svg('panel', 'remove')?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="panel-gallery-card-edit-label">
                        <?=Loc::getMessage('RX_PANEL_LANDING_CONTENT_GALLERY_CARD_EDIT_DESC')?>
                    </label>
                    <textarea class="form-control" name="ELEMENT_PREVIEW_TEXT"></textarea>
                </div>
                <div class="form-group">
                    <label class="panel-gallery-card-edit-label">
                        <?=Loc::getMessage('RX_PANEL_LANDING_CONTENT_GALLERY_CARD_EDIT_PICTURE')?>
                    </label>
                    <div class="form-group-pics">
                        <img src="">
                        <div class="form-group-pics-info">
                            <div class="form-group-pics-info-ext"></div>
                            <div class="form-group-pics-info-size"></div>
                        </div>
                        <a class="js-panel-gallery-edit-img-replace theme-border-hover">
                            <?=Loc::getMessage('RX_PANEL_LANDING_CONTENT_GALLERY_CARD_EDIT_REPLACE')?>
                        </a>
                        <a class="js-panel-gallery-edit-img-remove theme-border-hover">
                            <?=Loc::getMessage('RX_PANEL_LANDING_CONTENT_GALLERY_CARD_EDIT_REMOVE')?>
                        </a>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="panel-gallery-edit-file-input" id="ELEMENT_DETAIL_PICTURE"
                               name="ELEMENT_DETAIL_PICTURE" accept=".jpg, .jpeg, .gif, .png"/>
                        <label class="custom-file-label btn btn-transparent" for="ELEMENT_DETAIL_PICTURE">
                            <?=Loc::getMessage('RX_PANEL_LANDING_CONTENT_GALLERY_CARD_EDIT_UPLOAD')?>
                        </label>
                    </div>
                </div>
            </div>

        </div>

        <div class="panel-gallery-card active template" data-id="" data-order="">
            <?foreach ($arResult['CARD_PROPS_CODE'] as $propCode):?>
                <input type="hidden" name="<?=$propCode?>" value="">
            <?endforeach?>
            <div class="panel-gallery-card-action">
                <a href="#" class="panel-gallery-card-action-remove js-panel-gallery-card-remove">
                    <?=Helper::svg('panel', 'trash')?>
                </a>
                <a href="#" class="panel-gallery-card-action-deact js-panel-gallery-card-deact">
                    <?=Helper::svg('panel', 'hide')?>
                </a>
                <a href="#" class="panel-gallery-card-action-act js-panel-gallery-card-act">
                    <?=Helper::svg('panel', 'show')?>
                </a>
                <a href="#" class="panel-gallery-card-action-edit js-panel-gallery-card-edit theme-border-hover">
                    <?=Loc::getMessage('RX_PANEL_LANDING_CONTENT_GALLERY_CARD_EDIT')?>
                </a>
            </div>

            <img src="" class="panel-gallery-card-img">
            <div class="panel-gallery-card-info">
                <div class="panel-gallery-card-name"></div>
                <div class="panel-gallery-card-size"></div>
            </div>
        </div>
    </div>
</div>
<?endif?>

<?if(in_array('CONTENT_VIDEO', $arResult['SECTIONS'])):?>
    <?php
    $isTabChecked = $arResult['PROPS']['HIDE_VIDEO'] != 'Y';
    $isPopup = $arResult['PROPS']['VIDEO_POPUP_SHOW'] === 'Y';
    ?>
    <div class="panel-acc <?if($isTabChecked):?>open<?endif?>">
        <div class="panel-acc-header theme-color-hover-parent">

            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="panelContentTab5" name="SHOW_VIDEO" <?if($isTabChecked):?>checked<?endif?>>
                <label class="custom-control-label" for="panelContentTab5"></label>
            </div>

            <div class="panel-acc-title"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_VIDEO_ACC') ?></div>

            <div class="panel-acc-opened theme-color-hover"><?= Helper::svg('panel', 'acc_up') ?></div>
            <div class="panel-acc-closed theme-color-hover"><?= Helper::svg('panel', 'acc_down') ?></div>

        </div>
        <div class="panel-acc-body">

            <input type="hidden" name="CONTENT_VIDEO" value="true">

            <div class="form-group has-field-cleaner">
                <label>
                    <?= ($arResult['FIELDS_MESS']['VIDEO_LINK'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_VIDEO_LINK')) ?>
                </label>
                <input type="text" name="VIDEO_LINK" class="form-control"
                       value="<?=$arResult['PROPS']['VIDEO_LINK']?>"
                       placeholder="https://www.youtube.com/watch?v=XXXXXXXXXXX"/>
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="videoPopup" name="VIDEO_POPUP_SHOW" <?if($isPopup):?>checked<?endif?>>
                    <label class="custom-control-label" for="videoPopup">
                        <?= ($arResult['FIELDS_MESS']['VIDEO_POPUP_SHOW'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_VIDEO_POPUP_SHOW')) ?>
                    </label>
                </div>
            </div>

            <div class="form-group has-field-cleaner">
                <label>
                    <?= ($arResult['FIELDS_MESS']['VIDEO_NOTE'] ?? Loc::getMessage('RX_PANEL_LANDING_CONTENT_VIDEO_NOTE')) ?>
                </label>
                <input type="text" name="VIDEO_NOTE" class="form-control"
                       value="<?=$arResult['PROPS']['VIDEO_NOTE']?>"/>
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>

        </div>
    </div>
<?endif?>

<?if(in_array('CONTENT_BTN', $arResult['SECTIONS'])):?>
    <?for($i = 0; $i < 2; $i++):?>
    <?php
        $postFix = $i ? '_2' : '';
        $btnId = 'CONTENT' . $postFix;
        $btnShow = $arResult['PROPS']['BTN_SHOW'.$postFix];
        $btnType = $arResult['PROPERTIES']['BTN_TYPE'.$postFix]['VALUE_ENUM_ID'];
        $btnSize = $arResult['PROPERTIES']['BTN_SIZE'.$postFix]['VALUE_ENUM_ID'];
        $btnText = $arResult['PROPS']['BTN_TEXT'.$postFix];
        $btnLinkType = $arResult['PROPS']['BTN_LINK_TYPE'.$postFix];
        $btnLink = $arResult['PROPS']['BTN_LINK'.$postFix];
        $btnGoal = $arResult['PROPS']['BTN_GOAL'.$postFix];
        $btnClass = $arResult['PROPS']['BTN_CLASS'.$postFix];
        $btnTypeValues = $arResult['BTN_TYPE'.$postFix.'_VALUES'];
        $btnSizeValues = $arResult['BTN_SIZE'.$postFix.'_VALUES'];
        $btnAnchors = $arResult['ANCHORS'];
        $btnForms = $arResult['FORMS'];

        include __DIR__ . '/../include/btn.php';
    ?>
    <?endfor?>
<?endif?>

<?if(in_array('CONTENT_FORM', $arResult['SECTIONS'])):?>
<div class="panel-acc open">
    <div class="panel-acc-header theme-color-hover-parent">

        <div class="panel-acc-title"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_FORM_ACC') ?></div>

        <div class="panel-acc-opened theme-color-hover"><?= Helper::svg('panel', 'acc_up') ?></div>
        <div class="panel-acc-closed theme-color-hover"><?= Helper::svg('panel', 'acc_down') ?></div>

    </div>
    <div class="panel-acc-body">
        <div class="form-group">
            <label><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_FORM_ACC') ?></label>
            <select name="FORM" class="form-control">
                <option value="" <?if(!$arResult['PROPS']['FORM']):?>selected<?endif?>><?=Loc::getMessage('RX_PANEL_LANDING_CONTENT_FORM_DEFAULT')?></option>

                <?foreach($arResult['FORMS'] as $formCode => $formName):
                    $isSelected = $arResult['PROPS']['FORM'] == $formCode;
                    ?>
                    <option value="<?=$formCode?>" <?if($isSelected):?>selected<?endif?>><?=$formName?></option>
                <?endforeach?>
            </select>
        </div>
        <div class="form-group has-field-cleaner">
            <label><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_FORM_BTN_TEXT_TITLE') ?></label>
            <input type="text" name="FORM_BTN_TEXT" class="form-control" value="<?=$arResult['PROPS']['FORM_BTN_TEXT']?>" />
            <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                <?=Helper::svg('panel', 'remove')?>
            </div>
        </div>
    </div>
</div>
<?endif?>
