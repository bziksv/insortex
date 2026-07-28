<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

use Ranx\Landing\Page;
use Bitrix\Main\Loader;
use Ranx\Landing\Block;
use Ranx\Landing\Event;
use Ranx\Landing\Region;
use Ranx\Landing\Fields;
use Ranx\Landing\Config;
use Ranx\Landing\Landing;
use Ranx\Landing\Helpers;
use Ranx\Landing\BlockTabs;
use Bitrix\Main\Data\Cache;
use Ranx\Landing\BlockFilter;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\Contract\Controllerable;
use Ranx\Landing\Panel\Settings as PanelSettings;
use Ranx\Landing\Section\Manager as SectionManager;

/**
 * Component that shows one block on landing
 */
class RanxBlockLandingComponent extends CBitrixComponent implements Controllerable
{
    const ELEMENT_PROP_KEYS = [
        'NAME',
        'ID',
        'CODE',
        'VALUE',
        'DESCRIPTION',
        'VALUE_ENUM',
        'VALUE_XML_ID',
        '~VALUE',
        '~NAME',
        '~DESCRIPTION'
    ];

    private $arNavParams = false;
    private $filterObj = false;

    /**
     * NOTE! It is very IMPORTANT to add actions here
     * else they will not work on unauthorized users!
     *
     * @return array
     */
    public function configureActions()
    {
        return [
            'cardModal' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'videoModal' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'smartFilter' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'blockFilter' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
        ];
    }

    public function onPrepareComponentParams($arParams)
    {
        $arParams['DETAIL_ID'] = intval($arParams['DETAIL_ID']);
        if (empty($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = '36000000';
        }

        return parent::onPrepareComponentParams($arParams);
    }

    public function executeComponent()
    {
        Loader::includeModule('iblock');

        if ($this->arParams['DETAIL_ID'] <= 0 || $this->arParams['IBLOCK_ID'] <= 0) {
            //Iblock\Component\Tools::process404(Loc::getMessage('RX_BLOCK_LANDING_NOT_FOUND'), false, false, false, '');
            return;
        }

        $blockCode = $this->GetTemplateName();

        $elementsCount = isset($this->arParams['CARDS_COUNT']) ? $this->arParams['CARDS_COUNT'] : Config::getSectionElementsCount();
        if (Config::isProductList($blockCode)) {
            $elementsCount = Config::getSectionProductsCount();
        }
        if (Config::isNewsList($blockCode)) {
            $elementsCount = Config::getSectionNewsCount();
        }
        if ($elementsCount) {
            $this->arNavParams = [
                'nPageSize' => $elementsCount,
            ];
            \CPageOption::SetOptionString('main', 'nav_page_in_session', 'N');
            $arNavigation = \CDBResult::GetNavParams($this->arNavParams);
        }

        $useFilter = Config::isFilterEnabled($blockCode);
        if (!empty($useFilter)) {
            if (Config::isBlockFilter($blockCode)) {
                $arFilterParams = [
                    'BLOCK_ID' => $this->arParams['DETAIL_ID'],
                    'BLOCK_IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                    'BLOCK_CODE' => $blockCode
                ];
                $this->filterObj = new BlockFilter($arFilterParams, $this->arParams['PREFILTER']);
            }
            else {
                $this->filterObj = new Helpers\SmartFilter(
                    $this->arParams['DETAIL_ID'],
                    Config::getFilterTemplate($blockCode),
                    $this->arParams['REQUEST_URL'],
                );
            }
        }

        $cache = Cache::createInstance();
        $cacheId = 'ranx_block_landing_'.$this->arParams['DETAIL_ID'];
        $isCacheAdditions = false;

        if ($arNavigation) {
            $cacheId .= '|'.serialize($arNavigation);
            $isCacheAdditions = true;
        }
        if ($useFilter) {
            $cacheId .= '|'.serialize($this->filterObj->getConditions());
            $isCacheAdditions = true;
        }
        if (Block::canUseRegions($blockCode)) {
            $cacheId .= '|region';
            $isCacheAdditions = true;
        }
        if (Block::canUseBranches($blockCode)) {
            $cacheId .= '|'.Region::getCurrent()['ID'];
            $isCacheAdditions = true;
        }

        $cacheDir = 'ranx_block_landing'.($isCacheAdditions ? '/block_'.$this->arParams['DETAIL_ID'] : '');
        if (!Config::isEditMode() && $cache->initCache($this->arParams['CACHE_TIME'], $cacheId, $cacheDir)) {
            $vars = $cache->getVars();
            $this->arResult = $vars['arResult'];
        } elseif ($cache->startDataCache()) {
            $this->getBlockInfo();

            if (empty($this->arResult['ID'])) {
                $cache->abortDataCache();
                return;
            }

            $tagCacheDir = $cacheDir;
            if (!$isCacheAdditions) {
                $tagCacheDir .= '/'.substr(md5($cacheId), 0, 2);
            }
            $GLOBALS['CACHE_MANAGER']->StartTagCache($tagCacheDir);
            $isTagCache = true;

            // get elements
            $this->arResult['ITEMS'] = [];
            if ($this->arResult['AUTO_BLOCK']) {
                if ($this->arResult['AUTO_TYPE'] == 'element') {
                    $this->arResult['ITEMS'] = $this->getAutoElements();
                }
                if ($this->arResult['AUTO_TYPE'] == 'section') {
                    $this->arResult['ITEMS'] = $this->getAutoSections();
                }
                if ($this->arResult['AUTO_TYPE'] == 'detail') {
                    $this->arResult['ITEMS'] = $this->getAutoDetail();
                }

                if (Config::isCategoriesIncluded($this->arResult['CODE'])) {
                    $this->arResult['CATEGORIES'] = $this->getCategories();
                }
            }
            elseif ($this->arResult['IMPORT_ELEMENTS']) {
                if ($this->arResult['IMPORT_DATA_TYPE'] === 'NEWS') {
                    $this->arResult['ITEMS'] = $this->getImportNews();
                }
                if ($this->arResult['IMPORT_DATA_TYPE'] === 'PRODUCTS') {
                    $this->arResult['ITEMS'] = $this->getImportProducts();
                }
            }
            elseif (!$this->arResult['HIDE_ELEMENTS']) {
                if (Block::canUseBranches($this->arResult['CODE']) ||
                    Block::canUseRegions($this->arResult['CODE'])) {
                    $elementIds = [];

                    $GLOBALS['CACHE_MANAGER']->RegisterTag('rx_settings_dependent_blocks');

                    if (Block::canUseBranches($this->arResult['CODE'])) {
                        $branchIds = $this->getBranchIds();
                        $elementIds = array_merge($elementIds, $branchIds);
                    }
                    if (Block::canUseRegions($this->arResult['CODE'])) {
                        $regionIds = $this->getRegionIds();
                        $elementIds = array_merge($elementIds, $regionIds);
                    }

                    $this->arResult['ELEMENT_IDS'] = $elementIds;
                }
                $GLOBALS['CACHE_MANAGER']->EndTagCache();
                $isTagCache = false;

                $this->arResult['ITEMS'] = $this->getElements();
            }

            if ($isTagCache) {
                $GLOBALS['CACHE_MANAGER']->EndTagCache();
            }

            $cache->endDataCache(['arResult' => $this->arResult]);
        }

        // OUT OF CACHE
        $this->arResult['CLASSES'] = $this->getCssClasses();
        $this->arResult['DATA_ATTRS'] = $this->getDataAttrs();

        $this->arResult['BLOCK_TITLE'] = $this->getBlockTitle();
        $this->arResult['BLOCK_TABS'] = $this->getBlockTabs();
        $this->arResult['BLOCK_START'] = $this->getBlockStart();
        $this->arResult['BLOCK_FILTER'] = $this->getBlockFilter();
        $this->arResult['BLOCK_END'] = $this->getBlockEnd();
		
        $this->arParams['SETTINGS'] = PanelSettings::fillValues($this->arResult['CODE'], unserialize($this->arResult['DETAIL_TEXT']) ?: []);

        $this->requestVendors();

        foreach ($this->arResult['ITEMS'] as &$arItem) {
            $arItem = $this->formatElementFieldsOutOfCache($arItem);
        }

        if (Config::isSupportTabs($this->arResult['CODE'])) {
            $this->arResult['GROUPS'] = $this->groupByTabs($this->arResult['ITEMS']);
        }

        $this->includeComponentTemplate();

        // manually include styles and scripts for template in ajax
        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        if ($request->isAjaxRequest()) {
            $this->includeTemplateStyles();
            $this->includeTemplateScripts();
        }
    }

    private function getBlockInfo()
    {
        $arFilter = [
            'ID'          => $this->arParams['DETAIL_ID'],
            'IBLOCK_TYPE' => $this->arParams['IBLOCK_TYPE'],
            'IBLOCK_ID'   => $this->arParams['IBLOCK_ID'],
        ];
        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'IBLOCK_SECTION_ID',
            'NAME',
            'SORT',
            'CODE',
            'DETAIL_TEXT',
            'PREVIEW_PICTURE',
            'DETAIL_PICTURE',
        ];

        $rsBlock = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);

        $elementIds = [];
        if ($obBlock = $rsBlock->GetNextElement()) {
            $arBlock = $obBlock->GetFields();
            $arBlockProps = $obBlock->GetProperties();

            $elementIds = $arBlockProps['ELEMENTS']['VALUE'];

            $this->arResult['ID'] = $arBlock['ID'];
            $this->arResult['IBLOCK_ID'] = $arBlock['IBLOCK_ID'];
            $this->arResult['IBLOCK_SECTION_ID'] = $arBlock['IBLOCK_SECTION_ID'];
            $this->arResult['NAME'] = $arBlock['~NAME'];
            if ($this->arResult['NAME'] == Block::EMPTY_TITLE) {
                $this->arResult['NAME'] = '';
                $this->arResult['~NAME'] = '';
            }
            $this->arResult['SORT'] = $arBlock['SORT'];
            $this->arResult['CODE'] = $arBlock['CODE'];
            $this->arResult['PREVIEW_PICTURE'] = $arBlock['PREVIEW_PICTURE'];
            $this->arResult['DETAIL_PICTURE'] = $arBlock['DETAIL_PICTURE'];
            $this->arResult['DETAIL_TEXT'] = $arBlock['~DETAIL_TEXT'];

            $this->arResult['FORM'] = $arBlockProps['FORM']['VALUE'];

            $this->arResult['SUBTITLE'] = $arBlockProps['SUBTITLE']['~VALUE'];
            $this->arResult['CATTITLE'] = $arBlockProps['CATTITLE']['~VALUE'];
            $this->arResult['DESC'] = $arBlockProps['DESC']['~VALUE'];
            $this->arResult['HIDE_TITLE'] = $arBlockProps['HIDE_TITLE']['VALUE'] == 'Y';

            $this->arResult['HIDE_ELEMENTS'] = $arBlockProps['HIDE_ELEMENTS']['VALUE'] == 'Y';
            $this->arResult['TITLE_TAG'] = Block::formatTitleTag($arBlockProps['TITLE_TAG']);
            $this->arResult['ELEMENTS_SORT'] = $arBlockProps['ELEMENTS_SORT']['VALUE'];

            $themeColor = Config::getThemeColor();
            $this->arResult['BG_COLOR'] = $arBlockProps['BG_COLOR']['VALUE'] == 'theme' ? $themeColor : $arBlockProps['BG_COLOR']['VALUE'];
            $this->arResult['CARDS_BG_COLOR'] = $arBlockProps['CARDS_BG_COLOR']['VALUE'] == 'theme' ? $themeColor : $arBlockProps['CARDS_BG_COLOR']['VALUE'];
            $this->arResult['TINT_COLOR'] = $arBlockProps['TINT_COLOR']['VALUE'];
            $this->arResult['TEXT_LIGHT'] = $arBlockProps['TEXT_LIGHT']['VALUE'] == 'Y';
            $this->arResult['INDENT_TOP'] = Block::checkIndent($arBlockProps['INDENT_TOP']['VALUE']);
            $this->arResult['INDENT_BOT'] = Block::checkIndent($arBlockProps['INDENT_BOT']['VALUE']);
            $this->arResult['BLOCK_HEIGHT'] = Block::checkHeight($this->arResult['CODE'], $arBlockProps['HEIGHT']['VALUE']);
            $this->arResult['LINE_BOT'] = $arBlockProps['LINE_BOT']['VALUE'] == 'Y';
            $this->arResult['SLIDER'] = $arBlockProps['SLIDER']['VALUE'] == 'Y';
            $this->arResult['WIDE'] = $arBlockProps['WIDE']['VALUE'] == 'Y';
            $this->arResult['HOVER_EFFECT'] = $arBlockProps['HOVER_EFFECT']['VALUE_XML_ID'] == 'LIFT_UP';
            $this->arResult['ALIGN'] = Block::formatAlign($arBlock['CODE'], $arBlockProps['ALIGN']['VALUE']);
            $this->arResult['INDENT_ELEMENTS'] = $arBlockProps['INDENT_ELEMENTS']['VALUE'] == 'Y';
            $this->arResult['PICTURE_ALIGN'] = $arBlockProps['PICTURE_ALIGN']['VALUE'];
            $this->arResult['HIDE_TABS'] = isset($arBlockProps['HIDE_TABS']) && $arBlockProps['HIDE_TABS']['VALUE'] == 'Y';
            $this->arResult['BG_PICTURE'] = CFile::GetFileArray($arBlockProps['BG_PICTURE']['VALUE'])['SRC'] ?? '';
            $this->arResult['PARALLAX_EFFECT'] = in_array('parallax', $arBlockProps['EFFECTS']['VALUE_XML_ID'] ?: []);

            $this->arResult['COLS'] = Block::checkCols($this->arResult['CODE'], $arBlockProps['COLS']['VALUE']);

            $btn1 = $this->getBtn($arBlockProps, $this->arResult['NAME']);
            $btn2 = $this->getBtn($arBlockProps, $this->arResult['NAME'], '_2');

            $btns = '';
            if ($btn1 || $btn2) {
                $btns = '<div class="block-btns">';
                $btns .= $btn1 ? '<div>' . $btn1 . '</div>' : '';
                $btns .= $btn2 ? '<div>' . $btn2 . '</div>' : '';
                $btns .= '</div>';
            }

            $this->arResult['BTN'] = '';
            $this->arResult['BTN_TITLE'] = '';
            if (Config::getBlockTitlePosition($this->arResult['CODE']) == 'left') {
                $this->arResult['BTN_TITLE'] = $btns;
            } else {
                $this->arResult['BTN'] = $btns;
            }
            $this->arResult['BTN1'] = $btn1;
            $this->arResult['BTN2'] = $btn2;
            $this->arResult['FORM_BTN_TEXT'] = $arBlockProps['FORM_BTN_TEXT']['VALUE'];

            // landing props
            $this->arResult['MODE'] = $arBlockProps['MODE']['VALUE'] ?: Landing::MODE_ELEMENT;
            if ($this->arResult['MODE'] == Landing::MODE_SECTIONS || $this->arResult['MODE'] == Landing::MODE_ROOT_SECTION) {
                $this->arResult['LANDING_IBLOCK_ID'] = $arBlockProps['LANDING']['VALUE'];
                $this->arResult['LANDING_ID'] = $arBlockProps['LANDING']['VALUE'];
            } elseif ($this->arResult['MODE'] == Landing::MODE_SECTION) {
                $sectionId = $arBlockProps['LANDING']['VALUE'];
                $this->arResult['LANDING_IBLOCK_ID'] = Helpers\Iblock::getIblockIdBySectionId($sectionId);
                $this->arResult['LANDING_ID'] = $sectionId;
            } else {
                $elementId = $arBlockProps['LANDING']['VALUE'];
                $this->arResult['LANDING_IBLOCK_ID'] = Helpers\Iblock::getIblockIdByElementId($elementId);
                $this->arResult['LANDING_ID'] = $elementId;
            }
            $this->arResult['SECTION_DIR'] = SectionManager::getByIblockId($this->arResult['LANDING_IBLOCK_ID'])['PATH'];

            //import props
            $this->arResult['IMPORT_ELEMENTS'] = $arBlockProps['IMPORT_ELEMENTS']['VALUE'] == 'Y';
            $this->arResult['IMPORT_DATA_TYPE'] = Block::getImportDataType($this->arResult['CODE']);
            $this->arResult['IMPORT_ID'] = $arBlockProps['IMPORT_ID']['VALUE'];
            $this->arResult['IMPORT_FILTERS'] = $arBlockProps['IMPORT_FILTERS']['VALUE_XML_ID'] ?? 'LAST_ACTIVE';
            $this->arResult['ELEMENTS_COUNT'] = $arBlockProps['ELEMENTS_COUNT']['VALUE'] ?? 0;
            $this->arResult['IMPORT_ELEM_IDS'] = $arBlockProps['IMPORT_ELEM_IDS']['VALUE'] ?? [];
            $this->arResult['IMPORT_SECTION_ID'] = $arBlockProps['IMPORT_SECTION_ID']['VALUE'] ?? 0;
            $this->arResult['IMPORT_SORT'] = $arBlockProps['IMPORT_SORT']['VALUE_XML_ID'] ?? 'ID';
            $this->arResult['IMPORT_SORT_ORDER'] = $arBlockProps['IMPORT_SORT_ORDER']['VALUE_XML_ID'] ?? 'ASC';
            $this->arResult['IMPORT_PRICE_ID'] = $arBlockProps['IMPORT_PRICE_ID']['VALUE'];
            $this->arResult['IMPORT_LINK_TYPE'] = $arBlockProps['IMPORT_LINK_TYPE']['VALUE_XML_ID'];
            if (empty($this->arResult['IMPORT_LINK_TYPE'])) {
                //backward compatibility
                $this->arResult['IMPORT_LINK_TYPE'] = 'empty';
                if ($this->arResult['IMPORT_DATA_TYPE'] === 'PRODUCTS')
                    $this->arResult['IMPORT_LINK_TYPE'] = 'form';
            }

            // auto props
            $this->arResult['AUTO_BLOCK'] = $arBlockProps['AUTO_BLOCK']['VALUE'] == 'Y';
            $this->arResult['AUTO_TYPE'] = $arBlockProps['AUTO_TYPE']['VALUE'];
            $this->arResult['AUTO_COUNT'] = $arBlockProps['AUTO_COUNT']['VALUE'];

            //video props
            $this->arResult['HIDE_VIDEO'] = $arBlockProps['HIDE_VIDEO']['VALUE'] == 'Y';
            $this->arResult['VIDEO_POPUP_SHOW'] = $arBlockProps['VIDEO_POPUP_SHOW']['VALUE'] == 'Y';
            $this->arResult['VIDEO_LINK'] = $arBlockProps['VIDEO_LINK']['VALUE'];
            $this->arResult['VIDEO_NOTE'] = $arBlockProps['VIDEO_NOTE']['VALUE'];

            //tab props
            $this->arResult['USE_TABS'] = ($arBlockProps['USE_TABS']['VALUE'] == 'Y') && !$this->arResult['IMPORT_ELEMENTS'];
            if (!empty($this->arResult['USE_TABS'])) {
                $this->arResult['TABS'] = BlockTabs::get($this->arResult['ID'], ['ACTIVE' => 'Y']);
                if (Config::isEditMode()) {
                    $this->arResult['TABS'][] = BlockTabs::getEditorTab();
                }
            }
        }

        $this->arResult['ELEMENT_IDS'] = $elementIds ?? [];
    }

    private function getImportNews() {
        if (empty($this->arResult['IMPORT_ID'])) {
            return [];
        }

        $elements = [];
        $arOrder = [];
        $arFilter = [
            'IBLOCK_ID' => $this->arResult['IMPORT_ID'],
            'ACTIVE' => 'Y',
            'ACTIVE_DATE' => 'Y',
            'SECTION_ID' => $this->arResult['IMPORT_SECTION_ID'],
            'INCLUDE_SUBSECTIONS' => 'Y'
        ];
        $arNavStartParams = false;
        $arSelect = $this->getElementSelect();

        if ($this->arResult['IMPORT_FILTERS'] === 'LAST_ACTIVE') {
            if (empty($this->arResult['ELEMENTS_COUNT'])) {
                return $elements;
            }

            $arNavStartParams = ['nTopCount' => $this->arResult['ELEMENTS_COUNT']];
        }
        if ($this->arResult['IMPORT_FILTERS'] === 'ELEMS_SPEC_ID') {
            $arFilter['ID'] = $this->arResult['IMPORT_ELEM_IDS'];
        }

        $arOrder[$this->arResult['IMPORT_SORT']] = $this->arResult['IMPORT_SORT_ORDER'];
        if ($this->arResult['IMPORT_LINK_TYPE'] === 'detail'){
            $arSelect['FIELDS'][] = 'DETAIL_PAGE_URL';
        }

        $rsElements = \CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParams, $arSelect['FIELDS']);

        $sort = 1;
        while ($obElement = $rsElements->GetNextElement()) {
            $arElementTmp = $obElement->GetFields();
            $arElementTmp['PROPERTIES'] = $obElement->GetProperties();

            if ($this->arResult['IMPORT_LINK_TYPE'] != 'empty') {
                if ($this->arResult['IMPORT_LINK_TYPE'] == 'detail') {
                    $arElementTmp['PROPERTIES']['LINK']['VALUE'] = $arElementTmp['DETAIL_PAGE_URL'];
                    $arElementTmp['PROPERTIES']['LINK']['~VALUE'] = $arElementTmp['DETAIL_PAGE_URL'];
                    $arElementTmp['PROPERTIES']['LINK_TYPE']['VALUE'] = 'internal';
                    $arElementTmp['PROPERTIES']['LINK_TYPE']['~VALUE'] = 'internal';
                }
            }

            $arElementTmp = $this->formatElementFields($arElementTmp, $arSelect['PROPS']);
            $arElementTmp['SORT'] = $sort++;

            $elements[] = $arElementTmp;
        }

        return $elements;
    }

    private function getImportProducts()
    {
        if (empty($this->arResult['IMPORT_ID'])) {
            return [];
        }

        $isBxCatalog = Helpers\Catalog::checkCatalog($this->arResult['IMPORT_ID']);

        $priceTypeId = $this->arResult['IMPORT_PRICE_ID'];
        if (empty($priceTypeId) && $isBxCatalog) {
            $arBasePrice = CCatalogGroup::GetBaseGroup();
            if (empty($arBasePrice))
                return [];

            $priceTypeId = $arBasePrice['ID'];
        }

        $priceType = 'PRICE_'.$priceTypeId;
        if ($this->arResult['IMPORT_SORT'] === 'PRICE') {
            $this->arResult['IMPORT_SORT'] = $isBxCatalog ? $priceType : 'PROPERTY_PRICE';
        }
        if ($this->arResult['IMPORT_SORT'] === 'AVAILABLE') {
            $this->arResult['IMPORT_SORT'] = ($isBxCatalog ? '' : 'PROPERTY_').$this->arResult['IMPORT_SORT'];
        }

        $elements = [];
        $arOrder = [$this->arResult['IMPORT_SORT'] => $this->arResult['IMPORT_SORT_ORDER']];
        $arFilter = [
            'IBLOCK_ID' => $this->arResult['IMPORT_ID'],
            'ACTIVE' => 'Y',
            'SECTION_ID' => $this->arResult['IMPORT_SECTION_ID'],
            'INCLUDE_SUBSECTIONS' => 'Y'];

        $arSelect = $this->getElementSelect();
        if ($isBxCatalog) {
            $arSelect['FIELDS'][] = 'AVAILABLE';
            $arSelect['FIELDS'][] = $priceType;
        }
        if ($this->arResult['IMPORT_LINK_TYPE'] === 'detail'){
            $arSelect['FIELDS'][] = 'DETAIL_PAGE_URL';
        }

        if ($this->arResult['IMPORT_FILTERS'] === 'ELEMS_SPEC_ID') {
            $arFilter['ID'] = $this->arResult['IMPORT_ELEM_IDS'];
        }

        $rsElements = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect['FIELDS']);

        $sort = 1;
        while ($obElement = $rsElements->GetNextElement()) {
            $arElementTmp = $obElement->GetFields();
            $arElementTmp['PROPERTIES'] = $obElement->GetProperties();

            $arElementTmp = $this->formatElementFields($arElementTmp, $arSelect['PROPS']);
            $arElementTmp['SORT'] = $sort++;

            if ($isBxCatalog) {
                $arElementTmp['PROPERTIES']['AVAILABLE']['VALUE'] = $arElementTmp['AVAILABLE'];
                $arElementTmp['PROPERTIES']['AVAILABLE']['~VALUE'] = $arElementTmp['~AVAILABLE'];
                unset($arElementTmp['AVAILABLE']);
                unset($arElementTmp['~AVAILABLE']);

                $arElementTmp['PROPERTIES']['PRICE']['VALUE'] = $arElementTmp[$priceType];
                $arElementTmp['PROPERTIES']['PRICE']['~VALUE'] = $arElementTmp['~' . $priceType];
                unset($arElementTmp[$priceType]);
                unset($arElementTmp['~'.$priceType]);

                $arDiscountPrice = Helpers\Catalog::getDiscountPrice($arElementTmp['ID'], $priceTypeId);
                if (!empty($arDiscountPrice['RESULT_PRICE'])) {
                    $arElementTmp['PROPERTIES']['DISCOUNT_PRICE']['VALUE'] = $arDiscountPrice['RESULT_PRICE']['DISCOUNT'];
                    $arElementTmp['PROPERTIES']['DISCOUNT_PRICE']['~VALUE'] = $arDiscountPrice['RESULT_PRICE']['DISCOUNT'];
                }
            }

            if ($this->arResult['IMPORT_LINK_TYPE'] != 'empty') {
                $arElementTmp['PROPERTIES']['BTN_SHOW']['VALUE'] = 'Y';
                $arElementTmp['PROPERTIES']['BTN_TYPE']['VALUE_XML_ID'] = 'btn-primary';
                $arElementTmp['PROPERTIES']['BTN_SIZE']['VALUE_XML_ID'] = 'btn-mr';
                $arElementTmp['PROPERTIES']['BTN_TEXT']['VALUE'] = Loc::getMessage('RX_BLOCK_LANDING_DEFAULT_BTN_TEXT');
                if ($this->arResult['IMPORT_LINK_TYPE'] == 'form') {
                    $arElementTmp['PROPERTIES']['BTN_LINK']['VALUE'] = 'ranx_landing_form_order';
                    $arElementTmp['PROPERTIES']['BTN_LINK_TYPE']['VALUE'] = 'form';
                }
                if ($this->arResult['IMPORT_LINK_TYPE'] == 'detail') {
                    $arElementTmp['PROPERTIES']['BTN_LINK']['VALUE'] = $arElementTmp['DETAIL_PAGE_URL'];
                    $arElementTmp['PROPERTIES']['BTN_LINK_TYPE']['VALUE'] = 'internal';
                }
            }

            $elements[] = $arElementTmp;
        }

        return $elements;
    }

    private function getAutoElements()
    {
        $result = [];

        $arSort = ['SORT' => 'ASC', 'ID' => 'ASC'];
        if (Config::isNewsList($this->arResult['CODE'])) {
            $arSort = ['ACTIVE_FROM' => 'DESC', 'SORT' => 'ASC'];
        }

        $isSubsectionsIncluded = Config::isSubsectionsIncluded($this->arResult['CODE']);
        $arFilter = [
            'IBLOCK_ID' => $this->arResult['LANDING_IBLOCK_ID'],
            'SECTION_ID' => ($this->arResult['MODE'] == Landing::MODE_SECTION) ? $this->arResult['LANDING_ID'] : 0,
            'INCLUDE_SUBSECTIONS' => !empty($isSubsectionsIncluded) ? 'Y' : 'N',
            'ACTIVE' => 'Y',
        ];

        if ($this->filterObj !== false) {
            $arFilter = array_merge($arFilter, $this->filterObj->getConditions());
        }

        $arElementSelect = $this->getElementSelect();
        $arElementSelect['FIELDS'] = array_merge($arElementSelect['FIELDS'], ['DETAIL_PAGE_URL']);

        $rsElements = \CIBlockElement::GetList(
            $arSort,
            $arFilter,
            false,
            $this->arNavParams,
            $arElementSelect['FIELDS']
        );
        while ($obElement = $rsElements->GetNextElement()) {
            $arElementTmp = $obElement->GetFields();
            $arElementTmp['PROPERTIES'] = $obElement->GetProperties();

            if (!empty($arElementTmp['DETAIL_PAGE_URL'])) {
                $arElementTmp['PROPERTIES']['LINK']['VALUE'] = $arElementTmp['PROPERTIES']['LINK']['~VALUE'] = $arElementTmp['DETAIL_PAGE_URL'];
                $arElementTmp['PROPERTIES']['LINK_TYPE']['VALUE'] = $arElementTmp['PROPERTIES']['LINK_TYPE']['~VALUE'] = 'internal';
            }
            $arElementTmp = $this->formatElementFields($arElementTmp, $arElementSelect['PROPS']);

            $result[] = $arElementTmp;
        }

        $navComponentParameters = [
            'BASE_LINK' => $this->arParams['REQUEST_URL'],
        ];
        $this->arResult['NAV_STRING'] = $rsElements->GetPageNavStringEx(
            $navComponentObject,
            '',
            'rx_simple',
            'N',
            $this,
            $navComponentParameters
        );
        $this->arResult['NAV_RESULT'] = $rsElements;
        $this->arResult['NAV_CACHED_DATA'] = null;
        $this->arResult['NAV_PARAM'] = $navComponentParameters;

        return $result;
    }

    private function getAutoSections()
    {
        $result = [];

        $arFilter = [
            'IBLOCK_ID' => $this->arResult['LANDING_IBLOCK_ID'],
            'SECTION_ID' => ($this->arResult['MODE'] == Landing::MODE_SECTION) ? $this->arResult['LANDING_ID'] : 0,
            'ACTIVE' => 'Y',
        ];
        $arSelect = ['ID', 'NAME', 'SECTION_PAGE_URL', 'PICTURE', 'SORT'];
        $dbRes = \CIBlockSection::GetList(['SORT' => 'ASC', 'ID' => 'ASC'], $arFilter, false, $arSelect, $this->arNavParams);
        while ($arRes = $dbRes->GetNext()) {
            if (!empty($arRes['SECTION_PAGE_URL'])) {
                $arRes['PROPERTIES']['LINK']['VALUE'] = $arRes['PROPERTIES']['LINK']['~VALUE'] = $arRes['SECTION_PAGE_URL'];
                $arRes['PROPERTIES']['LINK_TYPE']['VALUE'] = $arRes['PROPERTIES']['LINK_TYPE']['~VALUE'] = 'internal';
            }
            $arRes['PREVIEW_PICTURE'] = $arRes['PICTURE'];

            $result[] = $arRes;
        }

        $navComponentParameters = [];
        $this->arResult['NAV_STRING'] = $dbRes->GetPageNavStringEx(
            $navComponentObject,
            '',
            'rx_simple',
            'N',
            $this,
            $navComponentParameters
        );
        $this->arResult['NAV_RESULT'] = $dbRes;
        $this->arResult['NAV_CACHED_DATA'] = null;
        $this->arResult['NAV_PARAM'] = $navComponentParameters;

        return $result;
    }

    private function getAutoDetail()
    {
        $result = [];
        if ($this->arResult['MODE'] !== Landing::MODE_ELEMENT) {
            return $result;
        }

        $arFilter = [
            'ID' => $this->arResult['LANDING_ID'],
            'IBLOCK_ID' => $this->arResult['LANDING_IBLOCK_ID'],
            'ACTIVE' => 'Y',
        ];
        $arElementSelect = $this->getElementSelect();

        $isDetailPageProps = Config::getBlockInfo($this->arResult['CODE'])['DETAIL_PAGE_PROPERTIES'] ?? false;
        if ($isDetailPageProps) {
            $detailPageProps = $this->getDetailPageProps();
            $propCodes = array_column($detailPageProps, 'CODE');

            $this->arResult['CHARS'] = array_diff($propCodes, $arElementSelect['PROPS']);
            $arElementSelect['PROPS'] = array_merge($arElementSelect['PROPS'], $propCodes);
        }

        $rsElements = \CIBlockElement::GetList(
            ['SORT' => 'ASC', 'ID' => 'ASC'],
            $arFilter,
            false,
            false,
            $arElementSelect['FIELDS']
        );
        while ($obElement = $rsElements->GetNextElement()) {
            $arElementTmp = $obElement->GetFields();
            $arElementTmp['PROPERTIES'] = $obElement->GetProperties();
            $arElementTmp = $this->formatElementFields($arElementTmp, $arElementSelect['PROPS']);

            $result[] = $arElementTmp;
        }

        return $result;
    }

    private function getCategories()
    {
        $arFilter = [
            'IBLOCK_ID' => $this->arResult['LANDING_IBLOCK_ID'],
            'ACTIVE' => 'Y',
            'CNT_ACTIVE' => 'Y',
        ];
        $arSelect = ['ID', 'NAME', 'SECTION_PAGE_URL', 'PICTURE', 'SORT', 'DEPTH_LEVEL'];

        $dbRes = \CIBlockSection::GetList(
            ['SORT' => 'ASC', 'ID' => 'ASC'],
            $arFilter,
            true,
            $arSelect
        );

        $result = [];
        while ($arRes = $dbRes->GetNext()) {
            $result[$arRes['ID']] = $arRes;
        }

        if ($this->arResult['MODE'] == Landing::MODE_SECTION) {
            $current = \CIBlockSection::GetNavChain(
                $this->arResult['LANDING_IBLOCK_ID'],
                $this->arResult['LANDING_ID'],
                ['ID'],
                true
            );

            foreach ($current as $category) {
                $id = $category['ID'];
                if (!empty($result[$id])) {
                    $result[$id]['SELECTED'] = true;
                }
            }
        }

        return $result;
    }

    private function getElements()
    {
        [$sort, $order] = explode('|', $this->arResult['ELEMENTS_SORT']);
        $arSort = !empty($sort) && !empty($order) ? [$sort => $order] : ['SORT' => 'ASC', 'ID' => 'ASC'];
        $arElementSelect = $this->getElementSelect();

        $arElementFilter = ['ID' => $this->arResult['ELEMENT_IDS'], 'ACTIVE' => 'Y'];
        if ($this->filterObj !== false && Config::isBlockFilter($this->arResult['CODE'])) {
            $arElementFilter = array_merge($arElementFilter, $this->filterObj->getConditions());
        }

        $elements = [];
        if (!empty($this->arResult['ELEMENT_IDS'])) {
            $rsElements = \CIBlockElement::GetList(
                $arSort,
                $arElementFilter,
                false,
                false,
                $arElementSelect['FIELDS']
            );

            while ($obElement = $rsElements->GetNextElement()) {
                $arElementTmp = $obElement->GetFields();
                $arElementTmp['PROPERTIES'] = $obElement->GetProperties();

                $arElementTmp = $this->formatElementFields($arElementTmp, $arElementSelect['PROPS']);

                $elements[] = $arElementTmp;
            }
        }

        return $elements;
    }

    private function getCssClasses()
    {
        $classes = '';

        if ($this->arParams['ACTIVE'] !== 'Y') {
            $classes .= 'hidden ';
        }
        if ($this->arResult['TEXT_LIGHT']) {
            $classes .= 'text-light ';
        }
        if ($this->arResult['INDENT_TOP']) {
            $classes .= 'indent-top-' . $this->arResult['INDENT_TOP'] . ' ';
        }
        if ($this->arResult['INDENT_BOT']) {
            $classes .= 'indent-bot-' . $this->arResult['INDENT_BOT'] . ' ';
        }
        if ($this->arResult['LINE_BOT']) {
            $classes .= 'line-bottom ';
        }
        if ($this->arResult['WIDE']) {
            $classes .= 'wide-block ';
        }
        if ($this->arResult['HOVER_EFFECT']) {
            $classes .= 'hover-effect ';
        }
        if ($this->isEnabledSection('ALIGN') && $this->arResult['ALIGN']) {
            $classes .= 'content-align-' . $this->arResult['ALIGN'] . ' ';
        }
        if ($this->isEnabledSection('PICTURE_ALIGN') && $this->arResult['PICTURE_ALIGN']) {
            $classes .= 'content-picture-' . $this->arResult['PICTURE_ALIGN'] . ' ';
        }
        if ($this->arResult['INDENT_ELEMENTS']) {
            $classes .= 'content-indent-elements ';
        }
        if ($this->arResult['PARALLAX_EFFECT']) {
            $classes .= 'parallax-effect ';
        }
        if ($this->isEnabledSection('BG_PICTURE') && $this->arResult['BG_PICTURE']) {
            $classes .= 'content-bg-picture ';
        }

        $blockInfo = Config::getBlockInfo($this->arResult['CODE']);
        if (!empty($blockInfo['IS_UNDER_HEADER']) && $blockInfo['IS_UNDER_HEADER']) {
            $classes .= 'block-under-header ';
        }

        if (isset($blockInfo['TITLE_POSITION']) && in_array($blockInfo['TITLE_POSITION'], ['left', 'center'])) {
            $classes .= 'block-title-' . $blockInfo['TITLE_POSITION'] . ' ';
        }

        return $classes;
    }

    private function getDataAttrs()
    {
        $result = '';

        $result .= 'data-id="'.$this->arResult['ID'].'" ';
        $result .= 'data-elements=\''.json_encode($this->arResult['ELEMENT_IDS']).'\' ';

        if ($this->arResult['IBLOCK_SECTION_ID']) {
            $result .= 'data-group-id="'.$this->arResult['IBLOCK_SECTION_ID'].'" ';
            $result .= 'id="block_group_'.$this->arResult['IBLOCK_SECTION_ID'].'" ';
        }

        return $result;
    }

    private function getBtn($props, $subject = false, $postFix = '')
    {
        // make size of second button as first ones, if default size is chosen
        if ($postFix == '_2' && !$props['BTN_SIZE' . $postFix]['VALUE_XML_ID'] && $props['BTN_SHOW']['VALUE'] == 'Y') {
            $props['BTN_SIZE' . $postFix]['VALUE_XML_ID'] = $props['BTN_SIZE']['VALUE_XML_ID'];
        }

        return Page::getBtn([
            'BTN_SHOW' => $props['BTN_SHOW' . $postFix]['VALUE'],
            'BTN_TYPE' => $props['BTN_TYPE' . $postFix]['VALUE_XML_ID'],
            'BTN_SIZE' => $props['BTN_SIZE' . $postFix]['VALUE_XML_ID'],
            'BTN_TEXT' => $props['BTN_TEXT' . $postFix]['VALUE'],
            'BTN_LINK_TYPE' => $props['BTN_LINK_TYPE' . $postFix]['VALUE'],
            'BTN_LINK' => $props['BTN_LINK' . $postFix]['VALUE'],
            'BTN_GOAL' => $props['BTN_GOAL' . $postFix]['VALUE'],
            'BTN_CLASS' => $props['BTN_CLASS' . $postFix]['VALUE'],
            'SUBJECT' => $subject,
        ]);
    }

    private function includeTemplateStyles()
    {
        $filePath = $this->__template->__folder . '/style.css';
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath)) {
            echo '<script>BX.loadCSS([\'' . $filePath . '\']);</script>';
        }
    }

    private function includeTemplateScripts()
    {
        $filePath = $this->__template->__folder . '/script.js';
        //echo '<script>BX.loadScript([\'' . $filePath . '\']);</script>'; // this method doesn't work, dunna why
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath)) {
            echo '<script>' . file_get_contents($_SERVER['DOCUMENT_ROOT'] . $filePath) . '</script>';
        }
    }

    private function formatDisplayDate($date)
    {
        if ($date)
            return strtolower(\FormatDate('j F Y', strtotime($date)));

        return '';
    }

    private function formatDisplayDatePeriod($from, $to)
    {
        $str = '';
        if ($from && !$to) {
            $str = strtolower(\FormatDate('j F', strtotime($from)));
        } elseif ($from && $to) {
            $str = Loc::getMessage('RX_BLOCK_LANDING_DATE_FROM') . strtolower(\FormatDate('j F', strtotime($from)));
            $str .= Loc::getMessage('RX_BLOCK_LANDING_DATE_BETWEEN') . strtolower(\FormatDate('j F', strtotime($to)));
        } elseif ($to) {
            $str = Loc::getMessage('RX_BLOCK_LANDING_DATE_TO') . strtolower(\FormatDate('j F', strtotime($to)));
        }

        return $str;
    }

    private function getLink($props, $elementId)
    {
        $link = $props['LINK']['VALUE'] ?? '';
        $linkType = $props['LINK_TYPE']['VALUE'] ?? '';

        if ($linkType == 'service') {
            $linkType = 'form';
            $link = 'ranx_landing_service';
            $linkProductId = $elementId;
        }

        if (!$link) {
            return [];
        }

        if ($linkType == 'external' && strpos($link, 'http') === false) {
            $link = 'http://' . $link;
        }

        return [
            'ATTRS' => ($linkType != 'form' ? 'href="'.$link.'" ' : 'href="#" ') . ($linkType == 'form' ? 'data-form-code="'.$link.'" ' : '')
                . ($linkType == 'external' ? 'target="_blank" ' : '') . (isset($linkProductId) ? 'data-product-id="'.$linkProductId.'"' : ''),
            'CLASS' => ($linkType == 'form') ? 'js-form-modal' : '',
        ];
    }

    private function getSocials($props)
    {
        $socials = Config::getBlockSocials($this->arResult['CODE']);
        if (empty($socials)) {
            return $socials;
        }

        $res = [];
        foreach ($socials as $social) {
            $prop = $props[$social];

            if (empty($prop['VALUE'])) {
                continue;
            }

            $res[$social] = [
                'NAME' => $prop['NAME'],
                'LINK' => $prop['VALUE'],
                'SVG' => Helpers\Helper::svg('block/social', strtolower($social)),
            ];
        }

        return $res;
    }

    private function getElementSelect()
    {
        $arElementSelect = [
            'ID',
            'IBLOCK_ID',
            'NAME',
            'SORT',
            'ACTIVE_FROM',
            'ACTIVE_TO',
            'PREVIEW_PICTURE',
            'PREVIEW_TEXT',
            'DETAIL_TEXT',
            'DETAIL_PICTURE',
            'IBLOCK_SECTION_ID',
        ];

        $elementsFields = Config::getBlockElementsFields($this->arResult['CODE']);
        $deprecatedElementsFields = Config::getBlockDeprecatedElementsFields($this->arResult['CODE']);
        $elementsFields = array_merge($elementsFields, $deprecatedElementsFields);

        $arFieldsSelect = [];
        $arPropsSelect = [];
        foreach ($elementsFields as $elementsField) {
            if (strpos($elementsField, 'PROPERTY_') === 0) {
                $arPropsSelect[] = substr($elementsField, 9);
            } else {
                $arFieldsSelect[] = $elementsField;
            }
        }
        $arElementSelect = array_merge($arElementSelect, $arFieldsSelect);

        return [
            'FIELDS' => $arElementSelect,
            'PROPS' => $arPropsSelect,
        ];
    }

    private function getDetailPageProps()
    {
        $arProps = [];
        $rsProps = \CIBlockProperty::GetList(
            ['SORT' => 'ASC'],
            ['IBLOCK_ID' => $this->arResult['LANDING_IBLOCK_ID'], 'ACTIVE' => 'Y']
        );
        while ($arProp = $rsProps->Fetch()){
            $arProps[] = [
                'ID' => $arProp['ID'],
                'CODE' => $arProp['CODE'],
                'PREFIX_CODE' => 'PROPERTY_'.$arProp['CODE'],
            ];
        }

        $arFilter = [
            'PROPERTY_ID' => array_column($arProps, 'ID'),
            'FEATURE_ID' => 'DETAIL_PAGE_SHOW',
            'IS_ENABLED' => 'Y',
        ];
        $detailPageProps = \Bitrix\Iblock\PropertyFeatureTable::getList([
            'select' => ['PROPERTY_ID'],
            'filter' => $arFilter,
        ])->fetchAll();
        $detailPageProps = array_filter(array_column($detailPageProps, 'PROPERTY_ID'));

        foreach ($arProps as $key => $arProp) {
            if (!in_array($arProp['ID'], $detailPageProps)) {
                unset($arProps[$key]);
            }
        }

        return $arProps;
    }

    private function formatElementFields($fields, $arElementProps)
    {
        $fields['NAME'] = $fields['~NAME'];

        if ($fields['NAME'] == Block::EMPTY_TITLE) {
            $fields['NAME'] = '';
            $fields['~NAME'] = '';
        }

        // unset unused keys (to decrease cache size)
        foreach ($fields['PROPERTIES'] as $elementPropKey => &$elementProp) {
            if (!in_array($elementPropKey, $arElementProps)) {
                unset($fields['PROPERTIES'][$elementPropKey]);
                continue;
            }

            $elementProp['VALUE'] = $elementProp['~VALUE'];

            foreach ($elementProp as $elPropKey => $elPropVal) {
                if (!in_array($elPropKey, self::ELEMENT_PROP_KEYS)) {
                    unset($fields['PROPERTIES'][$elementPropKey][$elPropKey]);
                }
            }
        }

        return $fields;
    }

    private function formatElementFieldsOutOfCache($element)
    {
        $element['NAME'] = $element['~NAME'];
        $element['PREVIEW_TEXT'] = $element['~PREVIEW_TEXT'];
        $element['DETAIL_TEXT'] = $element['~DETAIL_TEXT'];

        foreach ($element['PROPERTIES'] as $propKey => &$propVal) {
            $element['PROPS'][$propKey] = $propVal['~VALUE']; // short props, only value
        }
        // dates
        $element['DISPLAY_ACTIVE_FROM'] = $this->formatDisplayDate($element['ACTIVE_FROM']);
        $element['DISPLAY_ACTIVE_TO'] = $this->formatDisplayDate($element['ACTIVE_TO']);
        $element['DISPLAY_ACTIVE_PERIOD'] = $this->formatDisplayDatePeriod($element['ACTIVE_FROM'], $element['ACTIVE_TO']);

        $element['BTN1'] = $this->getBtn($element['PROPERTIES'], $element['NAME']);
        $element['BTN2'] = $this->getBtn($element['PROPERTIES'], $element['NAME'], '_2');

        $btns = '';
        if ($element['BTN1'] || $element['BTN2']) {
            $btns .= '<div class="block-el-btns">';
            $btns .= $element['BTN1'] ? '<div>' . $element['BTN1'] . '</div>' : '';
            $btns .= $element['BTN2'] ? '<div>' . $element['BTN2'] . '</div>' : '';
            $btns .= '</div>';
        }
        $element['BTN'] = $btns;

        if ($element['PROPS']['LINK'] || $element['PROPS']['LINK_TYPE']) {
            $element['LINK'] = $this->getLink($element['PROPERTIES'], $element['ID']);
        }
        $element['SOCIALS'] = $this->getSocials($element['PROPERTIES']);

        $element['PRICE'] = $element['PROPS']['PRICE'];
        if (!empty($element['PROPS']['DISCOUNT_PRICE']) && !empty($element['PRICE'])) {
            $elementPrices = Helpers\Helper::calcPrice($element['PROPS']['PRICE'], $element['PROPS']['DISCOUNT_PRICE']);
            $element = array_merge($element, $elementPrices);
        }

        if (Fields\IntervalTime::isIncludedToBlock($this->arResult['CODE'])) {
            $element['INTERVAL_TIME'] = Fields\IntervalTime::getDisplayValue($element['PROPS']);
        }

        return $element;
    }

    private function groupByTabs($items)
    {
        $tabs = [];
        foreach ($items as $item) {
            $key = $item['IBLOCK_SECTION_ID'] ?? '';

            if ($this->arResult['IMPORT_ELEMENTS']) {
                $key = '';
            }

            if (empty($tabs[$key])) {
                $tabs[$key] = [];
                $tabs[$key]['ITEMS'] = [];
            }

            $tabs[$key]['ITEMS'][] = $item;
        }

        foreach ($tabs as $key => &$tab) {
            $tab['ATTR'] = 'data-tab-id="'.$key.'"';
            $tab['CLASS'] = 'tab-item';
        }

        return $tabs;
    }

    private function getBlockTitle()
    {
        $blockInfo = Config::getBlockInfo($this->arResult['CODE']);

        $showBtn = true;
        if (isset($blockInfo['TITLE_SHOW_BTN']) && is_bool($blockInfo['TITLE_SHOW_BTN'])) {
            $showBtn = $blockInfo['TITLE_SHOW_BTN'];
        }
        $showDesc = true;
        if (isset($blockInfo['TITLE_SHOW_DESC']) && is_bool($blockInfo['TITLE_SHOW_DESC'])) {
            $showDesc = $blockInfo['TITLE_SHOW_DESC'];
        }

        ob_start();
        Page::showBlockTitle($this->arResult, $showBtn, $showDesc);
        return ob_get_clean();
    }

    private function getBlockTabs()
    {
        if (!empty($this->arResult['USE_TABS'])) {
            $this->arResult['DEFAULT_TAB_ID'] = BlockTabs::validateId(
                $this->arParams['DEFAULT_TAB_ID'] ?? 0, $this->arResult['TABS']);
        }

        ob_start();
        Page::showBlockTabs($this->arResult);
        return ob_get_clean();
    }

    private function getBlockStart()
    {
        $blockInfo = Config::getBlockInfo($this->arResult['CODE']);

        $blockClasses = 'block';
        $blockClasses .= ' block' . str_replace(['_', ' '], ['-', ''], $this->arResult['CODE']);
        if (!empty($blockInfo['TITLE_STICKY'])) {
            $blockClasses .= ' block-title-sticky';
        }
        if (!empty($blockInfo['CLASSES'])) {
            $blockClasses .= ' ' . $blockInfo['CLASSES'];
        }

        $html = '<div class="' . $blockClasses . ' ' . $this->arResult['CLASSES'] . '" ' . $this->arResult['DATA_ATTRS']
            . (!empty($this->arResult['BG_COLOR']) ? ' style="background-color:'.$this->arResult['BG_COLOR'].';"' : '') . '>';

        if ($this->isEnabledSection('BG_PICTURE') && !empty($this->arResult['BG_PICTURE'])) {
            $imgSrc = $this->arResult['BG_PICTURE'];
            $bgPictureHtml = '';
            if (!empty($imgSrc)) {
                // Even if there is parallax, the bg-picture will be in mobile
                $bgPictureHtml = '<img class="block-bg-picture" src="'.$imgSrc.'">';
                if ($this->arResult['PARALLAX_EFFECT']) {
                    $bgPictureHtml .= '<img class="parallax-picture" src="' . $imgSrc . '">';
                }
            }

            $html .= $bgPictureHtml;
        }

        if (Config::isEditMode()) {
            ob_start();
            include __DIR__ . '/include/edit_elements.php';
            $html .= ob_get_clean();
        }

        return $html;
    }

    private function getBlockEnd()
    {
        return '</div>';
    }

    private function getBlockFilter()
    {
        if ($this->filterObj == false) {
            return '';
        }

        return $this->filterObj->getHtmlCode();
    }

    private function getBranchIds()
    {
        $branches = Region::getCurrentBranches();
        return !empty($branches) ? array_column($branches, 'ID') : [];
    }

    private function getRegionIds()
    {
        $regions = Region::getRegions();
        return !empty($regions) ? array_column($regions, 'ID') : [];
    }

    private function requestVendors()
    {
        if ($this->arResult['PARALLAX_EFFECT']) {
            Config::enableParallaxVendor();
        }
        if (Block::isUseMasonryPlugin($this->arResult['CODE'])) {
            Config::includeMasonryVendor();
        }
    }

    private function isEnabledSection($section)
    {
        $blockCode = $this->arResult['CODE'] ?? '';
        if (empty($blockCode) || empty($section)) {
            return false;
        }

        $sections = Config::getBlockConfigSections($blockCode);
        return in_array($section, $sections);
    }

    /* ======== ACTIONS ======== */
    public function ajaxActionBefore()
    {
        if (!Loader::includeModule('ranx.landing')) {
            throw new Exception('No ranx.landing module');
        }
        Loader::includeModule('iblock');
        if (!defined('RX_LANDING_TEMPLATE')) {
            define('RX_LANDING_TEMPLATE', 1);
        }
    }

    public function cardModalAction($post)
    {
        $this->ajaxActionBefore();
        Event::removeOtherEvents();

        $code = trim($post['code']);
        $id = intval($post['id']);
        $blockId = intval($post['blockId']);

        if (!$code || $id <= 0) {
            throw new Exception('error');
        }

        $this->arResult = [];
        $this->arResult['CODE'] = $code;

        $this->arParams['SETTINGS'] = PanelSettings::getValues($blockId);

        $arElementSelect = $this->getElementSelect();

        $rsElement = \CIBlockElement::GetList(
            ['SORT' => 'ASC', 'ID' => 'ASC'],
            ['ID' => $id, 'ACTIVE' => 'Y'],
            false,
            false,
            $arElementSelect['FIELDS']
        );

        if ($obElement = $rsElement->GetNextElement()) {
            $arElementTmp = $obElement->GetFields();
            $arElementTmp['PROPERTIES'] = $obElement->GetProperties();

            $arElementTmp = $this->formatElementFields($arElementTmp, $arElementSelect['PROPS']);
            $arElementTmp = $this->formatElementFieldsOutOfCache($arElementTmp);

            $this->arResult = $arElementTmp;
        }

        $this->arResult['IS_MODAL'] = true;

        $this->setTemplateName($code);
        ob_start();
        $this->includeComponentTemplate('modal');
        $html = ob_get_clean();

        return [
            'body' => $html,
        ];
    }

    public function videoModalAction($post)
    {
        $this->ajaxActionBefore();
        Event::removeOtherEvents();

        $code = trim($post['code']);
        $id = intval($post['id']);

        if (!$code || $id <= 0) {
            throw new Exception('error');
        }

        $this->arResult = [];
        $this->arResult['CODE'] = $code;
        $iblockId = Block::getIblockId();

        $rsBlock = \CIBlockElement::GetList(
            ['SORT' => 'ASC', 'ID' => 'ASC'],
            ['ID' => $id, 'IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y'],
            false,
            false,
            []
        );

        if ($obBlock = $rsBlock->GetNextElement()) {
            $arBlock = $obBlock->GetFields();
            $arBlockProps = $obBlock->GetProperties();

            $this->arResult['VIDEO_LINK'] = $arBlockProps['VIDEO_LINK']['VALUE'];
        }

        $this->arResult['IS_MODAL'] = true;

        $this->setTemplateName($code);
        ob_start();
        $this->includeComponentTemplate('modal'); // this is very impressive decision
        $html = ob_get_clean();

        return [
            'body' => $html,
        ];
    }

    public function smartFilterAction($post)
    {
        $this->ajaxActionBefore();
        Event::removeOtherEvents();

        $blockId = intval($post['blockId'] ?? 0);
        $requestUrl = trim($post['requestUrl'] ?? '');

        if ($blockId <= 0 || empty($requestUrl)) {
            throw new Exception(__METHOD__.': invalid input parameters');
        }

        $arBlock = Block::get($blockId, ['PROPERTY_AUTO_COUNT']);
        if (empty($arBlock)) {
            throw new Exception(__METHOD__.': invalid block id');
        }

        ob_start();
        $GLOBALS['APPLICATION']->IncludeComponent(
            'ranx:block.landing',
            $arBlock['CODE'],
            [
                'DETAIL_ID' => $arBlock['ID'],
                'IBLOCK_TYPE' => 'ranx_landing',
                'IBLOCK_ID' => $arBlock['IBLOCK_ID'],
                'ACTIVE' => $arBlock['ACTIVE'],
                'CARDS_COUNT' => $arBlock['PROPERTY_AUTO_COUNT_VALUE'],
                'REQUEST_URL' => $requestUrl,
            ],
            false
        );
        $html = ob_get_clean();

        return [
            'html' => $html,
        ];
    }

    public function blockFilterAction($post, $blockId)
    {
        $this->ajaxActionBefore();
        Event::removeOtherEvents();

        $blockId = intval($blockId ?? 0);
        if ($blockId <= 0) {
            throw new Exception(__METHOD__.': invalid input parameters');
        }

        $arBlock = Block::get($blockId);
        if (empty($arBlock)) {
            throw new Exception(__METHOD__.': invalid block id');
        }

        ob_start();
        $GLOBALS['APPLICATION']->IncludeComponent(
            'ranx:block.landing',
            $arBlock['CODE'],
            [
                'DETAIL_ID' => $arBlock['ID'],
                'IBLOCK_TYPE' => Helpers\Iblock::TYPE,
                'IBLOCK_ID' => $arBlock['IBLOCK_ID'],
                'ACTIVE' => $arBlock['ACTIVE'],
                'PREFILTER' => $post ?? [],
            ],
            false
        );
        $html = ob_get_clean();

        return [
            'html' => $html,
        ];
    }
}
