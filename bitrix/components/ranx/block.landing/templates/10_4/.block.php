<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['CONTENT_CARDS'],
    '_INCLUDE' => ['CONTENT_GALLERY_CARDS'],
    'NAME' => Loc::getMessage('RX_BLOCK_10_4_NAME'),
    'COLS' => [3, 4],
    'COLS_DEFAULT' => 4,
    'USE_MASONRY_PLUGIN' => true,
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'DETAIL_PICTURE',
        'PREVIEW_TEXT',
        'PROPERTY_PICTURE_TITLE',
        'PROPERTY_PICTURE_ALT',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_10_4_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'CATTITLE' => Loc::getMessage('RX_BLOCK_10_4_DEMO_BLOCK_CATTITLE'),
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_10_4_DEMO_BLOCK_SUBTITLE'),
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_10_4_DEMO_ELEMENT_NAME'),
                'DETAIL_PICTURE' => 'stockvault-autumn-leaves186269.jpg',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_10_4_DEMO_ELEMENT_NAME'),
                'DETAIL_PICTURE' => 'stockvault-breakfast-coffee-photo284630.jpg',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_10_4_DEMO_ELEMENT_NAME'),
                'DETAIL_PICTURE' => 'stockvault-businessman-overlooking-manhattan-success-and-achievement272566.jpg',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_10_4_DEMO_ELEMENT_NAME'),
                'DETAIL_PICTURE' => 'stockvault-flight-schedules-departures-travel-airport272551.jpg',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_10_4_DEMO_ELEMENT_NAME'),
                'DETAIL_PICTURE' => 'stockvault-glass-building-photo284523.jpg',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_10_4_DEMO_ELEMENT_NAME'),
                'DETAIL_PICTURE' => 'stockvault-the-path-through-the-woods184902.jpg',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_10_4_DEMO_ELEMENT_NAME'),
                'DETAIL_PICTURE' => 'stockvault-online-shopping-man-with-smartphone-and-virtual-shopping-cart280170.jpg',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_10_4_DEMO_ELEMENT_NAME'),
                'DETAIL_PICTURE' => 'stockvault-reaching-the-summit-teamwork-effort-success269064.jpg',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_10_4_DEMO_ELEMENT_NAME'),
                'DETAIL_PICTURE' => 'stockvault-sharing-sharing-on-social-media-modern-communications269936.jpg',
            ],
        ],
    ],
];
