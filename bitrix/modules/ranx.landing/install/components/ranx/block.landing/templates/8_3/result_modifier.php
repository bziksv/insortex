<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult['ITEMS'])) {
    return;
}

$weekDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

foreach($arResult['GROUPS'] as &$arGroup) {
    foreach ($weekDays as $weekDay) {
        $arGroup['DAYS'][$weekDay]['ITEM_KEYS'] = [];
    }

    foreach ($arGroup['ITEMS'] as $key => $arItem) {
        if (!empty($arItem['PROPERTIES']['WEEK_DAY'])) {
            $day = $arItem['PROPERTIES']['WEEK_DAY']['VALUE_XML_ID'];
            if (isset($arGroup['DAYS'][$day])) {
                $arGroup['DAYS'][$day]['ITEM_KEYS'][] = $key;
            }
        }

        $arGroup['ITEMS'][$key]['PROPS']['YEARS'] = \Ranx\Landing\Fields\Years::getDisplayValue($arItem['PROPS']['YEARS']);
    }
}
