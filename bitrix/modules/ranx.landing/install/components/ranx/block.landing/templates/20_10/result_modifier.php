<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult['ITEMS'])) {
    return;
}

foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['DETAIL_PICTURE'])) {
        $arItem['IMG'] = \CFile::GetPath($arItem['DETAIL_PICTURE']);
    }

    $arItem['CATEGORY'] = $arResult['CATEGORIES'][$arItem['IBLOCK_SECTION_ID']];
}
