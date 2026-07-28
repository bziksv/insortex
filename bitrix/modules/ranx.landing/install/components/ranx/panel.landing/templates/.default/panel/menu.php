<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 */

use Bitrix\Main\Web\Json;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<button class="btn btn-primary btn-block js-panel-menu-add"><?= Loc::getMessage('RX_PANEL_LANDING_MENU_CARD_ADD') ?></button>

<div class="panel-cards js-sortable">

    <input type="hidden" name="path" value="<?= $arResult['DIR'] ?>">

    <?foreach($arResult['LINKS'] as $key => $arItem):
        if ($pos = strpos($arItem[1], '#block_')) {
            $selectedAnchorId = substr($arItem[1], $pos + strlen('#block_'));
        }

        $isLanding = empty($arItem[1]) || !empty($arResult['LANDINGS'][$arItem[1]]);
        $isAnchor = !empty($arResult['ANCHORS'][$selectedAnchorId]);
        $isCustom = !$isLanding && !$isAnchor;
    ?>
    <div class="panel-card <?if($arItem[3]['HIDDEN'] !== 'Y'):?>active<?endif?> <?if(!$key):?>panel-card--example<?endif?>">

        <input type="hidden" name="ITEM_LINKS[]" value='<?= Json::encode($arItem[2]) ?>'>
        <input type="hidden" name="ITEM_PARAMS[]" value='<?= Json::encode($arItem[3]) ?>'>
        <input type="hidden" name="ITEM_RULE[]" value="<?= $arItem[4] ?>">

        <input type="hidden" name="ITEM_HIDDEN[]" value="<?= ($arItem[3]['HIDDEN'] === 'Y' ? 'Y' : 'N')?>">
        <input type="hidden" name="ITEM_WIDE[]" value="<?= ($arItem[3]['FULL_DROPDOWN'] === 'Y' ? 'Y' : 'N')?>">

        <div class="panel-card-header">

            <div class="panel-card-title theme-color-hover theme-border-hover"><?= Helper::cutName($arItem[0], 15) ?></div>
            <div class="panel-card-menuwide form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input js-panel-menu-wide" id="panelMenuItemWide_<?= $key ?>"
                        <?if($arItem[3]['FULL_DROPDOWN'] === 'Y'):?>checked<?endif?>>
                    <label class="custom-control-label" for="panelMenuItemWide_<?= $key ?>">
                        <?= Loc::getMessage('RX_PANEL_LANDING_MENU_CARD_MENUWIDE') ?>
                    </label>
                </div>
            </div>
            <div class="panel-card-actions">
                <a href="#" class="panel-card-action-deact js-panel-menu-deact"><?= Loc::getMessage('RX_PANEL_LANDING_MENU_CARD_DEACT') ?></a>
                <a href="#" class="panel-card-action-act js-panel-menu-act"><?= Loc::getMessage('RX_PANEL_LANDING_MENU_CARD_ACT') ?></a>
                <a href="#" class="js-panel-menu-remove"><?= Loc::getMessage('RX_PANEL_LANDING_MENU_CARD_REMOVE') ?></a>
                <div class="panel-card-drag"><?= Helper::svg('panel', 'drag_drop') ?></div>
            </div>
        </div>

        <div class="panel-card-body">
            <div class="form-group">
                <label><?= Loc::getMessage('RX_PANEL_LANDING_MENU_CARD_FIELD_NAME') ?></label>
                <input type="text" name="ITEM_NAME[]" class="form-control" value="<?= $arItem[0] ?>">
            </div>
            <div class="form-row">
                <div class="form-group col-5">
                    <label><?= Loc::getMessage('RX_PANEL_LANDING_MENU_CARD_FIELD_LINK') ?></label>
                    <select class="form-control <?if(!$key):?>no-selectric<?endif?> js-panel-menu-linktype">
                        <option value="landing" <?if($isLanding):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_MENU_CARD_FIELD_LINK_TYPE_LANDING') ?></option>
                        <option value="anchor" <?if($isAnchor):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_MENU_CARD_FIELD_LINK_TYPE_ANCHOR') ?></option>
                        <option value="custom" <?if($isCustom):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_MENU_CARD_FIELD_LINK_TYPE_CUSTOM') ?></option>
                    </select>
                </div>
                <div class="form-group col-7" data-linktype="landing" <?if(!$isLanding):?>style="display: none;"<?endif?>>
                    <label>&nbsp;</label>
                    <select class="form-control <?if(!$key):?>no-selectric<?endif?>">
                        <option value=""><?= Loc::getMessage('RX_PANEL_LANDING_MENU_CARD_FIELD_LANDING_EMPTY') ?></option>
                        
                        <?if(!empty($arResult['LANDINGS'])):?>
                            <?foreach($arResult['LANDINGS'] as $landingLink => $landingName):
                                $isSelected = $landingLink == $arItem[1];
                            ?>
                                <option value="<?= $landingLink ?>" <?if($isSelected):?>selected<?endif?>><?= $landingName ?></option>
                            <?endforeach?>
                        <?endif?>
                        
                    </select>
                </div>
                <div class="form-group col-7" data-linktype="anchor" <?if(!$isAnchor):?>style="display: none;"<?endif?>>
                    <label>&nbsp;</label>
                    <select class="form-control <?if(!$key):?>no-selectric<?endif?>">
                        <option value=""><?= Loc::getMessage('RX_PANEL_LANDING_MENU_CARD_FIELD_ANCHOR_EMPTY') ?></option>

                        <?if(!empty($arResult['ANCHORS'])):?>
                            <?foreach ($arResult['ANCHORS'] as $anchorId => $anchorName):
                                $isSelected = $selectedAnchorId == $anchorId;
                            ?>
                                <option value="<?=$anchorId?>" <?if($isSelected):?>selected<?endif?>><?=$anchorName?></option>
                            <?endforeach?>
                        <?endif?>

                    </select>
                </div>
                <div class="form-group col-7" data-linktype="custom" <?if(!$isCustom):?>style="display: none;"<?endif?>>
                    <label>&nbsp;</label>
                    <input type="text" name="ITEM_LINK[]" class="form-control" value="<?= $arItem[1] ?>" placeholder="/example/">
                </div>
            </div>
        </div>

    </div>
    <?endforeach?>

</div>
