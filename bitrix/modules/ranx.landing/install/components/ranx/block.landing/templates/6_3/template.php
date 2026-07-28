<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">
    <?= $arResult['BLOCK_TITLE'] ?>

    <div class="row articles">
        <?foreach($arResult['ITEMS'] as $i => $arItem):?>
            <div class="col-xl-<?= $arResult['COLS'] ? 12 / $arResult['COLS'] : '3 big'?> col-sm-6 article text-center">

                <?if($arItem['PROPS']['DISCOUNT']):?>
                    <div class="tags">
                        <div class="tag sale"><?=$arItem['PROPS']['DISCOUNT']?></div>
                    </div>
                <?endif?>

                <?if(!empty($arItem['PROPS']['POPUP_SHOW'])):?>
                    <a class="js-card-modal" href="#" data-code="<?=$arResult['CODE']?>" data-id="<?=$arItem['ID']?>">
                    <div class="article-wrap lazy theme-color-hover-parent"
                        <?if($useLazyLoad):?> data-bg="<?=$arItem['PREVIEW_PICTURE_PATH']?>"<?else:?> style="background-image: url(<?=$arItem['PREVIEW_PICTURE_PATH']?>)"<?endif?>>
                <?elseif (!empty($arItem['LINK'])):?>
                    <a class="article-wrap lazy theme-color-hover-parent <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>
                        <?if($useLazyLoad):?> data-bg="<?=$arItem['PREVIEW_PICTURE_PATH']?>"<?else:?> style="background-image: url(<?=$arItem['PREVIEW_PICTURE_PATH']?>)"<?endif?>>
                <?else:?>
                    <div class="article-wrap lazy theme-color-hover-parent"
                        <?if($useLazyLoad):?> data-bg="<?=$arItem['PREVIEW_PICTURE_PATH']?>"<?else:?> style="background-image: url(<?=$arItem['PREVIEW_PICTURE_PATH']?>)"<?endif?>>
                <?endif?>

                    <div class="cover"></div>
                    <div class="title-group">
                        <div class="article-over-title-text"><?=$arItem['DISPLAY_ACTIVE_PERIOD']?></div>
                        <div class="article-name block-el-title"><?=$arItem['NAME']?></div>
                    </div>

                <?if(!empty($arItem['PROPS']['POPUP_SHOW'])):?>
                    </div>
                    </a>
                <?elseif (!empty($arItem['LINK'])):?>
                    </a>
                <?else:?>
                    </div>
                <?endif?>

            </div>
        <?endforeach?>
    </div>

<?=$arResult['BTN']?>
</div>

<?= $arResult['BLOCK_END'] ?>
