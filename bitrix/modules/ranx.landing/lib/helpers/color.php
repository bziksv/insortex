<?php

namespace Ranx\Landing\Helpers;

use Exception;
use Ranx\Landing\Config;
use Bitrix\Main\IO;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Proxy\Option;

class Color
{
    public static function rgbToHsl($r, $g, $b) {
        $oldR = $r;
        $oldG = $g;
        $oldB = $b;

        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max( $r, $g, $b );
        $min = min( $r, $g, $b );

        $h = 0;
        $s = 0;
        $l = ($max + $min) / 2;
        $d = $max - $min;

        if( $d == 0 ){
            $h = $s = 0; // achromatic
        } else {
            $s = $d / ( 1 - abs( 2 * $l - 1 ) );

            switch($max){
                case $r:
                    $h = 60 * fmod( ( ( $g - $b ) / $d ), 6 );
                    if ($b > $g) {
                        $h += 360;
                    }
                    break;

                case $g:
                    $h = 60 * ( ( $b - $r ) / $d + 2 );
                    break;

                case $b:
                    $h = 60 * ( ( $r - $g ) / $d + 4 );
                    break;
            }
        }

        return array( round( $h, 2 ), round( $s, 2 ), round( $l, 2 ) );
    }

    public static function hslToRgb($h, $s, $l){
        $r = 0;
        $g = 0;
        $b = 0;

        $c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
        $x = $c * ( 1 - abs( fmod( ( $h / 60 ), 2 ) - 1 ) );
        $m = $l - ( $c / 2 );

        if ( $h < 60 ) {
            $r = $c;
            $g = $x;
            $b = 0;
        } else if ( $h < 120 ) {
            $r = $x;
            $g = $c;
            $b = 0;
        } else if ( $h < 180 ) {
            $r = 0;
            $g = $c;
            $b = $x;
        } else if ( $h < 240 ) {
            $r = 0;
            $g = $x;
            $b = $c;
        } else if ( $h < 300 ) {
            $r = $x;
            $g = 0;
            $b = $c;
        } else {
            $r = $c;
            $g = 0;
            $b = $x;
        }

        $r = ( $r + $m ) * 255;
        $g = ( $g + $m ) * 255;
        $b = ( $b + $m  ) * 255;

        return array( floor( $r ), floor( $g ), floor( $b ) );
    }

    public static function lighten($color, $percent = .05)
    {
        if (strpos($color, '#') !== 0) {
            return false;
        }
        $color = str_replace('#', '', $color);

        list($hr, $hg, $hb) = str_split($color, 2);
        list($h, $s, $l) = self::rgbToHsl(hexdec($hr), hexdec($hg), hexdec($hb));

        $l += $percent;
        if ($l > 1) {
            $l = 1;
        }

        list($r, $g, $b) = self::hslToRgb($h, $s, $l);

        $hr = (string)dechex($r);
        if (strlen($hr) < 2) {
            $hr = '0' . $hr;
        }
        $hg = (string)dechex($g);
        if (strlen($hg) < 2) {
            $hg = '0' . $hg;
        }
        $hb = (string)dechex($b);
        if (strlen($hb) < 2) {
            $hb = '0' . $hb;
        }

        return '#' . $hr . $hg . $hb;
    }

    public static function darken($color, $percent = .05)
    {
        if (strpos($color, '#') !== 0) {
            return false;
        }
        $color = str_replace('#', '', $color);

        list($hr, $hg, $hb) = str_split($color, 2);
        list($h, $s, $l) = self::rgbToHsl(hexdec($hr), hexdec($hg), hexdec($hb));

        $l -= $percent;
        if ($l < 0) {
            $l = 0;
        }

        list($r, $g, $b) = self::hslToRgb($h, $s, $l);

        $hr = (string)dechex($r);
        if (strlen($hr) < 2) {
            $hr = '0' . $hr;
        }
        $hg = (string)dechex($g);
        if (strlen($hg) < 2) {
            $hg = '0' . $hg;
        }
        $hb = (string)dechex($b);
        if (strlen($hb) < 2) {
            $hb = '0' . $hb;
        }

        return '#' . $hr . $hg . $hb;
    }

    public static function opacity($color, $opacity)
    {
        if (strpos($color, '#') !== 0) {
            return false;
        }
        $color = str_replace('#', '', $color);
        list($hr, $hg, $hb) = str_split($color, 2);

        return 'rgba(' . hexdec($hr) . ', ' . hexdec($hg) . ', ' . hexdec($hb) . ', ' . $opacity . ')';
    }

    public static function generateThemeCustomCss($customColor, $siteId = '')
    {
        if (!$siteId) {
            $siteId = SITE_ID;
        }
        $settingId = Option::getSettingId();
        if (!empty($settingId)) {
            $settingId = '_'.$settingId;
        }

        if (strpos($customColor, '#') !== 0 || strlen($customColor) !== 7) {
            throw new Exception(Loc::getMessage('RX_LANDING_OPTION_COLOR_THEME_CUSTOM_INVALID'));
        }

        $templateDir = Config::getTemplateDir();
        $themesDir = $templateDir . '/themes';
        $customThemeDir = $themesDir . '/custom_' . $siteId.$settingId;

        if (Config::isDemoLanding()) {
            $customThemeDir = $themesDir . '/custom_' . $siteId.$settingId . '_' . bitrix_sessid();
        }

        if (!IO\Directory::isDirectoryExists($customThemeDir)) {
            if (!IO\Directory::createDirectory($customThemeDir)) {
                throw new Exception(Loc::getMessage('RX_LANDING_OPTION_COLOR_THEME_CUSTOM_ERROR'));
            }
        }

        $tplCssFile = $themesDir . '/color.css.tpl';
        $customThemeFile = $customThemeDir . '/color.css';

        if (!copy($tplCssFile, $customThemeFile)) {
            throw new Exception(Loc::getMessage('RX_LANDING_OPTION_COLOR_THEME_CUSTOM_ERROR'));
        }

        $customColorHover = self::darken($customColor);
        $color035 = self::opacity($customColor, 0.35);
        Helper::replaceMacrosInFile($customThemeFile, [
            '#COLOR#'       => $customColor,
            '#COLOR_HOVER#' => $customColorHover,
            '#COLOR_035#'   => $color035,
        ]);
    }

    public static function generateThemeCss()
    {
        $colorTheme = Config::getParamInfo('COLOR_THEME');
        $colors = $colorTheme['LIST'];

        $templateDir = Config::getTemplateDir();
        $themesDir = $templateDir . '/themes';

        foreach ($colors as $colorCode => $color) {
            $colorThemeDir = $themesDir . '/' . $colorCode;

            if (!IO\Directory::isDirectoryExists($colorThemeDir)) {
                if (!IO\Directory::createDirectory($colorThemeDir)) {
                    throw new Exception(Loc::getMessage('RX_LANDING_OPTION_COLOR_THEME_CUSTOM_ERROR'));
                }
            }

            $tplCssFile = $themesDir . '/color.css.tpl';
            $colorThemeFile = $colorThemeDir . '/color.css';

            if (!copy($tplCssFile, $colorThemeFile)) {
                throw new Exception(Loc::getMessage('RX_LANDING_OPTION_COLOR_THEME_CUSTOM_ERROR'));
            }

            $colorHover = self::darken($color['COLOR']);
            $color035 = self::opacity($color['COLOR'], 0.35);
            Helper::replaceMacrosInFile($colorThemeFile, [
                '#COLOR#'       => $color['COLOR'],
                '#COLOR_HOVER#' => $colorHover,
                '#COLOR_035#'   => $color035,
            ]);
        }
    }

    public static function generateThemeDevCss()
    {
        if (!defined('RX_LANDING_DEV_COLOR') || !RX_LANDING_DEV_COLOR) {
            return;
        }

        $templateDir = Config::getTemplateDir();
        $themesDir = $templateDir . '/themes';
        $devThemeDir = $themesDir . '/rx_dev/';
        $devThemeFile = $devThemeDir . RX_LANDING_DEV_COLOR . '.css';

        if (!IO\Directory::isDirectoryExists($devThemeDir)) {
            if (!IO\Directory::createDirectory($devThemeDir)) {
                throw new Exception(Loc::getMessage('RX_LANDING_OPTION_COLOR_THEME_CUSTOM_ERROR'));
            }
        }

        $tplCssFile = $themesDir . '/color.css.tpl';
        if (!copy($tplCssFile, $devThemeFile)) {
            throw new Exception(Loc::getMessage('RX_LANDING_OPTION_COLOR_THEME_CUSTOM_ERROR'));
        }

        $colorHover = self::darken('#' . RX_LANDING_DEV_COLOR);
        $color035 = self::opacity('#' . RX_LANDING_DEV_COLOR, 0.35);
        Helper::replaceMacrosInFile($devThemeFile, [
            '#COLOR#'       => '#' . RX_LANDING_DEV_COLOR,
            '#COLOR_HOVER#' => $colorHover,
            '#COLOR_035#'   => $color035,
        ]);
    }
}
