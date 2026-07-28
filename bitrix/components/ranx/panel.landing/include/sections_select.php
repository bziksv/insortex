<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 */

if (empty($arResult['ITEMS'])) {
    return;
}

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<select name="<?= $arResult['NAME'] ?>" class="form-control">
    <option value="" selected>
        <?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_SECTIONS_SELECT_DEFAULT') ?>
    </option>
    <?foreach($arResult['ITEMS'] as $arItem):?>
        <option value="<?= $arItem['ID'] ?>">
            <?=str_repeat("&nbsp;&nbsp;", $arItem['DEPTH_LEVEL'] - 1)?>[<?= $arItem['ID'] ?>]&nbsp;<?= $arItem['NAME'] ?>
        </option>
    <?endforeach?>
</select>
