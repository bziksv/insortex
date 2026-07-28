<?php 
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['PROPS']['IMG'])) {
        $img = CFile::ResizeImageGet($arItem['PROPS']['IMG'], ['width' => 200, 'height' => 200]);
        $arItem['PROPS']['IMG_PATH'] = $img['src'];
    }
}
