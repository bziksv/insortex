<?php

namespace Ranx\Landing\Helpers;

use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\IblockSiteTable;
use Bitrix\Iblock\IblockGroupTable;
use Bitrix\Iblock\IblockFieldTable;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Iblock\IblockMessageTable;
use Bitrix\Iblock\TypeTable;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\SectionTable;
use Ranx\Landing\Cache;
use Ranx\Landing\Config;
use Bitrix\Catalog\CatalogIblockTable;

class Iblock
{
    public const TYPE = 'ranx_landing';
    static $translitParams = [
        'replace_space' => '-',
        'replace_other' => '-',
    ];

    /**
     * Checks if ranx_landing iblock type exists or not
     */
    public static function isModuleTypeExists()
    {
        $type = TypeTable::getById(self::TYPE)->fetch();

        return !empty($type);
    }

    public static function getIblockById($id)
    {
        return IblockTable::getList([
            'filter' => [
                'IBLOCK_TYPE_ID' => self::TYPE,
                'ID' => $id,
            ],
        ])->fetch() ?? [];
    }

    public static function getIblockByCode($code)
    {
        return IblockTable::getList([
            'filter' => [
                'IBLOCK_TYPE_ID' => self::TYPE,
                'CODE' => $code
            ],
        ])->fetch() ?? [];
    }

    public static function getIblockIdByCode($code, $siteId = SITE_ID)
    {
        $iblockId = Cache::$arIBlocks[$siteId][self::TYPE][$code][0];

        $isAdminSection = \Bitrix\Main\Context::getCurrent()->getRequest()->isAdminSection();
        $isCli = defined('RX_CLI') && RX_CLI;
        if (empty($iblockId) && ($isAdminSection || $isCli)) {
            $arIblock = self::getIblockByCode($code);
            if (!empty($arIblock)) {
                $iblockId = $arIblock['ID'];
            }
        }

        if (!isset($iblockId) || intval($iblockId) < 1) {
            throw new \Exception('Failed to get iblock id: '.$code);
        }

        return $iblockId;
    }

    public static function getPropXmlIdByEnumId($enumId)
    {
        $prop = \CIBlockPropertyEnum::GetByID($enumId);
        return $prop['XML_ID'] ?? false;
    }

    public static function getListPropValues($propCode, $iblockId)
    {
        $dbRes = \CIBlockPropertyEnum::GetList(
            ['DEF' => 'DESC', 'SORT' => 'ASC'],
            ['IBLOCK_ID' => $iblockId, 'CODE' => $propCode]
        );

        $result = [];
        while ($arRes = $dbRes->Fetch()) {
            $result[] = [
                'ID' => $arRes['ID'],
                'XML_ID' => $arRes['XML_ID'],
                'VALUE' => $arRes['VALUE'],
                'CODE' => $arRes['PROPERTY_CODE'],
            ];
        }

        return $result;
    }

    public static function getMultiplePropValue($id, $iblockId, $code)
    {
        $values = [];
        $resDB = \CIBlockElement::GetProperty($iblockId, $id, ['SORT' => 'ASC'], ['CODE' => $code]);
        while ($ob = $resDB->GetNext())
        {
            if (!isset($ob['VALUE'])) {
                continue;
            }

            $values[] = $ob['VALUE'];
        }

        return $values;
    }

    public static function getPropValue($id, $iblockId, $code)
    {
        $dbProperty = \CIBlockElement::GetProperty($iblockId, $id, [], ['CODE' => $code]);
        if ($arProperty = $dbProperty->Fetch()) {
            return $arProperty['VALUE'];
        }

        return false;
    }

    public static function getListPropValuesByXmlId($propCode, $iblockId)
    {
        $values = self::getListPropValues($propCode, $iblockId);

        $result = [];
        foreach ($values as $value) {
            $result[$value['XML_ID']] = $value['ID'];
        }

        return $result;
    }

    public static function getListPropValuesByValue($propCode, $iblockId)
    {
        $values = self::getListPropValues($propCode, $iblockId);

        $result = [];
        foreach ($values as $value) {
            $result[$value['VALUE']] = $value['ID'];
        }

        return $result;
    }

    public static function getListPropDefaultValue($propCode, $iblockId)
    {
        $dbRes = \CIBlockPropertyEnum::GetList(
            [],
            ['IBLOCK_ID' => $iblockId, 'CODE' => $propCode, 'DEF' => 'Y']
        );
        if ($arRes = $dbRes->Fetch()) {
            return $arRes;
        }

        return false;
    }

    public static function getIblockIdByElementId($elementId)
    {
        $arRes = \CIBlockElement::GetByID($elementId)->Fetch();
        return $arRes['IBLOCK_ID'] ?? 0;
    }

    public static function getElementNameFromIblock($iblockId)
    {
        $arIblockMsg = IblockMessageTable::getList([
            'filter' => ['IBLOCK_ID' => $iblockId, 'MESSAGE_ID' => 'ELEMENT_NAME'],
        ])->fetch();
        return $arIblockMsg['MESSAGE_TEXT'] ?? '';
    }

    public static function getPropsInfoByCode($codes, $iblockId)
    {
        $props = PropertyTable::getList([
            'filter' => [
                'CODE' => $codes,
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $iblockId,
            ],
        ])->fetchAll();

        $res = [];
        foreach ($props as $prop) {
            $res[$prop['CODE']] = $prop;
        }

        return $res;
    }

    public static function getFormsForSelect()
    {
        $result = [];

        $iblocks = IblockTable::getList([
            'order' => [
                'SORT' => 'ASC',
                'NAME' => 'ASC',
            ],
            'filter' => [
                'ACTIVE' => 'Y',
                'CODE' => ['ranx_landing_form_%', 'ranx_landing_service'],
            ],
        ])->fetchAll();

        foreach ($iblocks as $iblock) {
            $result[$iblock['CODE']] = trim(str_replace([Loc::getMessage('RX_LANDING_LIB_HELPERS_IBLOCK_FORM_BEGIN'), 'Form:'], '', $iblock['NAME']));
        }

        return $result;
    }

    /**
     * Just basic info about iblock element
     *
     * @return array
     */
    public static function getElementsInfoByIds($ids)
    {
        if (empty($ids) || !is_array($ids)) {
            return [];
        }

        $result = [];

        $dbElements = \CIBlockElement::GetList(
            [
                'SORT' => 'ASC',
                'ID' => 'ASC',
            ],
            [
                'ID' => $ids,
            ],
            false,
            false,
            [
                'ID',
                'IBLOCK_ID',
                'IBLOCK_CODE',
                'NAME',
                'SORT',
                'ACTIVE',
            ]
        );
        while ($arElement = $dbElements->Fetch()) {
            $result[$arElement['ID']] = $arElement;
        }

        return $result;
    }

    public static function createLandingListIblock($id, $section)
    {
        $title = $section['TITLE'];
        $hasSection = $section['ROOT_MODE'] == SectionTable::ROOT_MODE_SECTIONS;
        $path = trim($section['PATH'], '/ ');

        $isSectionType = \Ranx\Landing\Section\Manager::isSectionType($section['TYPE']);
        $isLandingWithoutDomain = $section['TYPE'] == SectionTable::TYPE_LANDING && empty($section['DOMAIN']);

        if (!$title) {
            $title = Loc::getMessage('RX_LANDING_LIB_HELPERS_IBLOCK_LANDING_IBLOCK_TITLE') . $id;
        }

        $sectionPageUrl = '#SITE_DIR#' . $path . '/';
        if ($hasSection) {
            $sectionPageUrl = '#SITE_DIR#' . $path . '#SECTION_CODE_PATH#/';
        }

        $detailPageUrl = '#SITE_DIR#' . $path . '/#ELEMENT_CODE#/';
        if ($hasSection) {
            $sectionPageUrl = '#SITE_DIR#' . $path . '/#SECTION_CODE_PATH#/#ELEMENT_CODE#/';
        }

        $addRes = IblockTable::add([
            'IBLOCK_TYPE_ID'   => 'ranx_landing',
            'ACTIVE'           => 'Y',
            'LID'              => $section['SITE_ID'],
            'SORT'             => 1,
            'NAME'             => $title,
            'CODE'             => 'ranx_landing_list_' . $id,
            'LIST_PAGE_URL'    => '#SITE_DIR#' . $path . '/',
            'SECTION_PAGE_URL' => $sectionPageUrl,
            'DETAIL_PAGE_URL'  => $detailPageUrl,
            'LIST_MODE'        => IblockTable::LIST_MODE_COMBINED,
            'INDEX_ELEMENT'    => $isSectionType || $isLandingWithoutDomain ? 'Y' : 'N',
            'INDEX_SECTION'    => $hasSection ? 'Y' : 'N',
        ]);

        $iblockId = $addRes->getId();
        if (!$iblockId) {
            return false;
        }

        // add sites
        IblockSiteTable::add([
            'IBLOCK_ID' => $iblockId,
            'SITE_ID'   => $section['SITE_ID'],
        ]);
        // for backward compatibility
        $ib = new \CIBlock;
        $ib->Update($iblockId, ['LID' => $section['SITE_ID']]);

        // add rights
        IblockGroupTable::add([
            'IBLOCK_ID'  => $iblockId,
            'GROUP_ID'   => 2, // all users
            'PERMISSION' => 'R',
        ]);

        
        if ($landingEditorGroupId = Config::getEditorGroupId()) {
            IblockGroupTable::add([
                'IBLOCK_ID'  => $iblockId,
                'GROUP_ID'   => $landingEditorGroupId,
                'PERMISSION' => 'X',
            ]);
        }

        // change iblock code generation
        $codeField = IblockFieldTable::getList([
            'filter' => [
                'IBLOCK_ID' => $iblockId,
                'FIELD_ID'  => 'CODE',
            ],
        ])->fetch();
        if ($codeField) {

            $arDefaultValue = unserialize($codeField['DEFAULT_VALUE']);
            $arDefaultValue['UNIQUE'] = 'Y';
            $arDefaultValue['TRANSLITERATION'] = 'Y';

            IblockFieldTable::update(
                [
                    'IBLOCK_ID' => $codeField['IBLOCK_ID'],
                    'FIELD_ID'  => $codeField['FIELD_ID'],
                ],
                [
                    'IS_REQUIRED'   => 'Y',
                    'DEFAULT_VALUE' => serialize($arDefaultValue),
                ]
            );
        } else {
            $arDefaultValue = [
                'UNIQUE'          => 'Y',
                'TRANSLITERATION' => 'Y',
                'TRANS_LEN'       => 100,
                'TRANS_CASE'      => 'L',
                'TRANS_SPACE'     => '-',
                'TRANS_OTHER'     => '-',
                'TRANS_EAT'       => 'Y',
                'USE_GOOGLE'      => 'N',
            ];

            IblockFieldTable::add(
                [
                    'IBLOCK_ID'     => $iblockId,
                    'FIELD_ID'      => 'CODE',
                    'IS_REQUIRED'   => 'Y',
                    'DEFAULT_VALUE' => serialize($arDefaultValue),
                ]
            );
        }

        return $iblockId;
    }

    public static function getIblockIdBySectionId($sectionId)
    {
        $arRes = \CIBlockSection::GetByID($sectionId)->Fetch();
        return $arRes['IBLOCK_ID'] ?? 0;
    }

    public static function addSection($fields)
    {
        if (isset($fields['CODE']) && (empty($fields['CODE']) || !preg_match('/[a-z0-9-]+/', $fields['CODE']))) {
            $fields['CODE'] = \CUtil::translit($fields['NAME'], 'ru', self::$translitParams);
        }

        $bs = new \CIBlockSection;
        $secId = $bs->Add($fields);

        if (!$secId) {
            throw new \Exception($bs->LAST_ERROR);
        }

        return $secId;
    }

    public static function addElement($fields)
    {
        if (isset($fields['CODE']) && (empty($fields['CODE']) || !preg_match('/[a-z0-9-]+/', $fields['CODE']))) {
            $fields['CODE'] = \CUtil::translit($fields['NAME'], 'ru', self::$translitParams);
        }

        $el = new \CIBlockElement;
        $elId = $el->Add($fields);

        if (!$elId) {
            throw new \Exception($el->LAST_ERROR);
        }

        return $elId;
    }

    public static function updateElement($elementId, $arFields)
    {
        if (!empty($arFields['NAME']) && isset($arFields['CODE']) &&
            (empty($arFields['CODE']) || !preg_match('/[a-z0-9-]+/', $arFields['CODE']))) {
            $arFields['CODE'] = \CUtil::translit($arFields['NAME'], 'ru', self::$translitParams);
        }

        $el = new \CIBlockElement;
        $result = $el->Update($elementId, $arFields);

        if (!$result) {
            throw new \Exception($el->LAST_ERROR);
        }

        return true;
    }

    public static function updateProperties($elementId, $iblockId, $propertyValues)
    {
        \CIBlockElement::SetPropertyValuesEx($elementId, $iblockId, $propertyValues);
    }

    public static function updateSection($sectionId, $arFields)
    {
        $sc = new \CIBlockSection;
        $result = $sc->Update($sectionId, $arFields);

        if (!$result) {
            throw new \Exception($sc->LAST_ERROR);
        }

        return true;
    }

    public static function getCatalogIblocksForSelect()
    {
        $iblocks = CatalogIblockTable::getList([
            'select' => [
                'ID' => 'IBLOCK_ID',
                'NAME' => 'IBLOCK.NAME',
            ],
        ])->fetchAll();

        $res = [];
        foreach ($iblocks as $iblock) {
            $res[$iblock['ID']] = [
                'TITLE' => '[' . $iblock['ID'] . '] ' . $iblock['NAME'],
            ];
        }

        return $res;
    }

    public static function copyElement($id, $sectionId = 0)
    {
        $newId = 0;
        $el = new \CIBlockElement();

        $dbRes = \CIBlockElement::GetList([], ['ID' => $id]);
        if ($obRes = $dbRes->GetNextElement()) {
            $arFields = $obRes->GetFields();
            $arProps = $obRes->GetProperties();

            $props = self::preparePropsForCopy($arProps);

            $newId = $el->Add([
                'NAME' => $arFields['~NAME'],
                'CODE' => $arFields['CODE'],
                'SORT' => $arFields['SORT'],
                'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                'IBLOCK_SECTION_ID' => empty($sectionId) ? false : $sectionId,
                'ACTIVE' => $arFields['ACTIVE'],
                'ACTIVE_FROM' => $arFields['ACTIVE_FROM'],
                'ACTIVE_TO' => $arFields['ACTIVE_TO'],
                'PREVIEW_TEXT' => $arFields['~PREVIEW_TEXT'],
                'PREVIEW_TEXT_TYPE' => 'html',
                'DETAIL_TEXT' => $arFields['~DETAIL_TEXT'],
                'DETAIL_TEXT_TYPE' => 'html',
                'PREVIEW_PICTURE' => \CFile::MakeFileArray($arFields['PREVIEW_PICTURE']),
                'DETAIL_PICTURE' => \CFile::MakeFileArray($arFields['DETAIL_PICTURE']),
                'PROPERTY_VALUES' => $props,
            ]);
            if (!$newId) {
                throw new \Exception($el->LAST_ERROR);
            }
        }

        return $newId;
    }

    private static function preparePropsForCopy($arProps)
    {
        $res = [];

        foreach ($arProps as $propKey => $prop) {
            $isMultiple = $prop['MULTIPLE'] == 'Y';
            $withDesc = $prop['WITH_DESCRIPTION'] == 'Y';

            if ($prop['PROPERTY_TYPE'] == 'F') {
                if ($isMultiple) {
                    $res[$propKey] = array_map(
                        function ($fileId) { return \CFile::MakeFileArray($fileId); },
                        $prop['VALUE'] ?: []
                    );
                }
                else {
                    $res[$propKey] = \CFile::MakeFileArray($prop['VALUE']);
                }
            } elseif ($prop['USER_TYPE'] == 'HTML') {
                if (is_array($prop['VALUE'])) {
                    $res[$propKey] = [
                        'VALUE' => [
                            'TYPE' => $prop['VALUE']['TYPE'],
                            'TEXT' => htmlspecialchars_decode($prop['VALUE']['TEXT']),
                        ]
                    ];
                }
                else {
                    $res[$propKey] = false;
                }
            } elseif($prop['PROPERTY_TYPE'] == 'E' || $prop['PROPERTY_TYPE'] == 'G') {
                $res[$propKey] = $prop['VALUE'];
            } elseif ($prop['PROPERTY_TYPE'] == 'L') {
                $res[$propKey] = $prop['VALUE_ENUM_ID'];
            } elseif ($prop['PROPERTY_TYPE'] == 'S' && $isMultiple && $withDesc) {
                $res[$propKey] = [];
                foreach ($prop['~VALUE'] as $j => $val) {
                    $res[$propKey][] = [
                        'VALUE' => $val,
                        'DESCRIPTION' => $prop['~DESCRIPTION'][$j],
                    ];
                }
            } elseif ($prop['PROPERTY_TYPE'] == 'S' && !$isMultiple && $withDesc) {
                $res[$propKey] = [
                    'VALUE' => $prop['~VALUE'],
                    'DESCRIPTION' => $prop['~DESCRIPTION'],
                ];
            } else {
                $res[$propKey] = $prop['~VALUE'];
            }
        }

        return $res;
    }

    public static function removeSection($id, $iblockId = false)
    {
        if (!$iblockId) {
            $iblockId = self::getIblockIdBySectionId($id);
        }
        if (empty($iblockId) || intval($iblockId) < 1) {
            return false;
        }

        self::assertRxIblock($iblockId);
        return \CIBlockSection::Delete($id);
    }

    public static function removeElement($id, $iblockId = false)
    {
        if (!$iblockId) {
            $iblockId = self::getIblockIdByElementId($id);
        }
        if (empty($iblockId) || intval($iblockId) < 1) {
            return false;
        }

        self::assertRxIblock($iblockId);
        return \CIBlockElement::Delete($id);
    }

    public static function removeIblock($id)
    {
        $iblock = IblockTable::getById($id)->fetch();
        if (strpos($iblock['CODE'], 'ranx_landing_list_') !== 0) {
            return false;
        }

        \CIBlock::Delete($id);
    }

    private static function assertRxIblock($iblockId)
    {
        $iblock = IblockTable::getById($iblockId)->fetch();
        if (empty($iblock['ID'])) {
            throw new \Exception('No such iblock');
        }
        if (strpos($iblock['CODE'], 'ranx_landing_') !== 0 ||
            $iblock['IBLOCK_TYPE_ID'] != self::TYPE) {
            throw new \Exception('Wrong iblock');
        }
    }

    public static function getSectionIdByCode($code, $iblockId)
    {
        $section = \Bitrix\Iblock\SectionTable::getList([
            'filter' => [
                'CODE' => $code,
                'IBLOCK_ID' => $iblockId,
            ],
        ])->fetch();
        return $section['ID'] ?? 0;
    }

    public static function getElementIdByCode($code, $iblockId)
    {
        $element = \Bitrix\Iblock\ElementTable::getList([
            'filter' => [
                'CODE' => $code,
                'IBLOCK_ID' => $iblockId,
            ],
        ])->fetch();
        return $element['ID'] ?? 0;
    }

    public static function getLandingListIblocks()
    {
        $iblocks = IblockTable::getList([
            'filter' => [
                'CODE' => 'ranx_landing_list_%',
                'IBLOCK_TYPE_ID' => 'ranx_landing',
            ],
        ])->fetchAll();

        return !empty($iblocks) ? $iblocks : [];
    }

    public static function getRootElementsFromIblocks($iblocks)
    {
        $elements = ElementTable::getList([
            'filter' => [
                'IBLOCK_ID' => $iblocks,
                'IBLOCK_SECTION_ID' => false,
            ],
        ])->fetchAll();

        return !empty($elements) ? $elements : [];
    }

    public static function getExternalIblocksForSelect($importDataType = '')
    {
        switch ($importDataType) {
            case 'NEWS':     $sectionType = SectionTable::TYPE_NEWS; break;
            case 'PRODUCTS': $sectionType = SectionTable::TYPE_CATALOG; break;
        }

        if (!empty($sectionType)) {
            $sections = SectionTable::getList([
                'select' => ['IBLOCK_ID'],
                'filter' => ['TYPE' => $sectionType],
            ])->fetchAll();
            $additionalIds = array_filter(array_column($sections, 'IBLOCK_ID'));
        }

        $iblocks = IblockTable::getList([
            'order' => ['IBLOCK_TYPE_ID' => 'ASC'],
            'filter' => [
                [
                    'LOGIC' => 'OR',
                    ['!IBLOCK_TYPE_ID' => 'ranx_landing'],
                    ['ID' => $additionalIds ?? []],
                ],
                'ACTIVE' => 'Y',
            ],
            'select' => ['ID', 'NAME', 'IBLOCK_TYPE_ID', 'TYPE_NAME' => 'TYPE.LANG_MESSAGE.NAME'],
        ])->fetchAll();

        $result = [];
        foreach ($iblocks as $iblock) {
            if (!isset($result[$iblock['IBLOCK_TYPE_ID']])) {
                $result[$iblock['IBLOCK_TYPE_ID']] = [
                    'NAME' => $iblock['TYPE_NAME'],
                    'IBLOCKS' => [],
                ];
            }
            $result[$iblock['IBLOCK_TYPE_ID']]['IBLOCKS'][$iblock['ID']] = $iblock['NAME'];
        }
        return $result;
    }

    public static function getIblockSectionsForSelect($iblockId)
    {
        $result = [];

        $dbRes = \CIBlockSection::GetTreeList(['IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y'], ['ID', 'NAME', 'DEPTH_LEVEL']);
        while ($arRes = $dbRes->Fetch()) {
            $result[] = $arRes;
        }

        return $result;
    }

    public static function searchElements($query, $iblockId, $sectionId = false)
    {
        $arFilter = [
            [
                'LOGIC' => 'OR',
                ['NAME' => '%' . $query . '%'],
                ['=ID' => $query],
            ],
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y',
            'INCLUDE_SUBSECTIONS' => 'Y',
        ];
        if ($sectionId !== false) {
            $arFilter['SECTION_ID'] = $sectionId;
        }

        $result = [];

        $dbRes = \CIBlockElement::GetList(['NAME' => 'ASC'], $arFilter, false, ['nTopCount' => 5], ['ID', 'NAME']);
        while ($arRes = $dbRes->Fetch()) {
            $result[] = $arRes;
        }

        return $result;
    }

    public static function getElementNamesById($ids)
    {
        if (empty($ids)) {
            return [];
        }

        $result = [];

        $dbRes = \CIBlockElement::GetList([], ['ID' => $ids], false, false, ['ID', 'NAME']);
        while ($arRes = $dbRes->Fetch()) {
            $result[$arRes['ID']] = $arRes['NAME'];
        }

        return $result;
    }

    public static function getElementById($id)
    {
        return ElementTable::getById($id)->fetch() ?? [];
    }

    public static function getElementList($data)
    {
        $elements = ElementTable::getList($data)->fetchAll();
        return !empty($elements) ? $elements : [];
    }

    public static function getElementListFirst($data)
    {
        $element = ElementTable::getList($data)->fetch();
        return !empty($element) ? $element : [];
    }

    public static function getSectionList($data)
    {
        $sections = \Bitrix\Iblock\SectionTable::getList($data)->fetchAll();
        return !empty($sections) ? $sections : [];
    }

    public static function getSectionListFirst($data)
    {
        $section = \Bitrix\Iblock\SectionTable::getList($data)->fetch();
        return !empty($section) ? $section : [];
    }

    public static function updateIblock($iblockId, $arFields)
    {
        self::assertRxIblock($iblockId);

        $result = IblockTable::update($iblockId, $arFields);
        if (!$result->isSuccess()) {
            throw new \Exception($result->getErrorMessages());
        }

        return true;
    }
}
