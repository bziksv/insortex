<?php
/**
 * The script removes iblocks, blocks and cards that are not attached to any sections.
 */

if (php_sapi_name() !== 'cli') die();

$_SERVER['DOCUMENT_ROOT'] = realpath(dirname(__FILE__) . '/../../../..');
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('BX_CRONTAB', true);
define('BX_NO_ACCELERATOR_RESET', true);
define('RX_CLI', true);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

@set_time_limit(0);

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('ranx.landing');

use Ranx\Landing\Block;
use Ranx\Landing\Landing;
use Ranx\Landing\Helpers;
use Bitrix\Iblock\IblockTable;

function rxPrintArray($value)
{
    echo print_r($value, true)."\n";
}


// remove iblock
$dbIblock = IblockTable::getList([
    'filter' => [
        'IBLOCK_TYPE_ID' => 'ranx_landing',
        'CODE' => 'ranx_landing_list_%',
    ],
    'select' => ['ID']
]);

while ($arIblock = $dbIblock->fetch()) {
    $sections = \Ranx\Landing\SectionTable::getList([
        'filter' => ['IBLOCK_ID' => $arIblock['ID']]
    ])->fetchAll();

    if (count($sections) > 0) {
        continue;
    }

    Helpers\Iblock::removeIblock($arIblock['ID']);
}


// remove blocks
$blockIblockId = Block::getIblockId();
$dbLanding = \CIBlockElement::GetList(
    [],
    ['IBLOCK_ID' => $blockIblockId],
    ['PROPERTY_LANDING', 'PROPERTY_MODE'],
    false,
    []
);

$arLandings = [];
while($arLanding = $dbLanding->Fetch()) {
    $tmpLanding = [
        'LANDING' => $arLanding['PROPERTY_LANDING_VALUE'],
        'MODE' => $arLanding['PROPERTY_MODE_VALUE'],
    ];

    if (empty($tmpLanding['MODE'])) {
        $tmpLanding['MODE'] = Landing::MODE_ELEMENT;
    }

    $arLandings[] = $tmpLanding;
}

$dbLanding = \CIBlockSection::GetList(
    [],
    ['IBLOCK_ID' => $blockIblockId],
    false,
    ['UF_LANDING', 'UF_MODE']
);
while ($arLanding = $dbLanding->Fetch()) {
    $tmpLanding = [
        'LANDING' => $arLanding['UF_LANDING'],
        'MODE' => $arLanding['UF_MODE'],
    ];

    if (empty($tmpLanding['MODE'])) {
        $tmpLanding['MODE'] = Landing::MODE_ELEMENT;
    }

    $arLandings[] = $tmpLanding;
}

foreach ($arLandings as $arLanding) {
    $landingId = $arLanding['LANDING'];
    $mode = $arLanding['MODE'];
    $data = ['filter' => ['ID' => $landingId]];

    if ($mode === Landing::MODE_SECTIONS || $mode === Landing::MODE_ROOT_SECTION) {
        $data['filter']['IBLOCK_TYPE_ID'] = 'ranx_landing';
        $data['filter']['CODE'] = 'ranx_landing_list_%';
        $dbResult = IblockTable::getList($data)->fetchAll();
    }
    else if ($mode === Landing::MODE_SECTION) {
        $dbResult = Helpers\Iblock::getSectionList($data);
    }
    else if ($mode === Landing::MODE_ELEMENT) {
        $dbResult = Helpers\Iblock::getElementList($data);
    }
    else {
        echo 'Unknown mode: ';
        rxPrintArray($arLanding);
    }


    if (!isset($dbResult) || count($dbResult) > 0) {
        continue;
    }

    Block::deleteByLanding($landingId, $mode);
}


// remove elements
$elementIblockId = Block::getElementsIblockId();
$dbElement = \CIBlockElement::GetList(
    [],
    ['IBLOCK_ID' => $elementIblockId],
    false,
    false,
    ['ID']
);
while ($arElement = $dbElement->Fetch()) {
    $elementId = $arElement['ID'];
    $cnt = \CIBlockElement::GetList(
        [],
        ['IBLOCK_ID' => $blockIblockId, 'PROPERTY_ELEMENTS' => $elementId],
        [],
        false,
        []
    );

    if ($cnt > 0) {
        continue;
    }

    \CIBlockElement::Delete($elementId);
}

$dbSection = \CIBlockSection::GetList(
    [],
    ['IBLOCK_ID' => $elementIblockId],
    false,
    ['ID'],
    false
);
while($arSection = $dbSection->Fetch()) {
    $sectionId = $arSection['ID'];
    $cnt = \CIBlockElement::GetList(
        [],
        ['IBLOCK_ID' => $blockIblockId, 'PROPERTY_TABS' => $sectionId],
        [],
        false,
        []
    );

    if ($cnt > 0) {
        continue;
    }

    \CIBlockSection::Delete($sectionId);
}
?>
