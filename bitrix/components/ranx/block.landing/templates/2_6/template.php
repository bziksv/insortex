<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">
        <div class="row">
            <div class="col-lg-6">
                <?= $arResult['BLOCK_TITLE'] ?>
            </div>
            <div class="col-lg-6 tizers">
                <div class="row">
                    <?foreach($arResult['ITEMS'] as $arItem):?>
                    <div class="col-lg-12 col-sm-6 tizer">

                        <?if ($arItem['PROPS']['IMG_PATH']):?>
                            <div class="img-round lazy" <?if($useLazyLoad):?>data-bg="<?=$arItem['PROPS']['IMG_PATH']?>"
                                 <?else:?>style="background-image: url(<?=$arItem['PROPS']['IMG_PATH']?>);"<?endif?>>
                            </div>
                        <?endif;?>

                        <div class="tizer-name block-el-title"><?=$arItem['NAME']?></div>
                        <p class="tizer-text"><?=$arItem['~PREVIEW_TEXT']?></p>
                    </div>
                    <?endforeach;?>
                </div>
            </div>
        </div>
    </div>

<?= $arResult['BLOCK_END'] ?>
