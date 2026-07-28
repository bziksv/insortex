<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    'NAME' => Loc::getMessage('RX_BLOCK_2_4_NAME'),
    'COLS' => [2, 3, 4],
    'COLS_DEFAULT' => 4,
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PREVIEW_TEXT',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_2_4_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_2_4_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_2_4_DEMO_ELEMENT_1_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_2_4_DEMO_ELEMENT_1_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_2_4_DEMO_ELEMENT_2_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_2_4_DEMO_ELEMENT_2_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_2_4_DEMO_ELEMENT_3_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_2_4_DEMO_ELEMENT_3_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_2_4_DEMO_ELEMENT_4_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_2_4_DEMO_ELEMENT_4_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
            ],
        ],
    ],
];
