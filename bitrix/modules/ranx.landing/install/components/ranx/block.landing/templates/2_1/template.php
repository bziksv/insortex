<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="tizers-wrapper">
            <div class="row tizers <?= $arResult['SLIDER'] ? 'tizers--mobile-slider' : ''?>">
                <?foreach($arResult['ITEMS'] as $arItem):?>
                <div class="col-lg-<?= $arResult['COLS'] ? 12 / $arResult['COLS'] : '3' ?> col-md-6 tizer">
                    <?if($arItem['PROPS']['FA_CLASS']):?>
                        <div class="tizer-icon theme-color"><i class="<?= $arItem['PROPS']['FA_CLASS'] ?>"></i></div>
                    <?elseif(Helper::isSvg($arItem['PROPS']['IMG_INFO']['CONTENT_TYPE'])):?>
                        <div class="svg-icon theme-color">
                            <?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . $arItem['PROPS']['IMG_PATH'])?>
                        </div>
                    <?elseif($arItem['PROPS']['IMG_PATH']):?>
                        <div class="image">
                            <img class="tizer-img lazy" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>"
                                 <?if($useLazyLoad):?> data-src="<?=$arItem['PROPS']['IMG_PATH']?>"
                                 <?else:?> src="<?=$arItem['PROPS']['IMG_PATH']?>"
                                 <?endif?>>
                        </div>
                    <?endif;?>
                    <div class="tizer-description">
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

        window[$key] = new Block2_1Slider(<?=$arResult['ID']?>);
    });
</script>
<?endif;?>
