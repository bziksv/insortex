<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult)) {
    return;
}

foreach($arResult['GROUPS'] as &$arGroup)
{
    foreach ($arGroup['ITEMS'] as &$arItem) {
        if (!empty($arItem['PREVIEW_PICTURE'])) {
            $img = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 170, 'height' => 170]);
            $arItem['IMG'] = $img['src'] ?? '';
        }
    }
}

