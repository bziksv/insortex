<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
/**
 * @var array $arParams
 * @var array $arResult
 */

use Ranx\Landing\Page;
use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

$useLazyLoad = Config::isLazyLoadEnabled();
$useBasket = Config::isOrderEnabled() && $arParams['SETTINGS']['USE_BASKET'];
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>
        <?= $arResult['BLOCK_TABS'] ?>

        <?if(!empty($arResult['GROUPS'])):
        $col = ($arResult['COLS']) ? 12 / $arResult['COLS'] : 3;
        ?>

        <?foreach ($arResult['GROUPS'] as $arGroup):?>
        <div class="row <?if(empty($arResult['INDENT_ELEMENTS'])):?>no-gutters<?endif?> <?=$arGroup['CLASS']?>" <?=$arGroup['ATTR']?>>
            <?foreach($arGroup['ITEMS'] as $i => $arItem):?>

            <div class="col-xl-<?=$col?> col-lg-4 col-sm-6">
                <div class="product">

                    <?if(!empty($arItem['PROPERTIES']['MARKERS']['VALUE'])):?>
                    <div class="product-stickers">
                        <?foreach($arItem['PROPERTIES']['MARKERS']['VALUE_XML_ID'] as $j => $sticker):?>
                            <div class="product-sticker product-sticker-<?=strtolower($sticker)?>"><?=$arItem['PROPERTIES']['MARKERS']['VALUE'][$j]?></div>
                        <?endforeach?>
                    </div>
                    <?endif?>

                    <?if($arItem['PROPS']['POPUP_SHOW'] === 'Y'):?>
                        <a class="product-img js-card-modal" data-code="<?=$arResult['CODE']?>" data-id="<?=$arItem['ID']?>">
                    <?elseif (!empty($arItem['LINK'])):?>
                        <a class="product-img <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                    <?else:?>
                        <div class="product-img">
                    <?endif?>

                            <img class="lazy" <?if($useLazyLoad):?>data-src="<?=$arItem['IMG']?>"<?else:?>src="<?=$arItem['IMG']?>"<?endif?>
                                 alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">

                    <?if($arItem['PROPS']['POPUP_SHOW'] === 'Y'):?>
                        </a>
                        <a class="product-name block-el-title js-card-modal" data-code="<?=$arResult['CODE']?>"
                           data-id="<?=$arItem['ID']?>"><?=$arItem['NAME']?></a>
                    <?elseif(!empty($arItem['LINK'])):?>
                        </a>
                        <a class="product-name <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>><?=$arItem['NAME']?></a>
                    <?else:?>
                        </div>
                        <div class="product-name block-el-title"><?=$arItem['NAME']?></div>
                    <?endif?>

                    <?if(empty($arParams['SETTINGS']['HIDE_AVAILABLE'])):?>
                        <?if($arItem['PROPS']['AVAILABLE'] === 'Y'):?>
                            <div class="product-available product-available-yes"><?= Loc::getMessage('RX_BLOCK_LANDING_19_AVAILABLE') ?></div>
                        <?else:?>
                            <div class="product-available product-available-no"><?= Loc::getMessage('RX_BLOCK_LANDING_19_UNAVAILABLE') ?></div>
                        <?endif?>
                    <?endif?>

                    <div class="product-bottom">
                        <?if(!empty($arItem['PRICE'])):?>
                            <div class="product-prices">
                                <div>
                                    <div class="product-price"><?=Helper::money($arItem['PRICE'])?></div>
                                    <?if($arItem['OLD_PRICE']):?>
                                        <div class="product-price-old"><?=Helper::money($arItem['OLD_PRICE'])?></div>
                                    <?endif?>
                                </div>
                                <?if($arItem['OLD_PRICE']):?>
                                <div>
                                    <div class="product-economy-percent">-<?=$arItem['DISCOUNT_PERCENT']?></div>
                                    <div class="product-economy-money"><?= Loc::getMessage('RX_BLOCK_LANDING_19_ECONOMY') ?> <?=Helper::money($arItem['DISCOUNT_PRICE'])?></div>
                                </div>
                                <?endif?>
                            </div>
                        <?endif?>

                        <?if(!$useBasket && $arItem['BTN']):?>
                        <div>
                            <?=$arItem['BTN']?>
                        </div>
                        <?endif?>
                    </div>

                    <?if($useBasket):?>
                        <?= Page::showBasketBtn($arItem['ID']); ?>
                    <?endif?>
                </div>
            </div>

            <?endforeach?>
        </div>
        <?endforeach?>

        <?= $arResult['BTN'] ?>

    <?endif?>

    </div>

<?= $arResult['BLOCK_END'] ?>
