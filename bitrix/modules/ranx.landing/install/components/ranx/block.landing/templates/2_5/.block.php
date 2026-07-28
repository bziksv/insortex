<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    'NAME' => Loc::getMessage('RX_BLOCK_2_5_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'TITLE_POSITION' => 'left',
    'TITLE_STICKY' => true,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PREVIEW_TEXT',
        'PROPERTY_IMG',
        'PROPERTY_FA_CLASS',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_2_5_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_2_5_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_2_5_DEMO_ELEMENT_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_2_5_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.svg',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_2_5_DEMO_ELEMENT_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_2_5_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.svg',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_2_5_DEMO_ELEMENT_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_2_5_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'IMG' => '1.svg',
                ],
            ],
        ],
    ],
];
