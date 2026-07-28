<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

use Bitrix\Main\Data;
use Bitrix\Main\Loader;
use Ranx\Landing\Block;
use Ranx\Landing\Event;
use Ranx\Landing\Fields;
use Ranx\Landing\Config;
use Ranx\Landing\Region;
use Ranx\Landing\Landing;
use Ranx\Landing\Helpers;
use Ranx\Landing\BlockTabs;
use Ranx\Landing\BlockGroup;
use Ranx\Landing\ActionFilter;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\Contract\Controllerable;
use Ranx\Landing\Panel\Settings as PanelSettings;
use Ranx\Landing\Panel\Content\Filter as ContentFilter;

/**
 * Panel component (for manipulations with blocks on landing)
 */
class RanxPanelLandingComponent extends CBitrixComponent implements Controllerable
{
    const ACTIONS = [
        'getDesignTemplate',
        'getContentTemplate',
        'getCopyTemplate',
        'getCardTemplate',
        'getGroupTemplate',
        'getVariantTemplate',
        'getMenuTemplate',
        'getTabsTemplate',
        'getSettingsTemplate',
    ];

    /**
     * New bitrix ajax
     *
     * @return array
     */
    public function configureActions()
    {
        $this->includeModules();
        Config::defineSettingId();

        $result = [];
        foreach (self::ACTIONS as $action) {
            $result[$action] = [
                'prefilters' => [
                    new ActionFilter\CheckAccess(),
                ],
                'postfilters' => [],
            ];
        }
        $result['getUpdatesTemplate'] = [
            'prefilters' => [],
            'postfilters' => [],
        ];
        return $result;
    }

    private function includeModules()
    {
        Loader::includeModule('iblock');
        if (!Loader::includeModule('ranx.landing')) {
            throw new Exception('no ranx.landing module');
        }
    }

    public function executeComponent()
    {
        $this->prepareResult();

        $this->includeComponentTemplate();
    }

    private function prepareResult()
    {
        $this->arResult['BLOCK_GROUPS'] = $this->getBlockGroups();
        $this->arResult['PARAMS'] = $this->getParams();
        $this->arResult['FORMS'] = $this->getFormsList();
        $this->arResult['PRESET_GROUPS'] = $this->getPresetGroups();
    }

    private function getBlockGroups()
    {
        if (!Config::isEditMode()) {
            return [];
        }

        return Config::getFilteredBlockGroup($this->arParams['SECTION_TYPE'], $this->arParams['MODE']);
    }

    private function getParams()
    {
        return Config::$params;
    }

    private function getFieldsMess($fieldsMessBlock = [])
    {
        $fieldsMessDefault = [
            'NAME' => Loc::getMessage('RX_LANDING_PANEL_FIELD_MESS_NAME'),
            'PREVIEW_TEXT' => Loc::getMessage('RX_LANDING_PANEL_FIELD_MESS_PREVIEW_TEXT'),
            'DETAIL_TEXT' => Loc::getMessage('RX_LANDING_PANEL_FIELD_MESS_DETAIL_TEXT'),
            'PREVIEW_PICTURE' => Loc::getMessage('RX_LANDING_PANEL_FIELD_MESS_PREVIEW_PICTURE'),
            'DETAIL_PICTURE' => Loc::getMessage('RX_LANDING_PANEL_FIELD_MESS_DETAIL_PICTURE'),
            'ACTIVE_FROM' => Loc::getMessage('RX_LANDING_PANEL_FIELD_MESS_ACTIVE_FROM'),
            'ACTIVE_TO' => Loc::getMessage('RX_LANDING_PANEL_FIELD_MESS_ACTIVE_TO'),
        ];

        return array_merge($fieldsMessDefault, $fieldsMessBlock);
    }

    private function formatFieldsForCard($fields, $propsInfo, $fieldsMessBlock = [], $fieldsOptions = [])
    {
        $fieldsMess = $this->getFieldsMess($fieldsMessBlock);

        // add empty card
        if (empty($this->arResult['ID'])) {
            $this->arResult = [
                'ID' => '0',
                'ACTIVE' => 'Y',
                'IS_EMPTY' => true,
            ];
        }

        $this->arResult['FIELDS'] = [];

        foreach ($fields as $field) {
            $tmpField = [
                'ID' => 'cardField' . $field,
                'CODE' => $field,
                'NAME' => $field,
                'IS_PROPERTY' => strpos($field, 'PROPERTY_') === 0,
            ];

            $tmpField['TITLE']   = $fieldsMess[$field] ?? '';
            $tmpField['VALUE']   = $this->arResult[$field] ?? '';
            $tmpField['OPTIONS'] = $fieldsOptions[$field] ?? [];

            // detecting types
            if (in_array($field, ['NAME'])) {
                $tmpField['TYPE'] = 'string';
            } elseif (in_array($field, ['PREVIEW_TEXT', 'DETAIL_TEXT'])) {
                $tmpField['TYPE'] = 'text';
            } elseif (in_array($field, ['PREVIEW_PICTURE', 'DETAIL_PICTURE'])) {
                $tmpField['TYPE'] = 'file';
                $tmpField['MIME_TYPE'] = 'image';
            } elseif (in_array($field, ['ACTIVE_FROM', 'ACTIVE_TO'])) {
                $tmpField['TYPE'] = 'date';
            } elseif($tmpField['IS_PROPERTY']) {

                $propCode = substr($field, 9);
                $prop = $propsInfo[$propCode];
                $isMultiple = $prop['MULTIPLE'] == 'Y';
                if ($isMultiple) {
                    $tmpField['NAME'] .= '[]';
                }

                $tmpField['TITLE'] = $fieldsMess[$field] ?? $prop['NAME'];
                $tmpField['VALUE'] = $this->arResult['PROPERTIES'][$propCode]['VALUE'] ?? '';
                $tmpField['DESC']  = $this->arResult['PROPERTIES'][$propCode]['DESCRIPTION'] ?? '';
                $tmpField['MULTI'] = $isMultiple;
                $tmpField['HINT']  = $fieldsMess[$field . '_HINT'] ?? $prop['HINT'];
                $tmpField['DESC_HINT'] = $fieldsMess[$field . '_DESC_HINT'] ?? '';
                $tmpField['WITH_DESC'] = $prop['WITH_DESCRIPTION'] == 'Y';

                if ($prop['USER_TYPE'] == 'HTML') {
                    $tmpField['TYPE'] = 'text';
                    $tmpField['VALUE'] = $this->arResult['PROPERTIES'][$propCode]['VALUE']['TEXT'] ?? '';
                } elseif ($prop['USER_TYPE'] == 'map_yandex') {
                    $tmpField['TYPE'] = 'map';
                    $tmpField['LAT'] = '';
                    $tmpField['LON'] = '';
                    if (!empty($tmpField['VALUE']) && strpos($tmpField['VALUE'], ',') !== false) {
                        list($tmpField['LAT'], $tmpField['LON']) = explode(',', $tmpField['VALUE']);
                    }
                } elseif ($prop['PROPERTY_TYPE'] == 'F') {
                    $tmpField['TYPE'] = 'file';
                    $tmpField['FILE_TYPE'] = $this->arResult['PROPERTIES'][$propCode]['FILE_TYPE'] ?? '';
                } elseif ($prop['PROPERTY_TYPE'] == 'L') {

                    $propValues = Helpers\Iblock::getListPropValuesByValue($prop['CODE'], $prop['IBLOCK_ID']);

                    if ($prop['LIST_TYPE'] == 'C' && count($propValues) == 1) {
                        $tmpField['TYPE'] = 'checkbox';
                        $tmpField['IS_CHECKED'] = !empty($tmpField['VALUE']);
                        $tmpField['VALUE'] = reset($propValues);
                    } else {
                        $tmpField['TYPE'] = 'select';
                        $tmpField['VALUE'] = $this->arResult['PROPERTIES'][$propCode]['VALUE_ENUM_ID'] ?? '';
                    }

                    $tmpField['VALUES'] = $propValues;

                } elseif ($prop['PROPERTY_TYPE'] == 'S') {
                    if ($prop['ROW_COUNT'] > 1) {
                        $tmpField['TYPE'] = 'text';
                        $tmpField['ROWS'] = $prop['ROW_COUNT'];
                    } else {
                        $tmpField['TYPE'] = 'string';
                    }
                } elseif ($prop['PROPERTY_TYPE'] == 'N') {
                    $tmpField['TYPE'] = 'number';
                }
            }

            $this->arResult['FIELDS'][$field] = $tmpField;
        }
    }

    private function getFormsList()
    {
        $result = [];

        if (Config::isWebFormsEnabled()) {
            Loader::includeModule('form');

            $isFiltered = false;
            $by = 's_name';
            $order = 'asc';
            $arFilter = ['SITE' => SITE_ID];
            if (!Config::isAllFormsEnabled()) {
                $arFilter[] = [
                    'LOGIC' => 'OR',
                    ['SID' => 'ranx_landing_form_%'],
                    ['SID' => 'ranx_landing_service']
                ];
            }
            $rsForms = \CForm::GetList($by, $order, $arFilter, $isFiltered);

            while ($arForm = $rsForms->Fetch()) {
                if (strpos(strtolower($arForm['SID']), 'ranx_landing_sale_') !== false) {
                    continue;
                }
                $result[$arForm['SID']] = $arForm['NAME'];
            }

        } else {
            Loader::includeModule('iblock');

            $result = Helpers\Iblock::getFormsForSelect();
        }

        $b24Forms = Config::getB24FormsForSelect();
        $result = array_merge($result, $b24Forms);

        return $result;
    }

    private function getAnchorsList($landingId = false, $landingMode = false)
    {
        $res = [];

        if (!$landingId) {
            $landingId = $this->arResult['PROPS']['LANDING'];
        }
        if (!$landingMode) {
            $landingMode = $this->arResult['PROPS']['MODE'];
            if (!$landingMode) {
                $landingMode = Landing::MODE_ELEMENT;
            }
        }

        if (!$landingId) {
            return [];
        }

        $blocks = Block::getByLanding($landingId, $landingMode);
        foreach ($blocks as $block) {
            if ($block['ACTIVE'] == 'N') continue;

            $blockInfo = Config::getBlockInfo($block['CODE']);
            $name = '';
            if (!empty($blockInfo['_EXCLUDE']) && in_array('CONTENT_TITLE', $blockInfo['_EXCLUDE'])) {
                $name = Block::getFirstElementName($block['ID']);
            }
            if (!$name) {
                $name = $block['NAME'];
            }
            if (!empty($block['IBLOCK_SECTION_ID'])) {
                $res['group_' . $block['IBLOCK_SECTION_ID']] = '#' . $block['IBLOCK_SECTION_ID'] . ': ' . $name;
            } else {
                $res[$block['ID']] = '#' . $block['ID'] . ': ' . $name;
            }
        }

        return $res;
    }

    private function getPresetGroups()
    {
        return Config::getPresetGroups();
    }



    /* ============ actions ============ */

    public function getDesignTemplateAction($post)
    {
        Event::removeOtherEvents();

        $id = intval($post['id']);
        if ($id <= 0) {
            throw new Exception('error');
        }

        if (Config::isDemoMode()) {
            $landingId = Landing::getByBlockId($id);
            Landing::checkDemoAccess($landingId);
        }

        $this->arResult = Block::get($id, ['_PROPERTIES']);
        $this->arResult['CONFIG'] = Config::$blocks['CONFIG'];
        $this->arResult['INFO'] = Config::getBlockInfo($this->arResult['CODE']);
        $this->arResult['DESIGN_FIELDS_MESS'] = Config::getBlockDesignFieldsMess($this->arResult['CODE']);
        $this->arResult['SECTIONS'] = Config::getBlockConfigSections($this->arResult['CODE']);

        if (in_array('PICTURE_ALIGN', $this->arResult['SECTIONS'])) {
            $allowedOptions = Config::getBlockAllowedPictureAlignOptions($this->arResult['CODE']);
            $availableOptions = Helpers\Iblock::getListPropValues('PICTURE_ALIGN', $this->arResult['IBLOCK_ID']);
            $availableOptions = array_column($availableOptions, 'VALUE');

            $this->arResult['PICTURE_ALIGN_OPTIONS'] = $availableOptions;
            if (!empty($allowedOptions)) {
                $this->arResult['PICTURE_ALIGN_OPTIONS'] = array_intersect($availableOptions, $allowedOptions);
            }
        }
        if (in_array('BLOCK_HEIGHT', $this->arResult['SECTIONS'])) {
            $this->arResult['PROPS']['HEIGHT'] = Block::checkHeight($this->arResult['CODE'], $this->arResult['PROPS']['HEIGHT']);
        }

        ob_start();
        $this->includeComponentTemplate('panel/design');
        $html = ob_get_clean();

        return [
            'html' => $html,
        ];
    }

    public function getContentTemplateAction($post)
    {
        Event::removeOtherEvents();

        $id = intval($post['id']);
        $tabId = intval($post['tabId']);
        if ($id <= 0) {
            throw new Exception('error');
        }

        if (Config::isDemoMode()) {
            $landingId = Landing::getByBlockId($id);
            Landing::checkDemoAccess($landingId);
        }

        $this->arResult = Block::get($id, [
            'PREVIEW_PICTURE',
            'DETAIL_PICTURE',
            '_PROPERTIES',
        ]);
        $this->arResult['CONFIG'] = Config::$blocks['CONFIG'];
        $this->arResult['INFO'] = Config::getBlockInfo($this->arResult['CODE']);
        $this->arResult['FIELDS_MESS'] = Config::getBlockFieldsMess($this->arResult['CODE']);
        $this->arResult['FIELDS_OPTIONS'] = Config::getBlockFieldsOptions($this->arResult['CODE']);

        $this->arResult['SECTIONS'] = Config::getBlockConfigSections($this->arResult['CODE']);

        if (in_array('CONTENT_TITLE_TAG', $this->arResult['SECTIONS'])) {
            $this->arResult['TITLE_TAG_VALUES'] = Helpers\Iblock::getListPropValuesByValue('TITLE_TAG', $this->arResult['IBLOCK_ID']);
            asort($this->arResult['TITLE_TAG_VALUES']);
            $this->arResult['PROPS']['TITLE_TAG'] = $this->arResult['PROPERTIES']['TITLE_TAG']['VALUE_ENUM_ID'];
            if (empty($this->arResult['PROPS']['TITLE_TAG'])) {
                $defaultTitleTag = Helpers\Iblock::getListPropDefaultValue('TITLE_TAG', $this->arResult['IBLOCK_ID']);
                $this->arResult['PROPS']['TITLE_TAG'] = $defaultTitleTag['ID'];
            }
        }

        if (in_array('CONTENT_BTN', $this->arResult['SECTIONS'])) {
            $this->arResult['BTN_TYPE_VALUES'] = Helpers\Iblock::getListPropValuesByValue('BTN_TYPE', $this->arResult['IBLOCK_ID']);
            $this->arResult['BTN_SIZE_VALUES'] = Helpers\Iblock::getListPropValuesByValue('BTN_SIZE', $this->arResult['IBLOCK_ID']);
            $this->arResult['BTN_TYPE_2_VALUES'] = Helpers\Iblock::getListPropValuesByValue('BTN_TYPE_2', $this->arResult['IBLOCK_ID']);
            $this->arResult['BTN_SIZE_2_VALUES'] = Helpers\Iblock::getListPropValuesByValue('BTN_SIZE_2', $this->arResult['IBLOCK_ID']);
            $this->arResult['ANCHORS'] = $this->getAnchorsList();
        }

        if (in_array('CONTENT_BTN', $this->arResult['SECTIONS']) || in_array('CONTENT_FORM', $this->arResult['SECTIONS'])) {
            $this->arResult['FORMS'] = $this->getFormsList();
        }

        if (in_array('CONTENT_IMPORT', $this->arResult['SECTIONS'])) {
            $this->arResult['IMPORT_FILTERS_VALUES'] = Helpers\Iblock::getListPropValues('IMPORT_FILTERS', $this->arResult['IBLOCK_ID']);
            $this->arResult['IMPORT_SORT_VALUES'] = Helpers\Iblock::getListPropValues('IMPORT_SORT', $this->arResult['IBLOCK_ID']);
            $this->arResult['IMPORT_SORT_ORDER_VALUES'] = Helpers\Iblock::getListPropValues('IMPORT_SORT_ORDER', $this->arResult['IBLOCK_ID']);
            $this->arResult['IMPORT_LINK_TYPE_VALUES'] = Helpers\Iblock::getListPropValues('IMPORT_LINK_TYPE', $this->arResult['IBLOCK_ID']);

            if ($this->arResult['INFO']['IMPORT_DATA_TYPE'] === 'PRODUCTS') {
                $this->arResult['PRICES_INFO'] = Helpers\Catalog::getPricesInfo();
            }

            $importElemIds = [];
            if (!empty($this->arResult['PROPS']['IMPORT_ELEM_IDS'])) {
                $elementNames = Helpers\Iblock::getElementNamesById($this->arResult['PROPS']['IMPORT_ELEM_IDS']);
                foreach ($this->arResult['PROPS']['IMPORT_ELEM_IDS'] as $elemId) {
                    $importElemIds[$elemId] = [
                        'id' => $elemId,
                        'label' => $elementNames[$elemId],
                        'value' => $elementNames[$elemId],
                    ];
                }
            }
            $this->arResult['IMPORT_ELEM_IDS'] = $importElemIds;
        }

        $isWeekDaysSection = in_array('CONTENT_WEEK_DAYS', $this->arResult['SECTIONS']);
        if (in_array('CONTENT_CARDS', $this->arResult['SECTIONS']) || $isWeekDaysSection) {
            [$sort, $order] = explode('|', $this->arResult['PROPS']['ELEMENTS_SORT']);
            $this->arResult['CARDS_SORT'] = $this->arResult['PROPS']['ELEMENTS_SORT'];
            $arSelect = ['ID', 'IBLOCK_ID', 'SORT', 'ACTIVE', 'NAME', 'PREVIEW_TEXT', 'IBLOCK_SECTION_ID'];
            if ($isWeekDaysSection) {
                $arSelect[] = 'PROPERTY_WEEK_DAY';
            }

            $this->arResult['CARDS'] = Block::getCards($id, [$sort => $order], $arSelect);
            if ($isWeekDaysSection) {
                $weekDays = Helpers\Iblock::getListPropValues('WEEK_DAY', Block::getElementsIblockId());
                $weekDays = array_filter(array_column($weekDays, 'VALUE'));
                $this->arResult['WEEK_GAY_GROUPS'] = array_fill_keys($weekDays, []);
                foreach ($this->arResult['CARDS'] as $key => $arCard) {
                    $cardWeekDay = $arCard['PROPERTY_WEEK_DAY_VALUE'];
                    if (!isset($this->arResult['WEEK_GAY_GROUPS'][$cardWeekDay])) {
                        continue;
                    }

                    $this->arResult['WEEK_GAY_GROUPS'][$cardWeekDay][] = $key;
                }
            }
        }

        if (in_array('CONTENT_GALLERY_CARDS', $this->arResult['SECTIONS'])) {
            [$sort, $order] = explode('|', $this->arResult['PROPS']['ELEMENTS_SORT']);
            $this->arResult['CARDS_SORT'] = $this->arResult['PROPS']['ELEMENTS_SORT'];
            $arSelectProps = array_merge(['ID', 'SORT', 'ACTIVE'], Config::getBlockElementsFields($this->arResult['CODE']));
            $this->arResult['CARD_PROPS_CODE'] = $arSelectProps;
            $this->arResult['GALLERY_CARDS'] = Block::getCards($id, [$sort => $order], $arSelectProps);
            foreach ($this->arResult['GALLERY_CARDS'] as &$card) {
                if (!empty($card['DETAIL_PICTURE'])) {
                    $img = \CFile::ResizeImageGet($card['DETAIL_PICTURE'],
                        ['width' => 127, 'height' => 84], BX_RESIZE_IMAGE_EXACT);
                    $card['IMG_SRC'] = $img['src'];

                    $imgInfo = \CFile::GetFileArray($card['DETAIL_PICTURE']);
                    $card['IMG_SIZE'] = \CFile::FormatSize($imgInfo['FILE_SIZE']);
                }

                $card['PROPERTY_PICTURE_TITLE'] = $card['PROPERTY_PICTURE_TITLE_VALUE'];
                $card['PROPERTY_PICTURE_ALT'] = $card['PROPERTY_PICTURE_ALT_VALUE'];
            }
        }

        if (in_array('CONTENT_TABS', $this->arResult['SECTIONS'])) {
            foreach ($this->arResult['CARDS'] as &$card) {
                $cardTabId = intval($card['IBLOCK_SECTION_ID']);
                if ($cardTabId != $tabId) {
                    $card['TAB_CLASS'] = 'not-in-tab';
                }
            }

            $this->arResult['TAB_NAME'] = BlockTabs::getTabName($tabId);
        }

        if (ContentFilter::isInclude($this->arResult['CODE'], $this->arResult['SECTIONS'])) {
            $filterValue = unserialize($this->arResult['PROPS'][ContentFilter::getPropertyCode()] ?? '');
            if (!is_array($filterValue) || empty($filterValue)) {
                $filterValue = ContentFilter::getDefaultValue($this->arResult['CODE']);
            }

            $filterValue['TITLE'] = Config::getBlockElementsFieldsMess($this->arResult['CODE']);
            $this->arResult['FILTER'] = $filterValue;
        }

        ob_start();
        $this->includeComponentTemplate('panel/content');
        $html = ob_get_clean();

        return [
            'html' => $html,
        ];
    }

    public function getCopyTemplateAction()
    {
        Event::removeOtherEvents();

        $this->arResult['IBLOCKS'] = \Bitrix\Iblock\IblockTable::getList([
            'filter' => [
                'ACTIVE' => 'Y',
                'CODE' => 'ranx_landing_list_%',
            ],
        ])->fetchAll();

        $types = \Ranx\Landing\SectionTable::getList([
            'select' => ['IBLOCK_ID', 'TYPE']
        ])->fetchAll();
        $types = array_column($types, 'TYPE', 'IBLOCK_ID');

        foreach ($this->arResult['IBLOCKS'] as &$iblock) {
            $elements = \Bitrix\Iblock\ElementTable::getList([
                'order' => ['SORT' => 'ASC'],
                'filter' => [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $iblock['ID'],
                ],
            ])->fetchAll();

            $sections = \Bitrix\Iblock\SectionTable::getList([
                'order' => ['SORT' => 'ASC'],
                'filter' => [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $iblock['ID'],
                ],
            ])->fetchAll();

            foreach ($sections as &$section) {
                foreach ($elements as $k => $element) {
                    if ($element['IBLOCK_SECTION_ID'] == $section['ID']) {
                        $section['ELEMENTS'][] = $element;
                        unset($elements[$k]);
                    }
                }
            }

            $iblock['SECTIONS'] = $sections;
            $iblock['ELEMENTS'] = array_values($elements);
            $iblock['TYPE'] = $types[$iblock['ID']];
        }

        ob_start();
        $this->includeComponentTemplate('panel/copy');
        $html = ob_get_clean();

        return [
            'html' => $html,
        ];
    }

    public function getCardTemplateAction($post)
    {
        Event::removeOtherEvents();

        $id = intval($post['id']);
        $blockId = intval($post['blockId']);

        if ($blockId <= 0) {
            throw new Exception('Invalid block id');
        }

        if (Config::isDemoMode()) {
            $landingId = Landing::getByBlockId($blockId);
            Landing::checkDemoAccess($landingId);
        }

        $block = Block::get($blockId, ['_PROPERTIES']);
        $elementsIblockId = Block::getElementsIblockId();
        $elementsFields = Config::getBlockElementsFields($block['CODE']);
        $elementsFieldsMess = Config::getBlockElementsFieldsMess($block['CODE']);
        $elementsFieldsOptions = Config::getBlockElementsFieldsOptions($block['CODE']);

        // element properties
        $cardProps = [];
        $cardPropsList = [];
        $cardFieldsList = [];
        foreach ($elementsFields as $field) {
            if (strpos($field, 'PROPERTY_') === 0) {
                $cardPropsList[] = substr($field, 9);
            } else {
                $cardFieldsList[] = $field;
            }
        }
        if ($cardPropsList)
            $cardProps = Helpers\Iblock::getPropsInfoByCode($cardPropsList, $elementsIblockId);

        // elements
        $this->arResult = [];
        if (!empty($id)) {
            $dbCards = \CIBlockElement::GetList(
                ['SORT' => 'ASC'],
                ['ID' => $id, 'IBLOCK_ID' => $elementsIblockId],
                false,
                false,
                array_merge(['ID', 'IBLOCK_ID', 'SORT', 'ACTIVE'], $cardFieldsList)
            );
            while ($obCard = $dbCards->GetNextElement()) {
                $arCard = $obCard->GetFields();
                if ($arCard['NAME'] == Block::EMPTY_TITLE) {
                    $arCard['NAME'] = '';
                    $arCard['~NAME'] = '';
                }
                $arCard['PROPERTIES'] = $obCard->GetProperties();

                $this->arResult = $arCard;
            }
        }

        $this->formatFieldsForCard(
            $elementsFields, $cardProps, $elementsFieldsMess, $elementsFieldsOptions);

        $this->arResult['ELEMENTS_FIELDS'] = $elementsFields;
        $this->arResult['POPUP_ELEMENTS_FIELDS'] = Config::getBlockPopupElementsFields($block['CODE']);
        $this->arResult['BLOCK_CODE'] = $block['CODE'];

        if (in_array('PROPERTY_BTN_TYPE', $elementsFields)) {
            $this->arResult['BTN_TYPE_VALUES'] = Helpers\Iblock::getListPropValuesByValue('BTN_TYPE', $elementsIblockId);
        }
        if (in_array('PROPERTY_BTN_SIZE', $elementsFields)) {
            $this->arResult['BTN_SIZE_VALUES'] = Helpers\Iblock::getListPropValuesByValue('BTN_SIZE', $elementsIblockId);
        }
        if (in_array('PROPERTY_BTN_TYPE_2', $elementsFields)) {
            $this->arResult['BTN_TYPE_2_VALUES'] = Helpers\Iblock::getListPropValuesByValue('BTN_TYPE_2', $elementsIblockId);
        }
        if (in_array('PROPERTY_BTN_SIZE_2', $elementsFields)) {
            $this->arResult['BTN_SIZE_2_VALUES'] = Helpers\Iblock::getListPropValuesByValue('BTN_SIZE_2', $elementsIblockId);
        }
        if (in_array('PROPERTY_BTN_SHOW', $elementsFields) || in_array('PROPERTY_BTN_SHOW_2', $elementsFields)
            || in_array('PROPERTY_LINK', $elementsFields)) {
            $this->arResult['ANCHORS'] = $this->getAnchorsList($block['PROPS']['LANDING'], $block['PROPS']['MODE']);
            $this->arResult['FORMS'] = $this->getFormsList();
        }

        ob_start();
        $this->includeComponentTemplate('panel/card');
        $html = ob_get_clean();

        if (!empty($this->arResult['NAME'])) {
            $panelTitle = trim(strip_tags($this->arResult['~NAME']));
        } elseif (!empty($this->arResult['PREVIEW_TEXT'])) {
            $panelTitle = trim(strip_tags($this->arResult['PREVIEW_TEXT']));
        } elseif ($id) {
            $panelTitle = Loc::getMessage('RX_LANDING_PANEL_CARD_TITLE', ['#ID#' => $id]);
        } else {
            $panelTitle = Loc::getMessage('RX_LANDING_PANEL_CARD_ADD_TITLE');
        }
        $panelTitle = Helpers\Helper::cutName($panelTitle, 27);

        return [
            'html' => $html,
            'title' => $panelTitle,
        ];
    }

    public function getUpdatesTemplateAction()
    {
        Event::removeOtherEvents();

        $updatesRssUrl = Config::getUpdatesRssUrl();
        $this->arResult['UPDATES_BLOG_LINK'] = Config::getUpdatesBlogUrl();
        $this->arResult['ITEMS'] = [];

        $cacheId = 'ranx_panel_landing_updates';
        $cacheTime = 24 * 60 * 60;
        $cache = Data\Cache::createInstance();

        if ($cache->initCache($cacheTime, $cacheId, 'ranx_panel_landing')) {
            $vars = $cache->getVars();
            $this->arResult = $vars['arResult'];
        }
        elseif ($cache->startDataCache()) {
            $response = Helpers\Helper::getDataByUrl($updatesRssUrl);

            if (empty($response) || !class_exists('SimpleXmlElement')) {
                $cache->abortDataCache();
                throw new Exception(Loc::getMessage('RX_LANDING_PANEL_UPDATES_ERROR',
                    ['#BLOG_LINK#' => $this->arResult['UPDATES_BLOG_LINK']]));
            }

            $rssObj = new SimpleXmlElement($response);
            foreach ($rssObj ->channel->item as $itemObj) {
                $imgFile = Helpers\Helper::getDataByUrl($itemObj->enclosure['url']);
                $imgType = $itemObj->enclosure['type'];
                $description = str_replace('src="', 'data-src="', $itemObj->description);

                $this->arResult['ITEMS'][] = [
                    'TITLE' => (string)$itemObj->title,
                    'LINK' => (string)$itemObj->link,
                    'CONTENT' => $description,
                    'DATE' => strtolower(\FormatDate('j F Y', strtotime($itemObj->pubDate))),
                    'IMG_TO_BASE64' => 'data:'.$imgType.';base64,'.base64_encode($imgFile),
                ];
            }

            $cache->endDataCache(['arResult' => $this->arResult]);
        }

        ob_start();
        $this->includeComponentTemplate('panel/updates');
        $html = ob_get_clean();

        return [
            'html' => $html,
        ];
    }

    public function getGroupTemplateAction($post)
    {
        Event::removeOtherEvents();

        $blockId = intval($post['blockId']);
        $groupId = intval($post['groupId']);
        if ($groupId <= 0 && $blockId <= 0) {
            throw new Exception();
        }

        $this->arResult = [];
        $this->arResult['ID'] = $groupId;
        $this->arResult['BLOCK_ID'] = $blockId;

        if ($groupId) {
            $this->arResult['BLOCKS'] = BlockGroup::getBlocks($groupId, ['_PROPERTIES']);
        } else {
            $this->arResult['BLOCKS'][] = Block::get($blockId, ['_PROPERTIES']);
        }

        $regionIds = [];
        foreach ($this->arResult['BLOCKS'] as $block) {
            if (!empty($block['PROPS']['REGION_INCLUDE'])) {
                foreach ($block['PROPS']['REGION_INCLUDE'] as $regionId) {
                    if (!in_array($regionId, $regionIds)) {
                        $regionIds[] = $regionId;
                    }
                }
            }
            if (!empty($block['PROPS']['REGION_EXCLUDE'])) {
                foreach ($block['PROPS']['REGION_EXCLUDE'] as $regionId) {
                    if (!in_array($regionId, $regionIds)) {
                        $regionIds[] = $regionId;
                    }
                }
            }
        }
        $regions = Region::getRegionsById($regionIds);
        $regionNames = array_column($regions, 'NAME', 'ID');

        // set new names (by region)
        foreach ($this->arResult['BLOCKS'] as &$block) {
            $newName = '';

            if (!empty($block['PROPS']['REGION_INCLUDE'])) {
                $firstRegion = reset($block['PROPS']['REGION_INCLUDE']);
                if (empty($regionNames[$firstRegion])) {
                    continue;
                }
                $newName = $regionNames[$firstRegion];

                if (count($block['PROPS']['REGION_INCLUDE']) > 1) {
                    $newName .= Loc::getMessage('RX_LANDING_PANEL_GROUP_REGION_PLUS', ['#COUNT#' => count($block['PROPS']['REGION_INCLUDE']) - 1]);
                }

            } elseif (!empty($block['PROPS']['REGION_EXCLUDE'])) {
                $firstRegion = reset($block['PROPS']['REGION_EXCLUDE']);
                if (empty($regionNames[$firstRegion])) {
                    continue;
                }
                $newName = Loc::getMessage('RX_LANDING_PANEL_GROUP_REGION_EXCLUDE') . $regionNames[$firstRegion];

                if (count($block['PROPS']['REGION_EXCLUDE']) > 1) {
                    $newName .= Loc::getMessage('RX_LANDING_PANEL_GROUP_REGION_PLUS', ['#COUNT#' => count($block['PROPS']['REGION_EXCLUDE']) - 1]);
                }
            }

            if ($newName) {
                $block['NAME'] = $newName;
            }
        }
        unset($block);

        ob_start();
        $this->includeComponentTemplate('panel/group');
        $html = ob_get_clean();

        return [
            'html' => $html,
        ];
    }

    public function getVariantTemplateAction($post)
    {
        Event::removeOtherEvents();

        $id = intval($post['id']);
        if ($id <= 0) {
            throw new Exception();
        }

        $this->arResult = [];
        $this->arResult = Block::get($id);

        $this->arResult['REGION_INCLUDE'] = [];
        $this->arResult['REGION_EXCLUDE'] = [];
        $this->arResult['BRANCH_INCLUDE'] = [];
        $this->arResult['BRANCH_EXCLUDE'] = [];

        $includeRegions = Block::getIncludeRegions($id);
        $excludeRegions = Block::getExcludeRegions($id);
        $includeBranches = Block::getIncludeBranches($id);
        $excludeBranches = Block::getExcludeBranches($id);

        foreach ($includeRegions as $region) {
            $this->arResult['REGION_INCLUDE'][$region['ID']] = [
                'id' => $region['ID'],
                'label' => $region['NAME'],
                'value' => $region['NAME'],
            ];
        }
        foreach ($excludeRegions as $region) {
            $this->arResult['REGION_EXCLUDE'][$region['ID']] = [
                'id' => $region['ID'],
                'label' => $region['NAME'],
                'value' => $region['NAME'],
            ];
        }

        foreach ($includeBranches as $branch) {
            $this->arResult['BRANCH_INCLUDE'][$branch['ID']] = [
                'id' => $branch['ID'],
                'label' => $branch['NAME'],
                'value' => $branch['NAME'],
            ];
        }
        foreach ($excludeBranches as $branch) {
            $this->arResult['BRANCH_EXCLUDE'][$branch['ID']] = [
                'id' => $branch['ID'],
                'label' => $branch['NAME'],
                'value' => $branch['NAME'],
            ];
        }

        ob_start();
        $this->includeComponentTemplate('panel/variant');
        $html = ob_get_clean();

        if (!empty($this->arResult['NAME'])) {
            $panelTitle = trim(strip_tags($this->arResult['NAME']));
        } elseif (!empty($this->arResult['PREVIEW_TEXT'])) {
            $panelTitle = trim(strip_tags($this->arResult['PREVIEW_TEXT']));
        } elseif ($id) {
            $panelTitle = Loc::getMessage('RX_LANDING_PANEL_CARD_TITLE', ['#ID#' => $id]);
        } else {
            $panelTitle = Loc::getMessage('RX_LANDING_PANEL_CARD_ADD_TITLE');
        }
        $panelTitle = Helpers\Helper::cutName($panelTitle, 26);

        return [
            'html' => $html,
            'title' => $panelTitle,
        ];
    }

    public function getMenuTemplateAction($post)
    {
        Event::removeOtherEvents();

        $type = trim($post['type']);
        $path = trim($post['path']);
        $landingId = trim($post['landingId']);
        $mode = trim($post['mode']);
        if (!$path) {
            $path = '/';
        }
        if (!$type) {
            throw new Exception('No type provided');
        }

        Loader::includeModule('fileman');

        $menu = new \CMenu($type);
        $menu->Init($path);

        $this->arResult['PATH'] = $path;
        $this->arResult['DIR'] = $menu->MenuDir;
        $this->arResult['LINKS'] = $menu->arMenu;

        array_unshift($this->arResult['LINKS'], [
            Loc::getMessage('RX_LANDING_PANEL_MENU_NEW'),
            '',
            [],
            [],
            '',
        ]);

        $this->arResult['LANDINGS'] = Landing::getLinksForSelect();
        $this->arResult['ANCHORS'] = self::getAnchorsList($landingId, $mode);

        ob_start();
        $this->includeComponentTemplate('panel/menu');
        $html = ob_get_clean();

        return [
            'html' => $html,
            'title' => '',
        ];
    }

    public function getTabsTemplateAction($post)
    {
        $blockId = intval($post['blockId']);
        $tabId = $post['tabId'];

        if ($blockId <= 0) {
            throw new Exception();
        }

        $this->arResult = [];
        $this->arResult['BLOCK_ID'] = $blockId;
        $this->arResult['TAB_ID'] = $tabId;
        $this->arResult['TABS'] = BlockTabs::get($blockId);

        ob_start();
        $this->includeComponentTemplate('panel/tabs');
        $html = ob_get_clean();

        return [
            'html' => $html,
        ];
    }

    public function getSettingsTemplateAction($post)
    {
        $blockId = intval($post['id']);
        if ($blockId <= 0) {
            throw new Exception();
        }

        $this->arResult = PanelSettings::get($blockId);

        ob_start();
        $this->includeComponentTemplate('panel/settings');
        $html = ob_get_clean();

        return [
            'html' => $html,
        ];
    }
}
