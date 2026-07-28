<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_INCLUDE' => ['ALIGN', 'PICTURE_ALIGN', 'HOVER_EFFECT', 'CARDS_BG_COLOR', 'SLIDER'],
    'NAME' => Loc::getMessage('RX_BLOCK_2_3_NAME'),
    'COLS' => [2, 3, 4],
    'COLS_DEFAULT' => 4,
    'ALIGN_DEFAULT' => 'center',
    'PICTURE_ALIGN_DEFAULT' => 'center',
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PREVIEW_TEXT',
        'PROPERTY_IMG',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_2_3_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_2_3_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_2_3_DEMO_ELEMENT_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_2_3_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_2_3_DEMO_ELEMENT_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_2_3_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_2_3_DEMO_ELEMENT_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_2_3_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_2_3_DEMO_ELEMENT_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_2_3_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                ],
            ],
        ],
    ],
];
