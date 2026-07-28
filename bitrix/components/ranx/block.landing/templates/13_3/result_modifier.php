<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['ITEMS'])) {
    foreach ($arResult['ITEMS'] as $i => &$arItem) {
        if (!empty($arItem['PROPS']['IMG'])) {
            $resizedImg = CFile::ResizeImageGet($arItem['PROPS']['IMG'], ['width' => 110, 'height' => 60]);
            $arItem['LOGO'] = $resizedImg['src'] ?? '';
        }
    }
}
