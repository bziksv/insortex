<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_INCLUDE' => ['ALIGN', 'CONTENT_AUTO'],
    'NAME' => Loc::getMessage('RX_BLOCK_7_1_NAME'),
    'COLS' => [2, 3, 4],
    'COLS_DEFAULT' => 2,
    'ALIGN_DEFAULT' => 'center',
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'DETAIL_PICTURE',
        'PREVIEW_TEXT',
        '_LINK',
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_7_1_DEMO_BLOCK_NAME'),
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_7_1_DEMO_ELEMENT_1_NAME'),
                'DETAIL_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_7_1_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_7_1_DEMO_ELEMENT_2_NAME'),
                'DETAIL_PICTURE' => '1.png',
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_7_1_DEMO_ELEMENT_PREVIEW_TEXT'),
                'PREVIEW_TEXT_TYPE' => 'html',
            ],
        ],
    ],
];
