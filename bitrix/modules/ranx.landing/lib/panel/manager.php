<?php

namespace Ranx\Landing\Panel;

use Ranx\Landing\Config;
use Ranx\Landing\Landing;

class Manager
{
    public static function show($mode = Landing::MODE_ELEMENT, $sectionType = null)
    {
        if (Config::isPanelEnabled()) {
            $GLOBALS['APPLICATION']->IncludeComponent(
                'ranx:panel.landing',
                '',
                [
                    'MODE' => $mode,
                    'SECTION_TYPE' => $sectionType,
                ],
                false,
                [
                    'HIDE_ICONS' => 'Y',
                ]
            );
        }
    }

    public static function getOptionIconPath($code, $icon)
    {
        $iconPath = '/bitrix/images/' . Config::MODULE_ID . '/settings/' . strtolower($code) . '/' . $icon;
        if (strpos($icon, '/') !== false) {
            $iconPath = '/bitrix/images/' . Config::MODULE_ID . '/settings/' . $icon;
        }

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $iconPath)) {
            return $iconPath;
        }
        return '';
    }
}
