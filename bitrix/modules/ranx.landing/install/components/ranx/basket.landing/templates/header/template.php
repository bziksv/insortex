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

<div class="header-basket <?= $arParams['CLASSES'] ?>">
    <a href="<?= $arResult['LINK'] ?>" class="theme-stroke-hover">
        <div class="header-basket-icon"><?= Helper::svg('header/cart') ?></div>
        <div class="header-basket-count theme-bg <?= (!$arResult['ITEMS_COUNT'] ? 'empty' : '') ?>"><?= $arResult['ITEMS_COUNT'] ?></div>
    </a>

    <div class="basket-box">
        <div class="basket-box-title"><?= Loc::getMessage('RX_BASKET_LANDING_HEADER_BASKET_TITLE') ?></div>

        <?if($arResult['ITEMS_COUNT']):?>
            <div class="basket-box-items">
                
                <? foreach($arResult['ITEMS'] as $arItem): ?>
                    <div class="basket-box-item" data-id="<?= $arItem['ID'] ?>">
                        <div class="basket-box-item-picture">
                            <?if(!empty($arItem['URL'])):?><a href="<?= $arItem['URL'] ?>"><?endif?>
                            <img src="<?= $arItem['PICTURE'] ?: $defaultPicture ?>" alt="" />
                            <?if(!empty($arItem['URL'])):?></a><?endif?>
                        </div>
                        <div class="basket-box-item-info">
                            <?if(!empty($arItem['URL'])):?>
                                <a href="<?= $arItem['URL'] ?>" class="basket-box-item-title"><?= $arItem['NAME'] ?></a>
                            <?else:?>
                                <div class="basket-box-item-title"><?= $arItem['NAME'] ?></div>
                            <?endif?>
                            <div class="basket-box-item-prices">
                                <div class="basket-box-item-price"><?= Helper::money($arItem['PRICE']) ?></div>
                                <?if($arItem['OLD_PRICE']):?><div class="basket-box-item-oldprice"><?= Helper::money($arItem['OLD_PRICE']) ?></div><?endif?>
                            </div>
                        </div>
                        <a href="#" class="basket-box-item-remove js-basket-remove" data-id="<?= $arItem['ID'] ?>"><?= Helper::svg('panel', 'remove') ?></a>
                    </div>
                <? endforeach ?>

            </div>
            <div class="basket-box-total"><div><?= Loc::getMessage('RX_BASKET_LANDING_HEADER_BASKET_TOTAL') ?> </div><div><?= Helper::money($arResult['TOTAL_PRICE']) ?></div></div>
            <?if($arResult['LINK']):?>
                <a href="<?= $arResult['LINK'] ?>" class="basket-box-btn btn btn-primary btn-block"><?= Loc::getMessage('RX_BASKET_LANDING_HEADER_BASKET_ORDER') ?></a>
            <?endif?>
        <?else:?>
            <div class="basket-box-empty">
                <div class="basket-box-empty-icon"><?= Helper::svg('basket', 'empty') ?></div>
                <div class="basket-box-empty-title"><?= Loc::getMessage('RX_BASKET_LANDING_HEADER_BASKET_EMPTY') ?></div>
            </div>
        <?endif?>

        <div class="basket-box-loading"><div class="spinner-grow theme-color"></div></div>
    </div>
</div>
