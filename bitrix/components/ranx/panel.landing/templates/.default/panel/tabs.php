<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

use Ranx\Landing\BlockTabs;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<? $arResult['TABS'][] = BlockTabs::getTemplateTab();?>

<input type="hidden" name="blockId" value="<?=$arResult['BLOCK_ID']?>">
<input type="hidden" name="tabId" value="<?=$arResult['TAB_ID']?>">

<button class="btn btn-primary btn-block js-panel-tabs-add">
    <?=Loc::getMessage('RX_PANEL_LANDING_TABS_CARD_ADD')?>
</button>

<div class="panel-cards js-sortable">
    <?foreach($arResult['TABS'] as $arTab):?>
        <div class="panel-card
            <?if($arTab['ACTIVE'] == 'Y'):?>active<?endif?>
            <?if(empty($arTab['ID'])):?>template<?endif?>
            <?if(!empty($arResult['TAB_ID']) && $arTab['ID'] == $arResult['TAB_ID']):?>theme-border<?endif?>">

            <input type="hidden" name="ID[]" value="<?=$arTab['ID']?>">
            <input type="hidden" name="ACTIVE[]" value="<?=$arTab['ACTIVE']?>">

            <div class="panel-card-header">
                <div class="panel-card-title theme-color-hover theme-border-hover js-panel-tabs-title">
                    <?=$arTab['FORMAT_NAME']?>
                </div>
                <div class="panel-card-actions">
                    <a href="#" class="panel-card-action-deact js-panel-tabs-deact">
                        <?=Loc::getMessage('RX_PANEL_LANDING_TABS_CARD_DEACT')?>
                    </a>
                    <a href="#" class="panel-card-action-act js-panel-tabs-act">
                        <?=Loc::getMessage('RX_PANEL_LANDING_TABS_CARD_ACT')?>
                    </a>
                    <a href="#" class="js-panel-tabs-remove">
                        <?=Loc::getMessage('RX_PANEL_LANDING_TABS_CARD_REMOVE')?>
                    </a>
                    <div class="panel-card-drag"><?= Helper::svg('panel', 'drag_drop') ?></div>
                </div>
            </div>

            <div class="panel-card-body">
                <div class="form-group">
                    <label><?= Loc::getMessage('RX_PANEL_LANDING_TABS_CARD_FIELD_NAME') ?></label>
                    <input type="text" name="NAME[]" class="form-control js-panel-tabs-name" value="<?=htmlspecialchars($arTab['NAME'])?>">
                </div>
            </div>

        </div>
    <?endforeach;?>
</div>

<div class="panel-body-desc">
    <p><?= Loc::getMessage('RX_PANEL_LANDING_TABS_DESC') ?></p>
</div>
