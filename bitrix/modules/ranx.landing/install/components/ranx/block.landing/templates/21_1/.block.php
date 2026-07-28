<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['CONTENT_CARDS'],
    '_INCLUDE' => ['CONTENT_VIDEO', 'CONTENT_PREVIEW_PICTURE'],
    'NAME' => Loc::getMessage('RX_BLOCK_21_1_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [],
    'FIELDS_MESS' => [
        'PREVIEW_PICTURE' => Loc::getMessage('RX_BLOCK_21_1_FIELDS_MESS_PREVIEW_PICTURE'),
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_21_1_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_21_1_DEMO_BLOCK_SUBTITLE'),
                'VIDEO_LINK' => 'https://www.youtube.com/watch?v=deIpbUxVC2E',
                'VIDEO_NOTE' => Loc::getMessage('RX_BLOCK_21_1_DEMO_BLOCK_VIDEO_NOTE'),
            ],
        ],
        'ELEMENTS' => [],
    ],
];
