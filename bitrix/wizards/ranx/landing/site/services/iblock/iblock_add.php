<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/**
 * Adding iblock
 * Need $iblockShortCode var for working
 */

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\IblockSiteTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\GroupTable;

if (!Loader::includeModule('iblock')) return;
if (!defined('WIZARD_SITE_ID')) return;
if (empty($iblockShortCode)) return; // need iblock short code

$iblockType = 'ranx_landing';
$iblockCode = 'ranx_landing_' . $iblockShortCode;
$iblockXmlFile = WIZARD_SERVICE_RELATIVE_PATH . '/xml/' . LANGUAGE_ID . '/' . $iblockShortCode . '.xml';
$iblockId = false;

// get iblock if exists
$arIblock = IblockTable::getList([
    'filter' => [
        'IBLOCK_TYPE_ID' => $iblockType,
        'CODE'           => $iblockCode,
    ],
])->fetch();

if ($arIblock) { // if exists add new site id
    $iblockId = $arIblock['ID'];
    $arIblockSites = IblockSiteTable::getList([
        'filter' => [
            'IBLOCK_ID' => $iblockId,
            'SITE_ID'   => WIZARD_SITE_ID,
        ],
    ])->fetchAll();
    if (empty($arIblockSites)) {
        IblockSiteTable::add([
            'IBLOCK_ID' => $iblockId,
            'SITE_ID'   => WIZARD_SITE_ID,
        ]);
    }
} else { // add new

    // permissions
    $perms = [
        '1' => 'X',
        '2' => 'R',
    ];
    $arLandingEditorGroup = GroupTable::getList([
        'filter' => [
            'STRING_ID' => 'rx_landing_editor',
        ],
    ])->fetch();
    if (!empty($arLandingEditorGroup)) {
        $perms[$arLandingEditorGroup['ID']] = 'W';
    }

    // xml file processing
    $rootIblockXmlFile = $_SERVER["DOCUMENT_ROOT"] . $iblockXmlFile;
    $iblockId = \WizardServices::ImportIBlockFromXML($rootIblockXmlFile, $iblockCode, $iblockType, WIZARD_SITE_ID, $perms);
    if ($iblockId < 1)	return;

    // do some other stuff with created iblock
    IblockTable::update($iblockId, [
        'ACTIVE' => 'Y',
        'CODE'   => $iblockCode,
    ]);

    // setting property hints
    if (!empty($propertyHints)) {
        $dbProperty = PropertyTable::getList([
            'filter' => [
                'IBLOCK_ID' => $iblockId,
                'CODE' => array_keys($propertyHints),
            ],
            'select' => ['ID', 'CODE']
        ]);

        while ($arProperty = $dbProperty->fetch()) {
            PropertyTable::update($arProperty['ID'], [
                'HINT' => $propertyHints[$arProperty['CODE']]
            ]);
        }
    }
}

// clean cache
$cache = \Bitrix\Main\Application::getInstance()->getManagedCache();
$cache->cleanDir('b_iblock');
$cache->cleanAll();

if (Loader::includeModule('ranx.landing')) {
    \Ranx\Landing\Cache::ClearTagIBlock();
}
