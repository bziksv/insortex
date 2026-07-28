<?php


namespace Ranx\Landing;

use Ranx\Landing\Block;
use Ranx\Landing\Helpers\Iblock;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

class BlockTabs
{
    const EMPTY_NAME = '_empty';

    public static function get($blockId, $arFilter = [])
    {
        $arBlock = Block::get($blockId, ['_PROPERTIES']);
        return self::getByIds($arBlock['PROPS']['TABS'], $arFilter);
    }

    public static function getByIds($tabIds, $arFilter = [])
    {
        if (empty($tabIds)) {
            return [];
        }

        $arOrder = ['SORT' => 'ASC', 'ID' => 'ASC'];
        $arFilter['ID'] = $tabIds;
        $arSelect = ['ID', 'NAME', 'ACTIVE'];
        $dbRes = \CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);

        $result = [];
        while ($arRes = $dbRes->Fetch()) {
            $arRes['FORMAT_NAME'] = self::formatName($arRes['NAME']);
            $arRes['NAME'] = self::formatName($arRes['NAME'], false);
            $result[] = $arRes;
        }

        return $result;
    }

    public static function prepareForUpdate($data)
    {
        return array_map(
            function($id, $name, $active) {
                return [
                    'ID' => $id,
                    'NAME' => $name,
                    'ACTIVE' => $active,
                ];
            },
            $data['ID'] ?? [],
            $data['NAME'] ?? [],
            $data['ACTIVE'] ?? []
        ) ?? [];
    }

    public static function update($blockId, $tabs, $selectedTabId)
    {
        $blocksIblockId = Block::getIblockId();
        $elementsIblockId = Block::getElementsIblockId();
        $tabsIds = Iblock::getMultiplePropValue($blockId, $blocksIblockId, 'TABS');
        $elementsIds = Iblock::getMultiplePropValue($blockId, $blocksIblockId, 'ELEMENTS');
        $tabsIdsToDelete = array_diff($tabsIds, array_column($tabs, 'ID'));
        $selectedTabElementIds = BlockTabs::filterElementIdsByTab($elementsIds, $blockId, $selectedTabId);
        $newTabsIds = [];

        $dbSectionObj = new \CIBlockSection;
        foreach ($tabs as $sort => $tab) {
            $arFields = [
                'NAME' => !empty($tab['NAME']) ? $tab['NAME'] : self::EMPTY_NAME,
                'ACTIVE' => $tab['ACTIVE'] === 'Y' ? 'Y' : 'N',
                'SORT' => $sort + 1,
            ];
            $id = $tab['ID'];

            if (empty($id)) {
                $arFields['IBLOCK_ID'] = $elementsIblockId;
                if($id = $dbSectionObj->Add($arFields)) {
                    $newElementIds = [];
                    foreach ($selectedTabElementIds as $elementId) {
                        $newElementId = Iblock::copyElement($elementId, $id);
                        $newElementIds[] = $newElementId;
                    }
                    $elementsIds = array_merge($elementsIds, $newElementIds);
                    $newTabsIds[] = $id;
                }
            }
            else {
                if($dbSectionObj->Update($id, $arFields))
                    $newTabsIds[] = $id;
            }
        }

        \CIBlockElement::SetPropertyValuesEx($blockId, $blocksIblockId,
            ['TABS' => $newTabsIds, 'ELEMENTS' => $elementsIds]);

        foreach ($tabsIdsToDelete as $id) {
            Iblock::removeSection($id);
        }

        return true;
    }

    public static function copy($blockId)
    {
        $blocksIblockId = Block::getIblockId();
        $elementsIblockId = Block::getElementsIblockId();
        $dbSectionObj = new \CIBlockSection;

        $tabs = self::get($blockId);
        $elementIds = Iblock::getMultiplePropValue($blockId, $blocksIblockId, 'ELEMENTS');
        $elementForTabMap = self::getTabIdsForElements($elementIds);

        $newTabIds = [];
        $tabMap = [];
        foreach ($tabs as $tab) {
            $arFields = [
                'NAME' => $tab['NAME'],
                'ACTIVE' => $tab['ACTIVE'],
                'SORT' => $tab['SORT'],
                'IBLOCK_ID' => $elementsIblockId,
            ];

            if ($newTabId = $dbSectionObj->Add($arFields)) {
                $newTabIds[] = $newTabId;
                $tabMap[$tab['ID']] = $newTabId;
            }
        }

        $newElementIds = [];
        foreach ($elementIds as $elementId) {
            $oldTabId = $elementForTabMap[$elementId];
            $newTabId = $tabMap[$oldTabId];
            if (empty($newTabId))
                $newTabId = 0;

            if ($newElementId = Iblock::copyElement($elementId, $newTabId)) {
                $newElementIds[] = $newElementId;
            }
        }

        return [
            'ELEMENTS' => $newElementIds,
            'TABS' => $newTabIds,
        ];
    }

    private static function getTabIdsForElements($elementIds)
    {
        if (empty($elementIds)) {
            return [];
        }

        $dbRes = \CIBlockElement::GetList([], ['ID' => $elementIds], false, false, ['ID', 'IBLOCK_SECTION_ID']);
        $result = [];
        while($arRes = $dbRes->Fetch()) {
            $result[$arRes['ID']] = $arRes['IBLOCK_SECTION_ID'] ?? 0;
        }

        return $result;
    }

    private static function formatName($name, $doCut = true)
    {
        if ($name !== self::EMPTY_NAME) {
            return $doCut ? Helper::cutName($name) : $name;
        }
        return Loc::getMessage('RX_PANEL_LANDING_TABS_EMPTY_NAME');
    }

    public static function getTemplateTab()
    {
        $name = Loc::getMessage('RX_PANEL_LANDING_TABS_TEMPLATE');
        return [
            'ID' => '',
            'NAME' => $name,
            'FORMAT_NAME' => $name,
            'ACTIVE' => 'Y'
        ];
    }

    public static function getDefaultTab()
    {
        $name = Loc::getMessage('RX_PANEL_LANDING_TABS_DEFAULT');
        return [
            'ID' => '',
            'NAME' => $name,
            'FORMAT_NAME' => $name,
            'ACTIVE' => 'Y'
        ];
    }

    public static function getEditorTab()
    {
        $name = Loc::getMessage('RX_PANEL_LANDING_TABS_EDITOR');
        return [
            'ID' => '',
            'NAME' => $name,
            'FORMAT_NAME' => $name,
            'ACTIVE' => 'Y',
            'IS_EDITOR' => 'Y',
        ];
    }

    public static function validateId($tabId, $tabs = [])
    {
        $tabs = array_filter($tabs, function ($arTab) {
            return !empty($arTab['ID']);
        });

        if (in_array($tabId, array_column($tabs, 'ID'))){
            return $tabId;
        }

        if (!empty($tabs)) {
            return reset($tabs)['ID'];
        }

        return false;
    }

    public static function getTabName($id)
    {
        if (empty($id)) {
            return Loc::getMessage('RX_PANEL_LANDING_TABS_DEFAULT');
        }

        $arFilter['IBLOCK_ID'] = Block::getElementsIblockId();
        $tabs = self::getByIds($id, $arFilter);
        if (empty($tabs)) {
            return false;
        }

        return reset($tabs)['FORMAT_NAME'];
    }

    private static function filterElementIdsByTab($ids, $blockId, $tabId)
    {
        if (empty($ids)) {
            return [];
        }

        $iblockId = Block::getElementsIblockId();
        $arOrder = ['SORT' => 'ASC', 'ID' => 'ASC'];
        $arFilter = [
            'ID' => $ids,
            'IBLOCK_SECTION_ID' => empty($tabId) ? false : $tabId,
            'IBLOCK_ID' => $iblockId
        ];
        $arSelect = ['ID'];
        $dbRes = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        $result = [];
        while($arRes = $dbRes->Fetch()) {
            $result[] = $arRes['ID'];
        }

        return $result;
    }
}
