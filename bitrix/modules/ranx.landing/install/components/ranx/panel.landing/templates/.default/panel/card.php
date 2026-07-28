<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

use Ranx\Landing\Fields;
use Ranx\Landing\Config;
use Bitrix\Main\Web\Json;
use Bitrix\Main\Type\Date;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Helpers\FormHelper;

Loc::loadMessages(__FILE__);

$isPriceFields = in_array('PROPERTY_PRICE', $arResult['ELEMENTS_FIELDS']) && in_array('PROPERTY_DISCOUNT_PRICE', $arResult['ELEMENTS_FIELDS']);
$isBtnFields = in_array('PROPERTY_BTN_SHOW', $arResult['ELEMENTS_FIELDS']);
$isBtn2Fields = in_array('PROPERTY_BTN_SHOW_2', $arResult['ELEMENTS_FIELDS']);
$isLinkFields = in_array('PROPERTY_LINK', $arResult['ELEMENTS_FIELDS']);
$isPhoneEmailFields = in_array('PROPERTY_PHONE', $arResult['ELEMENTS_FIELDS']) && in_array('PROPERTY_EMAIL', $arResult['ELEMENTS_FIELDS']);
$isPopup = in_array('PROPERTY_POPUP_SHOW', $arResult['ELEMENTS_FIELDS']);
$isAllowedServiceLink = Config::isAllowedServiceLink($arResult['BLOCK_CODE']);

$isIntervalTime = Fields\IntervalTime::isIncludedToFieldList($arResult['ELEMENTS_FIELDS']);
$isShownIntervalTimeField = false;

$arPopupFields = $arResult['POPUP_ELEMENTS_FIELDS'] ?? [];

$socialProps = [];
$socials = Config::getBlockSocials($arResult['BLOCK_CODE']);
$useFontAwesome = Config::useFontAwesome();
foreach ($socials as $social) {
    $socialProps[] = 'PROPERTY_' . $social;
}
$firstSocialProp = reset($socialProps);
?>

<? $fieldCodes = array_keys($arResult['FIELDS']) ?>
<?foreach($fieldCodes as $fieldNumber => $fieldCode):
    $field     = $arResult['FIELDS'][$fieldCode];
    $prevField = NULL;
    $nextField = NULL;

    if($fieldNumber > 0)
        $prevField = $arResult['FIELDS'][$fieldCodes[$fieldNumber - 1]];

    if($fieldNumber < count($fieldCodes))
        $nextField = $arResult['FIELDS'][$fieldCodes[$fieldNumber + 1]];

    if ($isPriceFields && $field['CODE'] == 'PROPERTY_DISCOUNT_PRICE') continue;
    if ($isBtnFields && in_array($field['CODE'], ['PROPERTY_BTN_TYPE', 'PROPERTY_BTN_SIZE', 'PROPERTY_BTN_TEXT', 'PROPERTY_BTN_LINK_TYPE', 'PROPERTY_BTN_LINK', 'PROPERTY_BTN_GOAL', 'PROPERTY_BTN_CLASS',])) continue;
    if ($isBtn2Fields && in_array($field['CODE'], ['PROPERTY_BTN_TYPE_2', 'PROPERTY_BTN_SIZE_2', 'PROPERTY_BTN_TEXT_2', 'PROPERTY_BTN_LINK_TYPE_2', 'PROPERTY_BTN_LINK_2', 'PROPERTY_BTN_GOAL_2', 'PROPERTY_BTN_CLASS_2',])) continue;
    if ($isLinkFields && $field['CODE'] == 'PROPERTY_LINK_TYPE') continue;
    if ($isPhoneEmailFields && $field['CODE'] == 'PROPERTY_EMAIL') continue;
    if (!empty($socials) && $field['CODE'] !== $firstSocialProp && in_array($field['CODE'], $socialProps)) continue;
    if (in_array($field['CODE'], $arPopupFields)) continue;
    if (!$useFontAwesome && $fieldCode == 'PROPERTY_FA_CLASS') continue;
    if ($isIntervalTime && $isShownIntervalTimeField && in_array($field['CODE'], Fields\IntervalTime::getFullPropertyCodes())) continue;
    ?>

    <?if($isBtnFields && $field['CODE'] == 'PROPERTY_BTN_SHOW' || $isBtn2Fields && $field['CODE'] == 'PROPERTY_BTN_SHOW_2'):
        $preFix = 'PROPERTY_';
        $postFix = $field['CODE'] == 'PROPERTY_BTN_SHOW_2' ? '_2' : '';
        $btnId = 'CARD' . $postFix;
        $btnShow = $field['IS_CHECKED'] ? 'Y' : 'N';
        $btnShowValue = $arResult['FIELDS']['PROPERTY_BTN_SHOW' . $postFix]['VALUE'];
        $btnType = $arResult['FIELDS']['PROPERTY_BTN_TYPE' . $postFix]['VALUE'];
        $btnSize = $arResult['FIELDS']['PROPERTY_BTN_SIZE' . $postFix]['VALUE'];
        $btnText = $arResult['FIELDS']['PROPERTY_BTN_TEXT' . $postFix]['VALUE'];
        $btnLinkType = $arResult['FIELDS']['PROPERTY_BTN_LINK_TYPE' . $postFix]['VALUE'];
        $btnLink = $arResult['FIELDS']['PROPERTY_BTN_LINK' . $postFix]['VALUE'];
        $btnGoal = $arResult['FIELDS']['PROPERTY_BTN_GOAL' . $postFix]['VALUE'];
        $btnClass = $arResult['FIELDS']['PROPERTY_BTN_CLASS' . $postFix]['VALUE'];
        $btnTypeValues = $arResult['BTN_TYPE' . $postFix . '_VALUES'];
        $btnSizeValues = $arResult['BTN_SIZE' . $postFix . '_VALUES'];
        $btnAnchors = $arResult['ANCHORS'];
        $btnForms = $arResult['FORMS'];

        include __DIR__ . '/../include/btn.php';
    ?>

    <?elseif ($isPopup && $field['CODE'] == 'PROPERTY_POPUP_SHOW'):?>

    <div class="panel-acc <?if($field['IS_CHECKED']):?>open<?endif?>">
        <div class="panel-acc-header theme-color-hover-parent">

            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="panelPopup<?=$field['ID']?>" value="<?=$field['VALUE']?>"
                       name="<?=$field['NAME']?>" <?if($field['IS_CHECKED']):?>checked<?endif?>>
                <label class="custom-control-label" for="panelPopup<?=$field['ID']?>"></label>
            </div>

            <div class="panel-acc-title">
                <?=$field['TITLE']?>
            </div>

            <div class="panel-acc-opened theme-color-hover"><?= Helper::svg('panel', 'acc_up') ?></div>
            <div class="panel-acc-closed theme-color-hover"><?= Helper::svg('panel', 'acc_down') ?></div>

        </div>
        <div class="panel-acc-body">
            <?foreach ($arPopupFields as $popupFieldName):
                $field = $arResult['FIELDS'][$popupFieldName];

                if ($field['TYPE'] === 'file'):
                    include __DIR__ . '/../include/field/file.php';
                ?>

                <?elseif ($field['TYPE'] === 'string'):?>
                    <div class="form-group has-field-cleaner">
                        <label><?=$field['TITLE']?></label>

                        <?if(!$field['MULTI']):?>
                            <div class="cleaner-wrap">
                                <input type="text" name="<?=$field['NAME']?><?if($field['WITH_DESC']):?>[VALUE]<?endif?>" class="form-control"
                                   value="<?=$field['VALUE']?>" <?if(!empty($field['HINT'])):?>placeholder="<?=$field['HINT']?>"<?endif?> />
                                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                                    <?=Helper::svg('panel', 'remove')?>
                                </div>
                            </div>

                            <?if($field['WITH_DESC']):?>
                                <div class="cleaner-wrap">
                                    <input type="text" name="<?=$field['NAME']?>[DESCRIPTION]" class="form-control"
                                           value="<?=$field['DESC']?>" placeholder="<?= ($field['DESC_HINT'] ?: Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_FIELD_DESC')) ?>">
                                    <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                                        <?=Helper::svg('panel', 'remove')?>
                                    </div>
                                </div>
                            <?endif?>

                            <?if($fieldCode == 'PROPERTY_FA_CLASS'):?>
                                <a class="panel-card-sublink" href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_FA_LINK') ?></a>
                            <?endif?>

                        <?else:?>
                            <?if(!empty($field['VALUE'])):?>
                                <?foreach($field['VALUE'] as $val):?>
                                    <div class="cleaner-wrap">
                                        <input type="text" name="<?=$field['NAME']?>" class="form-control"
                                               value="<?=$val?>" />
                                        <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                                            <?=Helper::svg('panel', 'remove')?>
                                        </div>
                                    </div>
                                <?endforeach?>
                            <?endif?>
                            <div class="cleaner-wrap">
                                <input type="text" name="<?=$field['NAME']?>" class="form-control" value="" />
                                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                                    <?=Helper::svg('panel', 'remove')?>
                                </div>
                            </div>
                            <a href="#" class="form-group-pseudolink theme-border js-add-input-text"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_FIELD_ADD') ?></a>
                        <?endif?>
                    </div>

                <?elseif ($field['TYPE'] === 'text'):?>
                    <div class="form-group">
                        <label><?=$field['TITLE']?></label>
                        <? FormHelper::showTextField(
                            $field['NAME'], $field['ID'], $field['VALUE'], $field['OPTIONS']['SHOW_EDITOR'] ?? true);
                        ?>
                    </div>

                <?elseif ($field['TYPE'] === 'select'):?>
                    <div class="form-group">
                        <label><?=$field['TITLE']?></label>
                        <select name="<?=$field['NAME']?>" class="form-control" <?if($field['MULTI']):?>multiple<?endif?>>

                            <?foreach($field['VALUES'] as $valVal => $valId):var_dump($field['VALUE']);
                                if ($field['MULTI']) {
                                    $isSelected = in_array($valId, $field['VALUE']);
                                } else {
                                    $isSelected = $valId == $field['VALUE'];
                                }
                                ?>
                                <option value="<?=$valId?>" <?if($isSelected):?>selected<?endif?>><?=$valVal?></option>
                            <?endforeach?>

                        </select>
                    </div>

                <?elseif ($field['TYPE'] === 'checkbox'):?>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="<?=$field['ID']?>" name="<?=$field['NAME']?>"
                                   value="<?=$field['VALUE']?>" <?if($field['IS_CHECKED']):?>checked<?endif?> />
                            <label class="custom-control-label" for="<?=$field['ID']?>"><?=$field['TITLE']?></label>
                        </div>
                    </div>

                <?endif?>

            <?endforeach?>
        </div>
    </div>

    <?elseif($isLinkFields && $field['CODE'] == 'PROPERTY_LINK'):
    $linkTypeField = $arResult['FIELDS']['PROPERTY_LINK_TYPE'];
    ?>

    <?php
    $isLinkInternal = !$linkTypeField['VALUE'] || $linkTypeField['VALUE'] == 'internal';
    $isLinkExternal = $linkTypeField['VALUE'] == 'external';
    $isLinkAnchor = $linkTypeField['VALUE'] == 'anchor';
    $isLinkForm = $linkTypeField['VALUE'] == 'form';
    $isLinkService = $linkTypeField['VALUE'] == 'service';

    if ($isLinkService) {
        try {
            $field['VALUE'] = Json::decode(htmlspecialchars_decode($field['VALUE']));
        } catch (\Exception $e) {
            $field['VALUE'] = array_fill_keys(['NAME', 'PRICE', 'DISCOUNT'], '');
        }
    }
    ?>

    <div class="form-group js-panel-link">
        <div class="form-row">
            <div class="form-group col-4">
                <label><?=$field['TITLE']?></label>
                <select name="<?=$linkTypeField['NAME']?>" class="form-control" data-link-name="<?=$field['NAME']?>" data-link>
                    <option value="internal" <?if($isLinkInternal):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_CARD_BTN_LINK_TYPE_INTERNAL') ?></option>
                    <option value="external" <?if($isLinkExternal):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_CARD_BTN_LINK_TYPE_EXTERNAL') ?></option>
                    <option value="anchor" <?if($isLinkAnchor):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_CARD_BTN_LINK_TYPE_ANCHOR') ?></option>
                    <option value="form" <?if($isLinkForm):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_CARD_BTN_LINK_TYPE_FORM') ?></option>
                    <?if ($isAllowedServiceLink):?>
                        <option value="service" <?if($isLinkService):?>selected<?endif;?>><?= Loc::getMessage('RX_PANEL_LANDING_CARD_BTN_LINK_TYPE_SERVICE') ?></option>
                    <?endif;?>
                </select>
            </div>
            <div class="form-group col-8 has-field-cleaner" data-link-type="internal" <?if(!$isLinkInternal):?>style="display:none"<?endif?>>
                <label>&nbsp;</label>
                <input type="text" name="<?if($isLinkInternal):?><?=$field['NAME']?><?endif?>" class="form-control" value="<?if($isLinkInternal):?><?=$field['VALUE']?><?endif?>" placeholder="/about/" />
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>
            <div class="form-group col-8 has-field-cleaner" data-link-type="external" <?if(!$isLinkExternal):?>style="display:none"<?endif?>>
                <label>&nbsp;</label>
                <input type="text" name="<?if($isLinkExternal):?><?=$field['NAME']?><?endif?>" class="form-control" value="<?if($isLinkExternal):?><?=$field['VALUE']?><?endif?>" placeholder="https://ranx.ru" />
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>
            <div class="form-group col-8" data-link-type="anchor" <?if(!$isLinkAnchor):?>style="display:none"<?endif?>>
                <label>&nbsp;</label>
                <select name="<?if($isLinkAnchor):?><?=$field['NAME']?><?endif?>" class="form-control">
                    <?foreach($arResult['ANCHORS'] as $anchorId => $anchor):
                        $isSelected = $field['VALUE'] == '#block_' . $anchorId;
                        ?>
                        <option value="#block_<?=$anchorId?>"><?=$anchor?></option>
                    <?endforeach?>
                </select>
            </div>
            <div class="form-group col-8" data-link-type="form" <?if(!$isLinkForm):?>style="display:none"<?endif?>>
                <label>&nbsp;</label>
                <select name="<?if($isLinkForm):?><?=$field['NAME']?><?endif?>" class="form-control">
                    <?foreach($arResult['FORMS'] as $formCode => $formName):
                        $isSelected = $field['VALUE'] == $formCode;
                        ?>
                        <option value="<?=$formCode?>" <?if($isSelected):?>selected<?endif?>><?=$formName?></option>
                    <?endforeach?>
                </select>
            </div>
            <?if($isAllowedServiceLink && Config::isOrderEnabled()):?>
                <div class="form-group col-8" data-link-type="service" <?if(!$isLinkService):?>style="display:none"<?endif?>>
                    <label><?=Loc::getMessage('RX_PANEL_LANDING_LINK_SERVICE_PRODUCT_NAME')?></label>
                    <input type="text" name="<?if($isLinkService):?><?=$field['NAME']?>[NAME]<?endif?>"
                           data-link-name="<?=$field['NAME']?>[NAME]" class="form-control"
                           value="<?if($isLinkService):?><?=$field['VALUE']['NAME'] ?? ''?><?endif?>" />
                    <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                        <?=Helper::svg('panel', 'remove')?>
                    </div>
                </div>
            <?endif;?>
        </div>

        <?php
        if($isAllowedServiceLink && Config::isOrderEnabled()) {
            $showNames = $isLinkService;
            $priceName = $field['NAME'].'[PRICE]';
            $discountPriceName = $field['NAME'].'[DISCOUNT]';
            $discountTypeName = $field['NAME'].'[DTYPE]';
            $priceValue = is_array($field['VALUE']) ? $field['VALUE']['PRICE'] : '';
            $discountPriceValue = is_array($field['VALUE']) ? $field['VALUE']['DISCOUNT'] : '';
            $discountTypeValue = is_array($field['VALUE']) ? $field['VALUE']['DTYPE'] : '';
            if ($discountTypeValue === 'percent') {
                $discountPriceValue .= '%';
            }

            $priceRowAttrs = 'data-link-type="service"' .  (!$isLinkService ? ' style="display:none"' : '');
            include __DIR__ . '/../include/field/price.php';
        }
        ?>
    </div>

    <?elseif(!empty($socials) && $field['CODE'] == $firstSocialProp):?>

        <div class="form-row">

            <?foreach($socials as $i => $social):
                $socialProp = 'PROPERTY_' . $social;
                $socialField = $arResult['FIELDS'][$socialProp];
            ?>
                <div class="form-group col-6">
                    <?if($i == 0):?>
                        <label><?= Loc::getMessage('RX_PANEL_LANDING_CARD_SOCIALS_TITLE') ?></label>
                    <?elseif($i == 1):?>
                        <label>&nbsp;</label>
                    <?endif?>
                    <div class="form-social-icon-parent">
                        <input type="text" name="<?=$socialField['NAME']?>" class="form-control"
                               value="<?=$socialField['VALUE']?>" placeholder="<?=$socialField['TITLE']?>" />
                        <div class="form-social-icon">
                            <?= Helper::svg('block/social', strtolower($social)) ?>
                        </div>
                    </div>
                </div>
            <?endforeach?>

        </div>

    <?elseif($isPhoneEmailFields && $field['CODE'] == 'PROPERTY_PHONE'):
    $emailField = $arResult['FIELDS']['PROPERTY_EMAIL'];
    ?>

        <div class="form-row">
            <div class="form-group col-md-6 has-field-cleaner">
                <label><?=$field['TITLE']?></label>
                <input type="text" name="<?=$field['NAME']?>" class="form-control"
                       value="<?=$field['VALUE']?>" />
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>

            <div class="form-group col-md-6 has-field-cleaner">
                <label><?=$emailField['TITLE']?></label>
                <input type="text" name="<?=$emailField['NAME']?>" class="form-control"
                       value="<?=$emailField['VALUE']?>" />
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>
        </div>

    <?elseif($isPriceFields && $field['CODE'] == 'PROPERTY_PRICE'):

        $priceName = $field['NAME'];
        $priceValue = $field['VALUE'];
        $discountPriceField = $arResult['FIELDS']['PROPERTY_DISCOUNT_PRICE'];
        $discountPriceName = $discountPriceField['NAME'];
        $discountPriceValue = $discountPriceField['VALUE'];
        
        include __DIR__ . '/../include/field/price.php';
    ?>

    <?elseif($isIntervalTime && !$isShownIntervalTimeField && in_array($field['CODE'], Fields\IntervalTime::getFullPropertyCodes())):
        $isShownIntervalTimeField = true;
        $fromValue = $arResult['FIELDS']['PROPERTY_'.Fields\IntervalTime::getFromPropertyCode()]['VALUE'];
        $toValue = $arResult['FIELDS']['PROPERTY_'.Fields\IntervalTime::getToPropertyCode()]['VALUE'];
    ?>

        <div class="form-group has-field-cloak">
            <label><?=$field['TITLE']?></label>
            <input type="text" name="<?=Fields\IntervalTime::getInputName()?>" class="form-control js-mask-interval-time"
                   value="<?=Fields\IntervalTime::decodeValue($fromValue, $toValue)?>" />
            <div class="form-control-filed-icon theme-color">
                <?=Helper::svg('panel', 'cloak');?>
            </div>
        </div>

    <?elseif($field['TYPE'] == 'string'):?>
        <div class="form-group has-field-cleaner">
            <label><?=$field['TITLE']?></label>

            <?if(!$field['MULTI']):?>
                <div class="cleaner-wrap">
                    <input type="text" name="<?=$field['NAME']?><?if($field['WITH_DESC']):?>[VALUE]<?endif?>" class="form-control"
                           value="<?=$field['VALUE']?>" <?if(!empty($field['HINT'])):?>placeholder="<?=$field['HINT']?>"<?endif?> />
                    <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                        <?=Helper::svg('panel', 'remove')?>
                    </div>
                </div>

                <?if($field['WITH_DESC']):?>
                    <div class="cleaner-wrap">
                        <input type="text" name="<?=$field['NAME']?>[DESCRIPTION]" class="form-control"
                               value="<?=$field['DESC']?>" placeholder="<?= ($field['DESC_HINT'] ?: Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_FIELD_DESC')) ?>">
                        <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                            <?=Helper::svg('panel', 'remove')?>
                        </div>
                    </div>
                <?endif?>

                <?if($fieldCode == 'PROPERTY_FA_CLASS'):?>
                    <a class="panel-card-sublink" href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_FA_LINK') ?></a>
                <?endif?>

            <?else:?>
                <?if(!empty($field['VALUE'])):?>
                    <?foreach($field['VALUE'] as $val):?>
                        <div class="cleaner-wrap">
                            <input type="text" name="<?=$field['NAME']?>" class="form-control"
                                   value="<?=$val?>" />
                            <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                                <?=Helper::svg('panel', 'remove')?>
                            </div>
                        </div>
                    <?endforeach?>
                <?endif?>
                <div class="cleaner-wrap">
                    <input type="text" name="<?=$field['NAME']?>" class="form-control" value="" />
                    <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                        <?=Helper::svg('panel', 'remove')?>
                    </div>
                </div>
                <a href="#" class="form-group-pseudolink theme-border js-add-input-text"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_FIELD_ADD') ?></a>
            <?endif?>

        </div>
    <?elseif($field['TYPE'] == 'number'):?>
        <div class="form-group has-field-cleaner">
            <label><?=$field['TITLE']?></label>

            <?if(!$field['MULTI']):?>
                <div class="cleaner-wrap">
                    <input type="text" name="<?=$field['NAME']?>" class="form-control js-mask-integer"
                           value="<?=$field['VALUE']?>" />
                    <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                        <?=Helper::svg('panel', 'remove')?>
                    </div>
                </div>

            <?else:?>
                <?if(!empty($field['VALUE'])):?>
                    <?foreach($field['VALUE'] as $val):?>
                        <div class="cleaner-wrap">
                            <input type="text" name="<?=$field['NAME']?>" class="form-control js-mask-integer"
                                   value="<?=$val?>" />
                            <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                                <?=Helper::svg('panel', 'remove')?>
                            </div>
                        </div>
                    <?endforeach?>
                <?endif?>
                <div class="cleaner-wrap">
                    <input type="text" name="<?=$field['NAME']?>" class="form-control js-mask-integer" value="" />
                    <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                        <?=Helper::svg('panel', 'remove')?>
                    </div>
                </div>
                <a href="#" class="form-group-pseudolink theme-border js-add-input-text"><?= Loc::getMessage('RX_PANEL_LANDING_CONTENT_CARDS_FIELD_ADD') ?></a>
            <?endif?>
        </div>
    <?elseif($field['TYPE'] == 'date'):?>

        <? if($nextField
            && in_array($field['NAME'], ['ACTIVE_FROM', 'ACTIVE_TO'])
            && in_array($nextField['NAME'], ['ACTIVE_FROM', 'ACTIVE_TO'])): ?>

            <div class="form-row">
                <div class="form-group col-6 has-field-cleaner">
                    <label><?=$field['TITLE']?></label>
                    <input
                        type="text" name="<?= $field['NAME'] ?>"
                        class="form-control js-mask-date js-datepicker"
                        value="<?= $field['VALUE'] ?>"
                        data-format="dd.mm.yyyy"
                        <?if(!empty($field['VALUE'])):?>data-default-date="<?= (new Date($field['VALUE']))->format('Y-m-d') ?>"<?endif?>
                    />
                    <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                        <?=Helper::svg('panel', 'remove')?>
                    </div>
                </div>
                <div class="form-group col-6 has-field-cleaner">
                    <label><?=$nextField['TITLE']?></label>

                    <input
                        type="text" name="<?= $nextField['NAME'] ?>"
                        class="form-control js-mask-date js-datepicker"
                        value="<?= $nextField['VALUE'] ?>"
                        data-format="dd.mm.yyyy"
                        <?if(!empty($nextField['VALUE'])):?>data-default-date="<?= (new Date($nextField['VALUE']))->format('Y-m-d') ?>"<?endif?>
                    />
                    <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                        <?=Helper::svg('panel', 'remove')?>
                    </div>
                </div>
            </div>

        <? elseif(!$prevField
            || !in_array($field['NAME'], ['ACTIVE_FROM', 'ACTIVE_TO'])
            || !in_array($prevField['NAME'], ['ACTIVE_FROM', 'ACTIVE_TO'])): ?>

            <div class="form-group has-field-cleaner date">
                <label><?=$field['TITLE']?></label>

                <input
                    type="text" name="<?= $field['NAME'] ?>"
                    class="form-control js-mask-date js-datepicker"
                    value="<?= $field['VALUE'] ?>"
                    data-format="dd.mm.yyyy"
                    <?if(!empty($field['VALUE'])):?>data-default-date="<?= (new Date($field['VALUE']))->format('Y-m-d') ?>"<?endif?>
                />
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>

            </div>

        <? endif ?>

    <?elseif($field['TYPE'] == 'text'):?>

        <div class="form-group">
            <label><?=$field['TITLE']?></label>
            <? FormHelper::showTextField(
                $field['NAME'], $field['ID'], $field['VALUE'], $field['OPTIONS']['SHOW_EDITOR'] ?? true);
            ?>
        </div>

    <?elseif($field['TYPE'] == 'file'):
        include __DIR__ . '/../include/field/file.php';
    ?>

    <?elseif($field['TYPE'] == 'checkbox'):?>

        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="<?=$field['ID']?>" name="<?=$field['NAME']?>"
                       value="<?=$field['VALUE']?>" <?if($field['IS_CHECKED']):?>checked<?endif?> />
                <label class="custom-control-label" for="<?=$field['ID']?>"><?=$field['TITLE']?></label>
            </div>
        </div>

    <?elseif($field['TYPE'] == 'select'):?>

        <div class="form-group">
            <label><?=$field['TITLE']?></label>
            <select name="<?=$field['NAME']?>" class="form-control" <?if($field['MULTI']):?>multiple<?endif?>>

                <?if($field['MULTI']):?>
                    <option value="" disabled>
                        <?= Loc::getMessage('RX_PANEL_LANDING_CARD_MULTISELECT_DEFAULT') ?>
                    </option>
                <?endif?>

                <?foreach($field['VALUES'] as $valVal => $valId):
                    if ($field['MULTI'] && is_array($field['VALUE'])) {
                        $isSelected = in_array($valId, $field['VALUE']);
                    } else {
                        $isSelected = $valId == $field['VALUE'];
                    }
                    ?>
                    <option value="<?=$valId?>" <?if($isSelected):?>selected<?endif?>><?=$valVal?></option>
                <?endforeach?>

            </select>
        </div>

    <?elseif($field['TYPE'] == 'map'):?>

        <div class="form-row">
            <div class="form-group col-md-6 has-field-cleaner">
                <label><?=$field['TITLE']?></label>
                <input type="text" name="<?=$field['NAME']?>[LAT]" placeholder="<?=Loc::getMessage('RX_PANEL_LANDING_CARD_MAP_LAT')?>"
                       class="form-control" value="<?=$field['LAT']?>" />
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>

            <div class="form-group col-md-6 has-field-cleaner">
                <label>&nbsp;</label>
                <input type="text" name="<?=$field['NAME']?>[LON]" placeholder="<?=Loc::getMessage('RX_PANEL_LANDING_CARD_MAP_LON')?>"
                       class="form-control" value="<?=$field['LON']?>" />
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>
        </div>

    <?endif?>

<?endforeach?>