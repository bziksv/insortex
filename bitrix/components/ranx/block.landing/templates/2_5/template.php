<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
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
                        <div class="tizer-name block-el-title"><?=$arItem['NAME']?></div>
                        <p class="tizer-text"><?=$arItem['~PREVIEW_TEXT']?></p>
                    </div>
                    <?endforeach;?>
                </div>
            </div>
        </div>
    </div>

<?= $arResult['BLOCK_END'] ?>
