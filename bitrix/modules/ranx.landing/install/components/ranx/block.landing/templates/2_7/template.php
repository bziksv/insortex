<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="row tizers">
            <?foreach($arResult['ITEMS'] as $arItem):?>
            <div class="col-md-8 offset-md-2 tizer">
                <?if($arItem['PROPS']['FA_CLASS']):?>
                    <div class="tizer-icon theme-color"><i class="<?= $arItem['PROPS']['FA_CLASS'] ?>"></i></div>
                <?elseif(Helper::isSvg($arItem['PROPS']['IMG_INFO']['CONTENT_TYPE'])):?>
                    <div class="svg-icon theme-color">
                        <?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . $arItem['PROPS']['IMG_PATH'])?>
                    </div>
                <?elseif($arItem['PROPS']['IMG_PATH']):?>
                    <img class="tizer-img lazy" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>"
                        <?if($useLazyLoad):?> data-src="<?=$arItem['PROPS']['IMG_PATH']?>"
                        <?else:?> src="<?=$arItem['PROPS']['IMG_PATH']?>"
                        <?endif?>>
                <?endif;?>
                <div class="tizer-descripton">
                    <div class="tizer-name block-el-title"><?=$arItem['NAME']?></div>
                    <p class="tizer-text"><?=$arItem['~PREVIEW_TEXT']?></p>
                </div>
            </div>
            <?endforeach;?>
        </div>

        <?=$arResult['BTN']?>

    </div>

<?= $arResult['BLOCK_END'] ?>
