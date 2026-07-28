<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Helpers\Helper;

if (empty($arResult)) {
    return;
}

foreach($arResult['GROUPS'] as &$arGroup)
{
    foreach ($arGroup['ITEMS'] as &$arItem) {
        if (!empty($arItem['PROPERTIES']['ICON']['VALUE'])) {
            $arItem['PROPS']['IMG_PATH'] = CFile::GetPath($arItem['PROPS']['ICON']);
            $arItem['PROPERTIES']['IMG_INFO'] = CFile::GetFileArray($arItem['PROPS']['ICON']);
        }
    }
}
