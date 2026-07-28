<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['ITEMS'])) {
    foreach ($arResult['ITEMS'] as $i => &$arItem) {
        if (!empty($arItem['DETAIL_PICTURE'])) {
            $resizedImg = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], ['width' => 700, 'height' => 500]);
            $arItem['IMG'] = $resizedImg['src'] ?? '';
        }
    }
}
