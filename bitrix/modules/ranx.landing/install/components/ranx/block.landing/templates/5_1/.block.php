<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    'NAME' => Loc::getMessage('RX_BLOCK_5_1_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'PREVIEW_TEXT',
        'DETAIL_TEXT',
    ],
    'ELEMENTS_FIELDS_MESS' => [
        'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_1_1_ELEMENTS_FIELDS_MESS_PREVIEW_TEXT'),
        'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_1_1_ELEMENTS_FIELDS_MESS_DETAIL_TEXT'),
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_5_1_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_5_1_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_5_1_DEMO_ELEMENT_1_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_5_1_DEMO_ELEMENT_1_NAME'),
                'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_5_1_DEMO_ELEMENT_DETAIL_TEXT'),
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_5_1_DEMO_ELEMENT_2_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_5_1_DEMO_ELEMENT_2_NAME'),
                'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_5_1_DEMO_ELEMENT_DETAIL_TEXT'),
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_5_1_DEMO_ELEMENT_3_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_5_1_DEMO_ELEMENT_3_NAME'),
                'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_5_1_DEMO_ELEMENT_DETAIL_TEXT'),
            ],
        ],
    ],
];
