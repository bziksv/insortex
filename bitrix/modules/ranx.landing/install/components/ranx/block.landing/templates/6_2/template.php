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
                <?if( $arResult['COLS'] == '3'):?>
                    <div class="col-md-<?= ($i + 1) % 6 <= 1 ? '6 '.'big-article' : '3 '.'article' ?> text-center">

                        <?if(!empty($arItem['PROPS']['POPUP_SHOW'])):?>
                            <a class="article-wrap js-card-modal" href="#" data-code="<?=$arResult['CODE']?>" data-id="<?=$arItem['ID']?>">
                        <?elseif (!empty($arItem['LINK'])):?>
                            <a class="article-wrap <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                        <?else:?>
                            <div class="article-wrap">
                        <?endif?>

                            <?if ($arItem['PREVIEW_PICTURE']):?>
                                <div class="article-img lazy" <?if($useLazyLoad):?>data-bg="<?=$arItem['PREVIEW_PICTURE_PATH']?>"<?else:?>style="background-image: url('<?=$arItem['PREVIEW_PICTURE_PATH']?>')"<?endif?>></div>
                            <?endif;?>
                            <div class="cover"></div>
                            <div class="title-group">
                                <div class="article-name block-el-title <?= $i > 0 ? 'theme-color-hover' : '' ?>"><?=$arItem['NAME']?></div>
                                <p class="article-text"><?=$arItem['DISPLAY_ACTIVE_FROM']?></p>
                            </div>

                        <?if(!empty($arItem['PROPS']['POPUP_SHOW']) || !empty($arItem['LINK'])):?>
                            </a>
                        <?else:?>
                            </div>
                        <?endif?>

                    </div>
                <?elseif( $arResult['COLS'] == '4'):?>
                    <div class="col-md-3 article text-center">

                        <?if(!empty($arItem['PROPS']['POPUP_SHOW'])):?>
                            <a class="article-wrap js-card-modal" href="#" data-code="<?=$arResult['CODE']?>" data-id="<?=$arItem['ID']?>">
                        <?elseif (!empty($arItem['LINK'])):?>
                            <a class="article-wrap <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                        <?else:?>
                            <div class="article-wrap">
                        <?endif?>

                            <?if ($arItem['PREVIEW_PICTURE']):?>
                                <div class="article-img lazy" <?if($useLazyLoad):?>data-bg="<?=$arItem['PREVIEW_PICTURE_PATH']?>"<?else:?>style="background-image: url('<?=$arItem['PREVIEW_PICTURE_PATH']?>')"<?endif?>></div>
                            <?endif;?>
                            <div class="title-group">
                                <div class="article-name theme-color-hover"><?=$arItem['NAME']?></div>
                                <p class="article-text"><?=$arItem['DISPLAY_ACTIVE_FROM']?></p>
                            </div>

                        <?if(!empty($arItem['PROPS']['POPUP_SHOW']) || !empty($arItem['LINK'])):?>
                            </a>
                        <?else:?>
                            </div>
                        <?endif?>

                    </div>
                <?endif;?>
            <?endforeach;?>
        </div>

        <?= $arResult['NAV_STRING'] ?>

        <?=$arResult['BTN']?>

    </div>

<?= $arResult['BLOCK_END'] ?>
