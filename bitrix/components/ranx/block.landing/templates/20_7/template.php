<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Page;
use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

global $APPLICATION;
$useLazyLoad = Config::isLazyLoadEnabled();
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

                        <div class="product-buy">
                            <a href="#"
                               class="product-buy-btn btn btn-primary btn-block js-form-modal"
                               data-form-code="ranx_landing_form_oneclick"
                               data-product-id="<?= $arItem['ID'] ?>">
                                <?= Loc::getMessage('RX_BLOCK_LANDING_20_7_CONSULT_BTN') ?>
                            </a>
                        </div>
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
