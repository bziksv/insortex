<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_INCLUDE' => ['CONTENT_TABS'],
    'NAME' => Loc::getMessage('RX_BLOCK_23_1_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PROPERTY_FILE',
        'PREVIEW_TEXT',
    ],
    'ELEMENTS_FIELDS_MESS' => [
        'PROPERTY_FILE' => Loc::getMessage('RX_BLOCK_23_1_ELEMENTS_FIELDS_MESS_PROPERTY_FILE'),
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_23_1_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'USE_TABS' => 'N',
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_23_1_DEMO_ELEMENT_NAME'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_23_1_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PROPERTY_VALUES' => [
                    'FILE' => '1.txt',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_23_1_DEMO_ELEMENT_2_NAME'),
                'PROPERTY_VALUES' => [
                    'FILE' => '1.jpg',
                ],
            ],
        ],
    ],
];
