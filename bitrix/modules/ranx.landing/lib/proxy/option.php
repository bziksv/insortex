<?php


namespace Ranx\Landing\Proxy;

use Bitrix\Main\Config\Option as BxOption;

class Option
{
    static $settingId = '';

    public static function get($moduleId, $name, $default = "", $siteId = false)
    {
        if (self::$settingId) {
            $name = self::$settingId.':'.$name;
        }

        return BxOption::get($moduleId, $name, $default, $siteId);
    }

    public static function set($moduleId, $name, $value, $siteId)
    {
        if (self::$settingId) {
            $name = self::$settingId.':'.$name;
        }

        BxOption::set($moduleId, $name, $value, $siteId);
    }

    public static function delete($moduleId, $filter = [])
    {
        if (self::$settingId && $filter['name']) {
            $filter['name'] = self::$settingId.':'.$filter['name'];
        }

        BxOption::delete($moduleId, $filter);
    }

    public static function setSettingId($value)
    {
        self::$settingId = $value;
    }

    public static function getSettingId()
    {
        return self::$settingId;
    }
}
