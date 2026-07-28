<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $blockInfo
 */

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

if (!$GLOBALS['USER']->CanDoOperation('rx_landing_block_edit') && !Config::isDemoLanding()) {
    return;
}
?>

<span class="block_edit theme-border block_edit_top"></span>
<span class="block_edit theme-border block_edit_bottom"></span>
<span class="block_edit theme-border block_edit_left"></span>
<span class="block_edit theme-border block_edit_right"></span>

<span class="block_edit theme-bg theme-bg-hover block_edit_plus_top js-block-prepend" data-open-panel="#panelLib" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_ADD')?>" data-toggle="tooltip" data-placement="top"><?=Helper::svg('panel', 'plus')?></span>
<span class="block_edit theme-bg theme-bg-hover block_edit_plus_bottom js-block-append" data-open-panel="#panelLib" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_ADD')?>" data-toggle="tooltip" data-placement="top"><?=Helper::svg('panel', 'plus')?></span>

<span class="block_edit block_edit_label theme-bg-hover theme-border-hover js-block-replace" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_LABEL_TITLE')?>"
      data-toggle="tooltip" data-placement="top"></span>

<div class="block_edit block_edit_btns">

    <span class="block_edit_edit block_edit_txtbtn theme-bg theme-bg-hover js-block-content" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_CONTENT')?>"><?=Helper::svg('panel', 'content')?><span><?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_CONTENT')?></span></span>
    <span class="block_edit_design block_edit_txtbtn theme-bg theme-bg-hover js-block-design" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_DESIGN')?>"><?=Helper::svg('panel', 'design')?><span><?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_DESIGN')?></span></span>

    <?if(!empty($blockInfo['SETTINGS'])):?>
        <span class="block_edit_settings theme-bg theme-bg-hover js-block-settings" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_SETTINGS')?>" data-toggle="tooltip" data-placement="top"><?=Helper::svg('panel', 'settings')?></span>
    <?endif?>

    <?if(!Config::isDemoLanding()):?>
        <?if(Config::isRegionEnabled()):?>
            <span class="block_edit_group theme-bg theme-bg-hover js-block-group" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_GROUP')?>" data-toggle="tooltip" data-placement="top"><?=Helper::svg('panel', 'group')?></span>
        <?endif?>
        <span class="block_edit_copy theme-bg theme-bg-hover js-block-copy" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_COPY')?>" data-toggle="tooltip" data-placement="top"><?=Helper::svg('panel', 'copy')?></span>
    <?endif?>

    <span class="block_edit_trash theme-bg theme-bg-hover js-block-remove" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_REMOVE')?>" data-toggle="tooltip" data-placement="top"><?=Helper::svg('panel', 'trash')?></span>
    <span class="block_edit_hide theme-bg theme-bg-hover js-block-hide" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_HIDE')?>" data-toggle="tooltip" data-placement="top"><?=Helper::svg('panel', 'hide')?></span>
    <span class="block_edit_show theme-bg theme-bg-hover js-block-show" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_SHOW')?>" data-toggle="tooltip" data-placement="top"><?=Helper::svg('panel', 'show')?></span>
    <span class="block_edit_move_up theme-bg theme-bg-hover js-block-up" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_UP')?>" data-toggle="tooltip" data-placement="top"><?=Helper::svg('panel', 'up')?></span>
    <span class="block_edit_move_down theme-bg theme-bg-hover js-block-down" title="<?=Loc::getMessage('RX_BLOCK_LANDING_INCLUDE_EDIT_ELEMENTS_DOWN')?>" data-toggle="tooltip" data-placement="top"><?=Helper::svg('panel', 'down')?></span>

</div>

<div class="block-loading"><div class="spinner-grow theme-color"></div></div>
