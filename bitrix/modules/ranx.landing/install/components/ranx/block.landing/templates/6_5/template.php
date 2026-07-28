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
                <div class="col-lg-6 article">

                    <?if(!empty($arItem['PROPS']['POPUP_SHOW'])):?>
                        <a class="article-wrap theme-color-hover-parent js-card-modal" href="#" data-code="<?=$arResult['CODE']?>" data-id="<?=$arItem['ID']?>">
                    <?elseif (!empty($arItem['LINK'])):?>
                        <a class="article-wrap theme-color-hover-parent <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                    <?else:?>
                        <div class="article-wrap theme-color-hover-parent">
                    <?endif?>

                        <div class="row no-gutters justify-content-between">
                            <div class="col-12 col-sm-6 article-content">
                                <div class="title-group">
                                    <div class="article-over-title-text"><?=$arItem['DISPLAY_ACTIVE_PERIOD']?></div>
                                    <div class="article-name block-el-title theme-color-hover"><?=$arItem['NAME']?></div>
                                    <p class="main-text"><?= $arItem['PREVIEW_TEXT']?></p>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 article-img">
                                <?if ($arItem['PREVIEW_PICTURE']):?>
                                    <div class="img-round lazy" <?if($useLazyLoad):?>data-bg="<?=$arItem['PREVIEW_PICTURE_PATH']?>"<?else:?>style="background-image: url(<?=$arItem['PREVIEW_PICTURE_PATH']?>)"<?endif?>></div>
                                <?endif;?>
                            </div>
                        </div>
                        <div class="tags">
                            <?if($arItem['PROPS']['DISCOUNT']):?>
                                <div class="tag sale"><?= $arItem['PROPS']['DISCOUNT'] ?></div>
                            <?endif?>
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
