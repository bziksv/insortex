<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <div class="row">
            <div class="col-lg-6">
                <?= $arResult['BLOCK_TITLE'] ?>
            </div>
            <div class="col-lg-6 tizers">
                <div class="row">
                    <?foreach($arResult['ITEMS'] as $i => $arItem):?>
                    <div class="col-12 tizer">
                        <div class="tizer-img-wrap">
                            <div class="tizer-img theme-stroke">
                                <?= Helper::svg('block/check_circle'); ?>
                            </div>
                        </div>
                        <div class="tizer-text-wrap">
                            <div class="tizer-name block-el-title"><?=$arItem['NAME']?></div>
                            <p class="tizer-text"><?=$arItem['~PREVIEW_TEXT']?></p>
                        </div>
                    </div>
                    <?endforeach;?>
                </div>
            </div>
        </div>
    </div>

<?= $arResult['BLOCK_END'] ?>
