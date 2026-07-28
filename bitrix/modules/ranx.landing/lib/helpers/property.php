<?php


namespace Ranx\Landing\Helpers;


class Property
{
    const PREFIX = 'PROPERTY_';

    public static function checkByFullCode($fieldCode)
    {
        return strpos($fieldCode, self::PREFIX) === 0;
    }

    public static function getShortCodeByFull($code)
    {
        if (self::checkByFullCode($code)) {
            return substr($code, strlen(self::PREFIX));
        }

        return false;
    }

    public static function getFullCodeByShort($code)
    {
        return self::PREFIX.$code;
    }

    public static function isString($type)
    {
        return $type == 'S';
    }

    public static function isList($type)
    {
        return $type == 'L';
    }

    public static function isNumber($type)
    {
        return $type == 'N';
    }
}
