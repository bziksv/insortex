<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['CONTENT_CARDS'],
    '_INCLUDE' => ['ALIGN', 'WIDE'],
    'NAME' => Loc::getMessage('RX_BLOCK_15_1_NAME'),
    'ALIGN_DEFAULT' => 'center',
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'TITLE_SHOW_BTN' => false,
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_15_1_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_15_1_DEMO_BLOCK_SUBTITLE'),
                'CATTITLE' => Loc::getMessage('RX_BLOCK_15_1_DEMO_BLOCK_CATTITLE'),
                'DESC' => [
                    'VALUE' => [
                        'TEXT' => Loc::getMessage('RX_BLOCK_15_1_DEMO_BLOCK_DESC'),
                        'TYPE' => 'html',
                    ],
                ],
            ],
        ],
    ],
];
