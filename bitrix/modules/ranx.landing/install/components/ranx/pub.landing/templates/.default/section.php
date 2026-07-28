<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Page,
    Ranx\Landing\Landing;

Page::showPageTitle();
?>

<?$APPLICATION->IncludeComponent(
    'ranx:one.landing',
    '',
    [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'LANDING_ID' => !empty($arResult['VARIABLES']['SECTION_ID']) ? $arResult['VARIABLES']['SECTION_ID'] : $arParams['IBLOCK_ID'],
        'LANDING_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
        'MODE' => !empty($arResult['VARIABLES']['SECTION_ID']) ? Landing::MODE_SECTION : Landing::MODE_ROOT_SECTION,
        'PARENT_SECTION' => $arResult['VARIABLES']['SECTION_ID'],
        'PARENT_SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
    ],
    false,
    [
        'HIDE_ICONS' => 'Y',
    ]
);?>
