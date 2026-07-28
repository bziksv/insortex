<?php

use Ranx\Landing\Config;
use Ranx\Landing\Preset;
use Bitrix\Main\Localization\Loc;

Config::$presets = [
    'GROUPS' => [
        'CUSTOM' => [
            'TITLE' => Loc::getMessage('RX_LANDING_PRESETS_GROUPS_CUSTOM_TITLE'),
            'LIST' => [],
        ],
    ],
    'LIST' => [],
];

Preset::initList();
