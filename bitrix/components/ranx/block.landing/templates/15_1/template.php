<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <?if(!empty($arResult['BTN']) || !empty($arResult['BTN_TITLE'])):?>
            <?= $arResult['BTN'] ?><?= $arResult['BTN_TITLE'] ?>
        <?endif?>

    </div>

<?= $arResult['BLOCK_END'] ?>
