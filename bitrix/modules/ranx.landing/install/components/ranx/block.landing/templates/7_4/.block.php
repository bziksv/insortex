<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    'NAME' => Loc::getMessage('RX_BLOCK_7_4_NAME'),
	'COLS' => [3, 4],
	'COLS_DEFAULT' => 3,
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PREVIEW_PICTURE',
        'PREVIEW_TEXT',
		'PROPERTY_CHECK',
        'PROPERTY_LINK_TEXT',
        '_LINK',
    ],
	'ELEMENTS_FIELDS_MESS' => [
		'PROPERTY_CHECK' => Loc::getMessage('RX_BLOCK_7_4_ELEMENTS_FIELDS_MESS_PROPERTY_CHECK'),
	],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_7_4_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_7_4_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_7_4_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_7_4_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'LINK_TEXT' => Loc::getMessage('RX_BLOCK_7_4_DEMO_ELEMENT_LINK_TEXT'),
                    'CHECK' => 'Y',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_7_4_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_7_4_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'LINK_TEXT' => Loc::getMessage('RX_BLOCK_7_4_DEMO_ELEMENT_LINK_TEXT'),
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_7_4_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_7_4_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'LINK_TEXT' => Loc::getMessage('RX_BLOCK_7_4_DEMO_ELEMENT_LINK_TEXT'),
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_7_4_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_7_4_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'LINK_TEXT' => Loc::getMessage('RX_BLOCK_7_4_DEMO_ELEMENT_LINK_TEXT'),
                ],
            ],
        ],
    ],
];
