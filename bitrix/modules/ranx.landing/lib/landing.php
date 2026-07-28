<?php

namespace Ranx\Landing;

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Data;
use Bitrix\Main\SiteTable;
use Bitrix\Main\Loader;
use Ranx\Landing\Helpers;
use Ranx\Landing\Block;

/**
 * Class for working with landings
 */
class Landing
{
    const IBLOCK_ELEMENT_SELECT = [
        'ID',
        'IBLOCK_ID',
        'NAME',
        'CODE',
    ];
    const MODE_SECTIONS = 'sections';
    const MODE_ROOT_SECTION = 'root_section';
    const MODE_SECTION = 'section';
    const MODE_ELEMENT = 'element';
    const MODE_ALL = [self::MODE_SECTIONS, self::MODE_ROOT_SECTION, self::MODE_SECTION, self::MODE_ELEMENT];

    /**
     * Return element by code
     *
     * @param string $elementCode
     * @return array|boolean
     */
    public static function getByCode($elementCode, $iblockId, $mode = self::MODE_ELEMENT, $sectionCode = false)
    {
        if (!$elementCode) {
            return false;
        }

        if ($mode = self::MODE_ELEMENT) {
            if ($sectionCode) {
                $sectionId = Helpers\Iblock::getSectionIdByCode($sectionCode, $iblockId);
            }

            $arFilter = [
                'IBLOCK_ID' => $iblockId,
                'CODE' => $elementCode,
                'ACTIVE' => 'Y',
            ];

            if ($sectionId) {
                $arFilter['IBLOCK_SECTION_ID'] = $sectionId;
            }

            $dbRes = \CIBlockElement::GetList(
                [],
                $arFilter,
                false,
                false,
                self::IBLOCK_ELEMENT_SELECT
            );
        } elseif ($mode == self::MODE_SECTION) {
            $dbRes = \CIBlockSection::GetList(
                [],
                [
                    'IBLOCK_ID' => $iblockId,
                    'CODE' => $elementCode,
                    'ACTIVE' => 'Y',
                ]
            );
        } elseif ($mode == self::MODE_SECTIONS || $mode == self::MODE_ROOT_SECTION) {
            $dbRes = \CIBlock::GetList(
                [],
                [
                    'IBLOCK_ID' => $iblockId,
                    'ACTIVE' => 'Y',
                ]
            );
        }

        if ($arRes = $dbRes->Fetch()) {
            return $arRes;
        }

        return false;
    }

    /**
     * Returns landing data by id
     *
     * @param int $id
     * @return array|bool
     */
    public static function getById($id, $mode = self::MODE_ELEMENT)
    {
        if (intval($id) <= 0) {
            return false;
        }

        if ($mode == self::MODE_ELEMENT) {
            $rsElement = \CIBlockElement::GetList([], ['ID' => $id, 'ACTIVE' => 'Y'], false, false, self::IBLOCK_ELEMENT_SELECT);
            if ($arElement = $rsElement->Fetch()) {
                return $arElement;
            }
        } elseif ($mode == self::MODE_SECTION) {
            $rsElement = \CIBlockSection::GetList([], ['ID' => $id, 'ACTIVE' => 'Y']);
            if ($arElement = $rsElement->Fetch()) {
                return $arElement;
            }
        } elseif ($mode == self::MODE_SECTIONS || $mode == self::MODE_ROOT_SECTION) {
            $rsElement = \CIBlock::GetList([], ['ID' => $id, 'ACTIVE' => 'Y']);
            if ($arElement = $rsElement->Fetch()) {
                return $arElement;
            }
        }

        return false;
    }

    public static function getByBlockId($blockId)
    {
        if (!$blockId) {
            return false;
        }

        $block = Block::get($blockId, ['_PROPERTIES']);

        return [
            'ID' => $block['PROPS']['LANDING'],
            'MODE' => $block['PROPS']['MODE'],
        ];
    }

    public static function cleanCache($id, $mode = self::MODE_ELEMENT, $code = '')
    {
        if (intval($id) <= 0 || !in_array($mode, self::MODE_ALL)) {
            return false;
        }

        if (empty($code)) {
            $landing = self::getById($id);
            $code = $landing['CODE'];
        }

        $cacheIds = [
            'ranx_one_landing_' . $mode . '_' . $id,
            'ranx_one_landing_' . $mode . '_' . $code,
        ];

        if (Config::isRegionEnabled()) {

            $regions = Region::getRegions();
            if (Config::useRegionBranches()) {
                $branches = Region::getBranches();
            }

            foreach ($regions as $region) {
                $cacheIds[] = 'ranx_one_landing_' . $mode . '_' . $id . '_' . $region['ID'];
                $cacheIds[] = 'ranx_one_landing_' . $mode . '_' . $code . '_' . $region['ID'];

                if (!empty($branches[$region['ID']])) {
                    foreach ($branches[$region['ID']] as $branch) {
                        $cacheIds[] = 'ranx_one_landing_' . $mode . '_' . $id . '_' . $region['ID'] . '_' . $branch['ID'];
                        $cacheIds[] = 'ranx_one_landing_' . $mode . '_' . $code . '_' . $region['ID'] . '_' . $branch['ID'];
                    }
                }
            }
        }

        $cache = Data\Cache::createInstance();
        foreach ($cacheIds as $cacheId) {
            $cache->clean($cacheId, 'ranx_one_landing');
        }
    }

    public static function clearCacheByBlockId($blockId)
    {
        ['ID' => $id, 'MODE' => $mode] = Landing::getByBlockId($blockId);
        if (empty($id)) {
            throw new Exception(__METHOD__.': failed to get landing');
        }

        Landing::cleanCache($id, $mode);
        return $id;
    }

    public static function detectMode($iblockId)
    {
        $iblock = IblockTable::getById($iblockId)->fetch();
        if (empty($iblock['SECTION_PAGE_URL'])) {
            return false;
        }

        return strpos($iblock['SECTION_PAGE_URL'], '#SECTION_CODE_PATH#') !== false ? self::MODE_SECTIONS : self::MODE_ROOT_SECTION;
    }

    public static function getLink($id, $iblockId, $mode = self::MODE_ELEMENT, $siteId = SITE_ID)
    {
        $arIblock = \CIBlock::GetList([], ['ID' => $iblockId])->GetNext();
        if (empty($arIblock)) {
            throw new \Exception('Landing::getLink: incorrect $iblockId');
        }

        // landing has own domain
        $protocol = Helpers\Helper::getProtocol();
        $arSection = \Ranx\Landing\SectionTable::getList([
            'filter' => ['LANDING_ID' => $id, '!DOMAIN' => false],
            'select' => ['DOMAIN'],
        ])->fetchAll();
        if (!empty($arSection)) {
            $arSection = reset($arSection);
            $domain = \CBXPunycode::GetConverter()->Decode($arSection['DOMAIN']);
            return $protocol.$domain.'/';
        }

        // bitrix multisite by domain
        $arSite = SiteTable::getById($siteId)->fetch();
        $sitePrefix = '';
        if ($arSite['SERVER_NAME'] != $arIblock['SERVER_NAME']) {
            $sitePrefix = $protocol.$arIblock['SERVER_NAME'];
        }

        if ($mode == self::MODE_ELEMENT) {
            $dbItem = \CIBlockElement::GetList([], ['ID' => $id], false, false, ['ID', 'CODE', 'IBLOCK_ID', 'IBLOCK_SECTION_ID']);
            if ($arItem = $dbItem->GetNext()) {
                $arr = array_merge($arIblock, $arItem);
                $url = \CIBlock::ReplaceDetailUrl($arIblock['DETAIL_PAGE_URL'], $arr, false, 'E');
            }
        } elseif ($mode == self::MODE_SECTION) {
            $dbSection = \CIBlockSection::GetList([], ['ID' => $id], false, ['ID', 'CODE', 'IBLOCK_ID']);
            if ($arSection = $dbSection->GetNext()) {
                $arr = array_merge($arIblock, $arSection);
                $url = \CIBlock::ReplaceSectionUrl($arIblock['SECTION_PAGE_URL'], $arr, false, 'S');
            }
        } elseif (in_array($mode, [self::MODE_SECTIONS, self::MODE_ROOT_SECTION])) {
            $url = \CIBlock::ReplaceDetailUrl($arIblock['LIST_PAGE_URL'], $arIblock, false, false);
        }

        if (!empty($url)) {
            return $sitePrefix.$url;
        }

        return false;
    }

    public static function getSessCopy($iblockId, $name = '')
    {
        if (!$name) {
            $name = 'Main';
        }

        $el = new \CIBlockElement();
        $sessid = bitrix_sessid();

        $dbRes = \CIBlockElement::GetList([], [
            'IBLOCK_ID' => $iblockId,
            'CODE' => $sessid,
        ]);
        if ($arRes = $dbRes->Fetch()) {
            return $arRes['ID'];
        }

        Config::enterEditMode();

        return $el->Add([
            'IBLOCK_ID' => $iblockId,
            'CODE' => $sessid,
            'NAME' => $name,
        ]);
    }

    /** agent method */
    public static function removeTempLandings()
    {
        Loader::includeModule('iblock');

        $iblocks = Helpers\Iblock::getLandingListIblocks();
        if (empty($iblocks)) {
            return '\\'.__METHOD__.'();';
        }

        $GLOBALS['USER'] = new \CUser();
        $landings = Helpers\Iblock::getRootElementsFromIblocks(array_column($iblocks, 'ID'));

        foreach ($landings as $landing) {
            // check is this a md5 hash code
            if (strlen($landing['CODE']) === 32 && preg_match('/^[A-Za-z0-9]{32}$/', $landing['CODE'])) {
                Block::deleteByLanding($landing['ID'], self::MODE_ELEMENT);
                Helpers\Iblock::removeElement($landing['ID']);
            }
        }

        unset($GLOBALS['USER']);

        return '\\'.__METHOD__.'();';
    }

    public static function getDevColor($landing, $mode = self::MODE_ELEMENT)
    {
        if ($mode == self::MODE_ELEMENT) {
            $color = \CIBlockElement::GetProperty($landing['IBLOCK_ID'], $landing['ID'], ['sort' => 'asc'], ['CODE' => 'RX_COLOR_THEME'])->Fetch();
            $color['VALUE'] = str_replace('#', '', $color['VALUE']);
            return !empty($color['VALUE']) && strlen($color['VALUE']) == 6 ? $color['VALUE'] : '';
        }

        return '';
    }

    public static function getLinksForSelect()
    {
        $result = [];

        $sections = SectionTable::getList(['order' => ['TITLE' => 'ASC']])->fetchAll();
        foreach ($sections as $section) {
            if (empty($section['IBLOCK_ID'])) {
                continue;
            }

            $result[$section['PATH']] = '[' . $section['SITE_ID'] . '] ' . $section['TITLE'];

            if ($section['MODE_ROOT'] == SectionTable::ROOT_MODE_ELEMENTS) {
                $dbRes = \CIBlockElement::GetList(
                    ['SORT' => 'ASC'],
                    ['IBLOCK_ID' => $section['IBLOCK_ID'], 'ACTIVE' => 'Y', 'SECTION_ID' => false],
                    false,
                    false,
                    ['ID', 'IBLOCK_ID', 'NAME', 'DETAIL_PAGE_URL']
                );
                while ($arRes = $dbRes->GetNext()) {
                    $result[$arRes['DETAIL_PAGE_URL']] = '&nbsp;&nbsp;' . $arRes['NAME'];
                }
            } elseif ($section['MODE_ROOT'] == SectionTable::ROOT_MODE_SECTIONS) {

                $arElements = [];

                $dbRes = \CIBlockElement::GetList(
                    ['SORT' => 'ASC'],
                    ['IBLOCK_ID' => $section['IBLOCK_ID'], 'ACTIVE' => 'Y', '!SECTION_ID' => false],
                    false,
                    false,
                    ['ID', 'IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL']
                );
                while ($arRes = $dbRes->GetNext()) {
                    $arElements[$arRes['IBLOCK_SECTION_ID']][] = $arRes;
                }

                $dbRes = \CIBlockSection::GetList(
                    ['SORT' => 'ASC'],
                    ['IBLOCK_ID' => $section['IBLOCK_ID'], 'ACTIVE' => 'Y', 'SECTION_ID' => false],
                    false,
                    ['ID', 'IBLOCK_ID', 'NAME', 'SECTION_PAGE_URL']
                );
                while ($arRes = $dbRes->GetNext()) {
                    $result[$arRes['SECTION_PAGE_URL']] = '&nbsp;&nbsp;' . $arRes['NAME'];
                    if (!empty($arElements[$arRes['ID']])) {
                        foreach ($arElements[$arRes['ID']] as $arElement) {
                            $result[$arElement['DETAIL_PAGE_URL']] = '&nbsp;&nbsp;&nbsp;&nbsp;' . $arElement['NAME'];
                        }
                    }
                }
            }
        }

        return $result;
    }

    public static function checkDemoAccess($id)
    {
        if ($GLOBALS['USER']->CanDoOperation('rx_landing_settings_edit') || $GLOBALS['USER']->CanDoOperation('rx_landing_block_edit')) {
            return true;
        }
        $landing = self::getById($id);
        if ($landing['CODE'] !== bitrix_sessid()) {
            throw new \Exception('access denied');
        }
        Config::enableDemoLanding();
        return true;
    }

    public static function remove($id, $mode)
    {
        if (!in_array($mode, self::MODE_ALL)) {
            throw new \Exception('Unknown mode: '.$mode);
        }
        if ($mode === self::MODE_ROOT_SECTION || $mode === self::MODE_SECTIONS) {
            throw new \Exception("Can't remove landing with '".$mode."' mode");
        }

        Block::deleteByLanding($id, $mode);
        switch ($mode) {
            case self::MODE_SECTION: Helpers\Iblock::removeSection($id); break;
            case self::MODE_ELEMENT: Helpers\Iblock::removeElement($id); break;
        }

        return true;
    }
}
