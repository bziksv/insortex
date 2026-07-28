<?php


namespace Ranx\Landing\Helpers;


class SiteTemplate
{
    public static function set($siteId)
    {
        if (self::isSet($siteId)) {
            return false;
        }

        return \Bitrix\Main\SiteTemplateTable::add([
            'SITE_ID' => $siteId,
            'SORT' => 0,
            'TEMPLATE' => 'ranx-landing',
        ]);
    }

    public static function isSet($siteId)
    {
        $result = \Bitrix\Main\SiteTemplateTable::getList([
            'filter' => [
                'SITE_ID' => $siteId,
                'CONDITION' => false,
                'TEMPLATE' => 'ranx-landing',
            ],
        ])->fetch();

        return !empty($result);
    }

    public static function updatePathCondition($siteId, $oldPath, $newPath)
    {
        $siteTemplateId = 0;
        if ($oldPath) {
            $arSiteTemplate = \Bitrix\Main\SiteTemplateTable::getList([
                'filter' => [
                    'SITE_ID' => $siteId,
                    'CONDITION' => 'CSite::InDir(\''.$oldPath.'\')',
                ],
            ])->fetch();
            if (!empty($arSiteTemplate['ID'])) {
                $siteTemplateId = $arSiteTemplate['ID'];
            }
        }

        if ($siteTemplateId) {
            $siteTemplateResult = \Bitrix\Main\SiteTemplateTable::update($siteTemplateId, [
                'CONDITION' => 'CSite::InDir(\''.$newPath.'\')',
            ]);
        } else {
            if (self::isSet($siteId)) {
                return true;
            }

            self::removePathCondition($siteId, $newPath);
            $siteTemplateResult = \Bitrix\Main\SiteTemplateTable::add([
                'SITE_ID' => $siteId,
                'CONDITION' => 'CSite::InDir(\''.$newPath.'\')',
                'SORT' => 0,
                'TEMPLATE' => 'ranx-landing',
            ]);
        }

        return $siteTemplateResult->isSuccess();
    }

    public static function removePathCondition($siteId, $path)
    {
        $arSiteTemplate = \Bitrix\Main\SiteTemplateTable::getList([
            'filter' => [
                'SITE_ID' => $siteId,
                'CONDITION' => 'CSite::InDir(\''.$path.'\')',
            ],
        ])->fetch();
        if (!empty($arSiteTemplate['ID'])) {
            $siteTemplateId = $arSiteTemplate['ID'];
        }

        if (empty($siteTemplateId)) {
            return false;
        }

        return \Bitrix\Main\SiteTemplateTable::delete($siteTemplateId);
    }

    public static function addDomainCondition($siteId, $domain)
    {
        if (empty($domain)) {
            return;
        }

        $domains = [$domain, 'www.'.$domain];
        foreach ($domains as $domain) {
            \Bitrix\Main\SiteTemplateTable::add([
                'SITE_ID' => $siteId,
                'CONDITION' => '$_SERVER["HTTP_HOST"] === '.'"'.$domain.'"',
                'SORT' => 0,
                'TEMPLATE' => 'ranx-landing',
            ]);
        }
    }

    public static function removeDomainCondition($siteId, $domain)
    {
        if (empty($domain)) {
            return;
        }

        $domains = [$domain, 'www.'.$domain];
        foreach ($domains as $domain) {
            $arSiteTemplate = \Bitrix\Main\SiteTemplateTable::getList([
                'filter' => [
                    'SITE_ID' => $siteId,
                    'CONDITION' => '$_SERVER["HTTP_HOST"] === '.'"'.$domain.'"',
                ],
            ])->fetch();
            if (empty($arSiteTemplate['ID'])) {
                continue;
            }

            \Bitrix\Main\SiteTemplateTable::delete($arSiteTemplate['ID']);
        }
    }

    public static function updateDomainCondition($siteId, $newDomain, $oldDomain)
    {
        self::removeDomainCondition($siteId, $oldDomain);
        self::addDomainCondition($siteId, $newDomain);
    }
}
