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

<?= $arResult['BLOCK_START'] ?>

    <div class="block1-2-slider block-sort-<?= $arResult['SORT'] ?>" data-autoplay="<?= $arParams['SETTINGS']['AUTOPLAY'] ?>">
        <? if (!empty($arResult['ITEMS'])) : ?>
            <? foreach ($arResult['ITEMS'] as $i => $arItem) : ?>
                <div class="block1-2-item">
                    <div class="block1-2-container" <?if($arItem['PROPS']['BG_COLOR']):?> style="background-color:<?= $arItem['PROPS']['BG_COLOR'] ?>" <?endif?>>
                        <div class="block1-2-bg-image lazy"
                            <?if($arItem['BG_IMG']):?>
                                <?if($useLazyLoad):?> data-bg="<?=$arItem['BG_IMG']?>"
                                <?else:?> style="background-image: url(<?=$arItem['BG_IMG']?>);"
                                <?endif?>
                            <?endif?>>
                            <div class="mobile-img">
                                <? if (!empty($arItem['IMG'])) : ?>
                                    <img class="lazy" alt="<?=$arItem['NAME']?>"
                                         <?if($useLazyLoad):?> data-src="<?=$arItem['IMG']?>"
                                         <?else:?> src="<?=$arItem['IMG']?>"
                                         <?endif?>>
                                <? endif ?>
                            </div>
                        </div>
                        <div class="maxwidth-theme block1-2-content-height">
                            <div class="row height-fill">
                                <? $arLightTextClasses = preg_filter('/^/', 'text-light-', $arItem['PROPERTIES']['TEXT_LIGHT']['VALUE_XML_ID']); ?>
                                <? $lightTextClasses = implode(' ', is_array($arLightTextClasses) ? $arLightTextClasses : []); ?>
                                <div class="block-title col-lg-6 block1-2-content-left text-block <?= $lightTextClasses ?> header-light-target-<?= $arResult['SORT'] ?>-<?= $i ?>">
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
                                        <div class="block1-2-prices">
                                            <? if (!empty($arItem['PRICE'])) : ?>
                                                <div class="block1-2-price"><?= Helper::money($arItem['PRICE']) ?></div>
                                            <? endif ?>
                                            <? if (!empty($arItem['OLD_PRICE'])) : ?>
                                                <div class="block1-2-price-old"><?= Helper::money($arItem['OLD_PRICE']) ?></div>
                                            <? endif ?>
                                        </div>
                                    <? endif ?>

                                    <?=$arItem['BTN']?>

                                </div>
                                <div class="col-lg-6 col-flex-rw p-0 desc-img">
                                    <? if (!empty($arItem['IMG'])) : ?>
                                        <img class="lazy" alt="<?=$arItem['NAME']?>"
                                             <?if($useLazyLoad):?> data-src="<?=$arItem['IMG']?>"
                                             <?else:?>src="<?=$arItem['IMG']?>"
                                             <?endif?>>
                                    <? endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <? endforeach ?>
        <? endif ?>
    </div>

<?= $arResult['BLOCK_END'] ?>
