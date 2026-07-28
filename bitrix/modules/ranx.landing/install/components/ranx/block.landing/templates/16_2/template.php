<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

    <div>
        <? if (!empty($arResult['ITEMS'])) : ?>
            <?  $i = 0; $arItem = $arResult['ITEMS'][0]; ?>
            <div >
                <div class="maxwidth-theme height-inherit">
                    <div class="block16-2-bg-image lazy"
                        <?if($useLazyLoad && $arItem['BG_IMG']):?>data-bg="<?= $arItem['BG_IMG'] ?>"<?endif?> style="<?if(!$useLazyLoad && $arItem['BG_IMG']):?>background-image: url(<?= $arItem['BG_IMG'] ?>);<?endif?><?if($arItem['PROPS']['BG_COLOR']):?>background-color:<?=$arItem['PROPS']['BG_COLOR']?>;<?endif?>">
                        <div class="row height-fill">
                            <? $arLightTextClasses = preg_filter('/^/', 'text-light-', $arItem['PROPERTIES']['TEXT_LIGHT']['VALUE_XML_ID']); ?>
                            <? $lightTextClasses = implode(' ', is_array($arLightTextClasses) ? $arLightTextClasses : []); ?>
                            <div class="block-title col-lg-<?= !empty($arItem['IMG']) ? '5' : '8' ?> block16-2-content-<?= !empty($arItem['IMG']) ? 'left' : 'center' ?> <?= $lightTextClasses ?>">
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
                                    <div class="block16-2-prices  <? if (empty($arItem['IMG'])) : ?> block16-2-prices-center <? endif ?>">
                                        <? if (!empty($arItem['PRICE'])) : ?>
                                            <div class="block16-2-price"><?= Helper::money($arItem['PRICE']) ?></div>
                                        <? endif ?>
                                        <? if (!empty($arItem['OLD_PRICE'])) : ?>
                                            <div class="block16-2-price-old"><?= Helper::money($arItem['OLD_PRICE']) ?></div>
                                        <? endif ?>
                                    </div>
                                <? endif ?>

                                <?=$arItem['BTN']?>

                            </div>
                            <? if (!empty($arItem['IMG']) || !empty($arItem['BG_IMG']) ): ?>
                                <div class="col-lg-6 col-flex-rw p-0 lazy <? if (empty($arItem['IMG'])): ?> block16-2-no-image <? endif ?>"
                                    <?if($useLazyLoad && $arItem['BG_IMG']):?>data-bg="<?= $arItem['BG_IMG'] ?>"<?endif?> style="<?if(!$useLazyLoad && $arItem['BG_IMG']):?>background-image: url(<?= $arItem['BG_IMG'] ?>);<?endif?>">
                                    <? if (!empty($arItem['IMG'])) : ?>
                                        <img class="lazy" <?if($useLazyLoad):?>data-src="<?= $arItem['IMG'] ?>"<?else:?>src="<?= $arItem['IMG'] ?>"<?endif?> alt="<?= $arItem['NAME'] ?>">
                                    <? endif ?>
                                </div>
                            <? endif ?>
                        </div>
                    </div>
                </div>
            </div>

        <? endif ?>
    </div>

<?= $arResult['BLOCK_END'] ?>
