<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['PROPS']['IMG'])) {
        $arItem['PROPS']['IMG_PATH'] = CFile::GetPath($arItem['PROPS']['IMG']);
        $arItem['PROPS']['IMG_INFO'] = CFile::GetFileArray($arItem['PROPS']['IMG']);
    }
}
