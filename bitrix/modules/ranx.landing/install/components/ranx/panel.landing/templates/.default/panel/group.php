<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

/**
 * @var array $arResult
 */

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<div class="panel-group-body">

    <input type="hidden" name="id" value="<?= $arResult['ID'] ?>">
    <input type="hidden" name="blockId" value="<?= $arResult['BLOCK_ID'] ?>">

    <div class="panel-cards js-sortable">

        <?foreach($arResult['BLOCKS'] as $arBlock):?>
        <div class="panel-card <?if($arBlock['ACTIVE'] == 'Y'):?>active<?endif?>" data-id="<?= $arBlock['ID'] ?>">

            <div class="panel-card-header">

                <div class="panel-card-title js-panel-variant theme-color-hover theme-border-hover">
                    <?if(!empty($arBlock['NAME'])):?>
                        <?= Helper::cutName($arBlock['NAME'], 26) ?>
                    <?else:?>    
                        <?= Loc::getMessage('RX_PANEL_LANDING_GROUP_CARD_EMPTY_TITLE') ?>
                    <?endif?>
                </div>
                <div class="panel-card-actions">
                    <a href="#" class="js-panel-variant-edit"><?= Loc::getMessage('RX_PANEL_LANDING_GROUP_CARD_EDIT') ?></a>
                    <a href="#" class="panel-card-action-deact js-panel-variant-deact"><?= Loc::getMessage('RX_PANEL_LANDING_GROUP_CARD_DEACT') ?></a>
                    <a href="#" class="panel-card-action-act js-panel-variant-act"><?= Loc::getMessage('RX_PANEL_LANDING_GROUP_CARD_ACT') ?></a>
                    <a href="#" class="js-panel-variant-remove"><?= Loc::getMessage('RX_PANEL_LANDING_GROUP_CARD_REMOVE') ?></a>
                    <div class="panel-card-drag"><?= Helper::svg('panel', 'drag_drop') ?></div>
                </div>
            </div>

        </div>
        <?endforeach;?>

    </div>

    <button class="btn btn-primary btn-block js-panel-variant-add"><?= Loc::getMessage('RX_PANEL_LANDING_GROUP_CARD_ADD') ?></button>

</div>
