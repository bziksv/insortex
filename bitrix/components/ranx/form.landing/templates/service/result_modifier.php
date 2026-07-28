<?php

use \Ranx\Landing\Fields;

if (!empty($arParams['PRODUCT_ID'])) {
    $dbItem = \CIBlockElement::GetList([], ['ID' => $arParams['PRODUCT_ID']]);
    if ($obItem = $dbItem->GetNextElement()) {
        $arFields = $obItem->GetFields();
        $arFields['PROPERTIES'] = $obItem->GetProperties();

        foreach ($arResult['FIELDS'] as $code => $field) {
            if (in_array($code, ['WEEK_DAY', 'PERSON_NAME', 'CATEGORY'])) {
                $propCode = $code;
                if ($propCode == 'CATEGORY') {
                    $propCode = 'CAT';
                }
                $arResult['FIELDS'][$code]['VALUE'] = htmlspecialchars($arFields['PROPERTIES'][$propCode]['VALUE']);
                $arResult['FIELDS'][$code]['IS_DISABLED'] = true;
            }
            if ($code == 'TAB') {
                $dbSection = \CIBlockSection::GetList(
                    [],
                    ['ID' => $arFields['IBLOCK_SECTION_ID']],
                    false,
                    ['ID', 'NAME']
                );
                if ($arSection = $dbSection->Fetch()) {
                    $arResult['FIELDS'][$code]['VALUE'] = htmlspecialchars($arSection['NAME']);
                    $arResult['FIELDS'][$code]['IS_DISABLED'] = true;
                }
            }
            if ($code == 'NAME') {
                $arResult['FIELDS'][$code]['VALUE'] = $arFields[$code];
                $arResult['FIELDS'][$code]['IS_DISABLED'] = true;
            }
            if ($code == 'INTERVAL_TIME') {
                $fromValue = $arFields['PROPERTIES'][Fields\IntervalTime::getFromPropertyCode()]['VALUE'];
                $toValue = $arFields['PROPERTIES'][Fields\IntervalTime::getToPropertyCode()]['VALUE'];
                $arResult['FIELDS'][$code]['VALUE'] = Fields\IntervalTime::decodeValue($fromValue, $toValue);
                $arResult['FIELDS'][$code]['IS_DISABLED'] = true;
            }
        }

        if (\Ranx\Landing\Config::getPayment() == 'INVOICEBOX') {
            $btnValue = $arFields['PROPERTIES']['LINK']['VALUE'];
            try {
                $btnValue = \Bitrix\Main\Web\Json::decode(htmlspecialchars_decode($btnValue));
                $name = $btnValue['NAME'] ?: htmlspecialchars($arFields['NAME']);
                $discountPriceValue = $btnValue['DISCOUNT'];
                $priceValue = $btnValue['PRICE'];
                $discountTypeValue = $btnValue['DTYPE'];
                if ($discountTypeValue === 'percent') {
                    $discountPriceValue .= '%';
                }

                $totalPrice = \Ranx\Landing\Helpers\Helper::getTotalPrice($priceValue, $discountPriceValue);
                if (!empty($name) && $totalPrice > 0) {
                    $arResult['SERVICE_NAME'] = $name;
                    $arResult['SERVICE_PRICE'] = $totalPrice;
                }
            } catch (\Exception $e) {}
        }
    }
}
