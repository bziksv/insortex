<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['BG_PICTURE', 'CONTENT_BTN', 'CONTENT_CARDS'],
    '_SECTION_TYPE' => [SectionTable::TYPE_NEWS],
    '_EXCLUDE_MODE' => [Landing::MODE_ELEMENT],
    'NAME' => Loc::getMessage('RX_BLOCK_20_11_NAME'),
    'INDENT_TOP_DEFAULT' => 0,
    'INDENT_BOT_DEFAULT' => 100,
    'TITLE_SHOW_BTN' => false,
    'INCLUDE_SUBSECTIONS' => true,
    'INCLUDE_CATEGORIES' => true,
    'NEWS_LIST' => true,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PREVIEW_PICTURE',
        'PREVIEW_TEXT',
        'ACTIVE_FROM',
        '_LINK',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_20_11_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'HIDE_TITLE' => 'Y',
                'AUTO_BLOCK' => 'Y',
                'AUTO_TYPE' => 'element',
                'AUTO_COUNT' => 20,
            ],
        ],
    ],
];
