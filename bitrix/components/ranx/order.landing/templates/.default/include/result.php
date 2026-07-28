<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__DIR__ . '/../template.php');
?>
<div class="order-result-info">
    <div class="order-result-title"><?= Loc::getMessage('RX_ORDER_LANDING_ORDER_TITLE') ?></div>
    <div class="order-result-item">
        <div class="order-result-item-title"><?= Loc::getMessage('RX_ORDER_LANDING_ORDER_PRODUCTS') ?> (<?= count($arResult['BASKET_ITEMS']) ?>)</div>
		<?/*?><div class="order-result-item-value"><?= Helper::money($arResult['PRODUCTS_PRICE']) ?: Loc::getMessage('RX_ORDER_LANDING_FREE') ?></div><?*/?>
    </div>
<?/*?>
    <div class="order-result-item">
        <div class="order-result-item-title"><?= Loc::getMessage('RX_ORDER_LANDING_ORDER_DELIVERY') ?></div>
        <div class="order-result-item-value"><?= Helper::money($arResult['DELIVERY_PRICE']) ?: Loc::getMessage('RX_ORDER_LANDING_FREE') ?></div>
    </div>
    <?if($arResult['DISCOUNT_PRICE']):?>
    <div class="order-result-item">
        <div class="order-result-item-title"><?= Loc::getMessage('RX_ORDER_LANDING_ORDER_ECONOMY') ?></div>
        <div class="order-result-item-value order-result-item-value-discount">- <?= Helper::money($arResult['DISCOUNT_PRICE']) ?></div>
    </div>
    <?endif?>

    <div class="order-result-total">
        <div class="order-result-total-title"><?= Loc::getMessage('RX_ORDER_LANDING_ORDER_TOTAL') ?>:</div>
        <div class="order-result-total-value"><?= Helper::money($arResult['TOTAL_PRICE']) ?: Loc::getMessage('RX_ORDER_LANDING_FREE') ?></div>
    </div>
<?*/?>
</div>
