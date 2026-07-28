<?php


namespace Ranx\Landing\Fields;

use \Ranx\Landing\Config;

class IntervalTime
{
    public static function getBlockConfigFieldCode()
    {
        return '_INTERVAL_TIME';
    }

    public static function getInputName()
    {
        return 'INTERVAL_TIME';
    }

    public static function getFromPropertyCode()
    {
        return 'INTERVAL_TIME_FROM';
    }

    public static function getToPropertyCode()
    {
        return 'INTERVAL_TIME_TO';
    }

    public static function getFullPropertyCodes()
    {
        return [
            'PROPERTY_'.self::getFromPropertyCode(),
            'PROPERTY_'.self::getToPropertyCode()
        ];
    }

    public static function isIncludedToBlock($code)
    {
        if (empty($code))
            return false;

        $blockInfo = Config::getBlockInfo($code);
        if (!empty($blockInfo['ELEMENTS_FIELDS'])) {
            return in_array(self::getBlockConfigFieldCode(), $blockInfo['ELEMENTS_FIELDS']);
        }

        return false;
    }

    public static function isIncludedToFieldList($fieldList)
    {
        $fields = self::getFullPropertyCodes();
        foreach ($fields as $field) {
            if (in_array($field, $fieldList)) {
                return true;
            }
        }

        return false;
    }

    protected static function isValidValue($value)
    {
        return isset($value) && is_numeric($value);
    }

    protected static function formatTime($number)
    {
        $result = (string)$number;
        if (strlen($result) == 1) {
            $result = '0'.$result;
        }

        return $result;
    }

    protected static function getTimeFromNumber($value)
    {
        if ($value > 24 * 60)
            $value -= 24 * 60;

        return self::formatTime(intdiv($value, 60)).':'.self::formatTime($value % 60);
    }

    public static function decodeValue($fromValue, $toValue)
    {
        if (!self::isValidValue($fromValue)) $fromValue = $toValue;
        if (!self::isValidValue($toValue)) $toValue = $fromValue;

        if (!self::isValidValue($fromValue) && !self::isValidValue($toValue))
            return '';

        return self::getTimeFromNumber($fromValue).' - '.self::getTimeFromNumber($toValue);
    }

    public static function encodeValue($value)
    {
        $regExp = '/^([0-1-]?[0-9-]|2[0-3-]|):([0-5-][0-9-]|) - ([0-1-]?[0-9-]|2[0-3-]):([0-5-][0-9-])$/';
        $matches = [];
        $result = ['FROM' => false, 'TO' => false];

        if (!preg_match($regExp, $value, $matches)) {
            return $result;
        }

        if (ctype_digit($matches[1]) && ctype_digit($matches[2])) {
            $fromValue = $matches[1] * 60 + $matches[2];
        }
        if (ctype_digit($matches[3]) && ctype_digit($matches[4])) {
            $toValue = $matches[3] * 60 + $matches[4];
        }

        if (!isset($fromValue) && !isset($toValue)) {
            return $result;
        }
        if (!isset($fromValue)) $fromValue = $toValue;
        if (!isset($toValue)) $toValue = $fromValue;

        if ($fromValue > $toValue) {
            $toValue += 24 * 60;
        }

        return [
            'FROM' => $fromValue,
            'TO' => $toValue,
        ];
    }

    public static function prepareFieldToSave(&$data)
    {
        $name = self::getInputName();
        if (!isset($data[$name])) {
            return;
        }

        $decodedValue = $data[$name];
        $encodedValue = self::encodeValue($decodedValue);

        unset($data[$name]);
        $data['PROPERTY_'.self::getFromPropertyCode()] = $encodedValue['FROM'];
        $data['PROPERTY_'.self::getToPropertyCode()] = $encodedValue['TO'];
    }

    public static function getDisplayValue($propValues)
    {
        $fromValue = $propValues[self::getFromPropertyCode()];
        $toValue = $propValues[self::getToPropertyCode()];

        return self::decodeValue($fromValue, $toValue);
    }
}
