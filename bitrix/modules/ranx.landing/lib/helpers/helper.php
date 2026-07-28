<?php

namespace Ranx\Landing\Helpers;

use Ranx\Landing\Config;
use Bitrix\Main\Localization\Loc;

class Helper
{
    public static function svg($name, $hash = 'main')
    {
        return '<svg class="svg"><use xlink:href="'.self::svgPath($name, $hash).'"></use></svg>';
    }

    public static function svgPath($name, $hash = 'main')
    {
        // update unix epoch if some svg has been updated
        return Config::getTemplatePath().'/assets/img/'.$name.'.svg?1675149157'.($hash ? '#' . $hash : '');
    }

    public static function isSvg($mimeType)
    {
        return in_array(strtolower(trim($mimeType)), ['text/plain', 'image/svg+xml']);
    }

    public static function isImage($mimeType)
    {
        [$type, $subtype] = explode('/', $mimeType);
        return trim($type) == 'image';
    }

    public static function onlyDigits($str)
    {
        return preg_replace('/\D/', '', $str);
    }

    public static function phone($str)
    {
        $digits = self::onlyDigits($str);
        return strpos($digits, '7') === 0 ? '+' . $digits : $digits;
    }

    public static function money($num, $currency = false)
    {
        if (!$num) {
            return '';
        }
        // for not numeric just return back
        if (!is_numeric($num)) {
            return $num;
        }
        if ($currency === false) {
            $currency = Config::getCurrency();
        }
        return number_format(floatval($num), (strpos($num, '.') !== false ? 2 : 0), '.', ' ') . ($currency ? ' ' . $currency : '');
    }

    public static function getTotalPrice($price, $discount)
    {
        if (!$price || !is_numeric($price)) {
            $price = 0;
        }
        if (!$discount) {
            $discount = 0;
        }
        $isPercentType = strpos($discount, '%') !== false;
        $discount = floatval($discount);

        if ($isPercentType) {
            $discountMoney = $price * ($discount / 100);
        } else {
            $discountMoney = $discount;
        }

        $totalPrice = round($price - $discountMoney);

        return  $totalPrice ?: Loc::getMessage('RX_LANDING_LIB_HELPERS_HELPER_FREE');
    }

    public static function calcPrice($price, $discount)
    {
        if (!$price || !is_numeric($price)) {
            $price = 0;
        }
        if (!$discount) {
            $discount = 0;
        }
        $isPercentType = strpos($discount, '%') !== false;
        $discount = floatval($discount);

        if ($isPercentType) {
            $discountMoney = $price * ($discount / 100);
            $percent = $discount;
        } else {
            $discountMoney = $discount;
            $percent = $price ? intval($discount / $price * 100) : 0;
        }

        if ($discountMoney > $price) {
            $discountMoney = $price;
        }
        if ($percent > 100) {
            $percent = 100;
        }

        $totalPrice = round($price - $discountMoney);

        return  [
            'PRICE' => $totalPrice ?: Loc::getMessage('RX_LANDING_LIB_HELPERS_HELPER_FREE'),
            'OLD_PRICE' => $price != $totalPrice ? $price : 0,
            'DISCOUNT_PRICE' => $discountMoney,
            'DISCOUNT_PERCENT' => $percent . '%',
        ];
    }

    public static function cutName($name, $length = 30)
    {
        if (mb_strlen($name) > $length) {
            $name = mb_substr($name, 0, $length - 3) . '...';
        }
        return htmlspecialcharsbx($name);
    }

    public static function replaceMacrosInFile($file, $macros)
    {
        if (!file_exists($file)) {
            return false;
        }
        $fileData = file_get_contents($file);
        foreach ($macros as $macrosKey => $macrosValue) {
            $fileData = str_replace($macrosKey, $macrosValue, $fileData);
        }
        file_put_contents($file, $fileData);
    }

    public static function getDataByUrl($url)
    {
        $response = '';

        if (function_exists('curl_init')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
        }

        if (empty($response)) {
            $response = file_get_contents($url);
        }

        return $response;
    }

    public static function compareVersion($v1, $v2)
    {
        $arr1 = explode('.', $v1, 3);
        $arr2 = explode('.', $v2, 3);
        for ($i = 0; $i < 3; $i++) {
            if ($arr1[$i] == $arr2[$i]) {
                continue;
            }
            return (int)$arr2[$i] - (int)$arr1[$i];
        }

        return 0;
    }

    public static function getProtocol()
    {
        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        return $request->isHttps() ? 'https://' : 'http://';
    }

    public static function isJson($data, &$result)
    {
        $result = json_decode($data, true);
        return json_last_error() == JSON_ERROR_NONE;
    }

    public static function formatAsproPhone($value)
    {
        $arPhone = [];
        $isJson = self::isJson($value, $arPhone);
        if ($isJson && is_array($arPhone) && array_key_exists('PHONE', $arPhone)) {
            return $arPhone['PHONE'];
        }

        return $value;
    }
}
