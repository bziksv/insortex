<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['CONTENT_TITLE', 'BG_PICTURE', 'CONTENT_TITLE_TAG', 'CONTENT_BTN', 'TEXT_COLOR'],
    '_INCLUDE' => ['CONTENT_AUTO'],
    '_SECTION_TYPE' => [SectionTable::TYPE_CATALOG],
    '_EXCLUDE_MODE' => [Landing::MODE_ROOT_SECTION, Landing::MODE_ELEMENT],
    'NAME' => Loc::getMessage('RX_BLOCK_20_6_NAME'),
    'INDENT_TOP_DEFAULT' => 0,
    'INDENT_BOT_DEFAULT' => 100,
    'TITLE_SHOW_BTN' => false,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PREVIEW_PICTURE',
        '_LINK',
    ],
    'DEMO' => [
        'BLOCK' => [
            'PROPERTY_VALUES' => [
                'AUTO_BLOCK' => 'Y',
                'AUTO_TYPE' => 'section',
                'AUTO_COUNT' => 0,
            ],
        ],
    ],
];
