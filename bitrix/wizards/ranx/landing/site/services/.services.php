<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$arServices = [
    'main' => [
        'NAME' => Loc::getMessage('SERVICE_MAIN_SETTINGS'),
        'STAGES' => [
            'agreement.php',
            'events.php',
            'public.php',
        ],
    ],
    'fileman' => [
        'NAME' => Loc::getMessage('SERVICE_FILEMAN_SETTING'),
        'STAGES' => [
            'properties.php',
        ],
    ],
    'form' => [
        'NAME' => Loc::getMessage('SERVICE_FORM_DEMO_DATA'),
        'STAGES' => [
            'settings.php',
            'callback.php',
            'order.php',
            'sale_order.php',
            'service.php',
        ],
    ],
    'iblock' => [
        'NAME' => Loc::getMessage('SERVICE_IBLOCK_DEMO_DATA'),
        'STAGES' => [
            'types.php',
            'blocks.php',
            'regions.php',
            'elements.php',
            'branches.php',
            'sale_order.php',
            'service.php',
            'form_order.php',
            'form_callback.php',
        ],
    ],
    'ranx.landing' => [
        'NAME' => Loc::getMessage('SERVICE_RANX_LANDING_DEMO_DATA'),
        'STAGES' => [
            'main.php',
            'pages.php',
        ],
    ]
];
