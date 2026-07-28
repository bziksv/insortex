<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['ITEMS'])) {
    foreach ($arResult['ITEMS'] as $key => $arItem) {
        if (!empty($arItem['PREVIEW_PICTURE'])) {
            $img = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 375, 'height' => 375]);
            $arResult['ITEMS'][$key]['IMG'] = $img['src'];
        }

        if (!empty($arItem['PROPERTIES']['ADDRESS'])) {
            $arAddress = $arItem['PROPERTIES']['ADDRESS'];
            if (is_array($arAddress['VALUE'])) {
                $arAddress['VALUE'] = ((array)$arAddress['VALUE'])['TEXT'];
                $arAddress['~VALUE'] = ((array)$arAddress['~VALUE'])['TEXT'];
            }

            $arResult['ITEMS'][$key]['PROPERTIES']['LOCATION'] = $arAddress;
            $arResult['ITEMS'][$key]['PROPS']['LOCATION'] = $arAddress['VALUE'];
        }
    }
}
