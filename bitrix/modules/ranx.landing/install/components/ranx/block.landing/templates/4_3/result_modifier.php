<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult)) {
    return;
}

if (!empty($arResult['IS_MODAL']) && !empty($arResult['ID'])) {
    if (!empty($arResult['DETAIL_PICTURE'])) {
        $img = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], ['width' => 720, 'height' => 400]);
        $arResult['IMG'] = $img['src'] ?? '';
    }

    return;
}

if(!$arResult["COLS"]){
    $arResult["COLS"] = "4";
}

foreach($arResult['ITEMS'] as &$arItem)
{
    $img = '';
    if (!empty($arItem['PREVIEW_PICTURE'])) {
        $img = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width' => 500, 'height' => 210));
    }
    $arItem['IMG'] = $img;
}
