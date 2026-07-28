<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult['ITEMS'])) {
    return;
}

foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['PREVIEW_PICTURE'])) {
        $resizedImg = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 960, 'height' => 640]);
        $arItem['IMG'] = $resizedImg['src'] ?? '';
    }
    if (empty($arItem['IMG'])) {
        $arItem['IMG'] = $this->__folder.'/img/empty.png';
    }

    $arItem['CATEGORY'] = $arResult['CATEGORIES'][$arItem['IBLOCK_SECTION_ID']];
}
