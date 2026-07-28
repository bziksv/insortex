<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    'NAME' => Loc::getMessage('RX_BLOCK_8_1_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'ACTIVE_FROM',
        'PROPERTY_SCHEDULE',
        'PROPERTY_PERSON_NAME',
        'PROPERTY_LOCATION',
        '_PRICE',
        'PREVIEW_TEXT',
        '_BTN',
    ],
    'ELEMENTS_FIELDS_MESS' => [
        'ACTIVE_FROM' => Loc::getMessage('RX_BLOCK_8_1_ELEMENTS_FIELDS_MESS_ACTIVE_FROM'),
        'PROPERTY_SCHEDULE' => Loc::getMessage('RX_BLOCK_8_1_ELEMENTS_FIELDS_MESS_PROPERTY_SCHEDULE'),
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_8_1_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'CATTITLE' => Loc::getMessage('RX_BLOCK_8_1_DEMO_BLOCK_CATTITLE'),
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_8_1_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_NAME'),
                'ACTIVE_FROM' => '25.06.2020',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PROPERTY_VALUES' => [
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_PERSON_NAME'),
                    'SCHEDULE' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_SCHEDULE'),
                    'LOCATION' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_LOCATION'),
                    'PRICE' => '1500',
                    'OLD_PRICE' => '2000',
                    'BTN_SHOW' => 'Y',
                    'BTN_TYPE' => 'btn-transparent',
                    'BTN_SIZE' => false,
                    'BTN_TEXT' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_BTN_TEXT'),
                    'BTN_LINK' => 'ranx_landing_form_order',
                    'BTN_LINK_TYPE' => 'form',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_NAME'),
                'ACTIVE_FROM' => '25.06.2020',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PROPERTY_VALUES' => [
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_PERSON_NAME'),
                    'SCHEDULE' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_SCHEDULE'),
                    'LOCATION' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_LOCATION'),
                    'PRICE' => '1500',
                    'OLD_PRICE' => '2000',
                    'BTN_SHOW' => 'Y',
                    'BTN_TYPE' => 'btn-transparent',
                    'BTN_SIZE' => false,
                    'BTN_TEXT' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_BTN_TEXT'),
                    'BTN_LINK' => 'ranx_landing_form_order',
                    'BTN_LINK_TYPE' => 'form',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_NAME'),
                'ACTIVE_FROM' => '25.06.2020',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PROPERTY_VALUES' => [
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_PERSON_NAME'),
                    'SCHEDULE' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_SCHEDULE'),
                    'LOCATION' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_LOCATION'),
                    'PRICE' => '1500',
                    'OLD_PRICE' => '2000',
                    'BTN_SHOW' => 'Y',
                    'BTN_TYPE' => 'btn-transparent',
                    'BTN_SIZE' => false,
                    'BTN_TEXT' => Loc::getMessage('RX_BLOCK_8_1_DEMO_ELEMENT_BTN_TEXT'),
                    'BTN_LINK' => 'ranx_landing_form_order',
                    'BTN_LINK_TYPE' => 'form',
                ],
            ],
        ],
    ],
];
