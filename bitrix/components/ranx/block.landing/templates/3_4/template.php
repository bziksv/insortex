<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="row">
            <?foreach($arResult['ITEMS'] as $i => $arItem):
                $number = ($i + 1 < 10) ? '0' . ($i + 1) : $i + 1;
            ?>
                <div class="col-lg-8 offset-lg-2 stage">

                    <div class="row">
                        <div class="col-md-3 d-none d-md-block">
                            <div class="stage-name block-el-title"><?=$arItem['NAME']?></div>
                        </div>
                        <div class="col-2 col-md-1 d-flex justify-content-center <?= ($i + 1) < count($arResult['ITEMS']) ? 'stage-line' : '' ?>">
                            <div class="stage-number theme-color">
                                <?= $number ?>
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
