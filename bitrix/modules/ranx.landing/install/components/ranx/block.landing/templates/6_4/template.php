<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="row d-flex articles">
            <?foreach($arResult['ITEMS'] as $i => $arItem):?>
                <div class="col-xl-<?= $arResult['COLS'] ? 12 / $arResult['COLS'] : '3' ?> col-sm-6 article text-center">

                    <?if(!empty($arItem['PROPS']['POPUP_SHOW'])):?>
                        <a class="article-wrap theme-color-hover-parent js-card-modal" href="#" data-code="<?=$arResult['CODE']?>" data-id="<?=$arItem['ID']?>">
                    <?elseif (!empty($arItem['LINK'])):?>
                        <a class="article-wrap theme-color-hover-parent <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                    <?else:?>
                        <div class="article-wrap theme-color-hover-parent">
                    <?endif?>

                        <div class="tags">
                            <?if($arItem['PROPS']['DISCOUNT']):?>
                                <div class="tag sale"><?= $arItem['PROPS']['DISCOUNT'] ?></div>
                            <?endif?>
                        </div>
                        <?if ($arItem['PREVIEW_PICTURE']):?>
                            <div class="img-round lazy" <?if($useLazyLoad):?>data-bg="<?=$arItem['PREVIEW_PICTURE_PATH']?>"<?else:?>style="background-image: url(<?=$arItem['PREVIEW_PICTURE_PATH']?>);"<?endif?>></div>
                        <?endif;?>
                        <div class="title-group">
                            <div class="article-over-title-text text-center"><?=$arItem['DISPLAY_ACTIVE_PERIOD']?></div>
                            <div class="article-name block-el-title text-center theme-color-hover"><?=$arItem['NAME']?></div>
                        </div>

                    <?if (!empty($arItem['PROPS']['POPUP_SHOW']) || !empty($arItem['LINK'])):?>
                        </a>
                    <?else:?>
                        </div>
                    <?endif?>

                </div>
            <?endforeach;?>
        </div>

        <?=$arResult['BTN']?>

    </div>

<?= $arResult['BLOCK_END'] ?>
