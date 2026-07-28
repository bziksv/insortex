<?php


namespace Ranx\Landing\Fields;


use Bitrix\Main\Localization\Loc;

class Years
{
    public static function getDisplayValue($values)
    {
        if (empty($values) || !is_array($values)) {
            $values = (array)$values;
        }
        $values = array_filter($values, function ($v) {
            return !empty($v) && is_numeric($v) && $v > 0;
        });

        if (empty($values)) {
            return '';
        }

        sort($values, SORT_NUMERIC);

        $intervals = [];
        $left = false;
        $right = false;
        foreach ($values as $value) {
            if ($right != false) {
                if ($right + 1 == $value) {
                    $right = $value;
                    continue;
                }
                $intervals[] = self::getIntervalToString($left, $right);
            }
            $left = $right = $value;
        }
        $intervals[] = self::getIntervalToString($left, $right);

        return Loc::getMessage('RX_LANDING_LIB_FIELDS_YEARS_NAME', [
            '#INTERVAL#' => implode(', ', $intervals)
        ]);
    }

    protected static function getIntervalToString($leftValue, $rightValue)
    {
        if ($leftValue == $rightValue) {
            return $rightValue;
        }

        return $leftValue.'-'.$rightValue;
    }
}
