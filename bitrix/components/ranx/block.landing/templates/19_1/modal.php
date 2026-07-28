<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 */

use Ranx\Landing\Page;
use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

$useBasket = Config::isOrderEnabled() && $arParams['SETTINGS']['USE_BASKET'];
?>

<div class="product modal19_1">
    <?if(!empty($arResult['MORE_IMG'])):?>
    <div class="product-images js-simplebar js-custom-scroll">
        <?foreach ($arResult['MORE_IMG'] as $imageSrc):?>
            <img class="not-loaded" src="<?=$imageSrc?>" alt="<?=$arResult['NAME']?>" title="<?=$arResult['NAME']?>">
        <?endforeach?>
    </div>
    <?elseif (!empty($arResult['IMG'])):?>
    <div class="product-image">
        <img src="<?=$arResult['IMG']?>" alt="<?=$arResult['NAME']?>" title="<?=$arResult['NAME']?>">
    </div>
    <?endif?>

    <div class="product-header">
        <?if(!empty($arResult['PROPS']['CAT'])):?>
            <div class="product-category"><?=$arResult['PROPS']['CAT']?></div>
        <?endif?>

        <?if(!empty($arResult['NAME'])):?>
        <div class="product-name"><?=$arResult['NAME']?></div>
        <?endif?>

        <div class="product-info">
            <?if(empty($arParams['SETTINGS']['HIDE_AVAILABLE'])):?>
                <?if($arResult['PROPS']['AVAILABLE'] === 'Y'):?>
                    <div class="product-available product-available-yes">
                        <?= Loc::getMessage('RX_BLOCK_LANDING_19_1_MODAL_AVAILABLE') ?>
                    </div>
                <?else:?>
                    <div class="product-available product-available-no">
                        <?= Loc::getMessage('RX_BLOCK_LANDING_19_1_MODAL_UNAVAILABLE') ?>
                    </div>
                <?endif?>
            <?endif?>

            <?if(!($arResult['PROPS']['CHECK'] === 'Y')):?>
            <div class="product-rating">
                <?for($i = 0; $i < $arResult['MARK']; $i++):?>
                    <div class="product-star product-star--on"><?= Helper::svg('block/star') ?></div>
                <?endfor?>
                <?for($i = 0; $i < (5 - $arResult['MARK']); $i++):?>
                    <div class="product-star product-star--off"><?= Helper::svg('block/star') ?></div>
                <?endfor?>
            </div>
            <?endif?>
        </div>
    </div>

    <?if(!empty($arResult['PRICE']) || !empty($arResult['BTN'])):?>
        <div class="product-sale <?= empty($arResult['PRICE']) ? 'empty-price' : '' ?>">
            <?if (!empty($arResult['PRICE'])):?>
            <div class="product-cost">
                <div class="product-prices">
                    <div class="product-price"><?=Helper::money($arResult['PRICE'])?></div>
                    <?if($arResult['OLD_PRICE']):?>
                        <div class="product-price-old"><?=Helper::money($arResult['OLD_PRICE'])?></div>
                    <?endif?>
                </div>

                <?if($arResult['OLD_PRICE']):?>
                <div class="product-discount">
                    <div class="product-economy-percent">-<?=$arResult['DISCOUNT_PERCENT']?></div>
                    <div class="product-economy-money"><?=Loc::getMessage('RX_BLOCK_LANDING_19_1_MODAL_ECONOMY')?> <?=Helper::money($arResult['DISCOUNT_PRICE'])?></div>
                </div>
                <?endif?>
            </div>
            <?endif?>

            <?if(!$useBasket && $arResult['BTN']):?>
                <div class="product-button">
                    <?=$arResult['BTN']?>
                </div>
            <?elseif($useBasket):?>
                <div class="product-button">
                    <?= Page::showBasketBtn($arResult['ID']); ?>
                </div>
            <?endif?>
        </div>
    <?endif?>

    <?if(!empty($arResult['DETAIL_TEXT'])):?>
    <div class="product-desc">
        <div class="product-title"><?=Loc::getMessage('RX_BLOCK_LANDING_19_1_MODAL_DETAIL_TEXT')?></div>
        <div class="product-desc-text"><?=$arResult['DETAIL_TEXT']?></div>
    </div>
    <?endif?>

    <?if(!empty($arResult['PROPS']['CHARS'])):?>
    <div class="product-chars">
        <div class="product-title"><?=$arResult['PROPERTIES']['CHARS']['NAME']?></div>

        <?foreach ($arResult['PROPS']['CHARS'] as $char):?>
            <div class="product-char"><?=$char?></div>
        <?endforeach?>
    </div>
    <?endif?>
</div>

<script>
    $(document).ready(function() {
        let isMouseDown = false;
        let posX;
        let width;

        $('.modal19_1 .js-custom-scroll').on('mousedown', function(e) {
            e.preventDefault();

            if (e.which === 1 && !isMouseDown) {
                isMouseDown = true;
                posX = e.pageX;
            }
        });
        $('#cardModal').on('mouseup', function(e) {
            if (e.which === 1) isMouseDown = false;
        });
        $('.modal19_1 .js-custom-scroll').on('initSimplebar', function (e) {
            width = $(this).width();
        })

        $('.modal19_1 .js-custom-scroll').on('mousemove', function (e) {
            if (width === undefined || posX === undefined || !isMouseDown) {
                return;
            }

            const $node = $(this).find('.simplebar-content-wrapper');
            let curPosX = e.pageX;
            let offset = $node.scrollLeft() + (curPosX - posX) / 1.3;

            $node.scrollLeft(offset);
            posX = curPosX;
        })
    });

    // simplebar init after images are loaded
    $(document).ready(function() {
        $('.modal19_1 .js-simplebar').on('initSimplebar', function () {
            new SimpleBar(this, {autoHide: false});
        });

        $('.modal19_1 .product-images img').on('load', function() {
            $(this).removeClass('not-loaded');

            if ($('.modal19_1 .not-loaded').length === 0) {
                $('.modal19_1 .js-simplebar').trigger('initSimplebar');
            }
        }).each(function() {
            if(this.complete) {
                $(this).trigger('load');
            }
        });
    });
</script>
