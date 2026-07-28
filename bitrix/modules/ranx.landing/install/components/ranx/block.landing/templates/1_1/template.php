<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
/**
 * @var array $arParams
 * @var array $arResult
 */

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<style>
    @media (min-width: 768px) {
        .block1-1-height--<?=$arResult['ID']?> {
            height: <?=$arResult['BLOCK_HEIGHT']?>px !important;
        }
    }
    <?foreach ($arResult['ITEMS'] as $i => $arItem):?>
        <?if(!empty($arItem['VIDEO_RATIO']) && !empty($arItem['VIDEO_HEIGHT'])):?>
            .block1-1-bg-video-<?=$arItem['ID']?> {
                padding-top: calc(<?= 1 / $arItem['VIDEO_RATIO'] ?> * 100%);
            }
            @media (max-width: <?=($arItem['VIDEO_HEIGHT'] * $arItem['VIDEO_RATIO']).'px'?>) {
                .block1-1-bg-video-<?=$arItem['ID']?> {
                    width: calc(<?=$arItem['VIDEO_RATIO']?> * <?=$arItem['VIDEO_HEIGHT']?>px);
                    padding-top: 0;
                    height: <?=$arItem['VIDEO_HEIGHT']?>px;
                }
            }
        <?endif?>
    <?endforeach?>
</style>

<?= $arResult['BLOCK_START'] ?>

    <div class="block1-1-slider block-sort-<?= $arResult['SORT'] ?>" data-autoplay="<?= $arParams['SETTINGS']['AUTOPLAY'] ?>">
        <? if (!empty($arResult['ITEMS'])) : ?>
            <? foreach ($arResult['ITEMS'] as $i => $arItem) : ?>
                <div class="block1-1-bg-image block1-1-height--<?=$arResult['ID']?> lazy <?if(!empty($arItem['VIDEO_ID'])):?>block1-1-video<?endif?>" style="
                    <?if($arItem['PROPS']['BG_COLOR']):?>background-color: <?=$arItem['PROPS']['BG_COLOR']?>;<?endif?>
                    <?if($arItem['BG_IMG']):?>
                        <?if($useLazyLoad):?>" data-bg="<?=$arItem['BG_IMG']?>
                        <?else:?> background-image: url(<?=$arItem['BG_IMG']?>);<?endif?>
                    <?endif?>">

                    <?if(!empty($arItem['VIDEO_ID'])):?>
                        <div class="block1-1-bg-video block1-1-bg-video-<?=$arItem['ID']?>">
                            <? //don't remove "frameborder" ?>
                            <iframe src="<?=$arItem['VIDEO_SRC']?>" frameborder="0" allowfullscreen></iframe>
                        </div>
                    <?endif?>

                    <?if($arResult['TINT_COLOR']):?>
                        <div class="toner-block" style="background-color: <?= $arResult['TINT_COLOR'] ?>"></div>
                    <?endif?>
                    <div class="maxwidth-theme height-inherit">
                        <div class="row height-fill">
                            <? $arLightTextClasses = preg_filter('/^/', 'text-light-', $arItem['PROPERTIES']['TEXT_LIGHT']['VALUE_XML_ID']); ?>
                            <? $lightTextClasses = implode(' ', is_array($arLightTextClasses) ? $arLightTextClasses : []); ?>
                            <div class="block-title col-md-<?= !empty($arItem['IMG']) ? '6' : '8' ?> block1-1-align-content <?= $lightTextClasses ?> header-light-target-<?= $arResult['SORT'] ?>-<?= $i ?>">
                                <?if(!empty($arItem['PROPS']['CATTITLE'])):?>
                                    <span class="block-cattitle"><?=$arItem['PROPS']['CATTITLE']?></span>
                                <?endif?>
                                <? if (!empty($arItem['NAME'])) : ?>
                                    <<?=$arResult['TITLE_TAG']?> class="block-title-text"><?= $arItem['~NAME'] ?></<?=$arResult['TITLE_TAG']?>>
                                <? endif ?>
                                <? if (!empty($arItem['PREVIEW_TEXT'])) : ?>
                                    <div class="block-subtitle">
                                    <?if(strpos($arItem['PREVIEW_TEXT'], '<') === 0): // has own tags ?>
                                        <?= $arItem['~PREVIEW_TEXT'] ?>
                                    <?else:?>
                                        <p><?= $arItem['~PREVIEW_TEXT'] ?></p>
                                    <?endif?>
                                    </div>
                                <? endif ?>
                                <? if (!empty($arItem['PRICE']) || !empty($arItem['OLD_PRICE'])) : ?>
                                    <div class="block1-1-prices">
                                        <? if (!empty($arItem['PRICE'])) : ?>
                                            <div class="block1-1-price"><?= Helper::money($arItem['PRICE']) ?></div>
                                        <? endif ?>
                                        <? if (!empty($arItem['OLD_PRICE'])) : ?>
                                            <div class="block1-1-price-old"><?= Helper::money($arItem['OLD_PRICE']) ?></div>
                                        <? endif ?>
                                    </div>
                                <? endif ?>

                                <?=$arItem['BTN']?>

                            </div>
                            <? if (!empty($arItem['IMG']) || !empty($arItem['BG_IMG'])) : ?>
                                <div class="col-md-6 col-flex-rw p-0 lazy block1-1-image<?if(empty($arItem['IMG'])):?> block1-1-no-image<?endif?>" style="
                                    <?if($arItem['PROPS']['BG_COLOR']):?>background-color:<?=$arItem['PROPS']['BG_COLOR']?>;<?endif?>
                                    <?if($arItem['BG_IMG']):?>
                                        <?if($useLazyLoad):?>" data-bg="<?=$arItem['BG_IMG']?>
                                        <?else:?> background-image: url(<?=$arItem['BG_IMG']?>);<?endif?>
                                    <?endif?>">

                                    <? if (!empty($arItem['IMG'])) : ?>
                                        <img class="lazy" alt="<?=htmlspecialchars(strip_tags($arItem['NAME']))?>"
                                             <?if($useLazyLoad):?> data-src="<?=$arItem['IMG']?>"
                                             <?else:?> src="<?=$arItem['IMG']?>"<?endif?>>
                                    <? endif ?>
                                </div>
                            <? endif ?>
                        </div>
                    </div>
                </div>
            <? endforeach ?>
        <? endif ?>
    </div>

<?= $arResult['BLOCK_END'] ?>
