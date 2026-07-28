<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    'NAME' => Loc::getMessage('RX_BLOCK_3_4_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'TITLE_POSITION' => 'center',
    'TITLE_SHOW_BTN' => false,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PREVIEW_TEXT',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_3_4_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_3_4_DEMO_BLOCK_SUBTITLE'),
                'CATTITLE' => Loc::getMessage('RX_BLOCK_3_4_DEMO_BLOCK_CATTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_3_4_DEMO_ELEMENT_1_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_3_4_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_3_4_DEMO_ELEMENT_2_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_3_4_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_3_4_DEMO_ELEMENT_3_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_3_4_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_3_4_DEMO_ELEMENT_4_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_3_4_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
            ],
        ],
    ],
];
