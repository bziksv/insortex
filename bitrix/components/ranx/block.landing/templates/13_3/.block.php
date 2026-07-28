<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    'NAME' => Loc::getMessage('RX_BLOCK_13_3_NAME'),
    'COLS' => [4, 6],
    'COLS_DEFAULT' => 4,
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PROPERTY_IMG',
        'PROPERTY_BG_COLOR',
        'PREVIEW_TEXT',
        '_LINK',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_13_3_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_13_3_DEMO_BLOCK_SUBTITLE'),
                'CATTITLE' => Loc::getMessage('RX_BLOCK_13_3_DEMO_BLOCK_CATTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => 'Brand',
                'PREVIEW_TEXT' => 'Preview',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                    'BG_COLOR' => '#f5f6f7',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PREVIEW_TEXT' => 'Preview',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                    'BG_COLOR' => '#f5f6f7',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PREVIEW_TEXT' => 'Preview',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                    'BG_COLOR' => '#f5f6f7',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PREVIEW_TEXT' => 'Preview',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                    'BG_COLOR' => '#f5f6f7',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PREVIEW_TEXT' => 'Preview',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                    'BG_COLOR' => '#f5f6f7',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PREVIEW_TEXT' => 'Preview',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                    'BG_COLOR' => '#f5f6f7',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PREVIEW_TEXT' => 'Preview',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                    'BG_COLOR' => '#f5f6f7',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PREVIEW_TEXT' => 'Preview',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                    'BG_COLOR' => '#f5f6f7',
                ],
            ],
        ],
    ],
];
