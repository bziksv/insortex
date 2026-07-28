<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    'NAME' => Loc::getMessage('RX_BLOCK_12_2_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'PREVIEW_TEXT',
        'NAME',
        'PREVIEW_PICTURE',
        'PROPERTY_POST',
        'PROPERTY_MARK',
        'PROPERTY_CHECK',
        '_POPUP',
    ],
    'POPUP_ELEMENTS_FIELDS' => [
        'DETAIL_TEXT',
        'PROPERTY_POPUP_BTN_TEXT',
    ],
    'ELEMENTS_FIELDS_MESS' => [
        'NAME' => Loc::getMessage('RX_BLOCK_12_2_ELEMENTS_FIELDS_MESS_NAME'),
        'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_12_2_ELEMENTS_FIELDS_MESS_PREVIEW_TEXT'),
        'PREVIEW_PICTURE' => Loc::getMessage('RX_BLOCK_12_2_ELEMENTS_FIELDS_MESS_PREVIEW_PICTURE'),
        'PROPERTY_CHECK' => Loc::getMessage('RX_BLOCK_12_2_ELEMENTS_FIELDS_MESS_PROPERTY_CHECK'),
        'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_12_2_ELEMENTS_FIELDS_MESS_DETAIL_TEXT'),
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_12_2_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_12_2_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_12_2_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_12_2_DEMO_ELEMENT_PREVIEW_TEXT'),
                'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_12_2_DEMO_ELEMENT_DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    'POST' => Loc::getMessage('RX_BLOCK_12_2_DEMO_ELEMENT_USER_POST'),
                    'POPUP_SHOW' => 'Y',
                    'POPUP_BTN_TEXT' => Loc::getMessage('RX_BLOCK_12_2_DEMO_ELEMENT_POPUP_BTN_TEXT'),
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_12_2_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_12_2_DEMO_ELEMENT_PREVIEW_TEXT'),
                'DETAIL_TEXT' => Loc::getMessage('RX_BLOCK_12_2_DEMO_ELEMENT_DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    'POST' => Loc::getMessage('RX_BLOCK_12_2_DEMO_ELEMENT_USER_POST'),
                    'POPUP_SHOW' => 'Y',
                    'POPUP_BTN_TEXT' => Loc::getMessage('RX_BLOCK_12_2_DEMO_ELEMENT_POPUP_BTN_TEXT'),
                ],
            ],
        ],
    ],
];
