<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['BG_PICTURE', 'CONTENT_CARDS', 'CONTENT_BTN', 'TEXT_COLOR'],
    '_SECTION_TYPE' => [SectionTable::TYPE_SEARCH],
    '_EXCLUDE_MODE' => [Landing::MODE_ROOT_SECTION, Landing::MODE_SECTIONS, Landing::MODE_SECTION],
    'NAME' => Loc::getMessage('RX_BLOCK_20_12_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_20_12_DEMO_BLOCK_NAME'),
        ],
    ],
];
