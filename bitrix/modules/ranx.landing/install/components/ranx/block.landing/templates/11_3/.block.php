<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['CONTENT_BTN'],
    'NAME' => Loc::getMessage('RX_BLOCK_11_3_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'USE_MAPS' => true,
    'CAN_SHOW_BRANCHES' => true,
    'CAN_SHOW_REGIONS' => true,
    'ELEMENTS_FIELDS' => [
        'PROPERTY_CATTITLE',
        'NAME',
        'PROPERTY_PHONES',
        'PROPERTY_SCHEDULE',
        'PROPERTY_MAP',
        'PROPERTY_METRO',
        'PROPERTY_EMAIL',
        'PROPERTY_LOCATION',
        'PROPERTY_ADDRESS', // for regions
        '_POPUP',
        '_SOCIALS',
        '_BTN',
    ],
    'POPUP_ELEMENTS_FIELDS' => [
        'PREVIEW_PICTURE',
        'PREVIEW_TEXT',
    ],
    'ELEMENTS_FIELDS_MESS' => [
        'PROPERTY_LOCATION' => Loc::getMessage('RX_BLOCK_11_3_ELEMENTS_FIELDS_MESS_PROPERTY_LOCATION'),
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_11_3_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_11_3_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_11_3_DEMO_ELEMENT_1_NAME'),
                'PROPERTY_VALUES' => [
                    'CATTITLE' => Loc::getMessage('RX_BLOCK_11_3_DEMO_ELEMENT_CATTITLE'),
                    'METRO' => Loc::getMessage('RX_BLOCK_11_3_DEMO_ELEMENT_1_METRO'),
                    'SCHEDULE' => '09:00 — 20:00',
                    'PHONES' => [
                        '+7 (000) 000-00-00',
                    ],
                    'EMAIL' => 'info@example.com',
                    'MAP' => '55.159369, 61.387753',
                    'VK' => 'https://vk.com/ranx_ru',
                    'INSTAGRAM' => '',
                    'TELEGRAM' => 'https://t.me/ranx_bot',
                    'BTN_SHOW' => 'Y',
                    'BTN_TYPE' => 'btn-transparent',
                    'BTN_SIZE' => 'btn-lg',
                    'BTN_TEXT' => Loc::getMessage('RX_BLOCK_11_3_DEMO_ELEMENT_BTN_TEXT'),
                    'BTN_LINK' => 'ranx_landing_form_order',
                    'BTN_LINK_TYPE' => 'form',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_11_3_DEMO_ELEMENT_2_NAME'),
                'PROPERTY_VALUES' => [
                    'CATTITLE' => Loc::getMessage('RX_BLOCK_11_3_DEMO_ELEMENT_CATTITLE'),
                    'METRO' => Loc::getMessage('RX_BLOCK_11_3_DEMO_ELEMENT_1_METRO'),
                    'SCHEDULE' => '09:00 — 20:00',
                    'PHONES' => [
                        '+7 (000) 000-00-00',
                    ],
                    'EMAIL' => 'info@example.com',
                    'MAP' => '55.168017, 61.412612',
                    'VK' => 'https://vk.com/ranx_ru',
                    'INSTAGRAM' => '',
                    'TELEGRAM' => 'https://t.me/ranx_bot',
                    'BTN_SHOW' => 'Y',
                    'BTN_TYPE' => 'btn-transparent',
                    'BTN_SIZE' => 'btn-lg',
                    'BTN_TEXT' => Loc::getMessage('RX_BLOCK_11_3_DEMO_ELEMENT_BTN_TEXT'),
                    'BTN_LINK' => 'ranx_landing_form_order',
                    'BTN_LINK_TYPE' => 'form',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_11_3_DEMO_ELEMENT_3_NAME'),
                'PROPERTY_VALUES' => [
                    'CATTITLE' => Loc::getMessage('RX_BLOCK_11_3_DEMO_ELEMENT_CATTITLE'),
                    'METRO' => Loc::getMessage('RX_BLOCK_11_3_DEMO_ELEMENT_1_METRO'),
                    'SCHEDULE' => '09:00 — 20:00',
                    'PHONES' => [
                        '+7 (000) 000-00-00',
                    ],
                    'EMAIL' => 'info@example.com',
                    'MAP' => '55.170314, 61.446030',
                    'VK' => 'https://vk.com/ranx_ru',
                    'INSTAGRAM' => '',
                    'TELEGRAM' => 'https://t.me/ranx_bot',
                    'BTN_SHOW' => 'Y',
                    'BTN_TYPE' => 'btn-transparent',
                    'BTN_SIZE' => 'btn-lg',
                    'BTN_TEXT' => Loc::getMessage('RX_BLOCK_11_3_DEMO_ELEMENT_BTN_TEXT'),
                    'BTN_LINK' => 'ranx_landing_form_order',
                    'BTN_LINK_TYPE' => 'form',
                ],
            ],
        ],
    ],
];
