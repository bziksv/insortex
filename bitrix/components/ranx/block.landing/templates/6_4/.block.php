<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    'NAME' => Loc::getMessage('RX_BLOCK_6_4_NAME'),
    'COLS' => [3, 4],
    'COLS_DEFAULT' => 4,
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PREVIEW_PICTURE',
        'ACTIVE_FROM',
        'ACTIVE_TO',
        'PROPERTY_DISCOUNT',
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
            'NAME' => Loc::getMessage('RX_BLOCK_6_4_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_6_4_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_6_4_DEMO_ELEMENT_NAME'),
                'ACTIVE_FROM' => '20.02.2020',
                'ACTIVE_TO' => '10.03.2020',
                'PREVIEW_PICTURE' => '1.png',
                'DETAIL_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_6_4_DEMO_PREVIEW_TEXT'),
                'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_6_4_DEMO_DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    'DISCOUNT' => '-30%',
                    'POPUP_SHOW' => 'Y',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_6_4_DEMO_ELEMENT_NAME'),
                'ACTIVE_FROM' => '20.02.2020',
                'ACTIVE_TO' => '10.03.2020',
                'PREVIEW_PICTURE' => '1.png',
                'DETAIL_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_6_4_DEMO_PREVIEW_TEXT'),
                'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_6_4_DEMO_DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    'DISCOUNT' => '-30%',
                    'POPUP_SHOW' => 'Y',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_6_4_DEMO_ELEMENT_NAME'),
                'ACTIVE_FROM' => '20.02.2020',
                'ACTIVE_TO' => '10.03.2020',
                'PREVIEW_PICTURE' => '1.png',
                'DETAIL_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_6_4_DEMO_PREVIEW_TEXT'),
                'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_6_4_DEMO_DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    'DISCOUNT' => '-30%',
                    'POPUP_SHOW' => 'Y',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_6_4_DEMO_ELEMENT_NAME'),
                'ACTIVE_FROM' => '20.02.2020',
                'ACTIVE_TO' => '10.03.2020',
                'PREVIEW_PICTURE' => '1.png',
                'DETAIL_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_6_4_DEMO_PREVIEW_TEXT'),
                'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_6_4_DEMO_DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    'DISCOUNT' => '-30%',
                    'POPUP_SHOW' => 'Y',
                ],
            ],
        ],
    ],
];
