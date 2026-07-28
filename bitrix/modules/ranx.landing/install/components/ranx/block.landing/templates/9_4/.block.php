<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    'NAME' => Loc::getMessage('RX_BLOCK_9_4_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PROPERTY_POST',
        'PREVIEW_PICTURE',
        'PREVIEW_TEXT',
        'PROPERTY_EMAIL',
        'PROPERTY_PHONE',
        '_SOCIALS',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_9_4_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'CATTITLE' => Loc::getMessage('RX_BLOCK_9_4_DEMO_BLOCK_CATTITLE'),
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_9_4_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_9_4_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_9_4_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'POST' => Loc::getMessage('RX_BLOCK_9_4_DEMO_ELEMENT_POST'),
                    'EMAIL' => 'ab@example.com',
                    'PHONE' => '+7 (000) 000-00-00',
                    'VK' => 'https://vk.com/',
                    'INSTAGRAM' => 'https://instagram.com/',
                    'TELEGRAM' => 'https://t.me/',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_9_4_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_9_4_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'POST' => Loc::getMessage('RX_BLOCK_9_4_DEMO_ELEMENT_POST'),
                    'EMAIL' => 'ab@example.com',
                    'PHONE' => '+7 (000) 000-00-00',
                    'VK' => 'https://vk.com/',
                    'INSTAGRAM' => 'https://instagram.com/',
                    'TELEGRAM' => 'https://t.me/',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_9_4_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_9_4_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'POST' => Loc::getMessage('RX_BLOCK_9_4_DEMO_ELEMENT_POST'),
                    'EMAIL' => 'ab@example.com',
                    'PHONE' => '+7 (000) 000-00-00',
                    'VK' => 'https://vk.com/',
                    'INSTAGRAM' => 'https://instagram.com/',
                    'TELEGRAM' => 'https://t.me/',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_9_4_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_9_4_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
                'PROPERTY_VALUES' => [
                    'POST' => Loc::getMessage('RX_BLOCK_9_4_DEMO_ELEMENT_POST'),
                    'EMAIL' => 'ab@example.com',
                    'PHONE' => '+7 (000) 000-00-00',
                    'VK' => 'https://vk.com/',
                    'INSTAGRAM' => 'https://instagram.com/',
                    'TELEGRAM' => 'https://t.me/',
                ],
            ],
        ],
    ],
];
