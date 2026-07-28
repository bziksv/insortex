<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['BG_PICTURE', 'CONTENT_BTN', 'CONTENT_CARDS'],
    '_SECTION_TYPE' => [SectionTable::TYPE_CATALOG],
    '_EXCLUDE_MODE' => [Landing::MODE_ELEMENT],
    'NAME' => Loc::getMessage('RX_BLOCK_20_5_NAME'),
    'INDENT_TOP_DEFAULT' => 0,
    'INDENT_BOT_DEFAULT' => 100,
    'TITLE_SHOW_BTN' => false,
    'INCLUDE_SUBSECTIONS' => true,
    'USE_FILTER' => true,
    'PRODUCT_LIST' => true,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PREVIEW_PICTURE',
        'PROPERTY_MORE_PHOTO',
        'PROPERTY_AVAILABLE',
        'PROPERTY_MARKERS',
        'PROPERTY_MARK',
        '_PRICE',
        '_LINK',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_20_5_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'HIDE_TITLE' => 'Y',
                'AUTO_BLOCK' => 'Y',
                'AUTO_TYPE' => 'element',
                'AUTO_COUNT' => 8,
            ],
        ],
    ],
];
