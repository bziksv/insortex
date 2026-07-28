<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['CONTENT_TITLE', 'BG_PICTURE', 'CONTENT_CARDS', 'CONTENT_BTN'],
    '_INCLUDE' => ['CONTENT_AUTO'],
    '_SECTION_TYPE' => [SectionTable::TYPE_CATALOG],
    '_EXCLUDE_MODE' => [Landing::MODE_ROOT_SECTION, Landing::MODE_SECTIONS, Landing::MODE_SECTION],
    'NAME' => Loc::getMessage('RX_BLOCK_20_7_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'TITLE_SHOW_BTN' => false,
    'DETAIL_PAGE_PROPERTIES' => true,
    'DISPLAY_CHARS_COUNT' => 6,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PREVIEW_TEXT',
        'DETAIL_PICTURE',
        'PROPERTY_AVAILABLE',
        'PROPERTY_MARKERS',
        'PROPERTY_MARK',
        'PROPERTY_CML2_ARTICLE',
        '_PRICE',
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
