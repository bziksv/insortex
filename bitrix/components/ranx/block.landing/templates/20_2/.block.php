<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Landing;
use Bitrix\Main\Localization\Loc;

return [
    '_INCLUDE' => ['CONTENT_AUTO'],
    'NAME' => Loc::getMessage('RX_BLOCK_20_2_NAME'),
    'INDENT_TOP_DEFAULT' => 0,
    'INDENT_BOT_DEFAULT' => 100,
    'TITLE_SHOW_BTN' => false,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PREVIEW_PICTURE',
        '_LINK',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_20_2_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'AUTO_BLOCK' => 'Y',
                'AUTO_TYPE' => 'element',
                'AUTO_COUNT' => 4,
            ],
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_20_2_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_20_2_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_20_2_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_20_2_DEMO_ELEMENT_NAME'),
                'PREVIEW_PICTURE' => '1.png',
            ],
        ],
    ],
];
