<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['COLS', 'BG_COLOR', 'TEXT_COLOR', 'CONTENT_TITLE', 'CONTENT_BTN', 'BG_PICTURE'],
    'NAME' => Loc::getMessage('RX_BLOCK_16_1_NAME'),
    'INDENT_TOP_DEFAULT' => 0,
    'INDENT_BOT_DEFAULT' => 0,
    'MAX_CARD_COUNT' => 1,
    'ELEMENTS_FIELDS' => [
        'PROPERTY_CATTITLE',
        'NAME',
        'PREVIEW_PICTURE',
        'DETAIL_PICTURE',
        'PREVIEW_TEXT',
        'PROPERTY_BG_COLOR',
        'PROPERTY_TEXT_LIGHT',
        '_PRICE',
        '_BTN',
        '_BTN2',
    ],
    'ELEMENTS_FIELDS_MESS' => [
        'PREVIEW_PICTURE' => Loc::getMessage('RX_BLOCK_16_1_ELEMENTS_FIELDS_MESS_PREVIEW_PICTURE'),
        'DETAIL_PICTURE' => Loc::getMessage('RX_BLOCK_16_1_ELEMENTS_FIELDS_MESS_DETAIL_PICTURE'),
    ],
    'FIELDS_MESS' => [
        'TITLE_TAG' => Loc::getMessage('RX_BLOCK_16_1_FIELDS_MESS_TITLE_TAG'),
    ],
    'DEMO' => [
        'BLOCK' => [],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_16_1_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => 'img.png',
                'DETAIL_PICTURE' => 'bg.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_16_1_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'BTN_TEXT' => Loc::getMessage('RX_BLOCK_16_1_DEMO_ELEMENT_BTN_TEXT'),
                    'BTN_LINK' => '#',
                ],
            ],
        ],
    ],
];
