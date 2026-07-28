<?php

/**
 * Module blocks config
 * @copyright 2020 RANX
 */

use Ranx\Landing\Config;
use Ranx\Landing\Landing;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

Config::$blocks = [
    // main config
    'CONFIG' => [
        // default settings for all types of blocks
        // you can include/exclude settings for each block type
        '_DEFAULT' => [
            'COLS',
            'INDENTS',
            'LINE_BOT',
            'BG_COLOR',
            'TEXT_COLOR',
            'CONTENT_TITLE',
            'CONTENT_TITLE_TAG',
            'CONTENT_CARDS',
            'CONTENT_BTN',
            'BG_PICTURE',
            'PARALLAX_EFFECT',
        ],
        'INDENTS' => [0, 50, 100, 150],
        'BG_COLORS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_PARAMS_CONFIG_COLOR_WHITE'),
                'VALUE' => '#ffffff',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_PARAMS_CONFIG_COLOR_GREY'),
                'VALUE' => '#f8f8f8',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_PARAMS_CONFIG_COLOR_THEME'),
                'VALUE' => 'theme',
            ],
        ],
        'CARDS_BG_COLORS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_PARAMS_CONFIG_COLOR_WHITE'),
                'VALUE' => '#ffffff',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_PARAMS_CONFIG_COLOR_THEME'),
                'VALUE' => 'theme',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_PARAMS_CONFIG_COLOR_TRANSPARENT'),
                'VALUE' => 'transparent',
            ],
        ],
        'BG_COLOR_DEFAULT' => '#ffffff',
        'TINT_COLORS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_PARAMS_CONFIG_COLOR_WHITE'),
                'VALUE' => '#ffffff',
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_PARAMS_CONFIG_COLOR_BLACK'),
                'VALUE' => '#000000',
            ],
        ],
        'SOCIALS' => [
            'VK',
            'FACEBOOK',
            'INSTAGRAM',
            'TELEGRAM',
            'TWITTER',
            'OK',
            'WHATSAPP',
            'VIBER',
            'SKYPE',
        ],
    ],
    // config for each block
    'LIST' => [],
    'GROUPS' => [
        /*'AUTO' => [
            '_EXCLUDE_MODE' => [Landing::MODE_ELEMENT],
            'NOTE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_AUTO_NOTE'),
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_AUTO_TITLE'),
            'BLOCKS' => ['20_1', '20_2', '20_3', '20_4'],
        ],*/
        'NEWS' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_NEWS_TITLE'),
            'BLOCKS' => ['6_1', '6_2', '6_3', '6_4', '6_5', '20_9', '20_10', '20_11'],
        ],
        'BANNERS' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_BANNERS_TITLE'),
            'BLOCKS' => ['1_1', '1_2', '1_3', '16_1', '16_2', '16_3', '7_1', '7_2', '7_3', '7_4'],
        ],
        'BRANDS' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_BRANDS_TITLE'),
            'BLOCKS' => ['13_1', '13_2', '13_3'],
        ],
        'VIDEO' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_VIDEO_TITLE'),
            'BLOCKS' => ['21_1', '21_2'],
        ],
        'FAQ' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_FAQ_TITLE'),
            'BLOCKS' => ['5_1'],
        ],
        'GALLERY' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_GALLERY_TITLE'),
            'BLOCKS' => ['10_1', '10_2', '10_3', '10_4'],
        ],
        'DOCS' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_DOCS_TITLE'),
            'BLOCKS' => ['23_1'],
        ],
        'INSTAGRAM' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_INSTAGRAM_TITLE'),
            'BLOCKS' => ['22_1', '22_2'],
        ],
        'CATALOG' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_CATALOG_TITLE'),
            'BLOCKS' => ['20_5', '20_6', '20_7', '20_8', '20_13'],
        ],
        'CONTACTS' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_CONTACTS_TITLE'),
            'BLOCKS' => ['11_1', '11_2', '11_3', '18_2'],
        ],
        'REVIEWS' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_REVIEWS_TITLE'),
            'BLOCKS' => ['12_1', '12_2', '12_3', '12_4'],
        ],
        'SCHEDULE' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_SCHEDULE'),
            'BLOCKS' => ['8_1', '8_2', '8_3'],
        ],
        'SERVICE' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_SERVICE'),
            'BLOCKS' => ['20_12'],
        ],
        'STAFF' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_STAFF_TITLE'),
            'BLOCKS' => ['9_1', '9_2', '9_3', '9_4', '9_5'],
        ],
        'LIST' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_LIST_TITLE'),
            'BLOCKS' => ['20_1', '20_3', '7_1', '6_1'],
        ],
        'TARIFFS' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_TARIFFS_TITLE'),
            'BLOCKS' => ['17_1', '17_2', '17_3', '17_4', '17_5', '17_6'],
        ],
        'TEXT' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_TEXT_TITLE'),
            'BLOCKS' => ['15_1', '14_1', '14_2', '14_3', '14_4', '14_5'],
        ],
        'TEASERS' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_TEASERS_TITLE'),
            'BLOCKS' => ['2_1', '2_2', '2_3', '2_4', '2_5', '2_6', '2_7'],
        ],
        'PRODUCTS' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_PRODUCTS_TITLE'),
            'BLOCKS' => ['19_1'],
        ],
        'SERVICES' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_SERVICES_TITLE'),
            'BLOCKS' => ['4_1', '4_2', '4_3', '4_4', '4_5', '4_6'],
        ],
        'FORMS' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_FORMS_TITLE'),
            'BLOCKS' => ['18_1', '18_2', '18_3'],
        ],
        'STAGES' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_STAGES_TITLE'),
            'BLOCKS' => ['3_1', '3_2', '3_3', '3_4', '3_5', '3_6'],
        ],
        'HTML' => [
            'TITLE' => Loc::getMessage('RX_BLOCK_PARAMS_GROUPS_HTML_TITLE'),
            'BLOCKS' => ['15_2'],
        ],
        'CUSTOM' => [
            'TITLE' => 'Custom',
            'BLOCKS' => [],
        ],
    ],
];

// collect config from all blocks
\Ranx\Landing\Block::initList();

/**
 * @link https://help.landing-demo.ru/articles/186-396--events/
 */
$event = new \Bitrix\Main\Event(Config::MODULE_ID, 'onRanxLandingBlocks', Config::$blocks);
$event->send();
if ($eventResults = $event->getResults()) {
    foreach ($eventResults as $evenResult) {
        if ($evenResult->getType() == \Bitrix\Main\EventResult::SUCCESS) {
            Config::$blocks = $evenResult->getParameters();
        }
    }
}
