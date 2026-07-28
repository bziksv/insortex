<?php

namespace Ranx\Landing;

use Ranx\Landing\Helpers\Iblock;

class BlockGroup
{
    const IBLOCK_SELECT = [
        'ID',
        'IBLOCK_ID',
        'NAME',
        'ACTIVE',
        'SORT',
        'CODE',
        'UF_LANDING',
        'UF_MODE',
    ];

    public static function get($id)
    {
        if (intval($id) <= 0) {
            return [];
        }
        $iblockId = Block::getIblockId();
        return \CIBlockSection::GetList([], ['ID' => $id, 'IBLOCK_ID' => $iblockId, 'CHECK_PERMISSIONS' => 'N'], false, self::IBLOCK_SELECT)->Fetch();
    }

    public static function getByLanding($landingId, $mode, $getAll = false)
    {
        $iblockId = Block::getIblockId();
        $arFilter = [
            'IBLOCK_ID' => $iblockId,
            'UF_LANDING' => $landingId,
            'UF_MODE' => $mode,
            'SECTION_ID' => false,
        ];

        $isEditMode = Config::isEditMode();
        if (!$getAll && !$isEditMode) {
            $arFilter['ACTIVE'] = 'Y';
        }

        $dbRes = \CIBlockSection::GetList(['SORT' => 'ASC', 'ID' => 'ASC',], $arFilter, false, self::IBLOCK_SELECT);

        $result = [];
        while ($arRes = $dbRes->Fetch()) {
            $result[] = $arRes;
        }

        return $result;
    }

    public static function getFilteredBlocks($landingId, $mode, $getAll = false)
    {
        $landingGroups = self::getByLanding($landingId, $mode, $getAll);

        $groups = [];
        foreach ($landingGroups as $landingGroup) {
            $groups[$landingGroup['ID']] = $landingGroup;
        }

        if (empty($groups)) {
            return [];
        }

        $allBlocks = self::getBlocks(array_column($groups, 'ID'), ['_PROPERTIES'], false);

        $groupBlocks = [];
        foreach ($allBlocks as $block) {
            $groupBlocks[$block['IBLOCK_SECTION_ID']][] = $block;
        }
        unset($block);

        $isEditMode = Config::isEditMode();
        $result = [];

        // region filter
        if (!$getAll && (!$isEditMode || Config::useRegionFilterInEditMode())) {
            foreach ($groupBlocks as $groupId => &$blocks) {
                $blocks = Block::filterBlocks($blocks);
            }
            unset($blocks);
        }

        foreach ($groupBlocks as $groupId => $blocks) {

            $sort = 1;
            foreach ($blocks as $block) {
                // autofix sorts
                if ($isEditMode && ($block['SORT'] != $sort)) {
                    $block['SORT'] = $sort;
                    Block::setSort($block['ID'], $sort);
                }
                $sort++;
            }

            $firstBlock = reset($blocks); // just get the first one by sort

            $firstBlock['ACTIVE'] = $groups[$groupId]['ACTIVE'];
            $firstBlock['SORT'] = $groups[$groupId]['SORT'];

            $result[] = $firstBlock;
        }

        return $result;
    }

    public static function getBlocks($groupIds, $select = [], $getAll = true)
    {
        if (empty($groupIds)) {
            return [];
        }
        $arFilter = [
            'SECTION_ID' => $groupIds,
        ];
        $isEditMode = Config::isEditMode();
        if (!$getAll && !$isEditMode) {
            $arFilter['ACTIVE'] = 'Y';
        }

        $arSelect = array_merge(Block::IBLOCK_SELECT, $select);
        $blocks = Block::getList($arFilter, $arSelect);

        return $blocks;
    }

    public static function createFromBlock($blockId)
    {
        $bs = new \CIBlockSection;
        $iblockId = Block::getIblockId();
        $block = Block::get($blockId, ['PROPERTY_LANDING', 'PROPERTY_MODE']);

        $groupId = $bs->Add([
            'IBLOCK_ID' => $iblockId,
            'NAME' => 'Group',
            'SORT' => $block['SORT'],
            'ACTIVE' => $block['ACTIVE'],
            'UF_LANDING' => $block['PROPERTY_LANDING_VALUE'],
            'UF_MODE' => $block['PROPERTY_MODE_VALUE'],
        ]);

        if ($groupId)
            self::moveBlockToGroup($groupId, $blockId);

        return $groupId;
    }

    public static function moveBlockToGroup($groupId, $blockId)
    {
        $el = new \CIBlockElement;
        $el->Update($blockId, [
            'IBLOCK_SECTION_ID' => $groupId,
            'SORT' => 500,
        ]);
    }

    public static function getFirstBlockId($id)
    {
        $dbRes = \CIBlockElement::GetList(
            ['SORT' => 'ASC', 'ID' => 'ASC',],
            [
                'SECTION_ID' => $id,
            ],
            false,
            false,
            ['ID', 'NAME', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'ACTIVE', 'SORT', 'CODE',]
        );
        return $dbRes->Fetch()['ID'] ?? 0;
    }

    public static function remove($id)
    {
        $blocks = self::getBlocks($id);
        foreach ($blocks as $block) {
            Block::remove($block['ID']);
        }
        Iblock::removeSection($id);
    }

    private static function setActive($id, $active)
    {
        if (intval($id) <= 0) {
            return false;
        }

        $arFields = [
            'ACTIVE' => $active ? 'Y' : 'N',
        ];

        $el = new \CIBlockSection;
        if ($el->Update($id, $arFields)) {
            return true;
        }

        return false;
    }

    public static function deActivate($id)
    {
        return self::setActive($id, false);
    }

    public static function activate($id)
    {
        return self::setActive($id, true);
    }

    public static function incSort($id)
    {
        if (intval($id) <= 0) {
            return false;
        }

        $curSort = self::getCurrentSort($id);

        return self::setSort($id, $curSort + 1);
    }

    public static function decSort($id)
    {
        if (intval($id) <= 0) {
            return false;
        }

        $curSort = self::getCurrentSort($id);

        return self::setSort($id, $curSort - 1);
    }

    public static function setSort($id, $newSort)
    {
        if ($newSort < 1) {
            return false;
        }

        $arFields = [
            'SORT' => $newSort,
        ];

        $el = new \CIBlockSection;
        if ($el->Update($id, $arFields)) {
            return true;
        }

        return false;
    }

    public static function getCurrentSort($id)
    {
        $arElement = \CIBlockSection::GetList([], ['ID' => $id], false, false, ['SORT'])->Fetch();
        return $arElement['SORT'];
    }

    public static function getMaxSortByLanding($landingId, $mode, $siteId = SITE_ID)
    {
        if (!$landingId) {
            return false;
        }
        $iblockId = Block::getIblockId();
        $arSection = \CIBlockSection::GetList(['SORT' => 'DESC'], [
            'IBLOCK_ID' => $iblockId,
            'UF_LANDING' => $landingId,
            'UF_MODE' => $mode,
        ], ['nTopCount' => 1], ['SORT'])->Fetch();

        return $arSection['SORT'] ?? 0;
    }

    public static function copy($id, $landingId = false, $landingMode = false, $siteId = false, $copyElements = true)
    {
        $group = self::get($id);
        if (!$group) {
            return false;
        }

        if (!$landingId) {
            $landingId = $group['UF_LANDING'];
        }
        if (!$landingMode) {
            $landingMode = $group['UF_MODE'];
        }
        if (!$siteId) {
            $siteId = SITE_ID;
        }

        $maxSort = Block::getMaxSortByLanding($landingId, $landingMode, $siteId);

        $addFields = [
            'NAME' => $group['NAME'],
            'ACTIVE' => $group['ACTIVE'],
            'IBLOCK_ID' => $group['IBLOCK_ID'],
            'UF_LANDING' => $landingId,
            'UF_MODE' => $landingMode,
            'SORT' => $maxSort ? $maxSort + 1 : 500,
        ];

        $bs = new \CIBlockSection();
        if ($newGroupId = $bs->Add($addFields)) {
            $blocks = self::getBlocks($id);

            foreach ($blocks as $block) {
                Block::copy($block['ID'], $landingId, $landingMode, $siteId, $copyElements, $newGroupId);
            }

            return $newGroupId;
        }

        return false;
    }

    public static function move($id, $landingId, $mode = Landing::MODE_ELEMENT)
    {
        if (intval($landingId) <= 0 || !in_array($mode, Landing::MODE_ALL)) {
            return false;
        }

        $bs = new \CIBlockSection();
        $maxSort = self::getMaxSortByLanding($landingId, $mode);
        $bs->Update($id, [
            'SORT' => $maxSort + 1,
            'UF_LANDING' => $landingId,
            'UF_MODE' => $mode,
        ]);

        return true;
    }
}
