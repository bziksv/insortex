<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="row tizers">
            <?foreach($arResult['ITEMS'] as $i => $arItem):?>
                <div class="col-lg-<?= $arResult['COLS'] ? 12 / $arResult['COLS'] : '3' ?> col-sm-6 tizer dashed">
                
                <div class="tizer-img theme-stroke">
                    <?= Helper::svg('block/check_circle'); ?>
                </div>
                
                <div class="tizer-name block-el-title"><?=$arItem['~NAME']?></div>
                <p class="tizer-text"><?=$arItem['~PREVIEW_TEXT']?></p>
            </div>
            <?endforeach;?>
        </div>

        <?=$arResult['BTN']?>

    </div>

<?= $arResult['BLOCK_END'] ?>
