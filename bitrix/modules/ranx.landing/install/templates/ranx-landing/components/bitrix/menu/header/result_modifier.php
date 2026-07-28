<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Ranx\Landing\Helpers\Menu;
use Ranx\Landing\Config;

$arResult = Menu::getChilds($arResult);

$isHeaderWide = Config::isHeaderWide(Config::get('HEADER_TYPE'));
$arParams['WIDE_MENU_CLASS'] = 'header-nav-'.($isHeaderWide ? 'full' : 'by_size');
$arParams['IS_SHOW_IMAGE'] = Config::get('FULL_DROPDOWN_SHOW_IMAGE');
$arParams['IMAGE_TYPE'] = Config::get('FULL_DROPDOWN_IMAGE_TYPE');
$arParams['IMAGE_CLASS'] = Config::get('FULL_DROPDOWN_POSITION_IMAGE') === 'top' ? 'header-nav-flex-column' : '';
$arParams['SUBITEMS_CLASS'] = Config::get('FULL_DROPDOWN_DISPLAY_SUBITEMS') === 'column' ? 'header-nav-in-column' : '';
$arParams['COL'] = 12 / intval(Config::get('FULL_DROPDOWN_COUNT_COLUMN') ?? '3');

if (!empty($arResult)) {
    foreach ($arResult as &$arItem1) {
        $arItem1['DROPDOWN_MODIFIER'] = $arItem1['PARAMS']['FULL_DROPDOWN'] === 'Y' ? 'header-nav-hide-in-root' : '';

        if (empty($arItem1['CHILD'])) {
            continue;
        }

        foreach ($arItem1['CHILD'] as &$arItem2) {
            if ($arParams['IMAGE_TYPE'] === 'picture' && !empty($arItem2['PARAMS']['PICTURE'])) {
                $img = CFile::ResizeImageGet($arItem2['PARAMS']['PICTURE'], ['width' => 60, 'height' => 60], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
                $arItem2['IMG'] = $img['src'];
            }

            if ($arParams['IMAGE_TYPE'] === 'icon' && !empty($arItem2['PARAMS']['UF_ICON'])) {
                $img = CFile::ResizeImageGet($arItem2['PARAMS']['UF_ICON'], ['width' => 40, 'height' => 40], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
                $arItem2['IMG'] = $img['src'];
            }
        }
    }
}
