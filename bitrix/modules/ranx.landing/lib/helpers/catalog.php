<?php


namespace Ranx\Landing\Helpers;

use Bitrix\Main\Loader;
use Bitrix\Catalog\PriceTable;

class Catalog
{
    public static function includeCatalogModule()
    {
        return Loader::includeModule('catalog');
    }

    public static function getPricesInfo()
    {
        if (!self::includeCatalogModule())
            return [];

        $prices = [];
        $dbRes = \CCatalogGroup::GetList(['SORT' => 'ASC']);
        while($arRes = $dbRes->Fetch()) {
            $prices[] = $arRes;
        }

        return $prices;
    }

    public static function getDiscountPrice($productId, $priceTypeId, $count = 1)
    {
        if (!self::includeCatalogModule())
            return [];

        $dbResult = PriceTable::getList([
            'select' => ['ID', 'PRICE', 'CURRENCY', 'CATALOG_GROUP_ID'],
            'filter' => [
                'PRODUCT_ID' => $productId,
                'CATALOG_GROUP_ID' => $priceTypeId,
            ]
        ]);
        if ($arPrice = $dbResult->fetch()) {
            $arDiscountPrice = \CCatalogProduct::GetOptimalPrice($productId, $count,
                $GLOBALS['USER']->GetUserGroupArray(), 'N', [$arPrice]);
            if (!empty($arDiscountPrice))
                return $arDiscountPrice;
        }

        return [];
    }

    public static function checkCatalog($iblockId)
    {
        return self::includeCatalogModule() && \CCatalog::GetByID($iblockId);
    }
}
