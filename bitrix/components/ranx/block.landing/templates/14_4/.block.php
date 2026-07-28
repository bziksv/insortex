<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['CONTENT_CARDS'],
    '_INCLUDE' => ['CONTENT_PREVIEW_PICTURE'],
    'NAME' => Loc::getMessage('RX_BLOCK_14_4_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'TITLE_POSITION' => 'center',
    'TITLE_SHOW_BTN' => false,
    'TITLE_SHOW_DESC' => false,
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_14_4_DEMO_BLOCK_NAME'),
            'PREVIEW_PICTURE' => '1.png',
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_14_4_DEMO_BLOCK_SUBTITLE'),
                'CATTITLE' => Loc::getMessage('RX_BLOCK_14_4_DEMO_BLOCK_CATTITLE'),
                'BTN_TEXT' => Loc::getMessage('RX_BLOCK_14_4_DEMO_BLOCK_BTN_TEXT'),
                'BTN_LINK' => '#',
                'DESC' => [
                    'VALUE' => [
                        'TEXT' => Loc::getMessage('RX_BLOCK_14_4_DEMO_BLOCK_DESC'),
                        'TYPE' => 'html',
                    ],
                ],
            ],
        ],
    ],
];
