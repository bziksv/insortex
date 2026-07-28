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

switch ($arResult["COLS"]) {
    case 2:
        $arResult['BLOCK_CLASSES'] = "col-lg-6 col-md-6 col-sm-6";
        break;
    case 3:
        $arResult['BLOCK_CLASSES'] = "col-lg-4 col-md-6 col-sm-6";
        break;
    case 4:
        $arResult['BLOCK_CLASSES'] = "col-lg-3 col-md-6 col-sm-12";
        break;
    default:
        $arResult['BLOCK_CLASSES'] = "col-lg-3 col-md-6 col-sm-6";
}

foreach($arResult['ITEMS'] as &$arItem)
{
    if (!empty($arItem['PROPS']['ICON'])) {
        $img = CFile::ResizeImageGet($arItem['PROPS']['ICON'], ['width' => 100, 'height' => 100]);
        $arItem['IMG'] = $img['src'] ?? '';
        $arItem['IMG_PATH'] = CFile::GetPath($arItem['PROPS']['ICON']);
        $arItem['IMG_INFO'] = CFile::GetFileArray($arItem['PROPS']['ICON']);
    }
}
