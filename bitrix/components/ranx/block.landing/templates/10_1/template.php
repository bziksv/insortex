<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

    </div>

    <div class="block10-slider block10-slider-target-<?= $arResult['ID'] ?>" slider-target=".block10-slider-target-<?= $arResult['ID'] ?>">
        <? if (!empty($arResult['ITEMS'])) : ?>
            <a class="slick-arrow arrow-prev btn-transparent">
                <?= Helper::svg('block/arrow_prev') ?>
            </a>
            <div class="block10-1-items">
                <? foreach ($arResult['ITEMS'] as $i => $arItem) : ?>
                    <div class="block10-1-item ">
                        <? if ($arItem['DETAIL_IMG']) : ?>
                            <? if ($arItem['DETAIL_IMG']) : ?>
                                <a data-fancybox class="fancybox-slider lazy block10-1-target-<?=$i?>" href="<?= $arItem['DETAIL_IMG'] ?>"
                                   alt="<?=$arItem['PROPS']['PICTURE_ALT']?>" data-caption="<?=$arItem['PREVIEW_TEXT']?>"
                                   title="<?=$arItem['PROPS']['PICTURE_TITLE']?>"
                                   <?if($useLazyLoad):?> data-bg="<?=$arItem['IMG']?>"
                                   <?else:?> style="background-image: url(<?=$arItem['IMG']?>);"
                                   <?endif?>>
                                    <div class="dark-hover"></div>
                                </a>
                            <? endif ?>
                            <? if (!empty($arItem['PREVIEW_TEXT'])) : ?>
                                <div class="block10-1-title"><?= $arItem['~PREVIEW_TEXT'] ?></div>
                            <? endif ?>
                        <? endif ?>

                    </div>
                <? endforeach ?>
            </div>
            <a class="slick-arrow arrow-next btn-transparent">
                <?= Helper::svg('block/arrow_next') ?>
            </a>
        <? endif ?>
    </div>

    <div class="maxwidth-theme">
        <p class="slide-counter" style="display: none;">
            <span class="current-slide">6</span>/<span class="total-slide">20</span>
        </p>

        <?= $arResult['BTN'] ?>
    </div>

<?= $arResult['BLOCK_END'] ?>
