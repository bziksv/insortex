<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult)) {
    return;
}
    /* block1-2 */
foreach($arResult['ITEMS'] as &$arItem)
{
    $imgSrc = '';
    if (!empty($arItem['PREVIEW_PICTURE'])) {
        $img = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 1160, 'height' => 1160]);
        $imgSrc = $img['src'];        
    }
    $arItem['IMG'] = $imgSrc;
    $imgSrc = '';
    if (!empty($arItem['DETAIL_PICTURE'])) {
        $img = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], ['width' => 1920, 'height' => 850]);
        $imgSrc = $img['src'];        
    }
    $arItem['BG_IMG'] = $imgSrc; 
}