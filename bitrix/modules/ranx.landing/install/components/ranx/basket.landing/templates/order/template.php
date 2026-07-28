<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var CBitrixComponentTemplate $this
 * @var array $arParams
 * @var array $arResult
 */

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

$defaultPicture = $this->__component->__path . '/img/noimage.png';
?>

<div class="order-basket">
    <div class="order-basket-header">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="order_basket_select_all">
            <label class="custom-control-label" for="order_basket_select_all"><?= Loc::getMessage('RX_BASKET_LANDING_ORDER_SELECT_ALL') ?></label>
        </div>
        <a href="#" class="order-basket-remove-selected js-basket-remove-selected" style="display: none;"><?= Loc::getMessage('RX_BASKET_LANDING_ORDER_REMOVE_SELECTED') ?></a>
    </div>
    <div class="order-basket-items">
        <?foreach($arResult['ITEMS'] as $arItem):?>
            <div class="order-basket-item">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input order-basket-item-select" id="order_basket_select_<?= $arItem['ID'] ?>" data-id="<?= $arItem['ID'] ?>">
                    <label class="custom-control-label" for="order_basket_select_<?= $arItem['ID'] ?>"></label>
                </div>
                <div class="order-basket-item-picture">
                    <?if(!empty($arItem['URL'])):?><a href="<?= $arItem['URL'] ?>"><?endif?>
                    <img src="<?= $arItem['PICTURE'] ?: $defaultPicture ?>" alt="" />
                    <?if(!empty($arItem['URL'])):?></a><?endif?>
                </div>
                <div class="order-basket-item-info">
                    <?if(!empty($arItem['URL'])):?>
                        <a href="<?= $arItem['URL'] ?>" class="order-basket-item-title"><?= $arItem['NAME'] ?></a>
                    <?else:?>
                        <div class="order-basket-item-title"><?= $arItem['NAME'] ?></div>
                    <?endif?>
                    <div class="order-basket-item-prices">
                        <div class="order-basket-item-price"><?= Helper::money($arItem['PRICE']) ?></div>
                        <?if($arItem['OLD_PRICE']):?>
                            <div class="order-basket-item-oldprice"><?= Helper::money($arItem['OLD_PRICE']) ?></div>
                        <?endif?>
                    </div>
                    <?if($arItem['DISCOUNT_PRICE']):?>
                        <div class="order-basket-item-discount">
                            <div class="order-basket-item-discount-percent"><?= $arItem['DISCOUNT_PERCENT'] ?></div>
                            <div class="order-basket-item-discount-price">
                                <?= Loc::getMessage('RX_BASKET_LANDING_ORDER_ECONOMY') ?> <?= Helper::money($arItem['DISCOUNT_PRICE']) ?>
                            </div>
                        </div>
                    <?endif?>
                    <a href="#" class="order-basket-item-remove js-basket-remove" data-id="<?= $arItem['ID'] ?>"><?= Loc::getMessage('RX_BASKET_LANDING_ORDER_REMOVE') ?></a>
                </div>
                <div class="order-basket-item-counter">
                    <div class="counter">
                        <div class="counter-minus"></div>
                        <input class="counter-value" type="text" name="quantity" value="<?= $arItem['QUANTITY'] ?>" data-id="<?= $arItem['ID'] ?>">
                        <div class="counter-plus"></div>
                    </div>
                </div>
            </div>
        <?endforeach?>
    </div>

    <div class="order-basket-loading"><div class="spinner-grow theme-color"></div></div>
</div>
