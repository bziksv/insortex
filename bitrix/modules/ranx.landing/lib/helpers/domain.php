<?php


namespace Ranx\Landing\Helpers;

use Ranx\Landing\SectionTable;


class Domain
{
    public static function format($domain)
    {
        if (empty($domain)) {
            return $domain;
        }

        $domain = mb_strtolower(trim($domain, "/ \n\r\t\v\0"));

        $prefixes = ['https://', 'http://', 'www.'];
        foreach ($prefixes as $prefix) {
            if (mb_strpos($domain, $prefix) === 0) {
                $domain = mb_substr($domain, strlen($prefix));
            }
        }

        $converter = \CBXPunycode::GetConverter();
        return $converter->Encode($domain);
    }

    public static function isUnique($domain)
    {
        if (empty($domain)) {
            return true;
        }
        return !SectionTable::getList(['filter' => ['DOMAIN' => $domain]])->fetch();
    }

    public static function isValid($domain)
    {
        if (empty($domain)) {
            return false;
        }
        return filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
    }
}
