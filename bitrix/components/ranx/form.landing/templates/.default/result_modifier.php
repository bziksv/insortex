<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Ranx\Landing\Sale\Basket;

$arResult['REQUEST_PRODUCT'] = null;

if (!empty($arParams['IS_ONECLICK']) && (int)$arParams['PRODUCT_ID'] > 0) {
    try {
        $product = Basket::fetchItemData((int)$arParams['PRODUCT_ID']);
        if (!empty($product['NAME'])) {
            $arResult['REQUEST_PRODUCT'] = [
                'NAME' => $product['NAME'],
                'PICTURE' => $product['PICTURE'] ?? '',
            ];
        }
    } catch (\Exception $e) {
        $arResult['REQUEST_PRODUCT'] = null;
    }
}
