<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">

    <?= $arResult['BLOCK_TITLE'] ?>

    <div class="row news">
        <?foreach($arResult['ITEMS'] as $arItem):?>
            <div class="col-md-<?= $arResult['COLS'] ? 12 / $arResult['COLS']  : '3' ?> news-block text-center">

                <?if(!empty($arItem['PROPS']['POPUP_SHOW'])):?>
                    <a class="js-card-modal" href="#" data-code="<?=$arResult['CODE']?>" data-id="<?=$arItem['ID']?>">
                <?elseif (!empty($arItem['LINK'])):?>
                    <a class="news-wrap theme-color-hover-parent <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                <?else:?>
                    <div class="news-wrap theme-color-hover-parent">
                <?endif?>

                    <div class="news-wrap theme-color-hover-parent">

                        <?if ($arItem['PREVIEW_PICTURE_PATH']):?>
                            <div class="news-block-img lazy"
                                 <?if($useLazyLoad):?>data-bg="<?=$arItem['PREVIEW_PICTURE_PATH']?>"<?else:?>style="background-image: url('<?=$arItem['PREVIEW_PICTURE_PATH']?>')"<?endif?>></div>
                        <?endif;?>
                        <p class="news-block-text"><?=$arItem['DISPLAY_ACTIVE_FROM']?></p>
                        <div class="news-block-name block-el-title theme-color-hover"><?=$arItem['NAME']?></div>

                    </div>

                <?if(!empty($arItem['PROPS']['POPUP_SHOW']) || !empty($arItem['LINK'])):?>
                    </a>
                <?else:?>
                    </div>
                <?endif?>

            </div>
        <?endforeach;?>
    </div>

    <?= $arResult['NAV_STRING'] ?>

    <?=$arResult['BTN']?>

</div>

<?= $arResult['BLOCK_END'] ?>
