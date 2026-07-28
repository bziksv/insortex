<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;

$useLazyLoad = Config::isLazyLoadEnabled();
$isTransparentBgColor = ($arResult['CARDS_BG_COLOR'] == 'transparent');
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="tizers-wrapper">
            <div class="row tizers <?= $arResult['SLIDER'] ? 'tizers--mobile-slider' : ''?>">
                <?foreach($arResult['ITEMS'] as $arItem):?>
                <div class="col-lg-<?= $arResult['COLS'] ? 12 / $arResult['COLS'] : '3' ?> col-md-6 tizer
                    <?if($arResult['CARDS_BG_COLOR']):?>bg-color<?endif?>
                    <?if($isTransparentBgColor):?>bg-transparent<?endif?>">
                    <div class="tizer-wrap" <?if($arResult['CARDS_BG_COLOR']):?>style="background-color: <?=$arResult['CARDS_BG_COLOR']?>"<?endif?>>
                        <?if($arItem['PROPS']['FA_CLASS']):?>
                            <div class="tizer-picture tizer-icon theme-color"><i class="<?= $arItem['PROPS']['FA_CLASS'] ?>"></i></div>
                        <?elseif(Helper::isSvg($arItem['PROPS']['IMG_INFO']['CONTENT_TYPE'])):?>
                        <div class="tizer-picture svg-icon theme-color">
                            <?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . $arItem['PROPS']['IMG_PATH'])?>
                        </div>
                        <?elseif($arItem['PROPS']['IMG_PATH']):?>
                            <img class="tizer-picture tizer-img lazy" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>"
                                <?if($useLazyLoad):?> data-src="<?=$arItem['PROPS']['IMG_PATH']?>"
                                <?else:?> src="<?=$arItem['PROPS']['IMG_PATH']?>"
                                <?endif?>>
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

            window[$key] = new Block2_2Slider(<?=$arResult['ID']?>);
        });
    </script>
<?endif;?>
