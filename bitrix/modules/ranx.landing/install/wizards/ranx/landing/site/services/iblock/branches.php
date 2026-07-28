<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/**
 * Adding branches iblock
 */

$iblockShortCode = 'branches';
include_once 'iblock_add.php';

if (!empty($iblockId)) {
    $regionIblock = \Bitrix\Iblock\IblockTable::getList(['filter' => ['CODE' => 'ranx_landing_regions', 'IBLOCK_TYPE_ID' => 'ranx_landing']])->fetch();

    $regionProp = \Bitrix\Iblock\PropertyTable::getList(['filter' => ['XML_ID' => 'REGION', 'IBLOCK_ID' => $iblockId]])->fetch();
    if (!empty($regionProp['ID'])) {
        \Bitrix\Iblock\PropertyTable::update($regionProp['ID'], [
            'LINK_IBLOCK_ID' => $regionIblock['ID'],
        ]);
    }
}
