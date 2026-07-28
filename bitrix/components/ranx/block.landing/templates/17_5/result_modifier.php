<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Helpers\Helper;

if (empty($arResult)) {
    return;
}

foreach($arResult['GROUPS'] as &$arGroup)
{
    foreach ($arGroup['ITEMS'] as &$arItem) {
        if (!empty($arItem['PREVIEW_PICTURE'])) {
            $img = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 135, 'height' => 90]);
            $arItem['IMG'] = $img['src'] ?? '';
        }
    }
}
