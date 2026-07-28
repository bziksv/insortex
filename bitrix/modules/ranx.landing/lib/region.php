<?php

namespace Ranx\Landing;

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Ranx\Landing\Helpers;

class Region
{
    const SELECT = [
        'ID',
        'CODE',
        'NAME',
        'IBLOCK_ID',
        'IBLOCK_SECTION_ID',
        'PROPERTY_DEFAULT',
        'PROPERTY_FAVORIT_LOCATION',
        'PROPERTY_PHONES',
        'PROPERTY_PRICES_LINK',
        'PROPERTY_LOCATION_LINK',
        'PROPERTY_STORES_LINK',
        'PROPERTY_REGION_NAME_DECLINE_RP',
        'PROPERTY_REGION_NAME_DECLINE_PP',
        'PROPERTY_REGION_NAME_DECLINE_TP',
        'PROPERTY_SORT_REGION_PRICE',
        'PROPERTY_ADDRESS',
        'PROPERTY_EMAIL',
        'PROPERTY_MAIN_DOMAIN',
        'PROPERTY_DOMAINS',
    ];

    public static function getRegions()
    {
        if (!Config::isRegionEnabled()) {
            return false;
        }
        static $arRegions;

        if ($arRegions !== NULL) {
            return $arRegions;
        }

        if ($regionClass = self::getSolutionClass()) {
            $solutionClass = self::getSolutionClass('');
            $arRegions = $regionClass::getRegions();

            self::formatPhone($arRegions);

            if (method_exists($solutionClass, 'GetFrontParametrValue')) {
                if ($solutionClass::GetFrontParametrValue('REGIONALITY_TYPE') == 'SUBDOMAIN') {
                    self::setUrl($arRegions);
                }
            }

            return $arRegions;
        }

        $iblockId = self::getIblockId();
        if (!$iblockId) {
            return $arRegions;
        }

        Loader::includeModule('iblock');

        $arFilter = ['IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y'];
        $arSelect = self::SELECT;

        // add props started with REGION_TAG_
        $rsProperty = \CIBlockProperty::GetList([], array_merge($arFilter, ['CODE' => 'REGION_TAG_%']));
        while ($arProp = $rsProperty->Fetch()) {
            $arSelect[] = 'PROPERTY_' . $arProp['CODE'];
        }

        $arItems = [];
        $dbRes = \CIBLockElement::GetList(['SORT' => 'ASC', 'NAME' => 'ASC'], $arFilter, false, false, $arSelect);
        while ($obRes = $dbRes->GetNextElement()) {
            $arFields = $obRes->GetFields();
            $arProps = $obRes->GetProperties();

            $arItem = [];
            foreach ($arFields as $code => $value) {
                if (in_array($code, $arSelect)) {
                    $arItem[$code] = $value;
                }
            }

            foreach ($arProps as $code => $arProperty) {
                if (in_array('PROPERTY_'.$code, $arSelect)) {
                    $arItem['PROPERTY_'.$code.'_VALUE'] = $arProperty['~VALUE'];
                    if (isset($arProperty['WITH_DESCRIPTION']) && $arProperty['WITH_DESCRIPTION'] == 'Y') {
                        $arItem['PROPERTY_'.$code.'_DESCRIPTION'] = $arProperty['~DESCRIPTION'];
                    }
                }
            }

            $arItems[$arItem['ID']] = $arItem;
        }

        if (empty($arItems)) {
            return $arRegions;
        }

        if (Config::useRegionBranches()) {
            $regionIds = array_column($arItems, 'ID');
            $branches = self::getBranches($regionIds);
        }

        foreach ($arItems as $key => $arItem) {
            if(!$arItem['PROPERTY_MAIN_DOMAIN'] && $arItem['PROPERTY_DEFAULT_VALUE'] == 'Y')
                $arItems[$key]['PROPERTY_MAIN_DOMAIN'] = $_SERVER['HTTP_HOST'];

            //domains props
            if(!is_array($arItem['PROPERTY_DOMAINS_VALUE']))
                $arItem['PROPERTY_DOMAINS_VALUE'] = (array)$arItem['PROPERTY_DOMAINS_VALUE'];
            $arItems[$key]['LIST_DOMAINS'] = array_merge((array)$arItem['PROPERTY_MAIN_DOMAIN_VALUE'], $arItem['PROPERTY_DOMAINS_VALUE']);
            unset($arItems[$key]['PROPERTY_DOMAINS_VALUE']);
            unset($arItems[$key]['PROPERTY_DOMAINS_VALUE_ID']);

            //email props
            if(!is_array($arItem['PROPERTY_EMAIL_VALUE']))
                $arItems[$key]['PROPERTY_EMAIL_VALUE'] = (array)$arItem['PROPERTY_EMAIL_VALUE'];

            //phones props
            if(!is_array($arItem['PROPERTY_PHONES_VALUE']))
                $arItem['PROPERTY_PHONES_VALUE'] = (array)$arItem['PROPERTY_PHONES_VALUE'];
            $arItems[$key]['PHONES'] = $arItem['PROPERTY_PHONES_VALUE'];
            unset($arItems[$key]['PROPERTY_PHONES_VALUE']);
            unset($arItems[$key]['PROPERTY_PHONES_VALUE_ID']);

            if (!empty($branches[$arItem['ID']])) {
                $arItems[$key]['BRANCHES'] = $branches[$arItem['ID']];
            }
        }
        $arRegions = $arItems;

        if (Config::isRegionSubdomains()) {
            self::setUrl($arRegions);
        }

        return $arRegions;
    }

    private static function formatPhone(&$arRegions)
    {
        foreach ($arRegions as $key => $arRegion) {
            if (empty($arRegion['PHONES']) || !is_array($arRegion['PHONES'])) {
                $arRegions[$key]['PHONES'] = [];
                continue;
            }

            $phones = [];
            foreach ($arRegion['PHONES'] as $arPhone) {
                if (!is_array($arPhone)) {
                    $phones[] = $arPhone;
                }
                elseif (array_key_exists('PHONE', $arPhone)) {
                    $phones[] = $arPhone['PHONE'];
                }
            }
            $arRegions[$key]['PHONES'] = $phones;
        }
    }

    private static function setUrl(&$arRegions)
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $protocol = $request->isHttps() ? 'https://' : 'http://';
        $uri = !$request->isAjaxRequest() ? $request->getRequestUri() : '';

        foreach ($arRegions as &$arRegion) {
            $arRegion['URL'] = '';
            if (!empty($arRegion['LIST_DOMAINS'])) {
                if (mb_substr_count($_SERVER['HTTP_HOST'], '.') > 1) {
                    $host = mb_substr($_SERVER['HTTP_HOST'], mb_strpos($_SERVER['HTTP_HOST'], '.') + 1);
                } else {
                    $host = $_SERVER['HTTP_HOST'];
                }
                $isDomainFound = false;

                foreach ($arRegion['LIST_DOMAINS'] as $domain) {
                    $domain = \CBXPunycode::GetConverter()->Encode($domain);
                    if (mb_strpos($domain, $host) !== false) {
                        $arRegion['URL'] = $protocol . $domain . $uri;
                        $isDomainFound = true;
                    }
                }

                if (!$isDomainFound && !empty($arRegion['PROPERTY_MAIN_DOMAIN_VALUE'])) {
                    $arRegion['URL'] = $protocol . $arRegion['PROPERTY_MAIN_DOMAIN_VALUE'] . $uri;
                }
            }
        }
    }

    private static function formatRegionFromSolution($arSolutionRegion)
    {
        $arRegions = self::getRegions();
        foreach ($arRegions as $arRegion) {
            if ($arRegion['ID'] == $arSolutionRegion['ID']) {
                return $arRegion;
            }
        }

        return $arSolutionRegion;
    }

    public static function getCurrent()
    {
        if (!Config::isRegionEnabled()) {
            return false;
        }
        static $curRegion;

        if ($curRegion !== NULL) {
            return $curRegion;
        }

        if ($regionClass = self::getSolutionClass()) {
            return $curRegion = self::formatRegionFromSolution($regionClass::getCurrentRegion());
        }

        $arRegions = self::getRegions() ?? [];
        if (Config::isRegionSubdomains()) {
            foreach ($arRegions as $arRegion) {
                if (empty($arRegion['LIST_DOMAINS'])){
                    continue;
                }

                $converter = \CBXPunycode::GetConverter();
                $host = $_SERVER['SERVER_NAME'] ? $converter->Encode($_SERVER['SERVER_NAME']) : $converter->Encode($_SERVER['HTTP_HOST']);

                if (in_array($host, $arRegion['LIST_DOMAINS'])) {
                    return $arRegion;
                }
            }
        }

        // set from cookie
        if (!empty($_COOKIE['current_region']) && !empty($arRegions[$_COOKIE['current_region']])) {
            return $curRegion = $arRegions[$_COOKIE['current_region']];
        }

        // default
        foreach ($arRegions as $arRegion) {
            if ($arRegion['PROPERTY_DEFAULT_VALUE'] === 'Y') {
                return $curRegion = $arRegion;
            }
        }

        // no default, set to the first one
        return $curRegion = reset($arRegions) ?: false;
    }

    public static function getCurrentBranch()
    {
        if (!Config::isRegionEnabled() || !Config::useRegionBranches()) {
            return false;
        }
        static $curBranch;

        if ($curBranch !== NULL) {
            return $curBranch;
        }

        $arRegions = self::getRegions() ?? [];
        $curRegion = self::getCurrent();

        if (empty($curRegion['BRANCHES'])) {
            return $curBranch = false;
        }

        $arBranches = $curRegion['BRANCHES'];

        // set from cookie
        if (!empty($_COOKIE['current_branch']) && !empty($arBranches[$_COOKIE['current_branch']])) {
            return $curBranch = $arBranches[$_COOKIE['current_branch']];
        }

        // default
        foreach ($arBranches as $arBranch) {
            if ($arBranch['IS_DEFAULT']) {
                return $curBranch = $arBranch;
            }
        }

        // no default, set to the first one
        return $curBranch = reset($arBranches) ?: false;
    }

    public static function getCurrentSections()
    {
        if (!Config::isRegionEnabled()) {
            return false;
        }
        static $curSections;

        if ($curSections !== NULL) {
            return $curSections;
        }

        $current = self::getCurrent();
        $dbRes = \CIBlockElement::GetElementGroups($current['ID'], true, ['ID', 'NAME']);
        while ($arRes = $dbRes->Fetch()) {
            $curSections[] = $arRes;
        }

        return $curSections;
    }

    public static function getRegionByIp()
    {
        if (!Config::isRegionEnabled()) {
            return false;
        }
        static $curRegion;

        if ($curRegion !== NULL) {
            return $curRegion;
        }

        if ($regionClass = self::getSolutionClass()) {
            return $curRegion = self::formatRegionFromSolution($regionClass::getRealRegionByIP());
        }

        $curRegion = false;
        $arRegions = self::getRegions();

        if (empty($arRegions)) {
            return $curRegion;
        }

        $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }

        $city = false;
        if (class_exists('\Bitrix\Main\Service\GeoIp\Manager')) {
            $result = \Bitrix\Main\Service\GeoIp\Manager::getDataResult($ip, 'ru');
            if($result && $result->isSuccess()){
                if($geoData = $result->getGeoData()){
                    $_SESSION['GEOIP'] = get_object_vars($geoData);
                    $city = isset($_SESSION['GEOIP']['cityName']) && $_SESSION['GEOIP']['cityName'] ? $_SESSION['GEOIP']['cityName'] : '';
                }
            }
        }

        if ($city) {
            foreach ($arRegions as $arRegion) {
                if ($city === $arRegion['NAME']) {
                    $curRegion = $arRegion;
                }
            }
        }

        return $curRegion;
    }

    public static function replaceMacros($str)
    {
        if (!$str || !Config::isRegionEnabled()) {
            return $str;
        }

        $arRegion = self::getCurrent();
        if (!empty($arRegion)) {
            // replace declines
            $str = str_replace(
                [
                    '#REGION_NAME#',
                    '#REGION_NAME_DECLINE_PP#',
                    '#REGION_NAME_DECLINE_RP#',
                    '#REGION_NAME_DECLINE_TP#',
                ],
                [
                    $arRegion['NAME'],
                    $arRegion['PROPERTY_REGION_NAME_DECLINE_PP_VALUE'],
                    $arRegion['PROPERTY_REGION_NAME_DECLINE_RP_VALUE'],
                    $arRegion['PROPERTY_REGION_NAME_DECLINE_TP_VALUE'],
                ],
                $str
            );
            // replace REGION_TAG
            foreach ($arRegion as $key => $val) {
                if (strpos($key, 'PROPERTY_REGION_TAG_') === 0) {
                    $regionTag = substr($key, 20, -6);

                    $str = str_replace('#REGION_TAG_'.$regionTag.'#', $val, $str);
                }
            }
        }

        return $str;
    }

    public static function search($query, $includeSections = true)
    {
        if (mb_strlen($query) < 2) {
            return [];
        }

        $iblockId = self::getIblockId();

        if (!$iblockId) {
            return [];
        }

        $result = [];

        $dbRes = \CIBlockElement::GetList(['NAME' => 'ASC'], ['NAME' => $query . '%', 'IBLOCK_ID' => $iblockId], false, ['nTopCount' => 5], ['ID', 'NAME']);
        while ($arRes = $dbRes->Fetch()) {
            $result[] = [
                'id' => 'E' . $arRes['ID'],
                'label' => $arRes['NAME'],
                'value' => $arRes['NAME'],
            ];
        }

        if ($includeSections) {
            $dbRes = \CIBlockSection::GetList(['NAME' => 'ASC'], ['NAME' => $query . '%', 'IBLOCK_ID' => $iblockId], ['nTopCount' => 5], ['ID', 'NAME']);
            while ($arRes = $dbRes->Fetch()) {
                $result[] = [
                    'id' => 'S' . $arRes['ID'],
                    'label' => $arRes['NAME'],
                    'value' => $arRes['NAME'],
                ];
            }
        }

        return $result;
    }

    public static function searchBranches($query)
    {
        if (mb_strlen($query) < 2) {
            return [];
        }

        $iblockId = self::getBranchIblockId();

        if (!$iblockId) {
            return [];
        }

        $result = [];

        $dbRes = \CIBlockElement::GetList(['NAME' => 'ASC'], ['NAME' => $query . '%', 'IBLOCK_ID' => $iblockId], false, ['nTopCount' => 5], ['ID', 'NAME']);
        while ($arRes = $dbRes->Fetch()) {
            $result[] = [
                'id' => $arRes['ID'],
                'label' => $arRes['NAME'],
                'value' => $arRes['NAME'],
            ];
        }

        return $result;
    }

    public static function getRegionsById($ids)
    {
        $sectionIds = [];
        $elementIds = [];

        foreach ($ids as $id) {
            if (strpos($id, 'S') === 0) {
                $sectionIds[] = substr($id, 1);
            }
            if (strpos($id, 'E') === 0) {
                $elementIds[] = substr($id, 1);
            }
        }

        $iblockId = self::getIblockId();
        if (!$iblockId || empty($sectionIds) && empty($elementIds)) {
            return [];
        }

        $regions = [];

        if (!empty($sectionIds)) {
            $dbRes = \CIBlockSection::GetList(['NAME' => 'ASC'], ['ID' => $sectionIds, 'IBLOCK_ID' => $iblockId], false, ['ID', 'NAME']);
            while ($arRes = $dbRes->Fetch()) {
                $regions[] = [
                    'ID' => 'S' . $arRes['ID'],
                    'NAME' => $arRes['NAME'],
                ];
            }
        }

        if (!empty($elementIds)) {
            $dbRes = \CIBlockElement::GetList(['NAME' => 'ASC'], ['ID' => $elementIds, 'IBLOCK_ID' => $iblockId], false, false, ['ID', 'NAME']);
            while ($arRes = $dbRes->Fetch()) {
                $regions[] = [
                    'ID' => 'E' . $arRes['ID'],
                    'NAME' => $arRes['NAME'],
                ];
            }
        }

        return $regions;
    }

    public static function getBranchesById($ids)
    {
        $iblockId = self::getBranchIblockId();
        $ids = array_filter($ids);
        if (!$iblockId || empty($ids)) {
            return [];
        }

        $branches = [];

        $dbRes = \CIBlockElement::GetList(['NAME' => 'ASC'], ['ID' => $ids, 'IBLOCK_ID' => $iblockId], false, false, ['ID', 'NAME']);
        while ($arRes = $dbRes->Fetch()) {
            $branches[] = [
                'ID' => $arRes['ID'],
                'NAME' => $arRes['NAME'],
            ];
        }

        return $branches;
    }

    public static function getBranches($ids = [])
    {
        if (!Config::isRegionEnabled() || !Config::useRegionBranches()) {
            return false;
        }
        static $arBranches;

        if ($arBranches === NULL) {
            $arBranches = [];

            if (empty($ids)) {
                $regions = self::getRegions();
                $ids = array_column($regions, 'ID');
            }

            $dbRes = \CIBlockElement::GetList(
                ['SORT' => 'ASC', 'ID' => 'ASC'],
                ['IBLOCK_ID' => self::getBranchIblockId(), 'PROPERTY_REGION' => $ids, 'ACTIVE' => 'Y'],
                false,
                false,
                ['ID', 'NAME', 'IBLOCK_ID']
            );
            while ($obRes = $dbRes->GetNextElement()) {
                $arRes = $obRes->GetFields();
                $arProps = $obRes->GetProperties();

                $arRes['IS_DEFAULT'] = $arProps['DEFAULT']['VALUE'] === 'Y';
                $arRes['PHONES'] = $arProps['PHONES']['VALUE'];
                $arRes['PHONES_DESC'] = $arProps['PHONES']['DESCRIPTION'];
                $arRes['EMAIL'] = $arProps['EMAIL']['VALUE'];
                $arRes['ADDRESS'] = $arProps['ADDRESS']['VALUE'];

                $arBranches[$arProps['REGION']['VALUE']][$arRes['ID']] = $arRes;
            }
        }

        return $arBranches;
    }

    public static function getCurrentBranches()
    {
        if (!Config::isRegionEnabled() || !Config::useRegionBranches()) {
            return false;
        }
        static $arBranches;

        if ($arBranches === NULL) {
            $arBranches = [];

            $curRegion = self::getCurrent();
            if (empty($curRegion['ID'])) {
                return $arBranches = false;
            }

            $dbRes = \CIBlockElement::GetList(
                ['SORT' => 'ASC', 'ID' => 'ASC'],
                ['IBLOCK_ID' => self::getBranchIblockId(), 'PROPERTY_REGION' => $curRegion['ID'], 'ACTIVE' => 'Y'],
                false,
                false,
                ['ID', 'NAME', 'IBLOCK_ID']
            );
            while ($obRes = $dbRes->GetNextElement()) {
                $arRes = $obRes->GetFields();
                $arProps = $obRes->GetProperties();

                $arRes['IS_DEFAULT'] = $arProps['DEFAULT']['VALUE'] === 'Y';
                $arRes['PHONES'] = $arProps['PHONES']['VALUE'];
                $arRes['PHONES_DESC'] = $arProps['PHONES']['DESCRIPTION'];
                $arRes['EMAIL'] = $arProps['EMAIL']['VALUE'];
                $arRes['ADDRESS'] = $arProps['ADDRESS']['VALUE'];

                $arBranches[$arRes['ID']] = $arRes;
            }
        }

        return $arBranches;
    }

    private static function getSolutionClass($classPostfix = 'Regionality')
    {
        if (!Config::isRegionEnabled()) {
            return false;
        }
        $solution = Config::getSolution();
        if (!$solution || !Loader::includeModule($solution)) {
            return false;
        }

        if (strpos($solution, 'aspro.') === 0) {
            $code = substr($solution, strpos($solution, '.') + 1);

            $class = 'C' . ucfirst($code) . $classPostfix;

            if (class_exists($class)) {
                return $class;
            }
        }

        return false;
    }

    private static function getIblockId()
    {
        static $regionIblockId;

        if ($regionIblockId === NULL) {
            if (($regionClass = self::getSolutionClass()) && method_exists($regionClass, 'getRegionIBlockID')) {
                $regionIblockId = $regionClass::getRegionIBlockID();
            } elseif (!empty(self::getRegionIblockId())) {
                $regionIblockId = self::getRegionIblockId();
            }
        }

        return $regionIblockId;
    }

    private static function getBranchIblockId()
    {
        return Helpers\Iblock::getIblockIdByCode('ranx_landing_branches');
    }

    private static function getRegionIblockId()
    {
        return Helpers\Iblock::getIblockIdByCode('ranx_landing_regions');
    }
}
