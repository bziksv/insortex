<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

return [
    '_EXCLUDE' => ['CONTENT_CARDS'],
    '_INCLUDE' => ['CONTENT_TABS', 'CONTENT_FILTER', 'CONTENT_WEEK_DAYS'],
    'NAME' => Loc::getMessage('RX_BLOCK_8_3_NAME'),
    'INDENT_TOP_DEFAULT' => 100,
    'INDENT_BOT_DEFAULT' => 100,
    'USE_FILTER' => true,
    'ALLOWED_SERVICE_LINK' => true,
    'ELEMENTS_FIELDS' => [
        'PROPERTY_WEEK_DAY',
        'PROPERTY_CAT',
        'PROPERTY_YEARS',
        '_INTERVAL_TIME',
        'PROPERTY_PERSON_NAME',
        'NAME',
        'PREVIEW_TEXT',
        'PROPERTY_LINK_TEXT',
        '_LINK',
    ],
    'FILTER_FIELDS' => [
        'PROPERTY_CAT',
        'PROPERTY_YEARS',
        '_INTERVAL_TIME',
        'PROPERTY_PERSON_NAME',
        'NAME',
    ],
    'ELEMENTS_FIELDS_MESS' => [
        'NAME' => Loc::getMessage('RX_BLOCK_8_3_ELEMENTS_FIELDS_MESS_NAME'),
        'PROPERTY_PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_ELEMENTS_FIELDS_MESS_PROPERTY_PERSON_NAME'),
        'PROPERTY_INTERVAL_TIME_FROM' => Loc::getMessage('RX_BLOCK_8_3_ELEMENTS_FIELDS_MESS_PROPERTY_INTERVAL_TIME_FROM_NAME'),
        'PROPERTY_CAT' => Loc::getMessage('RX_BLOCK_8_3_ELEMENTS_FIELDS_MESS_PROPERTY_CAT'),
        'PROPERTY_YEARS' => Loc::getMessage('RX_BLOCK_8_3_ELEMENTS_FIELDS_MESS_PROPERTY_YEARS'),
        'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_8_3_ELEMENTS_FIELDS_MESS_PREVIEW_TEXT'),
        '_INTERVAL_TIME' => Loc::getMessage('RX_BLOCK_8_3_ELEMENTS_FIELDS_MESS_PROPERTY_INTERVAL_TIME_FROM_NAME'),
        'PROPERTY_LINK_TEXT' => Loc::getMessage('RX_BLOCK_8_3_ELEMENTS_FIELDS_MESS_PROPERTY_LINK_TEXT_NAME')
    ],
    'DEMO' => [
        'BLOCK' => [
            'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_BLOCK_NAME'),
            'PROPERTY_VALUES' => [
                'CATTITLE' => Loc::getMessage('RX_BLOCK_8_3_DEMO_BLOCK_CATTITLE'),
                'SUBTITLE' => Loc::getMessage('RX_BLOCK_8_3_DEMO_BLOCK_SUBTITLE'),
                'USE_TABS' => 'Y',
            ],
            'FILTER' => [
                'INCLUDE' => true,
                'INCLUDE_FIELDS' => [
                    '_INTERVAL_TIME' => true,
                    'PROPERTY_PERSON_NAME' => true,
                    'NAME' => true,
                    'PROPERTY_CAT' => true,
                    'PROPERTY_YEARS' => true,
                ]
            ]
        ],
        'TABS' => [
            Loc::getMessage('RX_BLOCK_8_3_DEMO_TAB_1'),
            Loc::getMessage('RX_BLOCK_8_3_DEMO_TAB_2'),
        ],
        'ELEMENTS' => [
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PREVIEW_TEXT_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'monday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1076,
                    'INTERVAL_TIME_TO' => 1523,
                    'CAT' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_CAT_1'),
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_2'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'monday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_2'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'monday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 890,
                    'INTERVAL_TIME_TO' => 890,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],

            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_3'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'tuesday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_3'),
                    'INTERVAL_TIME_FROM' => 480,
                    'INTERVAL_TIME_TO' => 1080,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'tuesday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'tuesday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],

            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'wednesday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1320,
                    'INTERVAL_TIME_TO' => 1380,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'wednesday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1410,
                    'INTERVAL_TIME_TO' => 1470,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'wednesday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1500,
                    'INTERVAL_TIME_TO' => 1560,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],

            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'thursday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                    'CAT' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_CAT_1'),
                    'YEARS' => [8, 9, 10, 12, 13, 14],
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'thursday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                    'YEARS' => [9, 10, 11, 12, 13, 14, 15, 16],
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'thursday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                    'YEARS' => [14, 15, 16],
                ],
            ],

            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'friday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'friday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'friday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],

            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'saturday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'saturday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'saturday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],

            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'sunday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'sunday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 0,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'sunday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],


            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'PREVIEW_TEXT' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PREVIEW_TEXT_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'monday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'CAT' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_CAT_1'),
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'monday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'monday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],

            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'tuesday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'tuesday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'tuesday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],

            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'wednesday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'wednesday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'wednesday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],

            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'thursday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'thursday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'thursday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],

            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'friday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'friday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'friday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],

            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'saturday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'saturday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'saturday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],

            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'sunday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'sunday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
            [
                'NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_NAME_1'),
                'TAB' => 1,
                'PROPERTY_VALUES' => [
                    'WEEK_DAY' => 'sunday',
                    'PERSON_NAME' => Loc::getMessage('RX_BLOCK_8_3_DEMO_ELEMENT_PROPERTY_PERSON_NAME_1'),
                    'INTERVAL_TIME_FROM' => 1140,
                    'INTERVAL_TIME_TO' => 1230,
                    'LINK' => 'ranx_landing_service',
                    'LINK_TYPE' => 'service',
                ],
            ],
        ],
    ],
];
