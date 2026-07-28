<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['CONTENT_TITLE', 'BG_PICTURE', 'CONTENT_CARDS', 'CONTENT_BTN', 'CONTENT_TITLE_TAG'],
    '_INCLUDE' => ['CONTENT_AUTO', 'ALIGN', 'WIDE'],
    '_SECTION_TYPE' => [SectionTable::TYPE_CATALOG],
    '_EXCLUDE_MODE' => [Landing::MODE_ROOT_SECTION, Landing::MODE_SECTIONS, Landing::MODE_SECTION],
    'NAME' => Loc::getMessage('RX_BLOCK_20_8_NAME'),
    'INDENT_TOP_DEFAULT' => 0,
    'INDENT_BOT_DEFAULT' => 100,
    'ALIGN_DEFAULT' => 'left',
    'TITLE_SHOW_BTN' => false,
    'ELEMENTS_FIELDS' => [
        'DETAIL_TEXT',
    ],
    'DEMO' => [
        'BLOCK' => [
            'PROPERTY_VALUES' => [
                'AUTO_BLOCK' => 'Y',
                'AUTO_TYPE' => 'detail',
                'AUTO_COUNT' => 0,
            ],
        ],
    ],
];
