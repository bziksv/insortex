<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['COLS', 'BG_COLOR', 'TEXT_COLOR', 'CONTENT_TITLE', 'CONTENT_TITLE_TAG', 'CONTENT_BTN', 'BG_PICTURE'],
    'NAME' => Loc::getMessage('RX_BLOCK_16_3_NAME'),
    'INDENT_TOP_DEFAULT' => 0,
    'INDENT_BOT_DEFAULT' => 0,
    'MAX_CARD_COUNT' => 1,
    'ELEMENTS_FIELDS' => [
        'NAME',
        'PROPERTY_IMG',
        '_LINK',
    ],
    'DEMO' => [
        'BLOCK' => [],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_16_3_DEMO_ELEMENT_NAME'),
                'PROPERTY_VALUES' => [
                    'IMG' => '1.png',
                    'LINK' => 'https://ranx.ru/',
                    'LINK_TYPE' => 'external',
                ],
            ],
        ],
    ],
];
