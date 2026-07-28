<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Page;
use Ranx\Landing\Landing;

Page::showPageTitle();
?>
<?$APPLICATION->IncludeComponent(
    'ranx:one.landing',
    '',
    [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'LANDING_ID' => $arParams['IBLOCK_ID'],
        'LANDING_CODE' => $arParams['IBLOCK_CODE'],
        'MODE' => Landing::MODE_SECTIONS,
    ],
    false,
    [
        'HIDE_ICONS' => 'Y',
    ]
);?>
