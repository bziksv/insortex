<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var string $template
 */
?>
<?$GLOBALS['APPLICATION']->IncludeComponent(
    'bitrix:catalog.smart.filter',
    $template,
    [
        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'SECTION_ID' => $arParams['SECTION_ID'],
        'BLOCK_ID' => $arParams['BLOCK_ID'],
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => '36000000',
        'FILTER_NAME' => $arParams['FILTER_NAME'],
        'SEF_MODE' => 'Y',
        'BASE_URL' => $arParams['BASE_URL'],
        'SEF_RULE' => $arParams['SEF_RULE'],
        'SEF_RULE_FILTER' => $arParams['SEF_RULE_FILTER'],
        'SMART_FILTER_PATH' => $arParams['SMART_FILTER_PATH'],
        'SHOW_ALL_WO_SECTION' => 'Y',
        'SAVE_IN_SESSION' => 'N',
        'XML_EXPORT' => 'N',
        'DISPLAY_ELEMENT_COUNT' => 'N',
    ],
    false);
?>
