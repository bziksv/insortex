<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/**
 * Adding elements iblock
 */

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$propertyHints = [
    'FA_CLASS' => Loc::getMessage('RX_ELEMENT_PROPERTY_HINT_FA_CLASS'),
];
$iblockShortCode = 'elements';
include_once 'iblock_add.php';
