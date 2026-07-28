<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult['ITEMS'])) {
    return;
}

foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['PREVIEW_PICTURE'])) {
        $resizedImg = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 250, 'height' => 250]);
        $arItem['IMG'] = $resizedImg['src'] ?? '';
    }

    $arItem['CATEGORY'] = $arResult['CATEGORIES'][$arItem['IBLOCK_SECTION_ID']];
}
