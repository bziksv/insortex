<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['ITEMS'])) {
    foreach ($arResult['ITEMS'] as $i => &$arItem) {
        if (!empty($arItem['DETAIL_PICTURE'])) {
            $resizedImg = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], ['width' => 1000, 'height' => 700]);
            $arItem['IMG'] = $resizedImg['src'] ?? '';
        }
    }
}
