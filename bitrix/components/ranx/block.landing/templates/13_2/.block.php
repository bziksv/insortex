<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    'NAME' => Loc::getMessage('RX_BLOCK_13_2_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'SETTINGS' => [
        'AUTOPLAY' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_13_2_SETTINGS_AUTOPLAY_TITLE'),
            'TYPE' => 'checkbox',
            'DEFAULT' => false,
        ],
    ],
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PROPERTY_IMG',
        '_LINK',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_13_2_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_13_2_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => 'Brand',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                ],
            ],
            [
                'NAME' => 'Brand',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                ],
            ],
        ],
    ],
];
