<?php

use Bitrix\Main\Application;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Json;
use Ranx\Landing\Landing;
use Ranx\Landing\Block;
use Ranx\Landing\BlockTabs;
use Ranx\Landing\Event;
use Ranx\Landing\BlockGroup;
use Ranx\Landing\Fields;
use Ranx\Landing\Config;
use Ranx\Landing\Preset;
use Ranx\Landing\Region;
use Ranx\Landing\Helpers;
use Ranx\Landing\ActionFilter;
use Ranx\Landing\SectionTable;
use Ranx\Landing\Section;

class RanxPanelLandingAjaxController extends Controller
{
    const ACTIONS = [
        'showBlock',
        'hideBlock',
        'downBlock',
        'upBlock',
        'sortBlock',
        'addBlock',
        'removeBlock',
        'replaceBlock',
        'moveBlock',
        'copyBlock',
        'refreshBlock',
        'editDesign',
        'editContent',
        'editCard',
        'editMenu',
        'editVariant',
        'addVariant',
        'editParams',
        'restoreParams',
        'addSection',
        'removeSection',
        'addElement',
        'removeElement',
        'downloadPreset',
        'uploadPreset',
        'applyPreset',
        'deleteCustomPreset',
        'searchElements',
        'searchRegions',
        'searchBranches',
        'getTotalPrice',
        'getIblockSectionsForSelect',
        'editTabs',
        'editSettings',
    ];

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
                'postfilters' => [
                    new ActionFilter\ClearCompositeCache(),
                ],
            ];
        }

        return $result;
    }

    private function includeModules()
    {
        Loader::includeModule('iblock');
        if (!Loader::includeModule('ranx.landing')) {
            throw new Exception('no ranx.landing module');
        }
    }

    private function getBlockTemplate($blockId, $withWrap = false, $defaultTabId = 0)
    {
        if (!$blockId || intval($blockId) <= 0) {
            return '';
        }

        $block = Block::get($blockId, ['_PROPERTIES']);

        if (empty($block)) {
            return '';
        }

        ob_start();

        if ($withWrap) {
            echo '<div class="block-wrap flex-order" id="block_'.$block['ID'].'" data-order="'.$block['SORT'].'" data-id="'.$block['ID'].'"' .
                'data-name="' . Config::getBlockTitle($block['CODE']). '">';
        }

        $GLOBALS['APPLICATION']->IncludeComponent(
            'ranx:block.landing',
            $block['CODE'],
            [
                'DETAIL_ID' => $block['ID'],
                'DEFAULT_TAB_ID' => $defaultTabId,
                'IBLOCK_TYPE' => 'ranx_landing',
                'IBLOCK_ID' => $block['IBLOCK_ID'],
                'ACTIVE' => $block['ACTIVE'],
                'CARDS_COUNT' => $block['PROPS']['AUTO_COUNT'],
            ],
            false
        );

        if ($withWrap) {
            echo '</div>';
        }

        return ob_get_clean();
    }

    public function showBlockAction($post)
    {
        $id = intval($post['id']);
        $groupId = intval($post['groupId']);

        if ($id <= 0) {
            throw new Exception();
        }

        $landingId = Landing::clearCacheByBlockId($id);
        if (Config::isDemoMode()) {
            Landing::checkDemoAccess($landingId);
        }

        if ($groupId > 0) {
            BlockGroup::activate($groupId);
        } else {
            Block::activate($id);
        }

        return true;
    }

    public function hideBlockAction($post)
    {
        $id = intval($post['id']);
        $groupId = intval($post['groupId']);

        if ($id <= 0) {
            throw new Exception();
        }

        $landingId = Landing::clearCacheByBlockId($id);
        if (Config::isDemoMode()) {
            Landing::checkDemoAccess($landingId);
        }

        if ($groupId > 0) {
            BlockGroup::deActivate($groupId);
        } else {
            Block::deActivate($id);
        }

        return true;
    }

    public function downBlockAction($post)
    {
        $id = intval($post['id']);
        $nextId = intval($post['nextId']);
        $groupId = intval($post['groupId']);
        $nextGroupId = intval($post['nextGroupId']);

        if ($id <= 0 || $nextId <= 0) {
            throw new Exception();
        }

        $landingId = Landing::clearCacheByBlockId($id);
        if (Config::isDemoMode()) {
            Landing::checkDemoAccess($landingId);
        }

        if ($groupId > 0) {
            BlockGroup::incSort($groupId);
        } else {
            Block::incSort($id);
        }

        if ($nextGroupId > 0) {
            BlockGroup::decSort($nextGroupId);
        } else {
            Block::decSort($nextId);
        }

        return true;
    }

    public function upBlockAction($post)
    {
        $id = intval($post['id']);
        $prevId = intval($post['prevId']);
        $groupId = intval($post['groupId']);
        $prevGroupId = intval($post['prevGroupId']);

        if ($id <= 0 || $prevId <= 0) {
            throw new Exception();
        }

        $landingId = Landing::clearCacheByBlockId($id);
        if (Config::isDemoMode()) {
            Landing::checkDemoAccess($landingId);
        }

        if ($groupId > 0) {
            BlockGroup::decSort($groupId);
        } else {
            Block::decSort($id);
        }

        if ($prevGroupId > 0) {
            BlockGroup::incSort($prevGroupId);
        } else {
            Block::incSort($prevId);
        }

        return true;
    }

    public function sortBlockAction($post)
    {
        $blockId = intval($post['id']);
        $sort = intval($post['sort']);

        if ($blockId <= 0 || $sort <= 0) {
            throw new Exception();
        }

        $landingId = Landing::clearCacheByBlockId($blockId);
        if (Config::isDemoMode()) {
            Landing::checkDemoAccess($landingId);
        }

        Block::setSort($blockId, $sort);

        return true;
    }

    public function addBlockAction($post)
    {
        Event::removeOtherEvents();

        $code = trim($post['code']);
        $landingId = intval($post['landingId']);
        $mode = trim($post['mode']);
        $nextBlockIds = $post['nextBlocks'];

        if (!$mode || !in_array($mode, Landing::MODE_ALL)) {
            $mode = Landing::MODE_ELEMENT;
        }

        if (!$landingId) {
            throw new Exception('No landing id');
        }

        if (Config::isDemoMode()) {
            Landing::checkDemoAccess($landingId);
            if (!empty($_SESSION['RX_LANDING_DEMO_MAX_BLOCKS']) && $_SESSION['RX_LANDING_DEMO_MAX_BLOCKS'] >= Config::DEMO_MAX_BLOCKS) {
                throw new Exception('Max blocks count exceeded for demo mode');
            }
        }

        Landing::cleanCache($landingId, $mode);

        if ($code && ($newBlock = Block::add($landingId, $code, $nextBlockIds, $mode))) {

            if (Config::isDemoMode()) {
                if (!isset($_SESSION['RX_LANDING_DEMO_MAX_BLOCKS'])) {
                    $_SESSION['RX_LANDING_DEMO_MAX_BLOCKS'] = 0;
                }
                $_SESSION['RX_LANDING_DEMO_MAX_BLOCKS']++;
            }

            $html = $this->getBlockTemplate($newBlock['ID'], true);

            return [
                'html' => $html,
            ];
        }

        throw new Exception('Error while adding a new block');
    }

    public function removeBlockAction($post)
    {
        $id = intval($post['id']);
        $groupId = intval($post['groupId']);

        $landing = Landing::getByBlockId($post['id']);
        if (empty($landing['ID'])) {
            throw new Exception();
        }

        Landing::cleanCache($landing['ID'], $landing['MODE']);
        if (Config::isDemoMode()) {
            Landing::checkDemoAccess($landing['ID']);
        }

        if ($groupId > 0) {
            BlockGroup::remove($groupId);
        } elseif ($id > 0){
            Block::remove($id);
        }

        // just for autofix
        Block::getByLanding($landing['ID'], $landing['MODE']);

        return true;
    }

    public function replaceBlockAction($post)
    {
        Event::removeOtherEvents();

        $id = intval($post['id']);
        $code = trim($post['code']);
        $tabId = $post['tabId'];

        if ($id <= 0 || !$code) {
            throw new Exception(__METHOD__.': incorrect data in post request');
        }

        $landingId = Landing::clearCacheByBlockId($id);
        if (Config::isDemoMode()) {
            Landing::checkDemoAccess($landingId);
        }

        Block::changeCode($id, $code);

        $html = $this->getBlockTemplate($id, true, $tabId);

        return [
            'html' => $html,
        ];
    }

    public function moveBlockAction($post)
    {
        Event::removeOtherEvents();

        $id = intval($post['id']);
        $groupId = intval($post['groupId']);
        $landingId = intval($post['landing']);
        $iblockId = intval($post['iblock']);
        $mode = trim($post['mode']);

        $request = Application::getInstance()->getContext()->getRequest();
        $siteId = $request->get('SITE_ID');

        if (!$id || !$iblockId) {
            throw new Exception(Loc::getMessage('RX_LANDING_PANEL_MOVE_BLOCK_ERROR'));
        }

        if (!$landingId) {
            $landingId = $iblockId;
            $mode = Landing::detectMode($landingId);
        }

        Landing::clearCacheByBlockId($id);
        Landing::cleanCache($landingId, $mode);

        if (($groupId <= 0 || !BlockGroup::move($groupId, $landingId, $mode)) &&
            ($groupId > 0  || !Block::move($id, $landingId, $mode))) {
            throw new Exception(Loc::getMessage('RX_LANDING_PANEL_MOVE_BLOCK_ERROR'));
        }

        $landingLink = Landing::getLink($landingId, $iblockId, $mode, $siteId);
        if ($landingLink) {
            $landingLink .= '#block_' . $id;
        }

        return [
            'link' => $landingLink,
        ];
    }

    public function copyBlockAction($post)
    {
        Event::removeOtherEvents();

        $id = intval($post['id']);
        $groupId = intval($post['groupId']);
        $landingId = intval($post['landing']);
        $iblockId = intval($post['iblock']);
        $mode = trim($post['mode']);
        $isThrough = isset($post['through']);

        $request = Application::getInstance()->getContext()->getRequest();
        $siteId = $request->get('SITE_ID');

        if (!$id || !$iblockId) {
            throw new Exception(Loc::getMessage('RX_LANDING_PANEL_COPY_BLOCK_ERROR'));
        }

        if (!$landingId) {
            $landingId = $iblockId;
            $mode = Landing::detectMode($landingId);
        }

        Landing::cleanCache($landingId, $mode);

        $newBlockId = 0;
        if ($groupId > 0) {
            $newGroupId = BlockGroup::copy($groupId, $landingId, $mode, $siteId, !$isThrough);
            if ($newGroupId) {
                $newBlockId = BlockGroup::getFirstBlockId($newGroupId);
            }
        } else {
            $newBlockId = Block::copy($id, $landingId, $mode, $siteId, !$isThrough);
        }

        if (!$newBlockId) {
            throw new Exception(Loc::getMessage('RX_LANDING_PANEL_COPY_BLOCK_ERROR'));
        }

        $landingLink = Landing::getLink($landingId, $iblockId, $mode, $siteId);
        if ($landingLink) {
            $landingLink .= '#block_' . $newBlockId;
        }

        return [
            'link' => $landingLink,
        ];
    }

    public function refreshBlockAction($post)
    {
        Event::removeOtherEvents();

        $id = intval($post['id']);
        if (!$id) {
            throw new Exception();
        }

        $html = $this->getBlockTemplate($id);

        return [
            'html' => $html,
        ];
    }

    public function editDesignAction($post)
    {
        Event::removeOtherEvents();

        $id = intval($post['id']);
        $tabId = intval($post['tabId']);
        if ($id <= 0) {
            throw new Exception('error');
        }

        $block = Block::get($id);
        if (empty($block)) {
            throw new Exception('Block is not found');
        }

        if (Config::isDemoMode()) {
            $landingId = Landing::getByBlockId($id);
            Landing::checkDemoAccess($landingId);
        }

        Block::cleanCache($id);

        Block::updateProps($id, $block['CODE'], [
            'COLS' => intval($post['cols']) ?? 0,
            'INDENT_TOP' => intval($post['indent_top']) ?? 0,
            'INDENT_BOT' => intval($post['indent_bot']) ?? 0,
            'LINE_BOT' => !empty($post['line_bot']),
            'HOVER_EFFECT' => !empty($post['hover_effect']),
            'BG_COLOR' => $post['bg_color_custom'] ?: ($post['bg_color'] ?: ''),
            'CARDS_BG_COLOR' => $post['cards_bg_color_custom'] ?: ($post['cards_bg_color'] ?: ''),
            'TINT_COLOR' => isset($post['tint_color_active']) ? ($post['tint_color'] ?: '') : '',
            'TEXT_LIGHT' => !empty($post['text_color']) && $post['text_color'] == 'light',
            'ALIGN' => !empty($post['align']) ? trim($post['align']) : 'default',
            'INDENT_ELEMENTS' => !empty($post['indent_elements']),
            'PICTURE_ALIGN' => !empty($post['picture_align']) ? trim($post['picture_align']) : 'right',
            'HIDE_TABS' => !empty($post['hide_tabs']),
            'BG_PICTURE' => $post['bg_picture'],
            'PARALLAX_EFFECT' => !empty($post['parallax_effect']),
            'SLIDER' => !empty($post['slider']),
            'WIDE' => !empty($post['wide']),
            'HEIGHT' => intval($post['block_height'] ?? 0),
        ]);

        $html = $this->getBlockTemplate($id, false, $tabId);

        return [
            'html' => $html,
        ];
    }

    public function editContentAction($post)
    {
        Event::removeOtherEvents();

        $id = intval($post['id']);
        $tabId = $post['tabId'];
        if ($id <= 0) {
            throw new Exception('error');
        }

        $block = Block::get($id);
        if (empty($block)) {
            throw new Exception('Block is not found');
        }

        if (Config::isDemoMode()) {
            $landingId = Landing::getByBlockId($id);
            Landing::checkDemoAccess($landingId);
        }

        Block::cleanCache($id);

        if (isset($post['TITLE'])) {
            Block::updateTitle($id, [
                'HIDE_TITLE' => !isset($post['SHOW_TITLE']),
                'NAME' => strlen(trim($post['TITLE'])) ? trim($post['TITLE']) : Block::EMPTY_TITLE,
                'CATTITLE' => strlen(trim($post['CATTITLE'])) ? trim($post['CATTITLE']) : '',
                'SUBTITLE' => strlen(trim($post['SUBTITLE'])) ? trim($post['SUBTITLE']) : '',
                'DESC' => strlen(trim($post['DESC'])) ? trim($post['DESC']) : '',
            ]);
        }
        Block::updateTitle($id, [
            'ANCHOR_TITLE' => strlen(trim($post['ANCHOR_TITLE'])) ? trim($post['ANCHOR_TITLE']) : '',
        ]);

        if (isset($post['TITLE_TAG'])) {
            Block::updateTitleTag($id, $post['TITLE_TAG']);
        }

        if (isset($post['PREVIEW_PICTURE'])) {
            Block::updatePictureFromBase64($id, $post['PREVIEW_PICTURE']);
        }
        if (isset($post['DETAIL_PICTURE'])) {
            Block::updatePictureFromBase64($id, $post['DETAIL_PICTURE'], 'DETAIL_PICTURE');
        }

        if (!empty($post['BTN_TYPE'])) { // BTN_TYPE cannot be empty
            Block::updateButton($id, [
                'BTN_SHOW' => isset($post['BTN_SHOW']),
                'BTN_TYPE' => $post['BTN_TYPE'] ?? 0,
                'BTN_SIZE' => $post['BTN_SIZE'] ?? 0,
                'BTN_TEXT' => $post['BTN_TEXT'] ?? '',
                'BTN_LINK' => $post['BTN_LINK'] ?? '',
                'BTN_LINK_TYPE' => $post['BTN_LINK_TYPE'] ?? '',
                'BTN_GOAL' => $post['BTN_GOAL'] ?? '',
                'BTN_CLASS' => $post['BTN_CLASS'] ?? '',
            ]);
        }
        if (!empty($post['BTN_TYPE_2'])) { // BTN_TYPE cannot be empty
            Block::updateButton($id, [
                'BTN_SHOW' => isset($post['BTN_SHOW_2']),
                'BTN_TYPE' => $post['BTN_TYPE_2'] ?? 0,
                'BTN_SIZE' => $post['BTN_SIZE_2'] ?? 0,
                'BTN_TEXT' => $post['BTN_TEXT_2'] ?? '',
                'BTN_LINK' => $post['BTN_LINK_2'] ?? '',
                'BTN_LINK_TYPE' => $post['BTN_LINK_TYPE_2'] ?? '',
                'BTN_GOAL' => $post['BTN_GOAL_2'],
                'BTN_CLASS' => $post['BTN_CLASS_2'],
            ], '_2');
        }

        if (isset($post['FORM'])) {
            Block::updateForm($id, $post['FORM'], $post['FORM_BTN_TEXT']);
        }

        if (isset($post['IMPORT_ID']) && !Config::isDemoLanding()) {
            Block::updateImportElementsFields($id, [
                'IMPORT_ELEMENTS' => $post['SHOW_IMPORT_ELEMENTS'] ?? '',
                'IMPORT_ID' => $post['IMPORT_ID'] ?? 0,
                'IMPORT_FILTERS' => $post['IMPORT_FILTERS'] ?? '',
                'ELEMENTS_COUNT' => $post['ELEMENTS_COUNT'] ?? 0,
                'IMPORT_ELEM_IDS' => $post['IMPORT_ELEM_IDS'] ?? [],
                'IMPORT_SECTION_ID' => $post['IMPORT_SECTION_ID'] ?? 0,
                'IMPORT_SORT' => $post['IMPORT_SORT'] ?? '',
                'IMPORT_SORT_ORDER' => $post['IMPORT_SORT_ORDER'] ?? '',
                'IMPORT_PRICE_ID' => $post['IMPORT_PRICE_ID'] ?? 0,
                'IMPORT_LINK_TYPE' => $post['IMPORT_LINK_TYPE'] ?? '',
            ]);
        }

        if (!isset($post['SHOW_ELEMENTS'])) {
            Block::hideElements($id);
        } else {
            Block::showElements($id);
        }

        if (isset($post['CONTENT_VIDEO'])) {
            Block::updateVideo($id, [
                'HIDE_VIDEO' => !isset($post['SHOW_VIDEO']),
                'VIDEO_LINK' => $post['VIDEO_LINK'] ?? '',
                'VIDEO_POPUP_SHOW' => isset($post['VIDEO_POPUP_SHOW']),
                'VIDEO_NOTE' => $post['VIDEO_NOTE'] ?? '',
            ]);
        }

        if (isset($post['CONTENT_TABS'])) {
            Block::updateTabs($id, [
                'USE_TABS' => isset($post['USE_TABS']),
            ]);
        }

        if (isset($post['CONTENT_AUTO'])) {
            Block::updateAuto($id, [
                'AUTO_BLOCK' => isset($post['AUTO_BLOCK']),
                'AUTO_TYPE' => $post['AUTO_TYPE'],
                'AUTO_COUNT' => $post['AUTO_COUNT'],
            ]);
        }

        if (isset($post['CONTENT_FILTER'])) {
            Block::updateFilterSettings($id, $post['FILTER'] ?? [], $block['CODE']);
        }

        if (isset($post['CONTENT_GALLERY_CARDS'])) {
            $elementFields = [];
            foreach ($post as $postKey => $postVal) {
                if (preg_match('/^ELEMENT_([0-9-]+)_([A-Z0-9_]+)$/', $postKey, $matches)) {
                    $elementId = empty($matches[1]) ? 0  : trim($matches[1]);
                    $fieldName = empty($matches[2]) ? '' : trim($matches[2]);

                    if (!$elementId || !$fieldName) {
                        continue;
                    }

                    if (!isset($elementFields[$elementId])) {
                        $elementFields[$elementId] = [];
                    }
                    $elementFields[$elementId][$fieldName] = $postVal;
                }
            }
            Block::updateGalleryElements($id, $elementFields);
            Block::updateElementsSort($id, $post['CARDS_SORT']);
        }

        if (isset($post['CONTENT_CARDS'])) {
            // collect element fields to array
            $elementIds = Block::getElementIds($id);
            $elementFields = [];
            $elementsToDelete = $elementIds;
            foreach ($post as $postKey => $postVal) {
                if (preg_match('/^ELEMENT_([A-Z0-9]+)_([A-Z0-9_]+)$/', $postKey, $matches)) {

                    $elementId = !empty($matches[1]) ? trim($matches[1]) : 0;
                    $fieldName = !empty($matches[2]) ? trim($matches[2]) : '';

                    if (!$elementId || !$fieldName || !in_array($elementId, $elementIds)) {
                        continue;
                    }

                    foreach ($elementsToDelete as $i => $elDeleteId) {
                        if ($elementId == $elDeleteId) {
                            unset($elementsToDelete[$i]);
                        }
                    }

                    if (!isset($elementFields[$elementId])) {
                        $elementFields[$elementId] = [];
                    }
                    $elementFields[$elementId][$fieldName] = $postVal;
                }
            }

            if ($elementFields) {
                Block::updateElementsActiveAndSort($elementFields);
            }

            if ($elementsToDelete) {
                Block::deleteElements($elementsToDelete);
            }

            Block::updateElementsSort($id, $post['CARDS_SORT']);
        }

        if (isset($post['PREVIEW_PICTURE']) || isset($post['DETAIL_PICTURE'])) {
            Helpers\File::removeTemp();
        }

        $html = $this->getBlockTemplate($id, false, $tabId);

        return [
            'html' => $html,
        ];
    }

    public function editCardAction($post)
    {
        Event::removeOtherEvents();

        $id = intval($post['id']);
        $blockId = intval($post['blockId']);
        $tabId = $post['tabId'];
        if ($blockId <= 0) {
            throw new Exception('Invalid block id');
        }
        if ($id < 0) {
            $id = 0;
        }

        if (Config::isDemoMode()) {
            $landingId = Landing::getByBlockId($blockId);
            Landing::checkDemoAccess($landingId);
        }

        $block = Block::get($blockId);
        Block::cleanCache($blockId);

        Fields\IntervalTime::prepareFieldToSave($post);
        $elementFieldsToUpdate = Config::getBlockElementsFields($block['CODE']);
        $elementsIblockId = Block::getElementsIblockId();

        $propCodes = [];
        foreach ($elementFieldsToUpdate as $field) {
            if (strpos($field, 'PROPERTY_') === 0) {
                $propCodes[] = substr($field, 9);
            }
        }
        $propInfo = Helpers\Iblock::getPropsInfoByCode($propCodes, $elementsIblockId);

        if ($id > 0) {
            Block::updateElement($id, $post, $elementFieldsToUpdate, $propInfo);
        } else {

            if (Config::isDemoMode()) {
                if (!empty($_SESSION['RX_LANDING_DEMO_MAX_CARDS']) && $_SESSION['RX_LANDING_DEMO_MAX_CARDS'] >= Config::DEMO_MAX_CARDS) {
                    throw new Exception('Max cards count exceeded for demo mode');
                }
            }

            Block::addElement($blockId, $post, $elementFieldsToUpdate, $propInfo, $block['CODE'], $tabId);

            if (Config::isDemoMode()) {
                if (!isset($_SESSION['RX_LANDING_DEMO_MAX_CARDS'])) {
                    $_SESSION['RX_LANDING_DEMO_MAX_CARDS'] = 0;
                }
                $_SESSION['RX_LANDING_DEMO_MAX_CARDS']++;
            }

        }

        Helpers\File::removeTemp();

        $html = $this->getBlockTemplate($blockId, false, $tabId);

        return [
            'html' => $html,
        ];
    }

    public function editParamsAction($post)
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $siteId = $request->get('SITE_ID');

        $params = is_array($post['params']) && !empty($post['params']) ? $post['params'] : [];
        if (empty($params) || empty($siteId)) {
            throw new Exception('No params provided');
        }

        if (Config::isDemoMode()) {
            Config::enableDemoLanding();
        }

        $mode = Config::isDemoLanding() ? Config::MODE_UPDATE_DEMO : Config::MODE_UPDATE_THEME;
        Config::updateParams($params, $mode, $siteId);
    }

    public function restoreParamsAction()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $siteId = $request->get('SITE_ID');

        if (empty($siteId)) {
            throw new Exception('Site error');
        }

        if (Config::isDemoMode()) {
            Config::enableDemoLanding();
        }

        $mode = Config::isDemoLanding() ? Config::MODE_UPDATE_DEMO : Config::MODE_UPDATE_THEME;
        Config::restoreParams($mode, $siteId);
    }

    public function addSectionAction($post)
    {
        $landingId = intval($post['landingId']);
        $mode = trim($post['mode']);
        $type = intval($post['type']);
        $name = trim($post['name']);
        $code = trim($post['code'], "\t\n\r\0\x0B\\/ ");
        $code = str_ireplace(['/', '\\'], '', $code);
        $code = urlencode($code);

        if ($mode !== Landing::MODE_SECTIONS && $mode !== Landing::MODE_SECTION) {
            throw new Exception('Cannot add section here');
        }

        if ($landingId <= 0) {
            throw new Exception('Landing id is invalid');
        }

        if (!$name) {
            throw new Exception('Name is empty');
        }

        if ($mode == Landing::MODE_SECTIONS) {
            $iblockId = $landingId;
            $parentId = 0;
        } else {
            $iblockId = Helpers\Iblock::getIblockIdBySectionId($landingId);
            $parentId = $landingId;
        }

        if (!($sectionId = Helpers\Iblock::addSection([
            'NAME' => $name,
            'CODE' => $code,
            'IBLOCK_ID' => $iblockId,
            'IBLOCK_SECTION_ID' => $parentId,
        ]))) {
            throw new Exception('Adding error');
        }

        // add first auto block to this section
        if ($type == SectionTable::TYPE_CATALOG) {
            Section\Type\Catalog::addDefaultSectionBlocks($sectionId);
        }
        elseif ($type == SectionTable::TYPE_NEWS) {
            Section\Type\News::addDefaultSectionBlocks($sectionId);
        }
        else {
            $block = Block::add($sectionId, '20_2', [], Landing::MODE_SECTION);
            if (!empty($block['ID'])) {
                Block::updateTitle($block['ID'], [
                    'HIDE_TITLE' => true,
                ]);
            }
        }

        return true;
    }

    public function removeSectionAction($post)
    {
        $id = intval($post['id']);
        $landingId = intval($post['landingId']);

        if ($id <= 0 || $landingId <= 0) {
            throw new Exception('error');
        }

        Helpers\Iblock::removeSection($id, $landingId);
    }

    public function addElementAction($post)
    {
        $landingId = intval($post['landingId']);
        $mode = trim($post['mode']);
        $type = intval($post['type']);
        $name = trim($post['name']);
        $code = trim($post['code'], "\t\n\r\0\x0B\\/ ");
        $code = str_ireplace(['/', '\\'], '', $code);
        $code = urlencode($code);

        if ($mode !== Landing::MODE_SECTION && $mode !== Landing::MODE_ROOT_SECTION) {
            throw new Exception('Cannot add section here');
        }

        if ($landingId <= 0) {
            throw new Exception('Landing id is invalid');
        }

        if (!$name) {
            throw new Exception('Name is empty');
        }

        if ($mode == Landing::MODE_SECTION) {
            $iblockId = Helpers\Iblock::getIblockIdBySectionId($landingId);
        } else {
            $iblockId = $landingId;
        }

        if (!$iblockId || !($elementId = Helpers\Iblock::addElement([
                'NAME' => $name,
                'CODE' => $code,
                'IBLOCK_ID' => $iblockId,
                'IBLOCK_SECTION_ID' => ($mode == Landing::MODE_SECTION) ? $landingId : 0,
            ]))) {
            throw new Exception('Adding error');
        }

        if ($type == SectionTable::TYPE_CATALOG) {
            Section\Type\Catalog::addDefaultElementBlocks($elementId);
        }
        elseif ($type == SectionTable::TYPE_NEWS) {
            Section\Type\News::addDefaultElementBlocks($elementId);
        }

        return true;
    }

    public function removeElementAction($post)
    {
        $id = intval($post['id']);

        if ($id <= 0) {
            throw new Exception('error');
        }

        Helpers\Iblock::removeElement($id);
    }

    public function downloadPresetAction($post)
    {
        Event::removeOtherEvents();

        $landingId = intval($post['landingId']);
        $mode = trim($post['mode']);

        if ($landingId <= 0 || !$mode) {
            throw new Exception('Incorrect landing data');
        }

        if (Config::isDemoMode()) {
            Landing::checkDemoAccess($landingId);
        }

        $landing = Landing::getById($landingId, $mode);

        return [
            'name' => $landing['NAME'] . '.rxlanding',
            'content' => Preset::generateFromLanding($landingId, $mode),
        ];
    }

    public function uploadPresetAction($post)
    {
        $data = trim($post['data']);

        if (!Preset::add($data)) {
            throw new Exception(Loc::getMessage('RX_LANDING_PANEL_UPLOAD_PRESET_ERROR'));
        }

        return true;
    }

    public function applyPresetAction($post)
    {
        $code = trim($post['code']);
        $landingId = intval($post['landingId']);
        $mode = trim($post['mode']);

        if ($landingId <= 0 || !$mode || !$code) {
            throw new Exception('Incorrect data');
        }

        if (Config::isDemoMode()) {
            Landing::checkDemoAccess($landingId);
        }

        Block::deleteByLanding($landingId, $mode);
        Preset::apply($code, $landingId, $mode);

        return true;
    }

    public function deleteCustomPresetAction($post)
    {
        $code = trim($post['code']);
        if (!$code) {
            throw new Exception('Incorrect data');
        }

        if (!Preset::deleteCustom($code)) {
            throw new Exception('Failed to delete');
        }

        return true;
    }

    public function searchElementsAction($post)
    {
        $query = trim($post['query']);
        $iblockId = intval($post['additional']['iblock']);
        $sectionId = intval($post['additional']['section']);
        if (mb_strlen($query) < 2 || $iblockId <= 0) {
            throw new Exception();
        }

        $result = [];
        $items = Helpers\Iblock::searchElements($query, $iblockId, $sectionId);

        foreach ($items as $item) {
            $result[] = [
                'id' => $item['ID'],
                'label' => '[' . $item['ID'] . '] ' . Helpers\Helper::cutName($item['NAME']),
                'value' => '[' . $item['ID'] . '] ' . $item['NAME'],
            ];
        }

        return $result;
    }

    public function addVariantAction($post)
    {
        $groupId = intval($post['groupId']);
        $blockId = intval($post['blockId']);

        if (!$groupId && !$blockId) {
            throw new Exception('No group or block');
        }

        if (!$groupId) { // try to get group from block
            $groupId = Block::getGroupId($blockId);
        }
        if (!$groupId) { // create new one and put block into it
            $groupId = BlockGroup::createFromBlock($blockId);
        }
        if (!$groupId) {
            throw new Exception('No group');
        }

        if (!$blockId) {
            $blockId = BlockGroup::getFirstBlockId($groupId);
        }
        if (!$blockId) {
            throw new Exception('No block');
        }

        $newBlockId = Block::copy($blockId);
        if ($newBlockId) {
            BlockGroup::moveBlockToGroup($groupId, $newBlockId);
            Block::removeRegions($newBlockId);
        }

        return [
            'groupId' => $groupId,
        ];
    }

    public function editVariantAction($post)
    {
        $id = intval($post['id']);
        if ($id <= 0) {
            throw new Exception();
        }

        $includeRegions = is_array($post['REGION_INCLUDE']) ? array_filter($post['REGION_INCLUDE']) : [];
        $excludeRegions = is_array($post['REGION_EXCLUDE']) ? array_filter($post['REGION_EXCLUDE']) : [];
        $includeBranches = is_array($post['BRANCH_INCLUDE']) ? array_filter($post['BRANCH_INCLUDE']) : [];
        $excludeBranches = is_array($post['BRANCH_EXCLUDE']) ? array_filter($post['BRANCH_EXCLUDE']) : [];

        Block::updateRegions($id, $includeRegions, $excludeRegions, $includeBranches, $excludeBranches);
        Landing::clearCacheByBlockId($id);

        return true;
    }

    public function searchRegionsAction($post)
    {
        $query = trim($post['query']);
        if (mb_strlen($query) < 2) {
            return [];
        }

        return Region::search($query);
    }

    public function searchBranchesAction($post)
    {
        $query = trim($post['query']);
        if (mb_strlen($query) < 2) {
            return [];
        }

        return Region::searchBranches($query);
    }

    public function editMenuAction($post)
    {
        $type = trim($post['type']);
        $path = rtrim($post['path']) . '/';
        if (!$type) {
            throw new Exception('No type provided');
        }

        Loader::includeModule('fileman');

        $request = Application::getInstance()->getContext()->getRequest();
        $siteId = $request->get('SITE_ID');

        $aMenuLinks = [];
        foreach ($post['ITEM_NAME'] as $i => $itemName) {

            $links = !empty($post['ITEM_LINKS'][$i]) ? Json::decode($post['ITEM_LINKS'][$i]) : [];
            $params = !empty($post['ITEM_PARAMS'][$i]) ? Json::decode($post['ITEM_PARAMS'][$i]) : [];

            if (!empty($post['ITEM_HIDDEN'][$i])) {
                $params['HIDDEN'] = $post['ITEM_HIDDEN'][$i];
            }
            if (!empty($post['ITEM_WIDE'][$i])) {
                $params['FULL_DROPDOWN'] = $post['ITEM_WIDE'][$i];
            }

            $aMenuLinks[] = [
                $itemName,
                $post['ITEM_LINK'][$i],
                $links,
                $params,
                $post['ITEM_RULE'][$i] ?? '',
            ];
        }

        \CFileMan::SaveMenu([$siteId, $path . '.' . $type . '.menu.php'], $aMenuLinks);

        return true;
    }

    public function getTotalPriceAction($post)
    {
        $price = floatval($post['price']);
        $discount = trim($post['discount']);

        $totalPrice = Helpers\Helper::getTotalPrice($price, $discount);

        return [
            'total' => $totalPrice,
            'total_formatted' => $totalPrice ? Helpers\Helper::money($totalPrice, '') : 0,
        ];
    }

    public function getIblockSectionsForSelectAction($post)
    {
        Event::removeOtherEvents();

        $iblockId = intval($post['iblockId']);
        $name = trim($post['name']);
        if ($iblockId <= 0) {
            throw new Exception('No iblockId');
        }

        $arResult = [];
        $arResult['NAME'] = $name;
        $arResult['ITEMS'] = Helpers\Iblock::getIblockSectionsForSelect($iblockId);

        ob_start();
        include 'include/sections_select.php';
        $html = ob_get_clean();

        return [
            'html' => $html,
        ];
    }

    public function editTabsAction($post)
    {
        $blockId = intval($post['blockId']);
        $tabId = intval($post['tabId']);
        if ($blockId <= 0) {
            throw new Exception();
        }

        if (Config::isDemoMode()) {
            $landingId = Landing::getByBlockId($blockId);
            Landing::checkDemoAccess($landingId);
        }

        $tabs = BlockTabs::prepareForUpdate($post);
        BlockTabs::update($blockId, $tabs, $tabId);
        Block::cleanCache($blockId);

        $html = $this->getBlockTemplate($blockId, false, $tabId);
        return [
            'html' => $html,
        ];
    }

    public function editSettingsAction($post)
    {
        $blockId = intval($post['id']);
        $settings = $post['settings'];
        if ($blockId <= 0) {
            throw new Exception();
        }

        if (Config::isDemoMode()) {
            $landingId = Landing::getByBlockId($blockId);
            Landing::checkDemoAccess($landingId);
        }

        \Ranx\Landing\Panel\Settings::update($blockId, $settings);
        Block::cleanCache($blockId);

        $html = $this->getBlockTemplate($blockId, false);
        return [
            'html' => $html,
        ];
    }
}
