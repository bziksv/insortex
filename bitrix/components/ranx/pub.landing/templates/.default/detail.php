<?if (!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)die();
$this->setFrameMode(true);
?>
<?$APPLICATION->IncludeComponent(
    'ranx:one.landing',
    '',
    [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'LANDING_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
        'LANDING_CODE' => $arResult['VARIABLES']['ELEMENT_CODE'],
        'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
    ],
    false,
    [
        'HIDE_ICONS' => 'Y',
    ]
);?>
