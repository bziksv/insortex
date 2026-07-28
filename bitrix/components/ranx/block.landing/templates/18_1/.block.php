<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['COLS', 'CONTENT_CARDS', 'CONTENT_BTN'],
    '_INCLUDE' => ['CONTENT_FORM'],
    'NAME' => Loc::getMessage('RX_BLOCK_18_1_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'TITLE_POSITION' => 'center',
    'TITLE_SHOW_BTN' => false,
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_18_1_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'CATTITLE' => Loc::getMessage('RX_BLOCK_18_1_DEMO_BLOCK_CATTITLE'),
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_18_1_DEMO_BLOCK_SUBTITLE'),
                'FORM_BTN_TEXT' => Loc::getMessage('RX_BLOCK_18_1_DEMO_BLOCK_BTN_TEXT'),
                'FORM' => 'ranx_landing_form_callback',
            ],
        ],
    ],
];
