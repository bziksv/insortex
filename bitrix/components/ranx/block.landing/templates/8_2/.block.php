<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_INCLUDE' => ['CONTENT_TABS'],
    'NAME' => Loc::getMessage('RX_BLOCK_8_2_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PROPERTY_SCHEDULE',
        'PROPERTY_LOCATION',
        'PROPERTY_PERSON_NAME',
        'PROPERTY_POST',
        'PREVIEW_PICTURE',
        'PREVIEW_TEXT',
    ],
    'ELEMENTS_FIELDS_MESS' => [
        'PROPERTY_SCHEDULE' => Loc::getMessage('RX_BLOCK_8_2_ELEMENTS_FIELDS_MESS_PROPERTY_SCHEDULE'),
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'CATTITLE' => Loc::getMessage('RX_BLOCK_8_2_DEMO_BLOCK_CATTITLE'),
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_8_2_DEMO_BLOCK_SUBTITLE'),
                'USE_TABS' => 'Y',
            ],
        ],
        'TABS' => [
            Loc::getMessage('RX_BLOCK_8_2_DEMO_TAB_1'),
            Loc::getMessage('RX_BLOCK_8_2_DEMO_TAB_2'),
            Loc::getMessage('RX_BLOCK_8_2_DEMO_TAB_3'),
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_PREVIEW_TEXT'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'SCHEDULE' => '10:30 &mdash; 11:30',
                    'LOCATION' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_LOCATION'),
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_PERSON_NAME'),
                    'POST' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_POST'),
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_PREVIEW_TEXT'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'SCHEDULE' => '11:00 &mdash; 12:00',
                    'LOCATION' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_LOCATION'),
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_PERSON_NAME'),
                    'POST' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_POST'),
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_PREVIEW_TEXT'),
                'TAB' => 2,
                'PROPERTY_VALUES' => [
                    'SCHEDULE' => '12:30 &mdash; 13:30',
                    'LOCATION' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_LOCATION'),
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_PERSON_NAME'),
                    'POST' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_POST'),
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_PREVIEW_TEXT'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'SCHEDULE' => '11:30 &mdash; 12:30',
                    'LOCATION' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_LOCATION'),
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_PERSON_NAME'),
                    'POST' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_POST'),
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_PREVIEW_TEXT'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'SCHEDULE' => '12:15 &mdash; 13:15',
                    'LOCATION' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_LOCATION'),
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_PERSON_NAME'),
                    'POST' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_POST'),
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_PREVIEW_TEXT'),
                'TAB' => 2,
                'PROPERTY_VALUES' => [
                    'SCHEDULE' => '14:00 &mdash; 15:00',
                    'LOCATION' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_LOCATION'),
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_PERSON_NAME'),
                    'POST' => Loc::getMessage('RX_BLOCK_8_2_DEMO_ELEMENT_POST'),
                ],
            ],
        ],
    ],
];
