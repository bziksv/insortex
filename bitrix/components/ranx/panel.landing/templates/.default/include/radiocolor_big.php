<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var bool $isCustomColor
 * @var string $customColorVal
 * @var string $radioColorGroup
 * @var string $customColorName
 */

use Ranx\Landing\Helpers\Helper;
?>

<div class="radiocolor theme-border-class-active" data-group="<?= $radioColorGroup ?>">
    <input type="hidden" name="<?= $customColorName ?>" value="<?= $customColorVal ?>">
    <div class="radiocolor-item radiocolor-item-big <?if($isCustomColor):?>active<?endif?>" data-value="<?= $customColorVal ?>">
        <div class="radiocolor-item-text-empty">#</div>
        <div class="radiocolor-item-color-empty"><?= Helper::svg('panel', 'pipet') ?></div>

        <div class="radiocolor-item-text"><?= ($customColorVal ? $customColorVal : '') ?></div>
        <input type="text" class="radiocolor-item-color js-color-picker" value="<?= ($customColorVal ? $customColorVal : '') ?>" />
    </div>
</div>
