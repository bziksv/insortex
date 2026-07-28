<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Helpers\Helper;

foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['PROPS']['IMG'])) {
        $arItem['PROPS']['IMG_PATH'] = CFile::GetPath($arItem['PROPS']['IMG']);
        $arItem['PROPS']['IMG_INFO'] = CFile::GetFileArray($arItem['PROPS']['IMG']);

        if (!Helper::isSvg($arItem['PROPS']['IMG_INFO']['CONTENT_TYPE'])) {
            $img = CFile::ResizeImageGet($arItem['PROPS']['IMG'], ['width' => 100, 'height' => 100]);
            $arItem['PROPS']['IMG_PATH'] = $img['src'] ?? '';
        }
    }
}
