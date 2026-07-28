<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult['ITEMS'])) {
    return;
}

foreach($arResult['GROUPS'] as &$arGroup)
{
    foreach ($arGroup['ITEMS'] as &$arItem) {
        if (!empty($arItem['PREVIEW_PICTURE'])) {
            $img = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 60, 'height' => 60]);
            $imgSrc = $img['src'] ?? '';
        }
        $arItem['IMG'] = $imgSrc;

        $eventInfo = [
            $arItem['PROPS']['PERSON_NAME'],
            $arItem['PROPS']['POST'],
            $arItem['PROPS']['LOCATION'],
        ];
        $arItem['EVENT_INFO'] = array_filter($eventInfo);
    }
}
