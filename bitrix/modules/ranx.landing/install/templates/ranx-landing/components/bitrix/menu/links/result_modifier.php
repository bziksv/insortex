<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Ranx\Landing\Helpers\Menu;

$menu = Menu::getChilds($arResult);

$arResult = [];
$arResult['MAIN_SECTION_ID'] = 'LinkMenu';
$arResult['SECTIONS'] = Menu::prepareSections($menu, $arResult['MAIN_SECTION_ID']);
