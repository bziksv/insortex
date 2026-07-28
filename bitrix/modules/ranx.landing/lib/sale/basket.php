<?php

namespace Ranx\Landing\Sale;

use Exception;
use Bitrix\Main\Loader;
use Ranx\Landing\Helpers\Helper;

class Basket
{
    public static function init()
    {
        Loader::includeModule('iblock');

        $userId = self::getUserId();
        if (!isset($_SESSION[SITE_ID][$userId]['BASKET_ITEMS'])) {
            $_SESSION[SITE_ID][$userId]['BASKET_ITEMS'] = [];
        }
    }

    public static function add($id, $quantity = 1)
    {
        self::init();

        $quantity = intval($quantity);
        if ($quantity < 1) {
            $quantity = 1;
        }

        $userId = self::getUserId();
        if (!empty($_SESSION[SITE_ID][$userId]['BASKET_ITEMS'][$id])) { // if exists, just add quantity
            $_SESSION[SITE_ID][$userId]['BASKET_ITEMS'][$id]['QUANTITY'] += $quantity;
        } else {
            $arItem = self::fetchItemData($id);
            $arItem['QUANTITY'] = $quantity;

            $_SESSION[SITE_ID][$userId]['BASKET_ITEMS'][$arItem['ID']] = $arItem;
        }

        return $_SESSION[SITE_ID][$userId]['BASKET_ITEMS'][$arItem['ID']];
    }

    public static function fetchItemData($id)
    {
        $arItem = \CIBlockElement::GetList(
            [],
            ['ID' => $id, 'ACTIVE' => 'Y'],
            false,
            false,
            ['ID', 'IBLOCK_ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'PROPERTY_PRICE', 'PROPERTY_DISCOUNT_PRICE']
        )->GetNext();
        if (empty($arItem)) {
            throw new Exception('Product not found');
        }

        $itemPrices = Helper::calcPrice($arItem['PROPERTY_PRICE_VALUE'], $arItem['PROPERTY_DISCOUNT_PRICE_VALUE']);
        $picture = \CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 70, 'height' => 70]);

        return [
            'ID' => $arItem['ID'],
            'NAME' => $arItem['NAME'],
            'URL' => strpos($arItem['DETAIL_PAGE_URL'], 'detail.php') === false ? $arItem['DETAIL_PAGE_URL'] : '',
            'OLD_PRICE' => $itemPrices['OLD_PRICE'],
            'PRICE' => $itemPrices['PRICE'],
            'DISCOUNT_PRICE' => $itemPrices['DISCOUNT_PRICE'],
            'DISCOUNT_PERCENT' => $itemPrices['DISCOUNT_PERCENT'],
            'PICTURE' => $picture['src'] ?? '',
        ];
    }

    public static function addCustom($name, $price, $discount = '')
    {
        if (!$name || !$price) {
            throw new Exception('No product name or price');
        }

        self::init();

        $itemPrices = Helper::calcPrice($price, $discount);

        $arItem = [
            'NAME' => htmlspecialchars(trim($name)),
            'URL' => '',
            'OLD_PRICE' => $itemPrices['OLD_PRICE'],
            'PRICE' => $itemPrices['PRICE'],
            'DISCOUNT_PRICE' => $itemPrices['DISCOUNT_PRICE'],
            'DISCOUNT_PERCENT' => $itemPrices['DISCOUNT_PERCENT'],
            'PICTURE' => '',
        ];

        $arItem['ID'] = md5(serialize($arItem));
        $arItem['QUANTITY'] = 1;

        $userId = self::getUserId();
        $_SESSION[SITE_ID][$userId]['BASKET_ITEMS'][$arItem['ID']] = $arItem;

        return $arItem;
    }

    public static function quantity($id, $quantity = 1)
    {
        self::init();

        $quantity = intval($quantity);
        if ($quantity < 1) {
            $quantity = 1;
        }

        $userId = self::getUserId();
        if (!empty($_SESSION[SITE_ID][$userId]['BASKET_ITEMS'][$id])) {
            $_SESSION[SITE_ID][$userId]['BASKET_ITEMS'][$id]['QUANTITY'] = $quantity;

            return true;
        }

        return false;
    }

    public static function get()
    {
        self::init();

        $userId = self::getUserId();
        return $_SESSION[SITE_ID][$userId]['BASKET_ITEMS'];
    }

    public static function getCount()
    {
        $items = self::get() ?? [];

        return count($items);
    }

    public static function getTotalPrice()
    {
        $items = self::get() ?? [];

        $total = 0;
        foreach ($items as $item) {
            $total += is_numeric($item['PRICE']) ? $item['PRICE'] * $item['QUANTITY'] : 0;
        }

        return $total;
    }

    public static function has($id)
    {
        self::init();

        $userId = self::getUserId();
        return isset($_SESSION[SITE_ID][$userId]['BASKET_ITEMS'][$id]);
    }

    public static function remove($id)
    {
        self::init();

        $userId = self::getUserId();
        unset($_SESSION[SITE_ID][$userId]['BASKET_ITEMS'][$id]);
    }

    public static function empty()
    {
        self::init();

        $userId = self::getUserId();
        unset($_SESSION[SITE_ID][$userId]['BASKET_ITEMS']);
    }

    public static function getStr()
    {
        $str = '';

        $items = self::get();
        foreach ($items as $item) {
            $str .= $item['NAME'] . ' (' . Helper::money($item['PRICE'], '') . ' x ' . $item['QUANTITY'] . ')' . "\n";
        }

        return $str;
    }

    private static function getUserId()
    {
        return $GLOBALS['USER']->IsAuthorized() ? $GLOBALS['USER']->GetID() : 0;
    }
}
