<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult)) {
    return;
}
    /* block10-1 */
foreach($arResult['ITEMS'] as &$arItem)
{
    $imgSrc = '';
    if (!empty($arItem['DETAIL_PICTURE'])) {
        $img = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], ['width' => 1000, 'height' => 1000]);
        $imgSrc = $img['src'];        
    }
    $arItem['IMG'] = $imgSrc;
    $imgSrc = '';
    if (!empty($arItem['DETAIL_PICTURE'])) {
        $img = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], ['width' => 2000, 'height' => 2000]);
        $imgSrc = $img['src'];        
    }
    $arItem['DETAIL_IMG'] = $imgSrc; 
}