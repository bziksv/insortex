<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['CONTENT_BTN', 'BG_PICTURE', 'BG_COLOR', 'TEXT_COLOR'],
    'NAME' => Loc::getMessage('RX_BLOCK_11_2_NAME'),
    'INDENT_TOP_DEFAULT' => 0,
    'INDENT_BOT_DEFAULT' => 0,
    'USE_MAPS' => true,
    'CAN_SHOW_BRANCHES' => true,
    'CAN_SHOW_REGIONS' => true,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PROPERTY_PHONES',
        'PROPERTY_SCHEDULE',
        'PROPERTY_MAP',
        'PROPERTY_METRO',
        'PROPERTY_EMAIL',
        'PROPERTY_LOCATION',
        'PROPERTY_ADDRESS', // for regions
        '_POPUP',
        '_BTN',
    ],
    'POPUP_ELEMENTS_FIELDS' => [
        'PREVIEW_PICTURE',
        'PREVIEW_TEXT',
    ],
    'ELEMENTS_FIELDS_MESS' => [
        'PROPERTY_LOCATION' => Loc::getMessage('RX_BLOCK_11_2_ELEMENTS_FIELDS_MESS_PROPERTY_LOCATION'),
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_11_2_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_11_2_DEMO_BLOCK_SUBTITLE'),
                'HIDE_TITLE' => 'Y',
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_11_2_DEMO_ELEMENT_1_NAME'),
                'PROPERTY_VALUES' => [
                    'SCHEDULE' => '09:00 — 20:00',
                    'PHONES' => [
                        '+7 (000) 000-00-00',
                        '+7 (000) 000-00-01',
                    ],
                    'EMAIL' => 'info@example.com',
                    'MAP' => '55.159369, 61.387753',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_11_2_DEMO_ELEMENT_2_NAME'),
                'PROPERTY_VALUES' => [
                    'SCHEDULE' => '09:00 — 20:00',
                    'PHONES' => [
                        '+7 (000) 000-00-00',
                        '+7 (000) 000-00-01',
                    ],
                    'EMAIL' => 'info@example.com',
                    'MAP' => '55.168017, 61.412612',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_11_2_DEMO_ELEMENT_3_NAME'),
                'PROPERTY_VALUES' => [
                    'SCHEDULE' => '09:00 — 20:00',
                    'PHONES' => [
                        '+7 (000) 000-00-00',
                        '+7 (000) 000-00-01',
                    ],
                    'EMAIL' => 'info@example.com',
                    'MAP' => '55.170314, 61.446030',
                ],
            ],
        ],
    ],
];
