<?php

namespace Ranx\Landing;


use Exception;
use CIBlockElement;
use Bitrix\Main\Data;
use Ranx\Landing\Config;
use Bitrix\Main\Web\Json;
use Ranx\Landing\Helpers;
use Ranx\Landing\BlockTabs;
use Ranx\Landing\Panel\Content\Filter as ContentFilter;
use Bitrix\Main\Localization\Loc;

class Block
{
    const TEMPLATES_LIST = [
        '/local/components/ranx/block.landing/templates',
        '/local/templates/ranx-landing/components/ranx/block.landing',
        '/local/templates/.default/components/ranx/block.landing',
        '/bitrix/components/ranx/block.landing/templates',
        '/bitrix/templates/ranx-landing/components/ranx/block.landing',
        '/bitrix/templates/.default/components/ranx/block.landing',
    ];
    const CACHE_ID = 'ranx_landing_blocks_list';
    const CACHE_TTL = 36000000;
    const EMPTY_TITLE = '_empty';

    const IBLOCK_SELECT = [
        'ID',
        'NAME',
        'IBLOCK_ID',
        'IBLOCK_SECTION_ID',
        'ACTIVE',
        'SORT',
        'CODE',
        'DETAIL_TEXT',
    ];

    public static function initList()
    {
        $cache = \Bitrix\Main\Application::getInstance()->getManagedCache();
        if ($cache->read(self::CACHE_TTL, self::CACHE_ID)) {
            $vars = $cache->get(self::CACHE_ID);
            Config::$blocks['LIST'] = $vars['LIST'];
            Config::$blocks['GROUPS'] = $vars['GROUPS'];
            return;
        }

        foreach (self::TEMPLATES_LIST as $folder) {
            $templatesFolder = $_SERVER['DOCUMENT_ROOT'] . $folder;
            if ($handle = @opendir($templatesFolder)) {
                while (($file = @readdir($handle)) !== false) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    }

                    $blockParamsFile = $templatesFolder . '/' . $file . '/.block.php';
                    if (preg_match('/^([a-z0-9_]+)_([a-z0-9_]+)$/', $file) && file_exists($blockParamsFile)) {
                        Loc::loadMessages($blockParamsFile);
                        $blockParams = include($blockParamsFile);
                        if (!empty($blockParams && is_array($blockParams)) && !isset(Config::$blocks['LIST'][$file])) {
                            Config::$blocks['LIST'][$file] = $blockParams;
                            Config::$blocks['LIST'][$file]['_PATH'] = $folder . '/' . $file;

                            if (!preg_match('/^(\d+)_(\d+)$/', $file)) {
                                $blockGroups = $blockParams['GROUPS'] ?? ['CUSTOM'];
                                foreach ($blockGroups as $blockGroup) {
                                    if (!empty(Config::$blocks['GROUPS'][$blockGroup])) {
                                        Config::$blocks['GROUPS'][$blockGroup]['BLOCKS'][] = $file;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $cache->set(self::CACHE_ID, [
            'LIST' => Config::$blocks['LIST'],
            'GROUPS' => Config::$blocks['GROUPS'],
        ]);
    }

    public static function getIblockId($siteId = SITE_ID)
    {
        return Helpers\Iblock::getIblockIdByCode('ranx_landing_blocks', $siteId);
    }

    public static function getElementsIblockId($siteId = SITE_ID)
    {
        return Helpers\Iblock::getIblockIdByCode('ranx_landing_elements', $siteId);
    }

    public static function get($id, $arSelectProps = [])
    {
        if (intval($id) <= 0) {
            return [];
        }

        $iblockId = self::getIblockId();
        $arFilter = ['ID' => $id, 'IBLOCK_ID' => $iblockId];

        $arSelect = self::IBLOCK_SELECT;
        $arSelect = array_merge($arSelect, $arSelectProps);

        $dbRes = \CIBlockElement::GetList(
            [
                'SORT' => 'ASC',
                'ID' => 'ASC',
            ],
            $arFilter,
            false,
            false,
            $arSelect
        );

        $getProps = false;
        foreach ($arSelect as $key => $select) {
            if ($select === '_PROPERTIES') {
                $getProps = true;
                unset($arSelect[$key]);
            }
        }

        if ($obRes = $dbRes->GetNextElement()) {
            $arRes = $obRes->GetFields();

            if ($getProps) {
                $arRes['PROPERTIES'] = $obRes->GetProperties();
                foreach ($arRes['PROPERTIES'] as $propKey => $prop) {
                    $arRes['PROPS'][$propKey] = $prop['~VALUE'];
                }
            }

            if ($arRes['NAME'] === self::EMPTY_TITLE) {
                $arRes['NAME'] = '';
            }

            return $arRes;
        }

        return [];
    }

    public static function getList($filter = [], $select = [])
    {
        $arOrder = [
            'SORT' => 'ASC',
            'ID' => 'ASC',
        ];
        $arFilter = [
            'IBLOCK_ID' => self::getIblockId(),
        ];
        $arFilter = array_merge($arFilter, $filter);
        $arSelect = array_merge(self::IBLOCK_SELECT, $select);

        $dbRes = \CIBlockElement::GetList(
            $arOrder,
            $arFilter,
            false,
            false,
            $arSelect
        );

        $getProps = false;
        foreach ($arSelect as $key => $select) {
            if ($select === '_PROPERTIES') {
                $getProps = true;
                unset($arSelect[$key]);
            }
        }

        $blocks = [];
        while ($obRes = $dbRes->GetNextElement()) {
            $arRes = $obRes->GetFields();

            if ($getProps) {
                $arRes['PROPERTIES'] = $obRes->GetProperties();
                foreach ($arRes['PROPERTIES'] as $propKey => $prop) {
                    $arRes['PROPS'][$propKey] = $prop['~VALUE'];
                }
                $arRes['ANCHOR'] = $arRes['PROPS']['ANCHOR_TITLE'];
            }

            if ($arRes['NAME'] === self::EMPTY_TITLE) {
                $arRes['NAME'] = '';
            }

            $blocks[] = $arRes;
        }

        return $blocks;
    }

    /**
     * Method returns the root block and first block in each group
     * GetAll - only affects ACTIVE and region filter
     * @param $landingId
     * @param string $mode
     * @param false $getAll
     * @return array
     */
    public static function getByLanding($landingId, $mode = Landing::MODE_ELEMENT, $getAll = false)
    {
        $blocks = self::getRootBlocksData($landingId, $mode, $getAll);
        $groupBlocks = BlockGroup::getFilteredBlocks($landingId, $mode, $getAll);

        $allBlocks = array_merge($blocks, $groupBlocks);
        usort($allBlocks, function($a, $b){
            if ($a['SORT'] == $b['SORT']) {
                return 0;
            }
            return $a['SORT'] > $b['SORT'] ? 1 : -1;
        });

        // autofix sort (must update $allBlocks by reference — otherwise HTML keeps gaps/duplicates
        // and move up/down breaks because it looks for order±1 neighbors)
        if (Config::isEditMode()) {
            $sort = 1;
            foreach ($allBlocks as &$block) {
                if ((int)$block['SORT'] !== $sort) {
                    $block['SORT'] = $sort;
                    !empty($block['IBLOCK_SECTION_ID'])
                        ? BlockGroup::setSort($block['IBLOCK_SECTION_ID'], $sort)
                        : self::setSort($block['ID'], $sort);
                }

                $sort++;
            }
            unset($block);
        }

        return $allBlocks;
    }

    public static function getAllByLanding($landingId, $mode = Landing::MODE_ELEMENT)
    {
        $rootBlocks = self::getRootBlocksData($landingId, $mode, true);
        $groups = BlockGroup::getByLanding($landingId, $mode, true);
        if (!empty($groups)) {
            $groupBlocks = BlockGroup::getBlocks(array_column($groups, 'ID'), ['_PROPERTIES']);
        }

        return array_merge($rootBlocks, $groupBlocks ?? []);
    }

    public static function getRootBlocksData($landingId, $mode, $getAll = false)
    {
        $arFilter = [
            'PROPERTY_LANDING' => $landingId,
            'PROPERTY_MODE' => $mode,
            'SECTION_ID' => 0,
        ];
        $arSelect = [
            '_PROPERTIES',
            'PREVIEW_TEXT',
            'DETAIL_TEXT',
            'PREVIEW_PICTURE',
            'DETAIL_PICTURE',
        ];

        if ($mode == Landing::MODE_ELEMENT) { // for backward compatibility
            unset($arFilter['PROPERTY_MODE']);
            $arFilter[] = [
                'LOGIC' => 'OR',
                ['PROPERTY_MODE' => Landing::MODE_ELEMENT],
                ['PROPERTY_MODE' => false],
            ];
        }

        $isEditMode = Config::isEditMode();
        if (!$getAll && !$isEditMode) {
            $arFilter['ACTIVE'] = 'Y';
        }

        $blocks = self::getList($arFilter, $arSelect);

        if (!$getAll && (!$isEditMode || Config::useRegionFilterInEditMode())) {
            $blocks = self::filterBlocks($blocks);
        }

        return $blocks;
    }

    public static function filterBlocks($blocks)
    {
        if (Config::isRegionEnabled()) {
            $regionElement = Region::getCurrent();
            $regionSections = Region::getCurrentSections();
            $curBranch = Region::getCurrentBranch();

            foreach ($blocks as $key => $block) {

                $bExclude = false;

                if (!empty($block['PROPS']['REGION_INCLUDE'])) {
                    if (!in_array('E' . $regionElement['ID'], $block['PROPS']['REGION_INCLUDE'])) {
                        $bExclude = true;
                    }
                    foreach ($regionSections as $sec) {
                        if (in_array('S' . $sec['ID'], $block['PROPS']['REGION_INCLUDE'])) {
                            $bExclude = false;
                        }
                    }
                }
                if (!empty($block['PROPS']['REGION_EXCLUDE'])) {
                    if (in_array('E' . $regionElement['ID'], $block['PROPS']['REGION_EXCLUDE'])) {
                        $bExclude = true;
                    }
                    foreach ($regionSections as $sec) {
                        if (in_array('S' . $sec['ID'], $block['PROPS']['REGION_EXCLUDE'])) {
                            $bExclude = true;
                        }
                    }
                }

                if (Config::useRegionBranches()) {
                    if (!empty($block['PROPS']['BRANCH_INCLUDE'])) {
                        if (!in_array($curBranch['ID'], $block['PROPS']['BRANCH_INCLUDE'])) {
                            $bExclude = true;
                        }
                    }
                    if (!empty($block['PROPS']['BRANCH_EXCLUDE'])) {
                        if (in_array($curBranch['ID'], $block['PROPS']['BRANCH_EXCLUDE'])) {
                            $bExclude = true;
                        }
                    }
                }

                if ($bExclude) {
                    unset($blocks[$key]);
                }
            }
        }

        $blocks = array_values($blocks);
        return $blocks;
    }

    /**
     * Set block element activity
     *
     * @param bool $active
     * @return bool
     */
    private static function setActive($id, $active)
    {
        if (intval($id) <= 0) {
            return false;
        }

        $arFields = [
            'ACTIVE' => $active ? 'Y' : 'N',
        ];

        $el = new CIBlockElement;
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

        $el = new CIBlockElement;
        if ($el->Update($id, $arFields)) {
            return true;
        }

        return false;
    }

    public static function getCurrentSort($id)
    {
        $arElement = \CIBlockElement::GetList([], ['ID' => $id], false, false, ['SORT', 'IBLOCK_SECTION_ID'])->Fetch();

        if (!empty($arElement['IBLOCK_SECTION_ID'])) {
            return BlockGroup::getCurrentSort($arElement['IBLOCK_SECTION_ID']);
        }

        return $arElement['SORT'];
    }

    public static function add($landingId, $code, $nextBlocks, $mode = Landing::MODE_ELEMENT, $siteId = SITE_ID)
    {
        $el = new CIBlockElement;

        if (!empty($nextBlocks)) {
            $nextBlockId = reset($nextBlocks);
            $sort = self::getCurrentSort($nextBlockId);
        } else {
            $sort = self::getMaxSortByLanding($landingId, $mode, $siteId) + 1;
        }

        $blockInfo = Config::getBlockInfo($code);

        $iblockId = self::getIblockId($siteId);
        if (intval($iblockId) <= 0) {
            return false;
        }

        $arFields = [
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y',
            'NAME' => Loc::getMessage('RX_LANDING_LIB_BLOCK_FIELD_NAME_DEFAULT'),
            'CODE' => $code,
            'PROPERTY_VALUES' => [
                'LANDING' => $landingId,
                'MODE' => $mode,
                'INDENT_TOP' => $blockInfo['INDENT_TOP_DEFAULT'] ?? 0,
                'INDENT_BOT' => $blockInfo['INDENT_BOT_DEFAULT'] ?? 0,
            ],
            'SORT' => $sort,
        ];
        
        if (!empty($blockInfo['DEMO']['BLOCK']['PROPERTY_VALUES']))
            $arProps = array_merge($arFields['PROPERTY_VALUES'], $blockInfo['DEMO']['BLOCK']['PROPERTY_VALUES']);
        else
            $arProps = $arFields['PROPERTY_VALUES'];

        if (!empty($blockInfo['DEMO']['BLOCK'])) {
            $arFields = array_merge($arFields, $blockInfo['DEMO']['BLOCK']);
        }
        $arFields['PROPERTY_VALUES'] = $arProps;

        if (isset($arFields['PROPERTY_VALUES']['HIDE_TITLE']) && $arFields['PROPERTY_VALUES']['HIDE_TITLE'] == 'Y') {
            $propValues = Helpers\Iblock::getListPropValuesByXmlId('HIDE_TITLE', $iblockId);
            $arFields['PROPERTY_VALUES']['HIDE_TITLE'] = $propValues['Y'] ?? false;
        }
        if (isset($arFields['PROPERTY_VALUES']['AUTO_BLOCK']) && $arFields['PROPERTY_VALUES']['AUTO_BLOCK'] == 'Y'
            && ($mode !== Landing::MODE_ELEMENT || $arFields['PROPERTY_VALUES']['AUTO_TYPE'] === 'detail')) {
            $propValues = Helpers\Iblock::getListPropValuesByXmlId('AUTO_BLOCK', $iblockId);
            $arFields['PROPERTY_VALUES']['AUTO_BLOCK'] = $propValues['Y'] ?? false;
        }
        if (isset($arFields['PROPERTY_VALUES']['USE_TABS']) && $arFields['PROPERTY_VALUES']['USE_TABS'] == 'Y') {
            $propValues = Helpers\Iblock::getListPropValuesByXmlId('USE_TABS', $iblockId);
            $arFields['PROPERTY_VALUES']['USE_TABS'] = $propValues['Y'] ?? false;
        }
        if (!empty($arFields['PROPERTY_VALUES']['TITLE_TAG'])) {
            $value = $arFields['PROPERTY_VALUES']['TITLE_TAG'];
            $propValues = Helpers\Iblock::getListPropValuesByXmlId('TITLE_TAG', $iblockId);
            if (!empty($propValues[$value])) {
                $arFields['PROPERTY_VALUES']['TITLE_TAG'] = $propValues[$value];
            }
        }

        if (ContentFilter::isInclude($code)) {
            $tmpValue = serialize(ContentFilter::getDefaultValue($code));
            $arFields['PROPERTY_VALUES'][ContentFilter::getPropertyCode()] = $tmpValue;
        }

        $tabsMap = self::addDemoTabs($code);
        if (!empty($tabsMap)) {
            $arFields['PROPERTY_VALUES']['TABS'] = array_values($tabsMap);
        }

        $elementIds = self::addDemoElements($code, $tabsMap);
        if ($elementIds) {
            $arFields['PROPERTY_VALUES']['ELEMENTS'] = $elementIds;
        }

        $imagesPath = $_SERVER['DOCUMENT_ROOT'] . $blockInfo['_PATH'] . '/demo/';
        if (!empty($arFields['PREVIEW_PICTURE'])) {
            $arFields['PREVIEW_PICTURE'] = Helpers\File::getDemo($imagesPath . $arFields['PREVIEW_PICTURE']);
        }
        if (!empty($arFields['DETAIL_PICTURE'])) {
            $arFields['DETAIL_PICTURE'] = Helpers\File::getDemo($imagesPath . $arFields['DETAIL_PICTURE']);
        }

        if ($newBlockId = $el->Add($arFields)) {

            foreach ($nextBlocks as $nextBlockId) {
                self::incSort($nextBlockId);
            }

            return [
                'ID' => $newBlockId,
                'IBLOCK_ID' => $iblockId,
                'SORT' => $sort,
                'ACTIVE' => 'Y',
                'CODE' => $code,
            ];
        } else {
            var_dump($el->LAST_ERROR);
            die();
        }
    }

    public static function getMaxSortByLanding($landingId, $mode = Landing::MODE_ELEMENT, $siteId = SITE_ID)
    {
        if (!$landingId) {
            return false;
        }
        $arFilter = [
            'IBLOCK_ID' => self::getIblockId(),
            'PROPERTY_LANDING' => $landingId,
            'PROPERTY_MODE' => $mode,
            'LID' => $siteId,
        ];
        if ($mode == Landing::MODE_ELEMENT) {
            unset($arFilter['PROPERTY_MODE']);
            $arFilter[] = [
                'LOGIC' => 'OR',
                ['PROPERTY_MODE' => Landing::MODE_ELEMENT],
                ['PROPERTY_MODE' => false],
            ];
        }
        $arElement = \CIBlockElement::GetList(['SORT' => 'DESC'], $arFilter, false, ['nTopCount' => 1], ['SORT'])->Fetch();

        $elementMaxSort = $arElement['SORT'] ?? 0;
        $sectionMaxSort = BlockGroup::getMaxSortByLanding($landingId, $mode, $siteId);

        return max($elementMaxSort, $sectionMaxSort);
    }

    public static function getMaxSortByBlock($blockId)
    {
        $iblockId = self::getIblockId();
        $propRes = CIBlockElement::GetProperty($iblockId, $blockId, 'sort', 'asc', ['CODE' => 'ELEMENTS']);
        return $propRes->SelectedRowsCount() + 1;
    }

    public static function remove($id)
    {
        // first, delete elements
        $block = self::get($id, ['_PROPERTIES']);
        $elementIds = is_array($block['PROPS']['ELEMENTS']) ? $block['PROPS']['ELEMENTS'] : [];
        $tabIds = is_array($block['PROPS']['TABS']) ? $block['PROPS']['TABS'] : [];
        $throughId = is_array($block['PROPS']['THROUGH_ID']) ? $block['PROPS']['THROUGH_ID'] : [];

        if (self::canDeleteElements($throughId)) {
            foreach ($elementIds as $elId) {
                Helpers\Iblock::removeElement($elId);
            }
            foreach ($tabIds as $tabId) {
                Helpers\Iblock::removeSection($tabId);
            }
        }

        if (Helpers\Iblock::removeElement($id)) {
            return true;
        }

        return false;
    }

    public static function move($id, $landingId, $mode = Landing::MODE_ELEMENT)
    {
        if (intval($landingId) <= 0 || !in_array($mode, Landing::MODE_ALL)) {
            return false;
        }

        $el = new \CIBlockElement;
        $maxSort = self::getMaxSortByLanding($landingId, $mode);
        $el->Update($id, ['SORT' => $maxSort + 1]);

        \CIBlockElement::SetPropertyValuesEx($id, false, ['LANDING' => $landingId, 'MODE' => $mode]);
        return true;
    }

    private static function addDemoTabs($code)
    {
        $blockInfo = Config::getBlockInfo($code);
        $elementsIblockId = self::getElementsIblockId();
        $result = [];

        if (empty($blockInfo['DEMO']) || empty($elementsIblockId)) {
            return $result;
        }

        $dbSectionObj = new \CIBlockSection;
        $sort = 1;
        foreach ($blockInfo['DEMO']['TABS'] as $key => $value) {
            $arField = [
                'NAME' => empty($value) ? BlockTabs::EMPTY_NAME : $value,
                'ACTIVE' => 'Y',
                'SORT' => $sort++,
                'IBLOCK_ID' => $elementsIblockId,
            ];

            if ($tabId = $dbSectionObj->Add($arField)) {
                $result[$key] = $tabId;
            }
        }

        return $result;
    }

    /**
     * Adding demo elements from config
     * and return ids of created elements
     */
    private static function addDemoElements($code, $tabsMap = [])
    {
        $res = [];
        $el = new CIBlockElement;

        $blockInfo = Config::getBlockInfo($code);
        $imagesPath = $_SERVER['DOCUMENT_ROOT'] . $blockInfo['_PATH'] . '/demo/';

        $elementsIblockId = self::getElementsIblockId();

        if (empty($blockInfo['DEMO']) || empty($elementsIblockId)) {
            return false;
        }

        $arFieldsDefault = [
            'ACTIVE' => 'Y',
            'NAME' => Loc::getMessage('RX_LANDING_LIB_BLOCK_FIELD_NAME_DEFAULT'),
            'IBLOCK_ID' => $elementsIblockId,
        ];

        $arPropsList = [];
        $arElementsFields = Config::getBlockElementsFields($code);
        foreach ($arElementsFields as $field) {
            if (strpos($field, 'PROPERTY_') === 0) {
                $arPropsList[] = substr($field, 9);
            }
        }
        $arPropsInfo = Helpers\Iblock::getPropsInfoByCode($arPropsList, $elementsIblockId);;

        // loop through every demo element
        foreach ($blockInfo['DEMO']['ELEMENTS'] as $arDemoFields) {
            $arElementFields = array_merge($arFieldsDefault, $arDemoFields);

            // process files
            if (!empty($arElementFields['PREVIEW_PICTURE'])) {
                $arElementFields['PREVIEW_PICTURE'] = Helpers\File::getDemo($imagesPath . $arElementFields['PREVIEW_PICTURE']);
            }
            if (!empty($arElementFields['DETAIL_PICTURE'])) {
                $arElementFields['DETAIL_PICTURE'] = Helpers\File::getDemo($imagesPath . $arElementFields['DETAIL_PICTURE']);
            }

            // process tab
            if (isset($arElementFields['TAB'])) {
                $tabId = $tabsMap[$arElementFields['TAB']];
                if (empty($tabId)) {
                    $tabId = false;
                }

                $arElementFields['IBLOCK_SECTION_ID'] = $tabId;
                unset($arElementFields['TAB']);
            }

            // also process files in props
            foreach ($arElementFields['PROPERTY_VALUES'] as $propKey => $propVal) {
                if ($arPropsInfo[$propKey]['PROPERTY_TYPE'] == 'F') {
                    if ($arPropsInfo[$propKey]['MULTIPLE'] === 'Y') {
                        if (!is_array($propVal)) {
                            continue;
                        }

                        $arElementFields['PROPERTY_VALUES'][$propKey] = [];
                        foreach ($propVal as $file) {
                            $arElementFields['PROPERTY_VALUES'][$propKey][] = Helpers\File::getDemo($imagesPath.$file);
                        }
                    }
                    else {
                        $arElementFields['PROPERTY_VALUES'][$propKey] = Helpers\File::getDemo($imagesPath . $propVal);
                    }
                }
                if ($arPropsInfo[$propKey]['PROPERTY_TYPE'] == 'L' && $propVal !== 'N') {
                    $listPropValues = Helpers\Iblock::getListPropValuesByXmlId($propKey, $elementsIblockId);
                    $arElementFields['PROPERTY_VALUES'][$propKey] = $listPropValues[$propVal];
                } elseif ($propVal === 'N') {
                    $arElementFields['PROPERTY_VALUES'][$propKey] = false;
                }
            }

            if ($elId = $el->Add($arElementFields)) {
                $res[] = $elId;
            }
        }

        return $res;
    }

    // TODO: refactor, put repeating parts of code to different methods
    public static function updateProps($id, $code, $props = [])
    {
        $blocksIblockId = self::getIblockId();
        $fields = [];

        if (!empty($props['COLS'])) { // need to update columns
            $fields['COLS'] = self::checkCols($code, $props['COLS']);
        }

        if (isset($props['INDENT_TOP'])) {
            $fields['INDENT_TOP'] = self::checkIndent($props['INDENT_TOP']);
        }
        if (isset($props['INDENT_BOT'])) {
            $fields['INDENT_BOT'] = self::checkIndent($props['INDENT_BOT']);
        }

        if (isset($props['HEIGHT'])) {
            $fields['HEIGHT'] = self::checkHeight($code, $props['HEIGHT']);
        }

        if (!empty($props['BG_COLOR'])) {
            $fields['BG_COLOR'] = self::checkColor($props['BG_COLOR']);
        }
        if (!empty($props['CARDS_BG_COLOR'])) {
            $fields['CARDS_BG_COLOR'] = self::checkColor($props['CARDS_BG_COLOR']);
        }

        if (!empty($props['TINT_COLOR'])) {
            $fields['TINT_COLOR'] = self::checkColor($props['TINT_COLOR']);
        } else {
            $fields['TINT_COLOR'] = '';
        }

        if (isset($props['TEXT_LIGHT'])) {
            if ($props['TEXT_LIGHT']) { // get enum id only if we need it
                $propValues = Helpers\Iblock::getListPropValuesByXmlId('TEXT_LIGHT', $blocksIblockId);
                $fields['TEXT_LIGHT'] = $propValues['Y'];
            } else {
                $fields['TEXT_LIGHT'] = false;
            }
        }
        if (isset($props['LINE_BOT'])) {
            if ($props['LINE_BOT']) { // get enum id only if we need it
                $propValues = Helpers\Iblock::getListPropValuesByXmlId('LINE_BOT', $blocksIblockId);
                $fields['LINE_BOT'] = $propValues['Y'];
            } else {
                $fields['LINE_BOT'] = false;
            }
        }
        if (isset($props['WIDE'])) {
            if ($props['WIDE']) {
                $propValues = Helpers\Iblock::getListPropValuesByXmlId('WIDE', $blocksIblockId);
                $fields['WIDE'] = $propValues['Y'];
            } else {
                $fields['WIDE'] = false;
            }
        }
        if (isset($props['HOVER_EFFECT'])) {
            if ($props['HOVER_EFFECT']) { // get enum id only if we need it
                $propValues = Helpers\Iblock::getListPropValuesByXmlId('HOVER_EFFECT', $blocksIblockId);
                $fields['HOVER_EFFECT'] = $propValues['LIFT_UP'];
            } else {
                $fields['HOVER_EFFECT'] = false;
            }
        }
        if (isset($props['INDENT_ELEMENTS'])) {
            if ($props['INDENT_ELEMENTS']) { // get enum id only if we need it
                $propValues = Helpers\Iblock::getListPropValuesByXmlId('INDENT_ELEMENTS', $blocksIblockId);
                $fields['INDENT_ELEMENTS'] = $propValues['Y'];
            } else {
                $fields['INDENT_ELEMENTS'] = false;
            }
        }
        if (isset($props['HIDE_TABS'])) {
            if ($props['HIDE_TABS']) { // get enum id only if we need it
                $propValues = Helpers\Iblock::getListPropValuesByXmlId('HIDE_TABS', $blocksIblockId);
                $fields['HIDE_TABS'] = $propValues['Y'];
            } else {
                $fields['HIDE_TABS'] = false;
            }
        }
        if (isset($props['ALIGN'])) {
            if ($props['ALIGN']) { // get enum id only if we need it
                $propValues = Helpers\Iblock::getListPropValuesByXmlId('ALIGN', $blocksIblockId);
                $fields['ALIGN'] = $propValues[$props['ALIGN']] ?? $propValues['default'];
            } else {
                $fields['ALIGN'] = false;
            }
        }
        if (isset($props['PICTURE_ALIGN'])) {
            if ($props['PICTURE_ALIGN']) { // get enum id only if we need it
                $propValues = Helpers\Iblock::getListPropValuesByXmlId('PICTURE_ALIGN', $blocksIblockId);
                $fields['PICTURE_ALIGN'] = $propValues[$props['PICTURE_ALIGN']] ?? $propValues['right'];
            } else {
                $fields['PICTURE_ALIGN'] = false;
            }
        }
        if (isset($props['BG_PICTURE'])) {
            $file = ($props['BG_PICTURE'] === 'del') ? ['del' => 'Y'] : Helpers\File::getImageFromBase64($props['BG_PICTURE']);
            if (!empty($file)) {
                $fields['BG_PICTURE'] = $file;
            }
        }
        if (isset($props['PARALLAX_EFFECT'])) {
            $fields['EFFECTS'] = false;
            if ($props['PARALLAX_EFFECT']) {
                $propValues = Helpers\Iblock::getListPropValuesByXmlId('EFFECTS', $blocksIblockId);
                $fields['EFFECTS'] = !empty($propValues['parallax']) ? [$propValues['parallax']] : false;
            }
        }
        if (isset($props['SLIDER'])) {
            $fields['SLIDER'] = false;
            if ($props['SLIDER']) {
                $propValues = Helpers\Iblock::getListPropValuesByXmlId('SLIDER', $blocksIblockId);
                $fields['SLIDER'] = $propValues['Y'] ?: false;
            }
        }

        if ($fields)
            \CIBlockElement::SetPropertyValuesEx($id, $blocksIblockId, $fields);
    }

    public static function checkCols($code, $cols)
    {
        $blockInfo = Config::getBlockInfo($code);

        if (!empty($blockInfo['COLS']) && in_array($cols, $blockInfo['COLS'])) {
            return $cols;
        }
        
        return $blockInfo['COLS_DEFAULT'] ?? 0;
    }

    public static function checkIndent($value)
    {
        return in_array($value, Config::$blocks['CONFIG']['INDENTS']) ? $value : 0;
    }

    public static function checkHeight($code, $value)
    {
        if (0 < $value && $value < 5000) {
            return $value;
        }

        $blockInfo = Config::getBlockInfo($code);
        return $blockInfo['BLOCK_HEIGHT_DEFAULT'] ?: false;
    }

    public static function checkColor($color)
    {
        if ($color == 'theme' || $color == 'transparent') {
            return $color;
        }
        return (strpos($color, '#') === 0 && strlen($color) === 7) ? $color : '#ffffff';
    }

    public static function getImportDataType($code)
    {
        $blockInfo = Config::getBlockInfo($code);
        return $blockInfo['IMPORT_DATA_TYPE'] ?? '';
    }

    public static function getElementIds($id)
    {
        $res = [];

        // get from GetList, because other methods require iblock id
        $dbRes = CIBlockElement::GetList([], ['ID' => $id], false, false, ['ID', 'IBLOCK_ID', 'PROPERTY_ELEMENTS']);
        while ($arRes = $dbRes->Fetch()) {
            if ($arRes['PROPERTY_ELEMENTS_VALUE'])
                $res[] = $arRes['PROPERTY_ELEMENTS_VALUE'];
        }

        return $res;
    }

    public static function getCards($id, $sort = [], $arSelectProps = [])
    {
        if (empty($id)) {
            return [];
        }
        $sort = array_filter($sort);

        $cards = [];
        $elementsIblockId = self::getElementsIblockId();
        $elementIds = self::getElementIds($id);
        $isEditMode = Config::isEditMode();
        if (!empty($elementIds)) {
            $dbCards = \CIBlockElement::GetList(
                $sort ?? ['SORT' => 'ASC'],
                ['ID' => $elementIds, 'IBLOCK_ID' => $elementsIblockId],
                false,
                false,
                $arSelectProps
            );

            $sort = 1;
            while ($arCard = $dbCards->Fetch()) {
                if ($arCard['NAME'] == self::EMPTY_TITLE) {
                    $arCard['NAME'] = '';
                }

                // autofix elements sort
                if ($isEditMode && $arCard['SORT'] != $sort) {
                    $arCard['SORT'] = $sort;
                    self::setSort($arCard['ID'], $sort);
                }
                $sort++;

                $cards[] = $arCard;
            }
        }

        return $cards;
    }

    public static function updateTitle($id, $data)
    {
        $blocksIblockId = self::getIblockId();
        $fields = [];

        if (isset($data['NAME']) && !empty($data['NAME'])) { // the only field that is not a prop
            $el = new \CIBlockElement;
            $el->Update($id, [
                'NAME' => $data['NAME'],
            ]);
        }

        if (isset($data['HIDE_TITLE'])) {
            if ($data['HIDE_TITLE']) { // get enum id only if we need it
                $propValues = Helpers\Iblock::getListPropValuesByXmlId('HIDE_TITLE', $blocksIblockId);
                $fields['HIDE_TITLE'] = $propValues['Y'];
            } else {
                $fields['HIDE_TITLE'] = false;
            }
        }

        if (isset($data['CATTITLE'])) {
            $fields['CATTITLE'] = trim($data['CATTITLE']);
        }
        if (isset($data['SUBTITLE'])) {
            $fields['SUBTITLE'] = trim($data['SUBTITLE']);
        }
        if (isset($data['DESC'])) {
            $fields['DESC'] = ['VALUE' => ['TEXT' => trim($data['DESC']), 'TYPE' => 'html']];
        }
        if (isset($data['ANCHOR_TITLE'])) {
            $fields['ANCHOR_TITLE'] = trim($data['ANCHOR_TITLE']);
        }

        if ($fields)
            \CIBlockElement::SetPropertyValuesEx($id, $blocksIblockId, $fields);
    }

    public static function updateTitleTag($id, $enumId)
    {
        if (!empty($enumId)) {
            $blocksIblockId = self::getIblockId();
            \CIBlockElement::SetPropertyValuesEx($id, $blocksIblockId, ['TITLE_TAG' => $enumId]);
        }
    }

    public static function updateButton($id, $data, $postFix = '')
    {
        $blocksIblockId = self::getIblockId();
        $fields = [];

        if (isset($data['BTN_SHOW'])) {
            if ($data['BTN_SHOW']) { // get enum id only if we need it
                $propValues = Helpers\Iblock::getListPropValuesByXmlId('BTN_SHOW' . $postFix, $blocksIblockId);
                $fields['BTN_SHOW' . $postFix] = $propValues['Y'];
            } else {
                $fields['BTN_SHOW' . $postFix] = false;
            }
        }

        if (isset($data['BTN_TYPE'])) {
            $fields['BTN_TYPE' . $postFix] = intval($data['BTN_TYPE']) > 0 ? intval($data['BTN_TYPE']) : false;
        }
        if (isset($data['BTN_SIZE'])) {
            $fields['BTN_SIZE' . $postFix] = intval($data['BTN_SIZE']) > 0 ? intval($data['BTN_SIZE']) : false;
        }
        if (isset($data['BTN_TEXT'])) {
            $fields['BTN_TEXT' . $postFix] = trim($data['BTN_TEXT']);
        }
        if (isset($data['BTN_LINK'])) {
            if ($data['BTN_LINK_TYPE'] === 'buy') {
                $data['BTN_LINK'] = Json::encode($data['BTN_LINK']);
            }
            $fields['BTN_LINK' . $postFix] = trim($data['BTN_LINK']);
        }
        if (isset($data['BTN_LINK_TYPE'])) {
            $fields['BTN_LINK_TYPE' . $postFix] = trim($data['BTN_LINK_TYPE']);
        }
        if (isset($data['BTN_GOAL'])) {
            $fields['BTN_GOAL' . $postFix] = trim($data['BTN_GOAL']);
        }
        if (isset($data['BTN_CLASS'])) {
            $fields['BTN_CLASS' . $postFix] = trim($data['BTN_CLASS']);
        }

        if ($fields)
            \CIBlockElement::SetPropertyValuesEx($id, $blocksIblockId, $fields);
    }

    public static function updateForm($id, $form, $btnText = '')
    {
        $blocksIblockId = self::getIblockId();
        \CIBlockElement::SetPropertyValuesEx($id, $blocksIblockId, ['FORM' => $form]);
        if ($btnText) {
            \CIBlockElement::SetPropertyValuesEx($id, $blocksIblockId, ['FORM_BTN_TEXT' => $btnText]);
        }
    }

    public static function updateVideo($id, $data)
    {
        $iblockId = self::getIblockId();
        $fields = [];

        $propValues = Helpers\Iblock::getListPropValuesByXmlId('HIDE_VIDEO', $iblockId);
        $fields['HIDE_VIDEO'] = $data['HIDE_VIDEO'] ? $propValues['Y'] : false;

        $fields['VIDEO_LINK'] = trim($data['VIDEO_LINK']);

        $propValues = Helpers\Iblock::getListPropValuesByXmlId('VIDEO_POPUP_SHOW', $iblockId);
        $fields['VIDEO_POPUP_SHOW'] = $data['VIDEO_POPUP_SHOW'] ? $propValues['Y'] : false;

        $fields['VIDEO_NOTE'] = trim($data['VIDEO_NOTE']);

        \CIBlockElement::SetPropertyValuesEx($id, $iblockId, $fields);
    }

    public static function updateAuto($id, $data)
    {
        $iblockId = self::getIblockId();
        $fields = [];

        $propValues = Helpers\Iblock::getListPropValuesByXmlId('AUTO_BLOCK', $iblockId);
        $fields['AUTO_BLOCK'] = $data['AUTO_BLOCK'] ? $propValues['Y'] : false;

        $fields['AUTO_TYPE'] = trim($data['AUTO_TYPE']);
        $fields['AUTO_COUNT'] = intval($data['AUTO_COUNT']);

        \CIBlockElement::SetPropertyValuesEx($id, $iblockId, $fields);
    }

    public static function updateTabs($id, $data)
    {
        $iblockId = self::getIblockId();
        $fields = [];

        $propValues = Helpers\Iblock::getListPropValuesByXmlId('USE_TABS', $iblockId);
        $fields['USE_TABS'] = !empty($data['USE_TABS']) ? $propValues['Y'] : false;

        \CIBlockElement::SetPropertyValuesEx($id, $iblockId, $fields);
    }

    public static function updateFilterSettings($id, $data, $code)
    {
        $iblockId = self::getIblockId();
        $value = ContentFilter::preparePostDataToSave($data, $code);
        $propCode = ContentFilter::getPropertyCode();

        \CIBlockElement::SetPropertyValuesEx($id, $iblockId, [$propCode => serialize($value)]);
    }

    public static function updateGalleryElements($blockId, $elementFields)
    {
        $block = Block::get($blockId);
        Block::cleanCache($blockId);

        $elementFieldsToUpdate = Config::getBlockElementsFields($block['CODE']);
        $elementsIblockId = Block::getElementsIblockId();

        $elementIds = Block::getElementIds($blockId);
        $elementsToDelete = array_diff($elementIds, array_keys($elementFields));
        $elementsToUpdate = [];

        $propCodes = [];
        foreach ($elementFieldsToUpdate as $field) {
            if (strpos($field, 'PROPERTY_') === 0) {
                $propCodes[] = substr($field, 9);
            }
        }
        $propInfo = Helpers\Iblock::getPropsInfoByCode($propCodes, $elementsIblockId);

        foreach ($elementFields as $elementId => $properties) {
            if ($elementId > 0) {
                Block::updateElement($elementId, $properties, $elementFieldsToUpdate, $propInfo);
                $elementsToUpdate[$elementId] = $properties;
            }
            if ($elementId < 0) {
                Block::addElement($blockId, $properties, $elementFieldsToUpdate, $propInfo, $block['CODE']);
            }
        }

        Block::updateElementsActiveAndSort($elementsToUpdate);
        Block::deleteElements($elementsToDelete);

        Helpers\File::removeTemp();
    }

    public static function addElement($blockId, $data, $fields, $propInfo, $code, $tabId = 0)
    {
        $fields = array_merge($fields, ['ACTIVE', 'SORT']);
        $blockInfo = Config::getBlockInfo($code);
        $demoFields = $blockInfo['DEMO']['ELEMENTS'][0] ?? [];
        $imagesPath = $_SERVER['DOCUMENT_ROOT'] . $blockInfo['_PATH'] . '/demo/';

        $validatedFields = self::proccessFieldsForElement($data, $fields, $propInfo, $demoFields, $imagesPath);

        $el = new \CIBlockElement;

        $arFields = $validatedFields['FIELDS'];
        $arProps  = $validatedFields['PROPS'];

        $arFields['IBLOCK_TYPE'] = 'ranx_landing';
        $arFields['IBLOCK_ID'] = Block::getElementsIblockId();

        if (!strlen($arFields['NAME'])) {
            $arFields['NAME'] = Block::EMPTY_TITLE;
        }
        if (empty($arFields['SORT'])) {
            $arFields['SORT'] = Block::getMaxSortByBlock($blockId);
        }

        if (!empty($tabId)) {
            $arFields['IBLOCK_SECTION_ID'] = $tabId;
        }

        if (!empty($arProps)) {
            $arFields['PROPERTY_VALUES'] = $arProps;
        }
        if (!empty($arFields)) {
            $elId = $el->Add($arFields);
        }

        if ($elId > 0) {
            $blocksIblockId = Block::getIblockId();

            $existingElementIds = [];
            $dbRes = \CIBlockElement::GetProperty($blocksIblockId, $blockId, 'sort', 'asc', ['CODE' => 'ELEMENTS']);
            while ($arRes = $dbRes->Fetch()) {
                $existingElementIds[] = $arRes['VALUE'];
            }

            $elementIds = array_merge($existingElementIds, [$elId]);

            \CIBlockElement::SetPropertyValuesEx($blockId, $blocksIblockId, ['ELEMENTS' => $elementIds]);

            $throughBlocks = self::getThroughBlocks($blockId);
            if (!empty($throughBlocks)) {
                foreach ($throughBlocks as $throughBlock) {
                    \CIBlockElement::SetPropertyValuesEx($throughBlock, $blocksIblockId, ['ELEMENTS' => $elementIds]);
                }
            }
        }
    }

    public static function updateElement($id, $data, $fields, $propInfo)
    {
        $validatedFields = self::proccessFieldsForElement($data, $fields, $propInfo);

        $el = new \CIBlockElement;

        $arFields = $validatedFields['FIELDS'];
        $arProps  = $validatedFields['PROPS'];

        if (!strlen($arFields['NAME'])) {
            $arFields['NAME'] = Block::EMPTY_TITLE;
        }

        if (!empty($arFields)) {
            if (!$el->Update($id, $arFields)) {
                throw new Exception('Element update error');
            }
        }

        if (!empty($arProps))
            \CIBlockElement::SetPropertyValuesEx($id, false, $arProps);
    }

    public static function updateElementsActiveAndSort($data)
    {
        $el = new \CIBlockElement;
        foreach ($data as $id => $fields) {
            $el->Update($id, [
                'ACTIVE' => $fields['ACTIVE'] == 'N' ? 'N' : 'Y',
                'SORT' => intval($fields['SORT']),
            ]);
        }
    }

    public static function getCardsSorts()
    {
        return [
            'SORT|ASC' => Loc::getMessage('RX_LANDING_LIB_BLOCK_CARDS_SORTS_SORT_ASC'),
            'NAME|ASC' => Loc::getMessage('RX_LANDING_LIB_BLOCK_CARDS_SORTS_NAME_ASC'),
            'DATE_CREATE|ASC' => Loc::getMessage('RX_LANDING_LIB_BLOCK_CARDS_SORTS_DATE_CREATE_ASC'),
            'DATE_CREATE|DESC' => Loc::getMessage('RX_LANDING_LIB_BLOCK_CARDS_SORTS_DATE_CREATE_DESC'),
        ];
    }

    public static function updateElementsSort($id, $sort = 'SORT|ASC')
    {
        if (!in_array($sort, array_keys(self::getCardsSorts()))) {
            $sort = 'SORT|ASC';
        }

        \CIBlockElement::SetPropertyValuesEx($id, false, ['ELEMENTS_SORT' => $sort]);
    }

    private static function proccessFieldsForElement($element, $fields, $propInfo, $demoFields = [], $imagesPath = '')
    {
        $res = [];

        $arFields = [];
        $arProps  = [];

        foreach ($fields as $field) {
            $isProperty = strpos($field, 'PROPERTY_') === 0;

            if (!$isProperty) {

                if (in_array($field, ['PREVIEW_PICTURE', 'DETAIL_PICTURE']) && empty($element[$field]) && !empty($demoFields[$field])) {
                    $fieldValue = Helpers\File::getDemo($imagesPath . $demoFields[$field]);
                } else {
                    $fieldValue = self::checkElementFieldValue($field, $element[$field]);
                }

                if (!is_null($fieldValue)) {
                    $arFields[$field] = $fieldValue;

                    if (in_array($field, ['PREVIEW_TEXT', 'DETAIL_TEXT'])) {
                        $arFields[$field . '_TYPE'] = 'html';
                    }
                }

            } elseif ($isProperty) {
                $propCode = substr($field, 9);
                $prop = $propInfo[$propCode];
                $isMultiple = $prop['MULTIPLE'] == 'Y';

                if (!$prop) {
                    continue;
                }

                if ($prop['PROPERTY_TYPE'] == 'F' && empty($element[$field]) && !empty($demoFields['PROPERTY_VALUES'][$propCode])) {
                    $demoFile = $demoFields['PROPERTY_VALUES'][$propCode];
                    if ($isMultiple) {
                        $propValue = [];
                        if (is_array($demoFile)) {
                            foreach ($demoFile as $file) {
                                $propValue[] = Helpers\File::getDemo($imagesPath . $file);
                            }
                        }
                    }
                    else {
                        $propValue = Helpers\File::getDemo($imagesPath . $demoFile);
                    }
                } else {
                    $propValue = self::checkElementPropValue($prop, $element[$field], $isMultiple);
                }

                if (!is_null($propValue)) {
                    $arProps[$propCode] = $propValue;
                }
            }
        }

        return [
            'FIELDS' => $arFields,
            'PROPS'  => $arProps,
        ];
    }

    private static function checkElementFieldValue($field, $value)
    {
        if (is_null($value)) {
            return null;
        }
        $res = null;

        if (in_array($field, ['NAME', 'SORT', 'ACTIVE', 'ACTIVE_FROM', 'ACTIVE_TO'])) {
            $res = trim($value);
        } elseif (in_array($field, ['PREVIEW_PICTURE', 'DETAIL_PICTURE'])) {
            if (Helpers\File::isBase64($value)) {
                $res = Helpers\File::getImageFromBase64($value);
            } elseif (strpos($value, 'del') === 0) {
                $res = ['del' => 'Y'];
            }
        } elseif (in_array($field, ['PREVIEW_TEXT', 'DETAIL_TEXT'])) {
            $res = trim($value);
        }

        return $res;
    }

    private static function checkElementPropValue($prop, $value, $isMultiple)
    {
        $res = null;

        if ($prop['PROPERTY_TYPE'] == 'L') {
            $res = $value ?? false;
        } elseif ($prop['USER_TYPE'] == 'HTML') {
            $res = ['VALUE' => ['TEXT' => trim($value), 'TYPE' => 'HTML']];
        } elseif ($prop['USER_TYPE'] == 'map_yandex') {
            $res = '';
            if (!empty($value['LAT']) && !empty($value['LON'])) {
                $res = trim($value['LAT']) . ',' . trim($value['LON']);
            }
        } elseif ($prop['PROPERTY_TYPE'] == 'F') {
            if ($isMultiple) {
                $res = [];
                foreach ($value as $file) {
                    if (strpos($file, 'del') === 0 || empty($file)) {
                        continue;
                    }
                    elseif (Helpers\File::isBase64($file)){
                        $file = Helpers\File::fromBase64($file);
                    }
                    else {
                        $file = \CFile::MakeFileArray($file);
                    }

                    if (!empty($file)) {
                        $res[] = $file;
                    }
                }
                if (empty($res)) {
                    $res = ['del' => 'Y'];
                }
            }
            else {
                if (Helpers\File::isBase64($value)) {
                    $res = Helpers\File::fromBase64($value);
                } elseif (strpos($value, 'del') === 0 || empty($value)) {
                    $res = ['del' => 'Y'];
                }
            }
        } elseif ($prop['PROPERTY_TYPE'] == 'S') {
            if (!$isMultiple) {
                if ($prop['WITH_DESCRIPTION'] == 'Y') {
                    $res = $value;
                } elseif (is_array($value)) {
                    $res = Json::encode($value, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE);
                } else {
                    $res = trim($value);
                }
            } else {
                $res = array_filter($value);
                if (empty($res)) {
                    $res = false;
                }
            }
        } elseif ($prop['PROPERTY_TYPE'] == 'N') {
            if (!$isMultiple) {
                $res = (isset($value) && is_numeric($value)) ? $value : false;
            }
            else {
                if (!is_array($value))
                    $value = (array)$value;
                $res = array_filter($value, function ($v) { return isset($v) && is_numeric($v); });
                if (empty($res)) {
                    $res = false;
                }
            }
        }

        return $res;
    }

    public static function deleteElements($elementIds)
    {
        if (empty($elementIds)) {
            return false;
        }

        $elementsInfo = Helpers\Iblock::getElementsInfoByIds($elementIds);

        foreach ($elementIds as $elId) {
            if (empty($elementsInfo[$elId])) {
                continue;
            }
            $info = $elementsInfo[$elId];

            // strong check (-_-)
            if (
                strpos($info['IBLOCK_CODE'], 'ranx_landing_') !== 0
                || strpos($info['IBLOCK_CODE'], 'ranx_landing_list_') !== false
                || strpos($info['IBLOCK_CODE'], 'ranx_landing_form_') !== false
                || strpos($info['IBLOCK_CODE'], 'ranx_landing_blocks') !== false
            ) {
                continue;
            }

            Helpers\Iblock::removeElement($elId);
        }

        return true;
    }

    public static function hideElements($id, $iblockId = false)
    {
        if (!$iblockId) {
            $iblockId = Helpers\Iblock::getIblockIdByElementId($id);
        }

        $propValues = Helpers\Iblock::getListPropValuesByXmlId('HIDE_ELEMENTS', $iblockId);
        if ($propValues['Y'])
            \CIBlockElement::SetPropertyValuesEx($id, $iblockId, ['HIDE_ELEMENTS' => $propValues['Y']]);

        return true;
    }

    public static function showElements($id)
    {
        \CIBlockElement::SetPropertyValuesEx($id, false, ['HIDE_ELEMENTS' => false]);

        return true;
    }

    public static function updateImportElementsFields($id, $data)
    {
        $fields = [];
        $iblockId = self::getIblockId();

        $propValues = Helpers\Iblock::getListPropValuesByXmlId('IMPORT_ELEMENTS', $iblockId);
        $fields['IMPORT_ELEMENTS'] = !empty($data['IMPORT_ELEMENTS']) ? $propValues['Y'] : false;

        $fields['IMPORT_ID'] = is_numeric($data['IMPORT_ID']) ? intval($data['IMPORT_ID']) : false;
        $fields['IMPORT_SECTION_ID'] = is_numeric($data['IMPORT_SECTION_ID']) ? intval($data['IMPORT_SECTION_ID']) : false;
        $fields['ELEMENTS_COUNT'] = is_numeric($data['ELEMENTS_COUNT']) ? intval($data['ELEMENTS_COUNT']) : false;
        $fields['IMPORT_PRICE_ID'] = is_numeric($data['IMPORT_PRICE_ID']) ? intval($data['IMPORT_PRICE_ID']) : false;

        $fields['IMPORT_FILTERS'] = false;
        $filtersValues = Helpers\Iblock::getListPropValuesByValue('IMPORT_FILTERS', $iblockId);
        if (in_array(intval($data['IMPORT_FILTERS']), $filtersValues)){
            $fields['IMPORT_FILTERS'] = intval($data['IMPORT_FILTERS']);
        }

        $fields['IMPORT_SORT'] = false;
        $filtersValues = Helpers\Iblock::getListPropValuesByValue('IMPORT_SORT', $iblockId);
        if (in_array(intval($data['IMPORT_SORT']), $filtersValues)){
            $fields['IMPORT_SORT'] = intval($data['IMPORT_SORT']);
        }

        $fields['IMPORT_SORT_ORDER'] = false;
        $filtersValues = Helpers\Iblock::getListPropValuesByValue('IMPORT_SORT_ORDER', $iblockId);
        if (in_array(intval($data['IMPORT_SORT_ORDER']), $filtersValues)){
            $fields['IMPORT_SORT_ORDER'] = intval($data['IMPORT_SORT_ORDER']);
        }

        $fields['IMPORT_LINK_TYPE'] = false;
        $filtersValues = Helpers\Iblock::getListPropValuesByValue('IMPORT_LINK_TYPE', $iblockId);
        if (in_array(intval($data['IMPORT_LINK_TYPE']), $filtersValues)){
            $fields['IMPORT_LINK_TYPE'] = intval($data['IMPORT_LINK_TYPE']);
        }

        $arIds = array_filter($data['IMPORT_ELEM_IDS']);
        $fields['IMPORT_ELEM_IDS'] = !empty($arIds) ? $arIds : false;

        \CIBlockElement::SetPropertyValuesEx($id, $iblockId, $fields);
    }

    public static function updatePictureFromBase64($id, $pic, $type = 'PREVIEW_PICTURE')
    {
        if (!$pic || !$id) {
            return false;
        }

        if ($pic == 'del') {
            $file = ['del' => 'Y'];
        } else {
            $file = Helpers\File::getImageFromBase64($pic);
        }

        if (!$file) {
            return false;
        }

        $el = new CIBlockElement;
        $el->Update($id, [
            $type => $file,
        ]);

        return true;
    }

    public static function getPreviewImg($code)
    {
        if (!$code) {
            return '';
        }

        $blockInfo = Config::getBlockInfo($code);
        $previewFile = $blockInfo['_PATH'] . '/preview.png';

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $previewFile)) {
            return $previewFile;
        }

        return '/bitrix/images/' . Config::MODULE_ID . '/custom.png';
    }

    public static function cleanCache($id)
    {
        if (intval($id) <= 0) {
            return false;
        }

        $cache = Data\Cache::createInstance();
        $cache->clean('ranx_block_landing_'.$id, 'ranx_block_landing');
        $cache->clean('ranx_filter_params_'.$id, Helpers\SmartFilter::CACHE_DIR);
        $cache->cleanDir('block_'.$id, 'cache/ranx_block_landing');
    }

    public static function getFirstElementName($id)
    {
        $iblockId = self::getIblockId();
        $dbRes = CIBlockElement::GetProperty($iblockId, $id, 'sort', 'asc', ['CODE' => 'ELEMENTS']);
        if ($arRes = $dbRes->Fetch()) {
            $element = CIBlockElement::GetByID($arRes['VALUE'])->Fetch();
            return $element['NAME'] ?? '';
        }

        return '';
    }

    public static function changeCode($id, $newCode)
    {
        $el = new CIBlockElement;
        $el->Update($id, [
            'CODE' => $newCode,
        ]);
    }

    public static function copy($id, $landingId = false, $landingMode = false, $siteId = false, $copyElements = true, $newSectionId = false)
    {
        $newId = Helpers\Iblock::copyElement($id);

        if ($newId > 0) {
            $block = self::get($newId, [
                'PROPERTY_LANDING',
                'PROPERTY_MODE',
                'PROPERTY_THROUGH_ID',
            ]);

            if (!$landingId) {
                $landingId = $block['PROPERTY_LANDING_VALUE'];
            }
            if (!$landingMode) {
                $landingMode = $block['PROPERTY_MODE_VALUE'] ?: Landing::MODE_ELEMENT;
            }
            if (!$siteId) {
                $siteId = SITE_ID;
            }

            $newFields = [];


            if ($newSectionId) {
                $newFields['IBLOCK_SECTION_ID'] = $newSectionId;
            } else {
                $maxSort = self::getMaxSortByLanding($landingId, $landingMode, $siteId);
                if ($maxSort) {
                    $newFields['SORT'] = $maxSort;
                }
            }
            if (!empty($newFields)) {
                $el = new \CIBlockElement();
                $el->Update($newId, $newFields);
            }

            $newProps = [];
            $newProps['LANDING'] = $landingId;
            $newProps['MODE'] = $landingMode;
            $newProps['THROUGH_ID'] = '';

            $elementIds = self::getElementIds($newId);
            if ($copyElements) {
                $newIds = BlockTabs::copy($newId);
                $newProps['ELEMENTS'] = $newIds['ELEMENTS'];
                $newProps['TABS'] = $newIds['TABS'];
            } elseif (!empty($elementIds)) {
                if (!$block['PROPERTY_THROUGH_ID_VALUE']) {
                    $throughId = self::generateThroughId($elementIds);
                    $newProps['THROUGH_ID'] = $throughId;
                    self::makeThrough($id, $throughId);
                } else {
                    $newProps['THROUGH_ID'] = $block['PROPERTY_THROUGH_ID_VALUE'];
                }
            }

            \CIBlockElement::SetPropertyValuesEx($newId, false, $newProps);
        }

        return $newId;
    }

    public static function makeThrough($id, $throughId)
    {
        if (!$throughId) {
            $elementIds = self::getElementIds($id);
            if (!empty($elementIds)) {
                $throughId = self::generateThroughId($elementIds);
            }
        }

        if ($throughId) {
            \CIBlockElement::SetPropertyValuesEx($id, false, ['THROUGH_ID' => $throughId]);
            return true;
        }

        return false;
    }

    public static function deleteByLanding($landingId, $mode)
    {
        $blocks = self::getByLanding($landingId, $mode, true);
        foreach ($blocks as $block) {
            if ($block['IBLOCK_SECTION_ID']) {
                BlockGroup::remove($block['IBLOCK_SECTION_ID']);
            } else {
                self::remove($block['ID']);
            }
        }
    }

    public static function isExists($code)
    {
        return !empty(Config::$blocks['LIST'][$code]);
    }

    public static function getGroupId($id)
    {
        $block = Block::get($id);
        return $block['IBLOCK_SECTION_ID'] ?? 0;
    }

    public static function updateRegions($id, $include = [], $exclude = [], $includeBranches = [], $excludeBranches = [])
    {
        $props = [
            'REGION_INCLUDE' => !empty($include) ? $include : false,
            'REGION_EXCLUDE' => !empty($exclude) ? $exclude : false,
        ];

        if (Config::useRegionBranches()) {
            $props['BRANCH_INCLUDE'] = !empty($includeBranches) ? $includeBranches : false;
            $props['BRANCH_EXCLUDE'] = !empty($excludeBranches) ? $excludeBranches : false;
        }

        $iblockId = self::getIblockId();
        \CIBlockElement::SetPropertyValuesEx($id, $iblockId, $props);
    }

    public static function getIncludeRegions($id)
    {
        $regions = [];
        $iblockId = self::getIblockId();

        $dbRes = \CIBlockElement::GetProperty($iblockId, $id, 'sort', 'asc', ['CODE' => 'REGION_INCLUDE']);
        while ($arRes = $dbRes->Fetch()) {
            $regions[] = $arRes['VALUE'];
        }

        return Region::getRegionsById($regions);
    }

    public static function getExcludeRegions($id)
    {
        $regions = [];
        $iblockId = self::getIblockId();

        $dbRes = \CIBlockElement::GetProperty($iblockId, $id, 'sort', 'asc', ['CODE' => 'REGION_EXCLUDE']);
        while ($arRes = $dbRes->Fetch()) {
            $regions[] = $arRes['VALUE'];
        }

        return Region::getRegionsById($regions);
    }

    public static function getIncludeBranches($id)
    {
        $branches = [];
        $iblockId = self::getIblockId();

        $dbRes = \CIBlockElement::GetProperty($iblockId, $id, 'sort', 'asc', ['CODE' => 'BRANCH_INCLUDE']);
        while ($arRes = $dbRes->Fetch()) {
            $branches[] = $arRes['VALUE'];
        }

        return Region::getBranchesById($branches);
    }

    public static function getExcludeBranches($id)
    {
        $branches = [];
        $iblockId = self::getIblockId();

        $dbRes = \CIBlockElement::GetProperty($iblockId, $id, 'sort', 'asc', ['CODE' => 'BRANCH_EXCLUDE']);
        while ($arRes = $dbRes->Fetch()) {
            $branches[] = $arRes['VALUE'];
        }

        return Region::getBranchesById($branches);
    }

    public static function removeRegions($id)
    {
        $iblockId = self::getIblockId();
        \CIBlockElement::SetPropertyValuesEx($id, $iblockId, [
            'REGION_INCLUDE' => false,
            'REGION_EXCLUDE' => false,
            'BRANCH_INCLUDE' => false,
            'BRANCH_EXCLUDE' => false,
        ]);
    }

    private static function canDeleteElements($throughId)
    {
        if (!$throughId) {
            return true;
        }

        $iblockId = self::getIblockId();
        $dbRes = \CIBlockElement::GetList([], ['IBLOCK_ID' => $iblockId, '=PROPERTY_THROUGH_ID' => $throughId]);
        $count = $dbRes->SelectedRowsCount();

        if ($count > 1) {
            return false;
        }

        return true;
    }

    private static function generateThroughId($elementIds)
    {
        if (empty($elementIds)) {
            return '';
        }
        return 'rx' . md5(implode('_', $elementIds));
    }

    private static function getThroughId($id)
    {
        $iblockId = self::getIblockId();
        $arRes = \CIBlockElement::GetProperty($iblockId, $id, 'sort', 'asc', ['CODE' => 'THROUGH_ID'])->Fetch();
        return $arRes['VALUE'] ?? '';
    }

    private static function getThroughBlocks($id)
    {
        $throughId = self::getThroughId($id);
        if (!$throughId) {
            return [];
        }

        $res = [];
        $iblockId = self::getIblockId();
        $dbRes = \CIBlockElement::GetList([], ['IBLOCK_ID' => $iblockId, 'PROPERTY_THROUGH_ID' => $throughId]);
        while ($arRes = $dbRes->Fetch()) {
            $res[] = $arRes['ID'];
        }

        return $res;
    }

    public static function canUseBranches($code)
    {
        $blockInfo = Config::getBlockInfo($code);
        return Config::isRegionEnabled() && Config::useRegionBranchesOnMap() && !empty($blockInfo['CAN_SHOW_BRANCHES']);
    }

    public static function canUseRegions($code)
    {
        $blockInfo = Config::getBlockInfo($code);
        return Config::isRegionEnabled() && Config::useRegionsOnMap() && !empty($blockInfo['CAN_SHOW_REGIONS']);
    }

    public static function isUseMasonryPlugin($code)
    {
        if (empty($code)) {
            return false;
        }

        $blockInfo = Config::getBlockInfo($code);
        return !empty($blockInfo['USE_MASONRY_PLUGIN']);
    }

    public static function formatAlign($code, $value)
    {
        if (empty($value) || $value === 'default') {
            return Config::getBlockDefaultAlign($code);
        }

        return $value;
    }

    public static function formatTitleTag($prop)
    {
        $value = $prop['VALUE_XML_ID'];
        if (empty($value)) {
            $value = Helpers\Iblock::getListPropDefaultValue($prop['CODE'], $prop['IBLOCK_ID'])['XML_ID'];
        }

        return $value;
    }
}
