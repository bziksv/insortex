<?php

/**
 * Module parameters list
 * @copyright 2020 RANX
 */

use Ranx\Landing\Config;
use Ranx\Landing\Helpers;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Captcha\CaptchaManager;

Loc::loadMessages(__FILE__);

$fontsList      = Config::getFontsForSelect();
$titleOptions   = Config::getTitleOptions();
$agreements     = Config::getAgreementsForSelect();
$solutions      = Config::getSolutionsForSelect();
$partnerModules = Config::getPartnerModulesForSelect();
$captchaOptions = CaptchaManager::getOptions();

/**
 * SHOW_IF usage
 *
 * There is SHOW_IF library which can help you hide some dependent options.
 * You can specify complex logic to handle visibility.
 * There are three operators currently implemented: 'AND', 'OR', '='.
 * It's possible to pass values of checkboxes, selects and hidden inputs
 * to visibility condition.
 * See /bitrix/js/ranx.landing/rx_show_if.js to learn about how it works.
 *
 * Examples
 *
 * Simply hide option when COOKIES_SHOW_BANNER checkbox not checked:
 * 'SHOW_IF' => ['COOKIES_SHOW_BANNER' => true]
 *
 * Show option only when FOOTER_TYPE set to '2':
 * 'SHOW_IF' => ['FOOTER_TYPE' => '2']
 *
 * Show option only when FOOTER_TYPE set to '2' or '3':
 * 'SHOW_IF' => [
 *     'LOGIC' => 'OR',
 *     ['FOOTER_TYPE' => '2'],
 *     ['FOOTER_TYPE' => '3'],
 * ]
 *
 * Or more simple:
 * 'SHOW_IF' => ['FOOTER_TYPE' => ['2', '3']]
 *
 * Very complex condition. Show option when FOOTER_TYPE set to '1' or 'custom',
 * or when FOOTER_TYPE set to '2' with FOOTER_BG set to 'dark',
 * or when FOOTER_TYPE set to '3' with FOOTER_BG set to 'light':
 *
 *'SHOW_IF' => [
 *     'LOGIC' => 'OR',
 *     ['FOOTER_TYPE' => '2', 'FOOTER_BG' => 'dark'],
 *     ['FOOTER_TYPE' => '3', 'FOOTER_BG' => 'light'],
 *     'FOOTER_TYPE' => ['1', 'custom']
 * ]
 */

Config::$params = [
    'MAIN' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_MAIN_TITLE'),
        'DEMO' => 'Y',
        'OPTIONS' => [
            'COLOR_THEME' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'red',
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_HINT'),
                'LIST' => [
                    'red' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_RED_TITLE'),
                        'COLOR' => '#F25353',
                    ],
                    'orange' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_ORANGE_TITLE'),
                        'COLOR' => '#FA611F',
                    ],
                    'yellow' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_YELLOW_TITLE'),
                        'COLOR' => '#FFBA0A',
                    ],
                    'lightgreen' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_LIGHTGREEN_TITLE'),
                        'COLOR' => '#88D110',
                    ],
                    'green' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_GREEN_TITLE'),
                        'COLOR' => '#28BA25',
                    ],
                    'darkgreen' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_DARKGREEN_TITLE'),
                        'COLOR' => '#16A773',
                    ],
                    'lightblue' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_LIGHTBLUE_TITLE'),
                        'COLOR' => '#08A9CC',
                    ],
                    'blue' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_BLUE_TITLE'),
                        'COLOR' => '#087ACC',
                    ],
                    'darkblue' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_DARKBLUE_TITLE'),
                        'COLOR' => '#1940CC',
                    ],
                    'purple' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_PURPLE_TITLE'),
                        'COLOR' => '#6B23C8',
                    ],
                    'magenta' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_MAGENTA_TITLE'),
                        'COLOR' => '#A723C8',
                    ],
                    'pink' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_PINK_TITLE'),
                        'COLOR' => '#C82386',
                    ],
                    'darkred' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_LIST_DARKRED_TITLE'),
                        'COLOR' => '#D31818',
                    ],
                ],
            ],
            'COLOR_THEME_CUSTOM' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COLOR_THEME_CUSTOM_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
            ],
            'CONTENT_WIDTH' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_CONTENT_WIDTH_TITLE'),
                'TYPE' => 'select',
                'DEFAULT' => '1348',
                'LIST' => [
                    '1700' => [
                        'TITLE' => '1700 px',
                    ],
                    '1500' => [
                        'TITLE' => '1500 px',
                    ],
                    '1348' => [
                        'TITLE' => '1348 px',
                    ],
                    '1200' => [
                        'TITLE' => '1200 px',
                    ],
                ],
            ],
            'BTN_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_BTN_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'default',
                'LIST' => [
                    'default' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_BTN_TYPE_LIST_DEFAULT_TITLE'),
                        'ICON' => 'default.png',
                    ],
                    'rounded' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_BTN_TYPE_LIST_ROUNDED_TITLE'),
                        'ICON' => 'rounded.png',
                    ],
                ],
            ],
            'LOGO' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_LOGO_TITLE'),
                'TYPE' => 'file',
                'DEFAULT' => 0,
                'MIME_TYPE' => 'image',
                'EXTS' => Helpers\File::IMG_EXTS,
            ],
            'LOGO_LIGHT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_LOGO_LIGHT_TITLE'),
                'TYPE' => 'file',
                'DEFAULT' => 0,
                'MIME_TYPE' => 'image',
                'EXTS' => Helpers\File::IMG_EXTS,
            ],
            'FAVICON' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FAVICON_TITLE'),
                'TYPE' => 'file',
                'DEFAULT' => 0,
                'MIME_TYPE' => 'image',
                'EXTS' => ['png', 'ico', 'svg'],
            ],
            'COOKIES_SHOW_BANNER' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COOKIES_SHOW_BANNER_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => false,
            ],
            'COOKIES_TEXT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COOKIES_TEXT_TITLE'),
                'TYPE' => 'text',
                'DEMO' => 'Y',
                'DEFAULT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COOKIES_TEXT_DEFAULT'),
                'SHOW_IF' => ['COOKIES_SHOW_BANNER' => true],
            ],
            'SLOGAN' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SLOGAN_TITLE'),
                'TYPE' => 'text',
                'DEMO' => 'Y',
                'DEFAULT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SLOGAN_DEFAULT'),
                'SIZES' => [
                    'COLS' => 30,
                    'ROWS' => 5,
                ],
            ],
            'PHONES' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PHONES_TITLE'),
                'TYPE' => 'aarray', // associative array
                'DEMO' => 'Y',
                'DEFAULT' => serialize([['NUMBER' => '+7 (000) 000-00-00', 'DESC' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PHONES_DEFAULT_DESC_1')], ['NUMBER' => '+7 (000) 000-00-01', 'DESC' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PHONES_DEFAULT_DESC_2')]]),
                'AARRAY' => [
                    'NUMBER' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PHONES_AARRAY_NUMBER'),
                        'CLASS' => 'phone',
                    ],
                    'DESC' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PHONES_AARRAY_DESC'),
                    ],
                ],
            ],
            'SCHEDULE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SCHEDULE_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '09:00 - 20:00',
            ],
            'EMAIL_PUBLIC' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_EMAIL_PUBLIC_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => 'info@example.com',
            ],
            'ADDRESS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_ADDRESS_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_ADDRESS_DEFAULT'),
            ],
            'USE_LAZYLOAD' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_LAZYLOAD_TITLE'),
                'TYPE' => 'checkbox',
                'DEFAULT' => false,
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_LAZYLOAD_HINT'),
            ],
        ],
    ],
    'FONT' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_FONT_TITLE'),
        'TEMPLATE' => 'font',
        'DEMO' => 'Y',
        'OPTIONS' => array_merge(array_merge([
            'FONT_FAMILY' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FONT_FAMILY_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => '2',
                'LIST' => $fontsList,
            ],
            'FONT_FAMILY_CUSTOM' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FONT_FAMILY_CUSTOM_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'PLACEHOLDER' => htmlspecialcharsbx('<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,500;1,500&display=swap" rel="stylesheet">'),
                'SHOW_IF' => ['FONT_FAMILY' => 'custom'],
                'DOC' => 'https://help.landing-demo.ru/articles/209-210-328--kak-izmenit-shrift-ili-dobavit-svoj-shrift-s-google-fonts/',
            ],
        ], $titleOptions), [
            'CARD_TITLE_FONT_WEIGHT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_CARD_TITLE_FONT_WEIGHT_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'bold',
                'LIST' => Config::getFontWeightsForSelect(),
            ],
        ]),
    ],
    'HEADER' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_HEADER_TITLE'),
        'DEMO' => 'Y',
        'OPTIONS' => [
            'HEADER_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'THEME_ICON_WIDE' => true,
                'DEFAULT' => '1',
                'LIST' => [
                    '1' => [
                        'TITLE' => '1',
                        'DESC' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_TYPE_LIST_1_DESC'),
                        'IS_WIDE' => true,
                        'ICON' => '1.png',
                    ],
                    '2' => [
                        'TITLE' => '2',
                        'DESC' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_TYPE_LIST_2_DESC'),
                        'IS_WIDE' => true,
                        'ICON' => '2.png',
                    ],
                    '3' => [
                        'TITLE' => '3',
                        'DESC' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_TYPE_LIST_3_DESC'),
                        'ICON' => '3.png',
                    ],
                    '4' => [
                        'TITLE' => '4',
                        'DESC' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_TYPE_LIST_4_DESC'),
                        'ICON' => '4.png',
                    ],
                    'custom' => [
                        'TITLE' => 'custom',
                        'DESC' => 'Custom',
                    ],
                ],
            ],
            'HEADER_TRANSPARENT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_TRANSPARENT_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_TRANSPARENT_HINT'),
                'DEFAULT' => true,
            ],
            'SHOW_HEADERFIXED' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SHOW_HEADERFIXED_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => true,
            ],
            'HEADERFIXED_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADERFIXED_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'THEME_ICON_WIDE' => true,
                'DEFAULT' => '1',
                'LIST' => [
                    '1' => [
                        'TITLE' => '1',
                        'DESC' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADERFIXED_TYPE_LIST_1_DESC'),
                        'ICON' => '1.png',
                    ],
                    '2' => [
                        'TITLE' => '2',
                        'DESC' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADERFIXED_TYPE_LIST_2_DESC'),
                        'ICON' => '2.png',
                    ],
                    'custom' => [
                        'TITLE' => 'custom',
                        'DESC' => 'Custom',
                    ],
                ],
                'SHOW_IF' => ['SHOW_HEADERFIXED' => true],
            ],
            'HEADERMOBILE_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADERMOBILE_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => '1',
                'LIST' => [
                    '1' => [
                        'TITLE' => '1',
                        'ICON' => '1.png',
                    ],
                    '2' => [
                        'TITLE' => '2',
                        'ICON' => '2.png',
                    ],
                    '3' => [
                        'TITLE' => '3',
                        'ICON' => '3.png',
                    ],
                    'custom' => [
                        'TITLE' => 'custom',
                    ],
                ],
            ],
            'HEADERMOBILE_FIXED' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADERMOBILE_FIXED_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => true,
            ],
            'HEADER_BTN_SHOW' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_SHOW_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => true,
                'CLASSES' => 'panel-row--nopadding no-last-btn',
            ],
            'HEADER_BTN_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'btn-primary',
                'LIST' => [
                    'btn-primary' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_TYPE_LIST_BTN_PRIMARY'),
                    ],
                    'btn-transparent' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_TYPE_LIST_BTN_TRANSPARENT'),
                    ],
                    'btn-white' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_TYPE_LIST_BTN_WHITE'),
                    ],
                ],
                'SHOW_IF' => ['HEADER_BTN_SHOW' => true],
            ],
            'HEADER_BTN_SIZE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_SIZE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'LIST' => [
                    '' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_SIZE_LIST_DEFAULT'),
                    ],
                    'btn-xs' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_SIZE_LIST_BTN_XS'),
                    ],
                    'btn-sm' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_SIZE_LIST_BTN_SM'),
                    ],
                    'btn-mr' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_SIZE_LIST_BTN_MR'),
                    ],
                    'btn-lg' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_SIZE_LIST_BTN_LG'),
                    ],
                ],
                'SHOW_IF' => ['HEADER_BTN_SHOW' => true],
            ],
            'HEADER_BTN_TEXT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_TEXT_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_TEXT_DEFAULT'),
                'SHOW_IF' => ['HEADER_BTN_SHOW' => true],
            ],
            'HEADER_BTN_LINK_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_LINK_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'form',
                'LIST' => [
                    'internal' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_LINK_TYPE_LIST_INTERNAL_TITLE'),
                    ],
                    'external' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_LINK_TYPE_LIST_EXTERNAL_TITLE'),
                    ],
                    'form' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_LINK_TYPE_LIST_FORM_TITLE'),
                    ],
                ],
                'SHOW_IF' => ['HEADER_BTN_SHOW' => true],
            ],
            'HEADER_BTN_LINK' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_LINK_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => 'ranx_landing_form_order',
                'SHOW_IF' => ['HEADER_BTN_SHOW' => true],
            ],
            'HEADER_BTN_CLASS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_CLASS_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'SHOW_IF' => ['HEADER_BTN_SHOW' => true],
            ],
            'HEADER_BTN_GOAL' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_GOAL_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'SHOW_IF' => ['HEADER_BTN_SHOW' => true],
            ],
            'PHONE_BTN_SHOW' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PHONE_BTN_SHOW_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => true,
                'HIDE_TYPE' => true,
                'HIDE_SIZE' => true,
                'CLASSES' => 'panel-row--nopadding',
            ],
            'PHONE_BTN_TEXT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_TEXT_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PHONE_BTN_TEXT_DEFAULT'),
                'SHOW_IF' => ['PHONE_BTN_SHOW' => true],
            ],
            'PHONE_BTN_LINK_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_LINK_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'form',
                'LIST' => [
                    'internal' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_LINK_TYPE_LIST_INTERNAL_TITLE'),
                    ],
                    'external' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_LINK_TYPE_LIST_EXTERNAL_TITLE'),
                    ],
                    'form' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_LINK_TYPE_LIST_FORM_TITLE'),
                    ],
                ],
                'SHOW_IF' => ['PHONE_BTN_SHOW' => true],
            ],
            'PHONE_BTN_LINK' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_LINK_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => 'ranx_landing_form_callback',
                'SHOW_IF' => ['PHONE_BTN_SHOW' => true],
            ],
            'PHONE_BTN_CLASS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_CLASS_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'SHOW_IF' => ['PHONE_BTN_SHOW' => true],
            ],
            'PHONE_BTN_GOAL' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HEADER_BTN_GOAL_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'SHOW_IF' => ['PHONE_BTN_SHOW' => true],
            ],
        ],
    ],
    'MENU' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_MENU_TITLE'),
        'OPTIONS' => [
            'ROOT_MENU_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_ROOT_MENU_TYPE_TITLE'),
                'TYPE' => 'string',
                'DEFAULT' => 'top',
            ],
            'CHILD_MENU_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_CHILD_MENU_TYPE_TITLE'),
                'TYPE' => 'string',
                'DEFAULT' => 'left',
            ],
            'SHOW_ANCHORS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SHOW_ANCHORS_TITLE'),
                'TYPE' => 'checkbox',
                'DEFAULT' => false,
            ],
            'FULL_DROPDOWN_SHOW_IMAGE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FULL_DROPDOWN_SHOW_IMAGE_TITLE'),
                'TYPE' => 'checkbox',
                'DEFAULT' => true,
            ],
            'FULL_DROPDOWN_IMAGE_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FULL_DROPDOWN_IMAGE_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEFAULT' => 'picture',
                'LIST' => [
                    'picture' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_IMAGE_TYPE_LIST_PICTURE'),
                    ],
                    'icon' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_IMAGE_TYPE_LIST_ICON'),
                    ]
                ],
                'SHOW_IF' => ['FULL_DROPDOWN_SHOW_IMAGE' => true],
            ],
            'FULL_DROPDOWN_POSITION_IMAGE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FULL_DROPDOWN_POSITION_IMAGE_TITLE'),
                'TYPE' => 'select',
                'DEFAULT' => 'left',
                'LIST' => [
                    'left' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_POSITION_IMAGE_LIST_LEFT'),
                    ],
                    'top' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_POSITION_IMAGE_LIST_TOP'),
                    ],
                ],
                'SHOW_IF' => ['FULL_DROPDOWN_SHOW_IMAGE' => true],
            ],
            'FULL_DROPDOWN_COUNT_COLUMN' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FULL_DROPDOWN_COUNT_COLUMN_TITLE'),
                'TYPE' => 'select',
                'DEFAULT' => '3',
                'LIST' => [
                    '2' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COUNT_COLUMN_LIST_TWO'),
                    ],
                    '3' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COUNT_COLUMN_LIST_THREE'),
                    ],
                    '4' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_COUNT_COLUMN_LIST_FOUR'),
                    ],
                ],
            ],
            'FULL_DROPDOWN_DISPLAY_SUBITEMS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FULL_DROPDOWN_DISPLAY_SUBITEMS_TITLE'),
                'TYPE' => 'select',
                'DEFAULT' => 'row',
                'LIST' => [
                    'row' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_DISPLAY_SUBITEMS_LIST_ROW'),
                    ],
                    'column' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_DISPLAY_SUBITEMS_LIST_COLUMN'),
                    ],
                ],
            ],
            'MEGAMENU_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MEGAMENU_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEFAULT' => '1',
                'LIST' => [
                    '1' => [
                        'TITLE' => '1',
                    ],
                    'custom' => [
                        'TITLE' => 'custom',
                    ],
                ],
            ],
            'MOBILEMENU_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MOBILEMENU_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEFAULT' => '1',
                'LIST' => [
                    '1' => [
                        'TITLE' => '1',
                    ],
                    'custom' => [
                        'TITLE' => 'custom',
                    ],
                ],
            ],
        ],
    ],
    'FOOTER' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_FOOTER_TITLE'),
        'DEMO' => 'Y',
        'OPTIONS' => [
            'FOOTER_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'THEME_ICON_WIDE' => true,
                'DEFAULT' => '1',
                'LIST' => [
                    '1' => [
                        'TITLE' => '1',
                        'DESC' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_TYPE_LIST_1_DESC'),
                        'ICON' => '1.png',
                    ],
                    '2' => [
                        'TITLE' => '2',
                        'DESC' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_TYPE_LIST_2_DESC'),
                        'ICON' => '2.png',
                    ],
                    '3' => [
                        'TITLE' => '3',
                        'DESC' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_TYPE_LIST_3_DESC'),
                        'ICON' => '3.png',
                    ],
                    'custom' => [
                        'TITLE' => 'custom',
                        'DESC' => 'Custom',
                    ],
                ],
            ],
            'FOOTER_BG' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BG_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'dark',
                'LIST' => [
                    'dark' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BG_LIST_DARK_TITLE'),
                    ],
                    'light' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BG_LIST_LIGHT_TITLE'),
                    ],
                ],
            ],
            'FOOTER_COPYRIGHT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_COPYRIGHT_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_COPYRIGHT_HINT'),
                'DEFAULT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_COPYRIGHT_DEFAULT'),
            ],
            'POLITICS_LINK' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_POLITICS_LINK_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
            ],
            'POLITICS_ID' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_POLITICS_ID_TITLE'),
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_POLITICS_ID_HINT'),
                'AFTER' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_POLITICS_ID_AFTER'),
                'TYPE' => 'select',
                'DEFAULT' => '0',
                'LIST' => [
                    '0' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_POLITICS_ID_LIST_0_TITLE'),
                    ],
                ] + $agreements,
                'DISABLED' => empty($agreements),
            ],
            'PAYOPTIONS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAYOPTIONS_TITLE'),
                'TYPE' => 'multiselect',
                'DEMO' => 'Y',
                'DEFAULT' => serialize(['cash', 'visa', 'sberbank', 'maestro', 'alfabank', 'mir']),
                'LIST' => [
                    'cash' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAYOPTIONS_LIST_CASH_TITLE'),
                    ],
                    'visa' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAYOPTIONS_LIST_VISA_TITLE'),
                    ],
                    'sberbank' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAYOPTIONS_LIST_SBERBANK_TITLE'),
                    ],
                    'maestro' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAYOPTIONS_LIST_MAESTRO_TITLE'),
                    ],
                    'alfabank' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAYOPTIONS_LIST_ALFABANK_TITLE'),
                    ],
                    'mir' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAYOPTIONS_LIST_MIR_TITLE'),
                    ],
                    'yamoney' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAYOPTIONS_LIST_YAMONEY_TITLE'),
                    ],
                    'webmoney' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAYOPTIONS_LIST_WEBMONEY_TITLE'),
                    ],
                ],
            ],
            'FOOTER_BTN_SHOW' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_SHOW_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => true,
                'CLASSES' => 'panel-row--nopadding',
            ],
            'FOOTER_BTN_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'btn-primary',
                'LIST' => [
                    'btn-primary' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_TYPE_LIST_BTN_PRIMARY'),
                    ],
                    'btn-transparent' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_TYPE_LIST_BTN_TRANSPARENT'),
                    ],
                    'btn-white' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_TYPE_LIST_BTN_WHITE'),
                    ],
                ],
                'SHOW_IF' => ['FOOTER_BTN_SHOW' => true],
            ],
            'FOOTER_BTN_SIZE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_SIZE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'LIST' => [
                    '' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_SIZE_LIST_DEFAULT'),
                    ],
                    'btn-xs' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_SIZE_LIST_BTN_XS'),
                    ],
                    'btn-sm' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_SIZE_LIST_BTN_SM'),
                    ],
                    'btn-mr' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_SIZE_LIST_BTN_MR'),
                    ],
                    'btn-lg' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_SIZE_LIST_BTN_LG'),
                    ],
                ],
                'SHOW_IF' => ['FOOTER_BTN_SHOW' => true],
            ],
            'FOOTER_BTN_TEXT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_TEXT_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_TEXT_DEFAULT'),
                'SHOW_IF' => ['FOOTER_BTN_SHOW' => true],
            ],
            'FOOTER_BTN_LINK_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_LINK_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'form',
                'LIST' => [
                    'internal' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_LINK_TYPE_LIST_INTERNAL_TITLE'),
                    ],
                    'external' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_LINK_TYPE_LIST_EXTERNAL_TITLE'),
                    ],
                    'form' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_LINK_TYPE_LIST_FORM_TITLE'),
                    ],
                ],
                'SHOW_IF' => ['FOOTER_BTN_SHOW' => true],
            ],
            'FOOTER_BTN_LINK' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_LINK_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => 'ranx_landing_form_order',
                'SHOW_IF' => ['FOOTER_BTN_SHOW' => true],
            ],
            'FOOTER_BTN_CLASS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_CLASS_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'SHOW_IF' => ['FOOTER_BTN_SHOW' => true],
            ],
            'FOOTER_BTN_GOAL' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FOOTER_BTN_GOAL_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'SHOW_IF' => ['FOOTER_BTN_SHOW' => true],
            ],
            'RANX_COPY' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_RANX_COPY_TITLE'),
                'TYPE' => 'checkbox',
                'THEME' => 'N',
                'DEFAULT' => true,
            ],
        ],
    ],
    'SECTIONS' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_SECTIONS_TITLE'),
        'OPTIONS' => [
            'SHOW_PAGETITLE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SHOW_PAGETITLE_TITLE'),
                'TYPE' => 'checkbox',
                'DEFAULT' => true,
            ],
            'PAGETITLE_ALIGN' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAGETITLE_ALIGN_TITLE'),
                'TYPE' => 'select',
                'DEFAULT' => 'left',
                'LIST' => [
                    'left' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAGETITLE_ALIGN_LIST_LEFT_TITLE'),
                        'ICON' => 'left.png',
                    ],
                    'center' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAGETITLE_ALIGN_LIST_CENTER_TITLE'),
                        'ICON' => 'center.png',
                    ],
                ],
                'SHOW_IF' => ['SHOW_PAGETITLE' => true],
            ],
            'SECTION_ELEMENTS_COUNT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SECTION_ELEMENTS_COUNT_TITLE'),
                'TYPE' => 'string',
                'DEFAULT' => '12',
            ],
            'SECTION_PRODUCTS_COUNT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SECTION_PRODUCTS_COUNT_TITLE'),
                'TYPE' => 'string',
                'DEFAULT' => '8',
            ],
            'SECTION_NEWS_COUNT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SECTION_NEWS_COUNT_TITLE'),
                'TYPE' => 'string',
                'DEFAULT' => '10',
            ],
        ],
    ],
    'BLOCKS' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_BLOCKS_TITLE'),
        'DEMO' => 'Y',
        'OPTIONS' => [
            'BLOCK_TITLE_ALIGN' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_BLOCK_TITLE_ALIGN_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'center',
                'LIST' => [
                    'center' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_BLOCK_TITLE_ALIGN_LIST_CENTER_TITLE'),
                        'ICON' => 'center.png',
                    ],
                    'left' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_BLOCK_TITLE_ALIGN_LIST_LEFT_TITLE'),
                        'ICON' => 'left.png',
                    ],
                ],
            ],
            'MAP_CONTROLS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MAP_CONTROLS_TITLE'),
                'TYPE' => 'multiselect',
                'DEMO' => 'Y',
                'DEFAULT' => serialize(['geolocationControl', 'zoomControl']),
                'LIST' => [
                    'geolocationControl' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MAP_CONTROLS_LIST_GEOLOCATION_TITLE'),
                    ],
                    'inputSearch' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MAP_CONTROLS_LIST_SEARCH_TITLE'),
                    ],
                    'routeButtonControl' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MAP_CONTROLS_LIST_ROUTE_TITLE'),
                    ],
                    'trafficControl' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MAP_CONTROLS_LIST_TRAFFIC_TITLE'),
                    ],
                    'typeSelector' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MAP_CONTROLS_LIST_TYPE_TITLE'),
                    ],
                    'fullscreenControl' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MAP_CONTROLS_LIST_FULLSCREEN_TITLE'),
                    ],
                    'zoomControl' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MAP_CONTROLS_LIST_ZOOM_TITLE'),
                    ],
                    'rulerControl' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MAP_CONTROLS_LIST_RULER_TITLE'),
                    ],
                ],
            ],
            'CURRENCY' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_CURRENCY_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '&#x20BD;',
            ],
        ],
    ],
    'CATALOG' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_CATALOG_TITLE'),
        'OPTIONS' => [
            'ORDER_ENABLED' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_ORDER_ENABLED_TITLE'),
                'TYPE' => 'checkbox',
                'DEFAULT' => false,
            ],
            'ORDER_PAGE_LINK' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_ORDER_PAGE_LINK_TITLE'),
                'TYPE' => 'string',
                'DEFAULT' => '',
                'SHOW_IF' => ['ORDER_ENABLED' => true],
            ],
            'ONECLICK_ENABLED' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_ONECLICK_ENABLED_TITLE'),
                'TYPE' => 'checkbox',
                'DEFAULT' => true,
                'SHOW_IF' => ['ORDER_ENABLED' => true],
            ],
            'DELIVERY' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_DELIVERY_TITLE'),
                'TYPE' => 'aarray',
                'DEFAULT' => serialize([
                    [
                        'NAME' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_DELIVERY_DEFAULT_0_NAME'),
                        'COST' => 0,
                        'SHOW_ADDRESS' => false,
                        'CAPTION' => '',
                    ], 
                    [
                        'NAME' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_DELIVERY_DEFAULT_1_NAME'),
                        'COST' => 500,
                        'SHOW_ADDRESS' => true,
                        'CAPTION' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_DELIVERY_DEFAULT_1_CAPTION'),
                    ],
                ]),
                'AARRAY_EXPANDED' => true,
                'AARRAY' => [
                    'NAME' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_DELIVERY_AARRAY_NAME_TITLE'),
                    ],
                    'COST' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_DELIVERY_AARRAY_COST_TITLE'),
                    ],
                    'SHOW_ADDRESS' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_DELIVERY_AARRAY_SHOW_ADDRESS_TITLE'),
                        'TYPE' => 'checkbox',
                    ],
                    'CAPTION' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_DELIVERY_AARRAY_CAPTION_TITLE'),
                        'TYPE' => 'text',
                    ],
                ],
                'SHOW_IF' => ['ORDER_ENABLED' => true],
            ],
            'PAYMENT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAYMENT_TITLE'),
                'TYPE' => 'select',
                'DEFAULT' => '',
                'LIST' => [
                    '' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAYMENT_LIST__TITLE'),
                    ],
                    'INVOICEBOX' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PAYMENT_LIST_INVOICEBOX_TITLE'),
                    ],
                ],
                'SHOW_IF' => ['ORDER_ENABLED' => true],
            ],
            'INVOICEBOX_ID' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_INVOICEBOX_ID_TITLE'),
                'TYPE' => 'string',
                'SHOW_IF' => ['ORDER_ENABLED' => true, 'PAYMENT' => 'INVOICEBOX'],
            ],
            'INVOICEBOX_IDENT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_INVOICEBOX_IDENT_TITLE'),
                'TYPE' => 'string',
                'SHOW_IF' => ['ORDER_ENABLED' => true, 'PAYMENT' => 'INVOICEBOX'],
            ],
            'INVOICEBOX_SECRET' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_INVOICEBOX_SECRET_TITLE'),
                'TYPE' => 'string',
                'SHOW_IF' => ['ORDER_ENABLED' => true, 'PAYMENT' => 'INVOICEBOX'],
            ],
            'INVOICEBOX_CURRENCY' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_INVOICEBOX_CURRENCY_TITLE'),
                'TYPE' => 'string',
                'PLACEHOLDER' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_INVOICEBOX_CURRENCY_PLACEHOLDER'),
                'SHOW_IF' => ['ORDER_ENABLED' => true, 'PAYMENT' => 'INVOICEBOX'],
            ],
            'INVOICEBOX_TESTMODE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_INVOICEBOX_TESTMODE_TITLE'),
                'TYPE' => 'checkbox',
                'DEFAULT' => true,
                'SHOW_IF' => ['ORDER_ENABLED' => true, 'PAYMENT' => 'INVOICEBOX'],
            ],
        ],
    ],
    'INSTAGRAM' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_INSTAGRAM_TITLE'),
        'OPTIONS' => [
            'INSTAGRAM_TOKEN' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_INSTAGRAM_TOKEN_TITLE'),
                'TYPE' => 'string',
                'DOC' => 'https://help.landing-demo.ru/articles/209-246-247--kak-poluchit-token-instagram/',
            ],
            'INSTAGRAM_POSTS_COUNT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_INSTAGRAM_POSTS_COUNT_TITLE'),
                'TYPE' => 'string',
                'DEFAULT' => '6',
            ],
            'INSTAGRAM_CACHE_TIME' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_INSTAGRAM_CACHE_TIME_TITLE'),
                'TYPE' => 'string',
                'DEFAULT' => '3600',
            ]
        ],
    ],
    'FORMS' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_FORMS_TITLE'),
        'DEMO' => 'Y',
        'OPTIONS' => [
            'USE_FORM_MODULE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_FORM_MODULE_TITLE'),
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_FORM_MODULE_HINT'),
                'TYPE' => 'checkbox',
                'DEFAULT' => \Bitrix\Main\ModuleManager::isModuleInstalled('form'),
                'DISABLED' => !\Bitrix\Main\ModuleManager::isModuleInstalled('form'),
            ],
            'SHOW_ALL_FORMS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SHOW_ALL_FORMS_TITLE'),
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SHOW_ALL_FORMS_HINT'),
                'TYPE' => 'checkbox',
                'THEME' => 'N',
                'DEFAULT' => false,
                'DISABLED' => !\Bitrix\Main\ModuleManager::isModuleInstalled('form'),
                'SHOW_IF' => ['USE_FORM_MODULE' => true],
            ],
            'USE_AGREEMENT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_AGREEMENT_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => true,
            ],
            'AGREEMENT_ACTIVE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_AGREEMENT_ACTIVE_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => false,
                'SHOW_IF' => ['USE_AGREEMENT' => true],
            ],
            'AGREEMENT_LINK' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_AGREEMENT_LINK_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '/include/licenses_detail.php',
                'SHOW_IF' => ['USE_AGREEMENT' => true],
            ],
            'AGREEMENT_ID' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_AGREEMENT_ID_TITLE'),
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_AGREEMENT_ID_HINT'),
                'AFTER' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_AGREEMENT_ID_AFTER'),
                'TYPE' => 'select',
                'DEFAULT' => '0',
                'LIST' => [
                    '0' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_AGREEMENT_ID_LIST_0_TITLE'),
                    ],
                ] + $agreements,
                'SHOW_IF' => ['USE_AGREEMENT' => true],
                'DISABLED' => empty($agreements),
            ],
            'MODAL_POSITION' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MODAL_POSITION_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'center',
                'LIST' => [
                    'center' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MODAL_POSITION_LIST_CENTER_TITLE'),
                        'ICON' => 'center.png',
                    ],
                    'right' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MODAL_POSITION_LIST_RIGHT_TITLE'),
                        'ICON' => 'right.png',
                    ],
                ],
            ],
            'MAX_FILE_SIZE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_MAX_FILE_SIZE_TITLE'),
                'TYPE' => 'string',
                'DEFAULT' => '16',
            ],
            'PHONE_MASK' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_PHONE_MASK_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '+7 (999) 999-99-99',
            ],
            'B24_FORMS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_B24_FORMS_TITLE'),
                'TYPE' => 'aarray',
                'DEFAULT' => serialize([]),
                'DOC' => 'https://help.landing-demo.ru/articles/209-249-522--kak-podklyuchit-crm-formy-bitriks24/',
                'AARRAY' => [
                    'NAME' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_B24_FORMS_AARRAY_NAME_TITLE'),
                    ],
                    'CODE' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_B24_FORMS_AARRAY_CODE_TITLE'),
                    ],
                ],
            ],
        ] + $captchaOptions,
    ],
    'CHAT' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_CHAT_TITLE'),
        'NOTE' => Loc::getMessage('RX_LANDING_PARAMS_CHAT_NOTE'),
        'TEMPLATE' => 'chat',
        'DEMO' => 'Y',
        'OPTIONS' => [
            'JIVOSITE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_JIVOSITE_TITLE'),
                'TYPE' => 'text',
                'DEMO' => 'Y',
                'LINK' => 'https://www.jivosite.ru/?partner_id=27536&pricelist_id=1105&lang=ru',
                'DEFAULT' => '',
                'ICON' => 'icon.png',
            ],
            'B24CHAT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_B24CHAT_TITLE'),
                'TYPE' => 'text',
                'DEMO' => 'Y',
                'LINK' => 'https://www.bitrix24.ru/create.php?p=8910405',
                'DEFAULT' => '',
                'ICON' => 'icon.png',
            ],
            'ENVYBOX' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_ENVYBOX_TITLE'),
                'TYPE' => 'text',
                'DEMO' => 'Y',
                'LINK' => 'http://envbx.ru/url/c53d64/',
                'DEFAULT' => '',
                'ICON' => 'icon.png',
            ],
            'REDHELPER' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_REDHELPER_TITLE'),
                'TYPE' => 'text',
                'DEMO' => 'Y',
                'LINK' => 'http://redhelper.ru/?p=2010424',
                'DEFAULT' => '',
                'ICON' => 'icon.png',
            ],
            'OTHER_CHAT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_OTHER_CHAT_TITLE'),
                'TYPE' => 'text',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'ICON' => 'icon.svg',
            ],
        ],
    ],
    'METRICS' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_METRICS_TITLE'),
        'NOTE' => Loc::getMessage('RX_LANDING_PARAMS_METRICS_NOTE'),
        'TEMPLATE' => 'chat',
        'DEMO' => 'Y',
        'OPTIONS' => [
            'YAMETRIKA' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_YAMETRIKA_TITLE'),
                'TYPE' => 'text',
                'DEMO' => 'Y',
                'LINK' => 'https://metrika.yandex.ru/',
                'DEFAULT' => '',
                'ICON' => 'icon.png',
            ],
            'YAMETRIKA_USE_GOALS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_YAMETRIKA_USE_GOALS_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'COLLAPSE' => 'Y',
                'DOC' => 'https://help.landing-demo.ru/articles/209-258-496--yandex-metrika-goals/',
                'DEFAULT' => false,
            ],
            'YAMETRIKA_COUNTER' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_YAMETRIKA_COUNTER_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'PLACEHOLDER' => '12345',
                'SHOW_IF' => ['YAMETRIKA_USE_GOALS' => true],
            ],
            'YAMETRIKA_USE_DEBUG' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_YAMETRIKA_USE_DEBUG_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => false,
                'SHOW_IF' => ['YAMETRIKA_USE_GOALS' => true],
            ],
            'GANALYTICS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_GANALYTICS_TITLE'),
                'TYPE' => 'text',
                'DEMO' => 'Y',
                'LINK' => 'https://analytics.google.com/analytics/web/',
                'DEFAULT' => '',
                'ICON' => 'icon.png',
            ],
            'GANALYTICS_USE_EVENTS' => [
                'TITLE' => Loc::getMEssage('RX_LANDING_PARAMS_OPTION_GANALYTICS_USE_EVENTS_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'COLLAPSE' => 'Y',
                'DEFAULT' => false,
            ],
            'GANALYTICS_RESOURCE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_GANALYTICS_RESOURCE_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'PLACEHOLDER' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_GANALYTICS_RESOURCE_PLACEHOLDER_TITLE'),
                'SHOW_IF' => ['GANALYTICS_USE_EVENTS' => true],
            ],
            'GTAGMANAGER' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_GTAGMANAGE_TITLE'),
                'TYPE' => 'multitext',
                'DEMO' => 'Y',
                'LINK' => 'https://marketingplatform.google.com/about/tag-manager/',
                'DEFAULT' => serialize(['', '']),
                'PLACEHOLDER' => [
                    Loc::getMessage('RX_LANDING_PARAMS_OPTION_GTAGMANAGE_PLACEHOLDER_1'),
                    Loc::getMessage('RX_LANDING_PARAMS_OPTION_GTAGMANAGE_PLACEHOLDER_2'),
                ],
                'ICON' => 'icon.svg',
            ],
            'OTHER_METRICS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_OTHER_METRICS_TITLE'),
                'TYPE' => 'text',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'ICON' => 'icon.svg',
            ],
        ],
    ],
    'SOCIAL' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_SOCIAL_TITLE'),
        'TEMPLATE' => 'social',
        'DEMO' => 'Y',
        'OPTIONS' => [
            'VK' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_VK_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => 'ranx_ru',
            ],
            'OK' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_OK_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
            ],
            'FACEBOOK' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_FACEBOOK_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
            ],
            'TWITTER' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_TWITTER_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
            ],
            'INSTAGRAM' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_INSTAGRAM_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
            ],
            'TELEGRAM' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_TELEGRAM_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => 'ranx_bot',
            ],
            'ZEN' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_ZEN_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => 'https://zen.yandex.ru/id/6165733290f2af1d1f3e6bc6',
            ],
            'TIKTOK' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_TIKTOK_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
            ],
            'WHATSAPP' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_WHATSAPP_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
            ],
            'VIBER' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_VIBER_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
            ],
            'YOUTUBE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_YOUTUBE_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
            ],
        ],
    ],
    'REGION' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_REGION_TITLE'),
        'DEMO' => 'Y',
        'OPTIONS' => [
            'USE_REGION' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_REGION_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => false,
            ],
            'SOLUTION' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SOLUTION_TITLE'),
                'TYPE' => 'select',
                'DEFAULT' => 'none',
                'LIST' => array_merge([
                    'none' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SOLUTION_LIST_NONE_TITLE'),
                    ],
                ], $solutions),
                'DISABLED' => empty($solutions),
                'SHOW_IF' => ['USE_REGION' => true],
            ],
            'REGIONS_VIEW' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_REGIONS_VIEW_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'select',
                'LIST' => [
                    'select' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_REGIONS_VIEW_LIST_SELECT_TITLE'),
                    ],
                    'popup_cities' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_REGIONS_VIEW_LIST_POPUP_CITIES_TITLE'),
                    ],
                ],
                'SHOW_IF' => ['USE_REGION' => true],
            ],
            'USE_ONLY_REGION_SEARCH' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_ONLY_REGION_SEARCH_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => false,
                'SHOW_IF' => ['USE_REGION' => true],
            ],
            'REGION_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_REGION_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'ONE_DOMAIN',
                'LIST' => [
                    'ONE_DOMAIN' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_REGION_TYPE_LIST_ONE_DOMAIN_TITLE'),
                    ],
                    'SUBDOMAINS' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_REGION_TYPE_LIST_SUBDOMAINS_TITLE'),
                    ],
                ],
                'SHOW_IF' => ['USE_REGION' => true],
            ],
            'USE_REGION_BY_IP' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_REGION_BY_IP_TITLE'),
                'TYPE' => 'checkbox',
                'THEME' => 'N',
                'DEFAULT' => true,
                'SHOW_IF' => ['USE_REGION' => true],
            ],
            'USE_REGIONS_ON_MAP' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_REGIONS_ON_MAP_TITLE'),
                'TYPE' => 'checkbox',
                'DEFAULT' => false,
                'SHOW_IF' => ['USE_REGION' => true],
            ],
            'USE_REGION_BRANCHES' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_REGION_BRANCHES_TITLE'),
                'TYPE' => 'checkbox',
                'DEFAULT' => false,
                'SHOW_IF' => ['USE_REGION' => true],
            ],
            'USE_REGION_BRANCHES_ON_MAP' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_REGION_BRANCHES_ON_MAP_TITLE'),
                'TYPE' => 'checkbox',
                'DEFAULT' => false,
                'SHOW_IF' => ['USE_REGION' => true],
            ],
            'USE_REGION_FILTER_IN_EDIT_MODE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_REGION_FILTER_IN_EDIT_MODE_TITLE'),
                'TYPE' => 'checkbox',
                'DEFAULT' => false,
                'SHOW_IF' => ['USE_REGION' => true],
            ],
        ],
    ],
    'SEARCH' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_SEARCH_TITLE'),
        'OPTIONS' => [
            'USE_SEARCH' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_SEARCH_TITLE'),
                'TYPE' => 'checkbox',
                'DEFAULT' => false,
                'DOC' => 'https://help.landing-demo.ru/articles/206-583--create-search-page/',
            ],
            'SEARCH_PAGE_LINK' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SEARCH_PAGE_LINK_TITLE'),
                'TYPE' => 'string',
                'SHOW_IF' => ['USE_SEARCH' => true],
            ],
            'SEARCH_PAGE_RESULT_COUNT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SEARCH_PAGE_RESULT_COUNT_TITLE'),
                'TYPE' => 'string',
                'DEFAULT' => '30',
                'SHOW_IF' => ['USE_SEARCH' => true],
            ],
        ],
    ],
    'UP_BUTTON' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_UP_BUTTON_TITLE'),
        'DEMO' => 'Y',
        'OPTIONS' => [
            'SHOW_UP_BUTTON' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SHOW_UP_BUTTON_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => true,
            ],
            'UP_BUTTON_TYPE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_UP_BUTTON_TYPE_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'btn-primary',
                'LIST' => [
                    'btn-primary' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_UP_BUTTON_TYPE_LIST_BTN_PRIMARY'),
                    ],
                    'btn-white' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_UP_BUTTON_TYPE_LIST_BTN_WHITE'),
                    ],
                ],
                'SHOW_IF' => ['SHOW_UP_BUTTON' => true],
            ],
            'UP_BUTTON_LOCATION' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_UP_BUTTON_LOCATION_TITLE'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => 'right',
                'LIST' => [
                    'left' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_UP_BUTTON_LOCATION_LIST_LEFT'),
                    ],
                    'right' => [
                        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_UP_BUTTON_LOCATION_LIST_RIGHT'),
                    ],
                ],
                'SHOW_IF' => ['SHOW_UP_BUTTON' => true],
            ],
            'UP_BUTTON_LEFT_INDENT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_UP_BUTTON_LEFT_INDENT_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '75',
                'SHOW_IF' => ['SHOW_UP_BUTTON' => true, 'UP_BUTTON_LOCATION' => 'left'],
            ],
            'UP_BUTTON_RIGHT_INDENT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_UP_BUTTON_RIGHT_INDENT_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '75',
                'SHOW_IF' => ['SHOW_UP_BUTTON' => true, 'UP_BUTTON_LOCATION' => 'right'],
            ],
            'UP_BUTTON_BOTTOM_INDENT' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_UP_BUTTON_BOTTOM_INDENT_TITLE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '55',
                'SHOW_IF' => ['SHOW_UP_BUTTON' => true],
            ],
            'HIDE_MOBILE_UP_BUTTON' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_HIDE_MOBILE_UP_BUTTON_TITLE'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => true,
                'SHOW_IF' => ['SHOW_UP_BUTTON' => true],
            ],
        ]
    ],
    'DEVELOP' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_DEVELOP_TITLE'),
        'NOTE' => Loc::getMessage('RX_LANDING_PARAMS_DEVELOP_NOTE'),
        'THEME' => 'N',
        'OPTIONS' => [
            'TEMPLATE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_TEMPLATE_TITLE'),
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_TEMPLATE_HINT'),
                'TYPE' => 'string',
                'THEME' => 'N',
                'DEFAULT' => 'ranx-landing',
                'DISABLED' => true,
                'HIDDEN' => true,
            ],
            'DEMO_MODE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_DEMO_MODE_TITLE'),
                'TYPE' => 'checkbox',
                'THEME' => 'N',
                'DEFAULT' => false,
                'DISABLED' => !defined('RANX_LANDING_DEMO_MODE') || !RANX_LANDING_DEMO_MODE,
                'HIDDEN' => !defined('RANX_LANDING_DEMO_MODE') || !RANX_LANDING_DEMO_MODE,
            ],
            'DM_MAX_FILE_SIZE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_DM_MAX_FILE_SIZE_TITLE'),
                'TYPE' => 'string',
                'DEFAULT' => '4',
                'SHOW_IF' => ['DEMO_MODE' => true],
            ],
            'USE_FONTAWESOME' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_FONTAWESOME_TITLE'),
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_FONTAWESOME_HINT'),
                'TYPE' => 'checkbox',
                'THEME' => 'N',
                'DEFAULT' => false,
            ],
            'USE_VISUAL_EDITOR' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_VISUAL_EDITOR_TITLE'),
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_VISUAL_EDITOR_HINT'),
                'TYPE' => 'checkbox',
                'THEME' => 'N',
                'DEFAULT' => false,
            ],
            'INCLUDE_MODULES' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_INCLUDE_MODULES_TITLE'),
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_INCLUDE_MODULES_HINT'),
                'TYPE' => 'multiselect',
                'THEME' => 'N',
                'DEFAULT' => serialize([]),
                'LIST' => $partnerModules,
                'HIDDEN' => empty($partnerModules),
            ],
            'USE_CSS_CLASSES' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_CSS_CLASSES_TITLE'),
                'HINT' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_USE_CSS_CLASSES_HINT'),
                'TYPE' => 'checkbox',
                'THEME' => 'N',
                'DEFAULT' => false,
            ],
        ],
    ],
    'STATS' => [
        'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_STATS_TITLE'),
        'NOTE' => Loc::getMessage('RX_LANDING_PARAMS_STATS_NOTE'),
        'THEME' => 'N',
        'OPTIONS' => [
            'SEND_STATS' => [
                'TITLE' => Loc::getMessage('RX_LANDING_PARAMS_OPTION_SEND_STATS_TITLE'),
                'TYPE' => 'checkbox',
                'THEME' => 'N',
                'DEFAULT' => true,
            ],
        ],
    ],
];

/**
 * @link https://help.landing-demo.ru/articles/186-396--events/
 */
$event = new \Bitrix\Main\Event(Config::MODULE_ID, 'onRanxLandingParams', Config::$params);
$event->send();
if ($eventResults = $event->getResults()) {
    foreach ($eventResults as $evenResult) {
        if ($evenResult->getType() == \Bitrix\Main\EventResult::SUCCESS) {
            Config::$params = $evenResult->getParameters();
        }
    }
}
