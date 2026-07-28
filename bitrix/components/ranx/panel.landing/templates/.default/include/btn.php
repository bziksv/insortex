<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var string $btnId
 * @var string $preFix
 * @var string $postFix
 * @var string $btnShow
 * @var string $btnShowValue
 * @var string $btnType
 * @var string $btnSize
 * @var string $btnText
 * @var string $btnLinkType
 * @var string $btnLink
 * @var string $btnGoal
 * @var string $btnClass
 *
 * @var boolean $hideBtnLinkTypeAnchor
 *
 * @var array $btnTypeValues
 * @var array $btnSizeValues
 * @var array $btnAnchors
 * @var array $btnForms
 */

use Ranx\Landing\Config;
use Bitrix\Main\Web\Json;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
$isTabChecked = $btnShow == 'Y';
$useBasket = Config::isOrderEnabled();
?>

<div class="panel-acc <?if($isTabChecked):?>open<?endif?>">
    <div class="panel-acc-header theme-color-hover-parent">

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="panelBtn<?=$btnId?>" value="<?= $btnShowValue ?: 'Y' ?>"
                   name="<?=$preFix?>BTN_SHOW<?=$postFix?>" <?if($isTabChecked):?>checked<?endif?>>
            <label class="custom-control-label" for="panelBtn<?=$btnId?>"></label>
        </div>

        <div class="panel-acc-title">
            <?if(!empty($btnText)):?>
                <?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_ACC_TITLE') ?>: <?= Helper::cutName($btnText, 30) ?>
            <?else:?>
                <?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_ACC_TITLE') ?>
            <?endif?>
        </div>

        <div class="panel-acc-opened theme-color-hover"><?= Helper::svg('panel', 'acc_up') ?></div>
        <div class="panel-acc-closed theme-color-hover"><?= Helper::svg('panel', 'acc_down') ?></div>

    </div>
    <div class="panel-acc-body">
        <?if (empty($hideBtnType) || empty($hideBtnSize)):?>
        <div class="form-row">
            <?if(empty($hideBtnType)):?>
            <div class="form-group col-6">
                <label><?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_TYPE') ?></label>
                <select name="<?=$preFix?>BTN_TYPE<?=$postFix?>" class="form-control">

                    <?foreach($btnTypeValues as $typeKey => $typeVal):
                        $isSelected = $btnType == $typeVal;
                    ?>
                        <option value="<?=$typeVal?>" <?if($isSelected):?>selected<?endif?>><?=$typeKey?></option>
                    <?endforeach?>

                </select>
            </div>
            <?endif?>
            <?if(empty($hideBtnSize)):?>
            <div class="form-group col-6">
                <label><?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_SIZE') ?></label>
                <select name="<?=$preFix?>BTN_SIZE<?=$postFix?>" class="form-control">

                    <option value="" <?if(empty($btnSize)):?>selected<?endif?>>
                        <?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_SIZE_DEFAULT') ?>
                    </option>

                    <?foreach($btnSizeValues as $sizeKey => $sizeVal):
                        if (!$sizeVal) continue;
                        $isSelected = $btnSize == $sizeVal;
                    ?>
                        <option value="<?=$sizeVal?>" <?if($isSelected):?>selected<?endif?>><?=$sizeKey?></option>
                    <?endforeach?>

                </select>
            </div>
            <?endif?>
        </div>
        <?endif?>
        <div class="form-group has-field-cleaner">
            <label><?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_TEXT') ?></label>
            <input type="text" name="<?=$preFix?>BTN_TEXT<?=$postFix?>" class="form-control" value="<?=$btnText?>" />
            <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                <?=Helper::svg('panel', 'remove')?>
            </div>
        </div>

        <?php
        $isLinkInternal = !$btnLinkType || $btnLinkType === 'internal';
        $isLinkExternal = $btnLinkType === 'external';
        $isLinkAnchor = $btnLinkType === 'anchor';
        $isLinkForm = $btnLinkType === 'form';
        $isLinkBuy = $btnLinkType === 'buy';

        if ($isLinkBuy) {
            try {
                $btnLink = Json::decode(htmlspecialchars_decode($btnLink));
            } catch (Exception $e) {
                $btnLink = [
                    'NAME' => '',
                    'PRICE' => '',
                    'DISCOUNT' => '',
                ];
            }
        }
        ?>
        <div class="js-panel-link">
            <div class="form-row">
                <div class="form-group col-4">
                    <label><?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_LINK') ?></label>
                    <select name="<?=$preFix?>BTN_LINK_TYPE<?=$postFix?>" class="form-control" data-link-name="<?=$preFix?>BTN_LINK<?=$postFix?>" data-link>
                        <option value="internal" <?if($isLinkInternal):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_LINK_TYPE_INTERNAL') ?></option>
                        <option value="external" <?if($isLinkExternal):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_LINK_TYPE_EXTERNAL') ?></option>
                        <?if(empty($hideBtnLinkTypeAnchor)):?><option value="anchor" <?if($isLinkAnchor):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_LINK_TYPE_ANCHOR') ?></option><?endif?>
                        <option value="form" <?if($isLinkForm):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_LINK_TYPE_FORM') ?></option>
                        <?if($useBasket):?><option value="buy" <?if($isLinkBuy):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_LINK_TYPE_BUY') ?></option><?endif?>
                    </select>
                </div>
                <div class="form-group col-8 has-field-cleaner" data-link-type="internal" <?if(!$isLinkInternal):?>style="display:none"<?endif?>>
                    <label>&nbsp;</label>
                    <input type="text" name="<?if($isLinkInternal):?><?=$preFix?>BTN_LINK<?=$postFix?><?endif?>" class="form-control" value="<?if($isLinkInternal):?><?=$btnLink?><?endif?>" placeholder="/about/" />
                    <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                        <?=Helper::svg('panel', 'remove')?>
                    </div>
                </div>
                <div class="form-group col-8 has-field-cleaner" data-link-type="external" <?if(!$isLinkExternal):?>style="display:none"<?endif?>>
                    <label>&nbsp;</label>
                    <input type="text" name="<?if($isLinkExternal):?><?=$preFix?>BTN_LINK<?=$postFix?><?endif?>" class="form-control" value="<?if($isLinkExternal):?><?=$btnLink?><?endif?>" placeholder="https://ranx.ru" />
                    <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                        <?=Helper::svg('panel', 'remove')?>
                    </div>
                </div>
                <?if(empty($hideBtnLinkTypeAnchor)):?>
                    <div class="form-group col-8" data-link-type="anchor" <?if(!$isLinkAnchor):?>style="display:none"<?endif?>>
                        <label>&nbsp;</label>
                        <select name="<?if($isLinkAnchor):?><?=$preFix?>BTN_LINK<?=$postFix?><?endif?>" class="form-control">
                            <?foreach($btnAnchors as $anchorId => $anchor):
                                $isSelected = $btnLink == '#block_' . $anchorId;
                            ?>
                                <option value="#block_<?=$anchorId?>" <?if($isSelected):?>selected<?endif?>><?=$anchor?></option>
                            <?endforeach?>
                        </select>
                    </div>
                <?endif?>
                <div class="form-group col-8" data-link-type="form" <?if(!$isLinkForm):?>style="display:none"<?endif?>>
                    <label>&nbsp;</label>
                    <select name="<?if($isLinkForm):?><?=$preFix?>BTN_LINK<?=$postFix?><?endif?>" class="form-control">
                        <?foreach($btnForms as $formCode => $formName):
                            $isSelected = $btnLink == $formCode;
                        ?>
                            <option value="<?=$formCode?>" <?if($isSelected):?>selected<?endif?>><?=$formName?></option>
                        <?endforeach?>
                    </select>
                </div>

                <?if($useBasket):?>
                    <div class="form-group col-8" data-link-type="buy" <?if(!$isLinkBuy):?>style="display:none"<?endif?>>
                        <label><?=Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_BUY_PRODUCT_NAME')?></label>
                        <input type="text" name="<?if($isLinkBuy):?><?=$preFix?>BTN_LINK<?=$postFix?>[NAME]<?endif?>"
                            data-link-name="<?=$preFix?>BTN_LINK<?=$postFix?>[NAME]"
                            class="form-control" value="<?if($isLinkBuy):?><?=($btnLink['NAME'] ?? '')?><?endif?>" />
                        <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                            <?=Helper::svg('panel', 'remove')?>
                        </div>
                    </div>
                <?endif?>

            </div>

            <?php
                if($useBasket) {
                    $showNames = $isLinkBuy;
                    $priceName = $preFix . 'BTN_LINK' . $postFix . '[PRICE]';
                    $discountPriceName = $preFix . 'BTN_LINK' . $postFix . '[DISCOUNT]';
                    $discountTypeName = $preFix . 'BTN_LINK' . $postFix . '[DTYPE]';
                    $priceValue = is_array($btnLink) ? $btnLink['PRICE'] : '';
                    $discountPriceValue = is_array($btnLink) ? $btnLink['DISCOUNT'] : '';

                    $discountTypeValue = is_array($btnLink) ? $btnLink['DTYPE'] : '';
                    if ($discountTypeValue === 'percent') {
                        $discountPriceValue .= '%';
                    }

                    $priceRowAttrs = 'data-link-type="buy"' .  (!$isLinkBuy ? ' style="display:none"' : '');

                    include __DIR__ . '/field/price.php';
                }
            ?>

        </div>

        <?if(Config::getYametrikaCounter() && Config::useYametrikaGoals()):?>
            <div class="form-group has-field-cleaner">
                <label><?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_GOAL') ?></label>
                <input type="text" name="<?=$preFix?>BTN_GOAL<?=$postFix?>" class="form-control" value="<?=$btnGoal?>" />
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>
        <?endif?>

        <?if(Config::useCssClasses()):?>
            <div class="form-group has-field-cleaner">
                <label><?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_BTN_CLASS') ?></label>
                <input type="text" name="<?=$preFix?>BTN_CLASS<?=$postFix?>" class="form-control" value="<?=$btnClass?>" />
                <div class="form-control-field-cleaner js-clear-text-field theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>
        <?endif?>
    </div>
</div>
