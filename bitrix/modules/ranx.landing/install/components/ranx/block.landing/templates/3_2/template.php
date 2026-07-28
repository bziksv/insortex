<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="row tizers">
            <?foreach($arResult['ITEMS'] as $i => $arItem):?>
            <div class="col-lg-<?= $arResult['COLS'] ? 12 / $arResult['COLS'] : '3' ?> col-sm-6 tizer dashed">
                <div class="tizer-number theme-color"><?=iconv_strlen($i+1) < 2 ? '0' : '' ?><?=($i+1)?></div>
                <div class="tizer-name block-el-title"><?=$arItem['~NAME']?></div>
                <?if(!empty($arItem['PREVIEW_TEXT'])):?>
                <p class="tizer-text"><?=$arItem['~PREVIEW_TEXT']?></p>
                <?endif?>
            </div>
            <?endforeach;?>
        </div>

        <?=$arResult['BTN']?>

    </div>

<?= $arResult['BLOCK_END'] ?>
