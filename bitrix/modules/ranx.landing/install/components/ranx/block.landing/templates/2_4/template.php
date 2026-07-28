<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="row tizers">
            <?foreach($arResult['ITEMS'] as $arItem):?>
            <div class="col-lg-<?= $arResult['COLS'] ? 12 / $arResult['COLS'] : '3' ?> col-md-6 tizer text-center">
                <div class="big-int block-el-title theme-color"><?=$arItem['NAME']?></div>
                <p class="tizer-text"><?=$arItem['~PREVIEW_TEXT']?></p>
            </div>
            <?endforeach;?>
        </div>

        <?=$arResult['BTN']?>

    </div>

<?= $arResult['BLOCK_END'] ?>
