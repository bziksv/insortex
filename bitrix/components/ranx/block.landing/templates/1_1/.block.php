<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_INCLUDE' => ['TINT_COLOR', 'ALIGN', 'BLOCK_HEIGHT'],
    '_EXCLUDE' => ['COLS', 'BG_COLOR', 'TEXT_COLOR', 'CONTENT_TITLE', 'CONTENT_BTN', 'BG_PICTURE'],
    'NAME' => Loc::getMessage('RX_BLOCK_1_1_NAME'),
    'IS_UNDER_HEADER' => true,
    'ALIGN_DEFAULT' => 'left',
    'BLOCK_HEIGHT_DEFAULT' => 830,
    'SETTINGS' => [
        'AUTOPLAY' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_1_1_SETTINGS_AUTOPLAY_TITLE'),
            'TYPE' => 'checkbox',
            'DEFAULT' => false,
        ],
    ],
    'ELEMENTS_FIELDS' => [
        'PROPERTY_CATTITLE',
        'NAME',
        'PREVIEW_PICTURE',
        'DETAIL_PICTURE',
        'PROPERTY_LINK_TEXT',
        'PREVIEW_TEXT',
        'PROPERTY_BG_COLOR',
        'PROPERTY_TEXT_LIGHT',
        '_PRICE',
        '_BTN',
        '_BTN2',
    ],
    'FIELDS_MESS' => [
        'TITLE_TAG' => Loc::getMessage('RX_BLOCK_1_1_FIELDS_MESS_TITLE_TAG'),
    ],
    'ELEMENTS_FIELDS_MESS' => [
        'PREVIEW_PICTURE' => Loc::getMessage('RX_BLOCK_1_1_ELEMENTS_FIELDS_MESS_PREVIEW_PICTURE'),
        'DETAIL_PICTURE' => Loc::getMessage('RX_BLOCK_1_1_ELEMENTS_FIELDS_MESS_DETAIL_PICTURE'),
        'PROPERTY_LINK_TEXT' => Loc::getMessage('RX_BLOCK_1_1_ELEMENTS_FIELDS_MESS_LINK_TEXT'),
    ],
    'DEMO' => [
        'BLOCK' => [],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_1_1_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => 'img.png',
                'DETAIL_PICTURE' => 'bg.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_1_1_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
            ],
        ],
    ],
];
