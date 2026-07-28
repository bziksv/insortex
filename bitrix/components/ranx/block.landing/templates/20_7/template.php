<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Page;
use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

global $APPLICATION;
$useLazyLoad = Config::isLazyLoadEnabled();
$useBasket = Config::isOrderEnabled();
$isAjaxRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAjaxRequest();
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">
    <?if (!empty($arResult['ITEMS'])):?>
        <? $arItem = reset($arResult['ITEMS']); ?>
        <div class="product">
            <div class="product-img-wrap">
                <? $arMarkers = $arItem['PROPERTIES']['MARKERS']; ?>
                <?if(!empty($arMarkers['VALUE'])):?>
                    <div class="product-stickers">
                        <?foreach($arMarkers['VALUE_XML_ID'] as $j => $sticker):?>
                            <div class="product-sticker product-sticker-<?=strtolower($sticker)?>">
                                <?=$arMarkers['VALUE'][$j]?>
                            </div>
                        <?endforeach?>
                    </div>
                <?endif?>

                <div class="product-img">
                    <img class="lazy" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>"
                         <?if($useLazyLoad):?> data-src="<?=$arItem['IMG']?>"<?else:?> src="<?=$arItem['IMG']?>"<?endif?>>
                </div>
            </div>
            <div class="product-info-wrap">
                <div class="product-breadcrumb">
                    <?if (!$isAjaxRequest):?>
                        <?$APPLICATION->IncludeComponent(
                           'bitrix:breadcrumb',
                           '',
                           [
                                'START_FROM' => '1',
                                'PATH' => '',
                                'SITE_ID' => SITE_ID,
                           ]
                        );?>
                    <?endif;?>
                </div>
                <?if (!empty($arItem['NAME'])):?>
                    <<?=$arResult['TITLE_TAG']?> class="product-name block-el-title"><?=$arItem['NAME']?></<?=$arResult['TITLE_TAG']?>>
                <?endif?>
                <div class="product-info">
                    <?if (isset($arItem['PROPS']['AVAILABLE'])):?>
                        <?if($arItem['PROPS']['AVAILABLE'] === 'Y'):?>
                            <div class="product-available product-available-yes">
                                <span><?= Loc::getMessage('RX_BLOCK_LANDING_20_7_AVAILABLE') ?></span>
                            </div>
                        <?else:?>
                            <div class="product-available product-available-no">
                                <span><?= Loc::getMessage('RX_BLOCK_LANDING_20_7_UNAVAILABLE') ?></span>
                            </div>
                        <?endif?>
                    <?endif?>

                    <?if(!empty($arItem['MARK'])):?>
                        <div class="product-rating">
                            <?for($i = 0; $i < $arItem['MARK']; $i++):?>
                                <div class="product-star product-star--on"><?= Helper::svg('block/star') ?></div>
                            <?endfor?>
                            <?for($i = 0; $i < (5 - $arItem['MARK']); $i++):?>
                                <div class="product-star product-star--off"><?= Helper::svg('block/star') ?></div>
                            <?endfor?>
                        </div>
                    <?endif?>
                    <?if(!empty($arItem['PROPS']['CML2_ARTICLE'])):?>
                    <div class="product-article">
                        <?= Loc::getMessage('RX_BLOCK_LANDING_20_7_ARTICLE', ['#VALUE#' => $arItem['PROPS']['CML2_ARTICLE']]) ?>
                    </div>
                    <?endif?>
                </div>

                <?if (!empty($arItem['PREVIEW_TEXT'])):?>
                    <div class="product-preview-text"><?=$arItem['PREVIEW_TEXT']?></div>
                <?endif?>

                <div class="product-detail">
                    <div class="product-sale">
                        <?if (!empty($arItem['PRICE'])):?>
                            <div class="product-prices">
                                <div class="product-price-align-wrap">
                                    <div class="product-price"><?=Helper::money($arItem['PRICE'])?></div>
                                    <?if($arItem['OLD_PRICE']):?>
                                        <div class="product-price-old"><?=Helper::money($arItem['OLD_PRICE'])?></div>
                                    <?endif?>
                                </div>
                                <?if($arItem['OLD_PRICE']):?>
                                    <div>
                                        <div class="product-economy-percent">-<?=$arItem['DISCOUNT_PERCENT']?></div>
                                        <div class="product-economy-money"><?= Loc::getMessage('RX_BLOCK_LANDING_20_7_ECONOMY') ?> <?=Helper::money($arItem['DISCOUNT_PRICE'])?></div>
                                    </div>
                                <?endif?>
                            </div>
                        <?endif?>

                        <?if($useBasket):?>
                            <?= Page::showBasketBtn($arItem['ID']); ?>

                            <?if(Config::isOneclickEnabled()):?>
                            <a href="#" class="product-oneclick theme-color-hover js-form-modal" data-form-code="ranx_landing_form_oneclick" data-product-id="<?= $arItem['ID'] ?>">
                                <div class="product-oneclick-icon">
                                    <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7 4C7 1.79086 8.79086 0 11 0C13.2091 0 15 1.79086 15 4V5H16C17.1046 5 18 5.89543 18 7V14C18 15.1046 17.1046 16 16 16H6C4.89543 16 4 15.1046 4 14V12C4 11.4477 4.44772 11 5 11C5.55228 11 6 11.4477 6 12V14H16V7H15V9C15 9.55229 14.5523 10 14 10C13.4477 10 13 9.55229 13 9V7H8H1C0.447715 7 0 6.55228 0 6C0 5.44772 0.447715 5 1 5H7V4ZM9 5H13V4C13 2.89543 12.1046 2 11 2C9.89543 2 9 2.89543 9 4V5ZM2 9C2 8.44771 2.44772 8 3 8H8C8.55228 8 9 8.44771 9 9C9 9.55229 8.55228 10 8 10H3C2.44772 10 2 9.55229 2 9Z" fill="currentColor"/>
                                    </svg>
                                </div>
                                <span class="product-oneclick-text"><?= Loc::getMessage('RX_BLOCK_LANDING_20_7_ONECLICK_BUY') ?></span>
                            </a>
                            <?endif?>
                        <?else:?>
                            <div class="product-btn">
                                <div class="block-el-btns"><?=$arItem['BTN']?></div>
                            </div>
                        <?endif?>
                    </div>
                    <?if (!empty($arItem['CHARS'])):?>
                        <div class="product-chars">
                                <div class="product-chars-title">
                                    <?= Loc::getMessage('RX_BLOCK_LANDING_20_7_CHARS_TITLE') ?>
                                </div>
                                <?foreach ($arItem['CHARS'] as $arChar):?>
                                    <div class="product-char"><span><?=$arChar['NAME']?></span><?=' &mdash; '.$arChar['VALUE']?></div>
                                <?endforeach?>
                        </div>
                    <?endif?>
                </div>
            </div>
        </div>
    <?endif?>
</div>

<?= $arResult['BLOCK_END'] ?>
