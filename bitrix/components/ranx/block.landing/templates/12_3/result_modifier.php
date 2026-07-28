<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['IS_MODAL']) && !empty($arResult['ID'])) {
    $imgSrc = '';
    if (!empty($arResult['PREVIEW_PICTURE'])) {
        $img = CFile::ResizeImageGet($arResult['PREVIEW_PICTURE'], ['width' => 80, 'height' => 80]);
        $imgSrc = $img['src'] ?? '';
    }
    $arResult['IMG'] = $imgSrc;
    $arResult['MARK'] = (empty($arResult['PROPERTIES']['MARK']['VALUE_XML_ID'])) ? 5 : $arResult['PROPERTIES']['MARK']['VALUE_XML_ID'];

    return;
}

if (!empty($arResult['ITEMS'])) {
    foreach ($arResult['ITEMS'] as &$arItem) {
        $imgSrc = '';
        if (!empty($arItem['PREVIEW_PICTURE'])) {
            $img = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 80, 'height' => 80]);
            $imgSrc = $img['src'] ?? '';
        }
        $arItem['IMG'] = $imgSrc;
        $arItem['MARK'] = (empty($arItem['PROPERTIES']['MARK']['VALUE_XML_ID'])) ? 5 : $arItem['PROPERTIES']['MARK']['VALUE_XML_ID'];
    }
}
