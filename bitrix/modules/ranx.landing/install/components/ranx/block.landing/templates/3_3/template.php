<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="row">
            <?foreach($arResult['ITEMS'] as $i => $arItem):?>
            <div class="col-lg-8 offset-lg-2 stage">

                <div class="row">
                    <div class="col-md-3 d-none d-md-block">
                        <div class="stage-name block-el-title"><?=$arItem['NAME']?></div>
                    </div>
                    <div class="col-2 col-md-1 d-flex justify-content-center <?= ($i + 1) < count($arResult['ITEMS']) ? 'stage-line' : '' ?>">
                        <div class="stage-icon theme-stroke">
                            <?= Helper::svg('block/check_circle'); ?>
                        </div>
                    </div>
                    <div class="col-10 col-md-8">
                        <div class="stage-name block-el-title d-md-none"><?=$arItem['NAME']?></div>
                        <p class="stage-text"><?=$arItem['~PREVIEW_TEXT']?></p>
                    </div>
                </div>

            </div>
            <?endforeach;?>
        </div>

        <?=$arResult['BTN']?>

    </div>

<?= $arResult['BLOCK_END'] ?>
