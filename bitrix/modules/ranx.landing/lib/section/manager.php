<?php


namespace Ranx\Landing\Section;

use Ranx\Landing\SectionTable;
use Ranx\Landing\Section\Type;
use Bitrix\Main\Localization\Loc;

class Manager
{
    public static function add($arSection, $arOptions = [])
    {
        if (!$GLOBALS['USER']->CanDoOperation('rx_landing_section_edit')) {
            return false;
        }
        $newSection = self::getSectionObject($arSection['TYPE']);
        self::setDefaultOptions($arOptions);

        $newSection->setSiteId($arSection['SITE_ID']);
        $newSection->setTitle($arSection['TITLE']);
        $newSection->setRootMode($arSection['ROOT_MODE']);
        $newSection->setIblockId(0);
        $newSection->setLandingId(0);
        $newSection->setPath($arSection['PATH'], $arOptions['PATH_FORCE_REPLACE']);
        $newSection->setDomain($arSection['DOMAIN'] ?? '');
        $newSection->setOwnSettings($arSection['OWN_SETTINGS'] ?? false);

        return $newSection->save($arOptions);
    }

    public static function update($sectionId, $arSection, $arOptions = [])
    {
        if (empty($sectionId) || !$GLOBALS['USER']->CanDoOperation('rx_landing_section_edit')) {
            return false;
        }

        $type = SectionTable::getByPrimary($sectionId, ['select' => ['TYPE']])->fetch()['TYPE'];
        $section = self::getSectionObject($type, $sectionId);
        self::setDefaultOptions($arOptions);

        foreach ($arSection as $field => $value) {
            switch ($field) {
                case 'SITE_ID':      $section->setSiteId($value); break;
                case 'TITLE':        $section->setTitle($value); break;
                case 'IBLOCK_ID':    $section->setIblockId($value); break;
                case 'LANDING_ID':   $section->setLandingId($value); break;
                case 'PATH':         $section->setPath($value, $arOptions['PATH_FORCE_REPLACE']); break;
                case 'DOMAIN':       $section->setDomain($value ?? ''); break;
                case 'OWN_SETTINGS': $section->setOwnSettings($value ?? false); break;
                case 'ROOT_MODE':    $section->setRootMode($value); break;
            }
        }

        return $section->save($arOptions);
    }

    public static function delete($sectionId, $arOptions = [])
    {
        if (empty($sectionId) || !$GLOBALS['USER']->CanDoOperation('rx_landing_section_edit')) {
            return false;
        }

        $type = SectionTable::getByPrimary($sectionId, ['select' => ['TYPE']])->fetch()['TYPE'];
        $section = self::getSectionObject($type, $sectionId);
        $section->remove();

        return true;
    }

    public static function getByIblockId($iblockId)
    {
        if (empty($iblockId)) {
            return [];
        }

        return SectionTable::getList(['filter' => ['IBLOCK_ID' => $iblockId]])->fetch();
    }

    public static function getElementType($sectionType)
    {
        $types = array_keys(SectionTable::getTypes());
        if (!in_array($sectionType, $types)) {
            return false;
        }

        switch ($sectionType) {
            case SectionTable::TYPE_CATALOG: return 'PRODUCT';
            case SectionTable::TYPE_NEWS:    return 'NEWS';
            default:                         return 'ELEMENT';
        }
    }

    public static function isSectionType($type)
    {
        $sectionTypes = [
            SectionTable::TYPE_SECTION,
            SectionTable::TYPE_CATALOG,
            SectionTable::TYPE_NEWS,
        ];

        return in_array($type, $sectionTypes);
    }

    private static function getSectionObject($type, $sectionId = 0)
    {
        switch ($type) {
            case SectionTable::TYPE_SECTION:     return (new Type\Section($sectionId));
            case SectionTable::TYPE_LANDING:     return (new Type\Landing($sectionId));
            case SectionTable::TYPE_MAIN:        return (new Type\Main($sectionId));
            case SectionTable::TYPE_CATALOG:     return (new Type\Catalog($sectionId));
            case SectionTable::TYPE_NEWS:        return (new Type\News($sectionId));
            case SectionTable::TYPE_SEARCH:      return (new Type\Search($sectionId));
            case SectionTable::TYPE_ORDER:      return (new Type\Order($sectionId));
            default: throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_MANAGER_INCORRECT_TYPE'));
        }
    }

    private static function setDefaultOptions(&$arOptions)
    {
        if (!isset($arOptions)) {
            $arOptions = [];
        }
        if (!isset($arOptions['CREATE_DEFAULT_BLOCKS'])) {
            $arOptions['CREATE_DEFAULT_BLOCKS'] = true;
        }
    }
}
