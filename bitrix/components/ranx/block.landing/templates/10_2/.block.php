<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['CONTENT_CARDS'],
    '_INCLUDE' => ['CONTENT_GALLERY_CARDS'],
    'NAME' => Loc::getMessage('RX_BLOCK_10_2_NAME'),
    'COLS' => [3, 4],
    'COLS_DEFAULT' => 3,
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'DETAIL_PICTURE',
        'PREVIEW_TEXT',
        'PROPERTY_PICTURE_TITLE',
        'PROPERTY_PICTURE_ALT',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_10_2_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'CATTITLE' => Loc::getMessage('RX_BLOCK_10_2_DEMO_BLOCK_CATTITLE'),
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_10_2_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_10_2_DEMO_ELEMENT_NAME'),
                'DETAIL_PICTURE' => '1.png',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_10_2_DEMO_ELEMENT_NAME'),
                'DETAIL_PICTURE' => '1.png',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_10_2_DEMO_ELEMENT_NAME'),
                'DETAIL_PICTURE' => '1.png',
            ],
        ],
    ],
];
