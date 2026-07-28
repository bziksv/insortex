<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['CONTENT_TITLE', 'BG_PICTURE', 'CONTENT_CARDS', 'CONTENT_BTN'],
    '_INCLUDE' => ['CONTENT_AUTO', 'ALIGN', 'WIDE'],
    '_SECTION_TYPE' => [SectionTable::TYPE_NEWS],
    '_EXCLUDE_MODE' => [Landing::MODE_ROOT_SECTION, Landing::MODE_SECTIONS, Landing::MODE_SECTION],
    'NAME' => Loc::getMessage('RX_BLOCK_20_10_NAME'),
    'INDENT_TOP_DEFAULT' => 50,
    'INDENT_BOT_DEFAULT' => 100,
    'ALIGN_DEFAULT' => 'left',
    'TITLE_SHOW_BTN' => false,
    'INCLUDE_CATEGORIES' => true,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'ACTIVE_FROM',
        'DETAIL_PICTURE',
        'DETAIL_TEXT',
    ],
    'FIELDS_MESS' => [
        'TITLE_TAG' => Loc::getMessage('RX_BLOCK_20_10_FIELDS_MESS_TITLE_TAG'),
    ],
    'DEMO' => [
        'BLOCK' => [
            'PROPERTY_VALUES' => [
                'AUTO_BLOCK' => 'Y',
                'AUTO_TYPE' => 'detail',
                'AUTO_COUNT' => 0,
                'TITLE_TAG' => 'h1',
            ],
        ],
    ],
];
