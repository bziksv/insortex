<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="tizers-wrapper">
            <div class="row tizers <?= $arResult['SLIDER'] ? 'tizers--mobile-slider' : ''?>">
                <?foreach($arResult['ITEMS'] as $arItem):?>
                <div class="col-lg-<?= $arResult['COLS'] ? 12 / $arResult['COLS'] : '3' ?> col-md-6 tizer
                    <?if($arResult['CARDS_BG_COLOR']):?>bg-color<?endif?>">
                    <div class="tizer-wrap" <?if($arResult['CARDS_BG_COLOR']):?>style="background-color: <?=$arResult['CARDS_BG_COLOR']?>"<?endif?>>
                        <?if ($arItem['PROPS']['IMG_PATH']):?>
                        <div class="img-round lazy" <?if($useLazyLoad):?>data-bg="<?=$arItem['PROPS']['IMG_PATH']?>"
                             <?else:?>style="background-image: url(<?=$arItem['PROPS']['IMG_PATH']?>);"<?endif?>>
                        </div>
                        <?endif;?>
                        <div class="tizer-name block-el-title"><?=$arItem['NAME']?></div>
                        <p class="tizer-text"><?=$arItem['~PREVIEW_TEXT']?></p>
                    </div>
                </div>
                <?endforeach;?>
            </div>
        </div>

        <?=$arResult['BTN']?>

    </div>

<?= $arResult['BLOCK_END'] ?>

<?if($arResult['SLIDER']):?>
    <script>
        $(document).ready(function () {
            const $key = 'block_<?=$arResult['ID']?>_slider';
            if (window[$key]) {
                window[$key].release();
                delete window[$key];
            }

            window[$key] = new Block2_3Slider(<?=$arResult['ID']?>);
        });
    </script>
<?endif;?>
