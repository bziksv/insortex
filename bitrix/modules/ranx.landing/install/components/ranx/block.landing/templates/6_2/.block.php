<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_INCLUDE' => ['CONTENT_IMPORT'],
    'NAME' => Loc::getMessage('RX_BLOCK_6_2_NAME'),
    'COLS' => [3, 4],
    'COLS_DEFAULT' => 3,
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'IMPORT_DATA_TYPE' => 'NEWS',
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PREVIEW_PICTURE',
        'ACTIVE_FROM',
        '_POPUP',
        '_LINK',
    ],
    'POPUP_ELEMENTS_FIELDS' => [
        'DETAIL_PICTURE',
        'PREVIEW_TEXT',
        'DETAIL_TEXT',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_6_2_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_6_2_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_6_2_DEMO_ELEMENT_NAME'),
                'ACTIVE_FROM' => '20.02.2020',
                'PREVIEW_PICTURE' => '1.png',
                'DETAIL_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_6_2_DEMO_PREVIEW_TEXT'),
                'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_6_2_DEMO_DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    'CAT' => Loc::getMessage('RX_BLOCK_6_2_DEMO_ELEMENT_CAT'),
                    'POPUP_SHOW' => 'Y',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_6_2_DEMO_ELEMENT_NAME'),
                'ACTIVE_FROM' => '20.02.2020',
                'PREVIEW_PICTURE' => '1.png',
                'DETAIL_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_6_2_DEMO_PREVIEW_TEXT'),
                'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_6_2_DEMO_DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    'CAT' => Loc::getMessage('RX_BLOCK_6_2_DEMO_ELEMENT_CAT'),
                    'POPUP_SHOW' => 'Y',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_6_2_DEMO_ELEMENT_NAME'),
                'ACTIVE_FROM' => '20.02.2020',
                'PREVIEW_PICTURE' => '1.png',
                'DETAIL_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_6_2_DEMO_PREVIEW_TEXT'),
                'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_6_2_DEMO_DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    'CAT' => Loc::getMessage('RX_BLOCK_6_2_DEMO_ELEMENT_CAT'),
                    'POPUP_SHOW' => 'Y',
                ],
            ],
        ],
    ],
];
