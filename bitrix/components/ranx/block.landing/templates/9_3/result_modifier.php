<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult['ITEMS'])) {
    return;
}

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['IMG'] = '';
    if (!empty($arItem['PREVIEW_PICTURE'])) {
        $img = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 660, 'height' => 660]);
        $arItem['IMG'] = $img['src'] ?? '';
    }
}
