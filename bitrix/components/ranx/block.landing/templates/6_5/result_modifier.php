<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['IS_MODAL'])) {
    if (empty($arResult['DETAIL_PICTURE']) || empty($arResult['ID'])) {
        return;
    }

    $img = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], ['width' => 720, 'height' => 400]);
    $arResult['DETAIL_PICTURE_PATH'] = $img['src'];

    return;
}

if (!empty($arResult['ITEMS'])) {
    foreach ($arResult['ITEMS'] as &$arItem) {
        if (empty($arItem['PREVIEW_PICTURE'])) {
            continue;
        }

        $img = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 300, 'height' => 190]);
        $arItem['PREVIEW_PICTURE_PATH'] = $img['src'];
    }
}
