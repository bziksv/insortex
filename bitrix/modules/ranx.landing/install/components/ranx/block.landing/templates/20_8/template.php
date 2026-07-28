<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
?>

<?if (!empty($arResult['ITEMS']) && !empty($arResult['ITEMS'][0]['DETAIL_TEXT']) || Config::isEditMode()):?>

    <?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?if (!empty($arResult['ITEMS'])):?>
            <? $arItem = reset($arResult['ITEMS']); ?>
            <div class="detail-text"><?= $arItem['DETAIL_TEXT'] ?></div>
        <?endif?>

    </div>

    <?= $arResult['BLOCK_END'] ?>

<?endif?>
