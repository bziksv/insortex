<?php

namespace Ranx\Landing;

use Bitrix\Main\Loader;
use Ranx\Landing\Fields;
use Ranx\Landing\Landing;
use Bitrix\Main\GroupTable;
use Bitrix\Main\Application;
use Ranx\Landing\Proxy\Option;
use Bitrix\Main\ModuleManager;
use Ranx\Landing\SectionTable;
use Ranx\Landing\Helpers\Domain;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Helpers\Helper;
use Ranx\Landing\Helpers\File;

/**
 * Class for working with settings
 */
class Config
{
    const MODULE_ID = 'ranx.landing';
    const FILES_PATH = 'ranx.landing'; // in upload, for saving module settings pictures
    const DOCS_URL = 'https://help.landing-demo.ru';
    const EDITOR_GROUP = 'rx_landing_editor';

    const MODE_UPDATE_ALL = 0;
    const MODE_UPDATE_THEME = 1;
    const MODE_UPDATE_DEMO = 2;

    const DEMO_MAX_BLOCKS = 100;
    const DEMO_MAX_CARDS = 100;

    static $params = [];
    static $blocks = [];
    static $presets = [];

    public static function getVersion()
    {
        return ModuleManager::getVersion(self::MODULE_ID);
    }

    public static function get($code, $defaultValue = null, $siteId = SITE_ID, $moduleId = self::MODULE_ID)
    {
        $paramInfo = self::getParamInfo($code);
        if ($defaultValue === null) {
            $defaultValue = $paramInfo['DEFAULT'];
        }

        // for disabled return default value
        if (!empty($paramInfo['DISABLED'])) {
            return $paramInfo['DEFAULT'] ?? '';
        }

        if (!$GLOBALS['USER']->CanDoOperation('rx_landing_settings_edit') && isset($_SESSION['RX_SETTINGS'][$siteId][$code]) && $paramInfo['DEMO'] === 'Y') {
            $optionVal = $_SESSION['RX_SETTINGS'][$siteId][$code];
        } else {
            $optionVal = Option::get($moduleId, $code, $defaultValue, $siteId);
        }

        if (in_array($paramInfo['TYPE'], ['multiselect', 'aarray', 'multitext'])) {
            return $optionVal ? unserialize($optionVal) : unserialize($defaultValue);
        }

        if ($paramInfo['TYPE'] == 'checkbox') {
            return !empty($optionVal) ? true : false;
        }

        return $optionVal;
    }

    public static function defineSettingId()
    {
        $context = Application::getInstance()->getContext();
        $server = $context->getServer();
        $request = $context->getRequest();

        if ($request->isAjaxRequest()) {
            $settingId = $request->getPost('settingId');
            if (!empty($settingId)) {
                Option::setSettingId($settingId);
            }
        }
        else {
            $domain = Domain::format($server->getHttpHost());
            $path = $request->getRequestedPageDirectory();
            if (substr($path, -1) !== '/') {
                $path .= '/';
            }

            $section = SectionTable::getList([
                'select' => ['ID', 'SITE_ID', 'DOMAIN', 'OWN_SETTINGS'],
                'filter' => [
                    'LOGIC' => 'OR',
                    ['DOMAIN' => $domain],
                    ['PATH' => $path, 'SITE_ID' => SITE_ID],
                ],
                'order' => ['DOMAIN' => 'DESC'],
            ])->fetch();

            if (!empty($section) && $section['OWN_SETTINGS'] === 'Y') {
                Option::setSettingId($section['ID']);
            }
        }
    }

    public static function getSettingId()
    {
        return Option::getSettingId();
    }

    public static function getParamInfo($paramName, $groupName = '')
    {
        if ($groupName) {
            return self::$params[$groupName]['OPTIONS'][$paramName] ?? [];
        }

        // otherwise, search in every group
        foreach (self::$params as $group) {
            if (!empty($group['OPTIONS'][$paramName])) {
                return $group['OPTIONS'][$paramName] ?? [];
            }
        }

        return [];
    }

    public static function getParamDefault($paramName, $groupName = '')
    {
        $paramInfo = self::getParamInfo($paramName, $groupName);
        return $paramInfo['DEFAULT'] ?? '';
    }

    public static function getParamList($paramName, $groupName = false)
    {
        $paramInfo = self::getParamInfo($paramName, $groupName);
        return $paramInfo['LIST'] ?? false;
    }

    public static function getParamListVal($paramName, $listName, $groupName = false)
    {
        $paramInfo = self::getParamInfo($paramName, $groupName);
        return $paramInfo['LIST'][$listName] ?? false;
    }

    public static function getParamPattern($paramName)
    {
        $paramInfo = self::getParamInfo($paramName);
        return $paramInfo['PATTERN'] ?? false;
    }

    public static function getGroupOptions($groupName)
    {
        return self::$params[$groupName]['OPTIONS'];
    }

    public static function getAarrayFields($paramName, $groupName = false)
    {
        $paramInfo = self::getParamInfo($paramName, $groupName);
        return $paramInfo['AARRAY'] ?? [];
    }

    public static function set($code, $value, $siteId = SITE_ID, $moduleId = self::MODULE_ID)
    {
        $paramInfo = self::getParamInfo($code);
        if (!empty($paramInfo['DISABLED'])) {
            return false;
        }
        if (self::isDemoLanding() && $paramInfo['DEMO'] != 'Y') {
            return false;
        }

        if (in_array($paramInfo['TYPE'], ['multiselect', 'aarray', 'multitext'])) {
            $value = is_array($value) ? array_values($value) : [];

            if (!$GLOBALS['USER']->CanDoOperation('rx_landing_settings_edit')) {
                $_SESSION['RX_SETTINGS'][$siteId][$code] = serialize($value);
            } else {
                Option::set($moduleId, $code, serialize($value), $siteId);
            }

            return true;
        }

        if (!$GLOBALS['USER']->CanDoOperation('rx_landing_settings_edit')) {
            $_SESSION['RX_SETTINGS'][$siteId][$code] = $value;
        } else {
            Option::set($moduleId, $code, $value, $siteId);
        }

        return true;
    }

    public static function enableParam($code)
    {
        foreach (self::$params as &$group) {
            if (!empty($group['OPTIONS'][$code])) {
                $group['OPTIONS'][$code]['DISABLED'] = false;
                break;
            }
        }
    }

    public static function updateAarray($code, $value, $index, $field, $siteId = SITE_ID, $moduleId = self::MODULE_ID)
    {
        $paramInfo = self::getParamInfo($code);
        if (!empty($paramInfo['DISABLED'])) {
            return false;
        }

        $optionVal = self::get($code);

        if (isset($optionVal[$index])) {
            $optionVal[$index][$field] = $value;

            // check if all values are empty then delete row
            if (!$value) {
                $allRowsAreEmpty = true;
                foreach ($optionVal[$index] as $optionValOne) {
                    if (!empty($optionValOne)) {
                        $allRowsAreEmpty = false;
                    }
                }
                if ($allRowsAreEmpty) {
                    unset($optionVal[$index]);
                }
            }
        } else {
            $optionVal[] = [
                $field => $value,
            ];
        }

        self::set($code, $optionVal, $siteId, $moduleId);
    }

    public static function delete($code, $siteId = SITE_ID, $moduleId = self::MODULE_ID)
    {
        if (empty($code)) return;

        $paramInfo = self::getParamInfo($code);

        if ($paramInfo['TYPE'] == 'file') {
            self::deleteParamFile($code, $siteId);
        }

        Option::delete($moduleId, ['name' => $code, 'site_id' => $siteId]);
    }

    public static function deleteAllOptionsForSection($arSection)
    {
        if (empty($arSection['ID']) || empty($arSection['SITE_ID'])) {
            return false;
        }

        $codes = [];
        foreach (self::$params as $group) {
            foreach ($group['OPTIONS'] as $code => $option) {
                $codes[] = $code;
            }
        }

        $oldSettingId = Option::getSettingId();
        Option::setSettingId($arSection['ID']);
        foreach ($codes as $code) {
            self::delete($code, $arSection['SITE_ID']);
        }

        Option::setSettingId($oldSettingId);
        return true;
    }

    public static function getEditorGroupId()
    {
        static $id;
        if (is_null($id)) {
            $group = GroupTable::getList([
                'filter' => [
                    'STRING_ID' => self::EDITOR_GROUP,
                ],
            ])->fetch();
            $id = $group['ID'] ?? 0;
        }
        return $id;
    }

    /**
     * @return boolean
     */
    public static function isEditMode()
    {
        return (isset($_COOKIE['RX_LANDING_EDIT']) && $_COOKIE['RX_LANDING_EDIT'] === 'Y')
            && self::isPanelEnabled() && (self::isDemoLanding() || $GLOBALS['USER']->CanDoOperation('rx_landing_block_edit'));
    }

    public static function enterEditMode()
    {
        if (empty($_COOKIE['RX_LANDING_EDIT'])) {
            setcookie('RX_LANDING_EDIT', 'Y', 0, '/');
            $_COOKIE['RX_LANDING_EDIT'] = 'Y';
        }
    }

    public static function isPanelEnabled()
    {
        return $GLOBALS['APPLICATION']->GetGroupRight(self::MODULE_ID) >= 'W' || self::isDemoLanding();
    }

    public static function getTemplateName()
    {
        return 'ranx-landing';
    }

    public static function getTemplatePath()
    {
        $templateName = self::getTemplateName();
        return str_replace(Loader::getDocumentRoot(), '', Loader::getLocal('templates/' . $templateName));
    }

    public static function getTemplateDir()
    {
        $templateName = self::getTemplateName();
        return Loader::getLocal('templates/' . $templateName);
    }

    public static function isHeaderWide($type)
    {
        $headerType = self::getParamListVal('HEADER_TYPE', $type, 'HEADER');
        return !empty($headerType['IS_WIDE']);
    }

    public static function getLogoPath()
    {
        $fileId = self::get('LOGO');
        if (intval($fileId) <= 0) {
            return '';
        }

        return \CFile::GetPath($fileId) ?? '';
    }

    public static function getLightLogoPath()
    {
        $fileId = self::get('LOGO_LIGHT');
        if (intval($fileId) <= 0) {
            return '';
        }

        return \CFile::GetPath($fileId) ?? '';
    }

    public static function getFaviconPath()
    {
        $fileId = self::get('FAVICON');
        if (intval($fileId) <= 0) {
            return '';
        }

        $resizedFile = \CFile::ResizeImageGet($fileId, ['width' => 120, 'height' => 120]);

        return $resizedFile['src'] ?? '';
    }

    public static function getSlogan()
    {
        return self::get('SLOGAN');
    }

    public static function getRootMenuType()
    {
        return self::get('ROOT_MENU_TYPE');
    }

    public static function setRootMenuType($name, $settingId = false, $siteId = SITE_ID)
    {
        if ($settingId) {
            $oldSetting = Option::getSettingId();
            Option::setSettingId($settingId);
        }

        self::set('ROOT_MENU_TYPE', $name, $siteId);

        if ($oldSetting) {
            Option::setSettingId($oldSetting);
        }
    }

    public static function getChildMenuType()
    {
        return self::get('CHILD_MENU_TYPE');
    }

    public static function getPhones()
    {
        if (self::isRegionEnabled()) {
            $phones = [];

            if (self::useRegionBranches()) {
                $arBranch = Region::getCurrentBranch();
                if (!empty($arBranch) && !empty($arBranch['PHONES'])) {
                    $phoneNumbers = array_filter($arBranch['PHONES']);

                    foreach ($phoneNumbers as $i => $number) {
                        $phones[] = [
                            'NUMBER' => $number,
                            'DESC' => $arBranch['PHONES_DESC'][$i] ?? '',
                        ];
                    }
                }
            }
            if (empty($phones)) {
                $arRegion = Region::getCurrent();
                if (!empty($arRegion) && !empty(array_filter($arRegion['PHONES']))) {
                    $phoneNumbers = array_filter($arRegion['PHONES']);

                    foreach ($phoneNumbers as $i => $number) {
                        $phones[] = [
                            'NUMBER' => $number,
                            'DESC' => $arRegion['PROPERTY_PHONES_DESCRIPTION'][$i] ?? '',
                        ];
                    }
                }
            }

            if (!empty($phones)) {
                return $phones;
            }
        }
        return self::get('PHONES');
    }

    public static function getFirstPhone()
    {
        $phones = self::getPhones();
        return !empty($phones) ? reset($phones) : '';
    }

    public static function getCookiesText()
    {
        return self::get('COOKIES_TEXT');
    }

    public static function isOrderEnabled($siteId = SITE_ID)
    {
        return self::get('ORDER_ENABLED', null, $siteId);
    }

    public static function getBlockTitlePosition($code = false)
    {
        if ($code && ($blockInfo = self::getBlockInfo($code))) {
            return !empty($blockInfo['TITLE_POSITION']) && in_array($blockInfo['TITLE_POSITION'], ['left', 'center']) ? $blockInfo['TITLE_POSITION'] : self::get('BLOCK_TITLE_ALIGN');
        }
        return self::get('BLOCK_TITLE_ALIGN');
    }

    public static function getDefaultHeaderType()
    {
        return self::get('HEADER_TYPE');
    }

    // TODO: get from settings
    public static function getDefaultPageTitleType()
    {
        return 1;
    }

    public static function getColorTheme()
    {
        $settingId = Option::getSettingId();
        if (!empty($settingId)) {
            $settingId = '_'.$settingId;
        }

        if (self::get('COLOR_THEME_CUSTOM')) {
            if (self::isDemoMode() && !$GLOBALS['USER']->CanDoOperation('rx_landing_settings_edit')) {
                return 'custom_' . SITE_ID.$settingId . '_' . bitrix_sessid();
            }
            return 'custom_' . SITE_ID.$settingId ;
        }
        return self::get('COLOR_THEME');
    }

    public static function getDefaultColorTheme()
    {
        return 'red';
    }

    public static function getThemeColor()
    {
        if (Config::isDevMode() && defined('RX_LANDING_DEV_COLOR') && RX_LANDING_DEV_COLOR) {
            return '#' . RX_LANDING_DEV_COLOR;
        }

        $custom = self::get('COLOR_THEME_CUSTOM');
        if ($custom) {
            return $custom;
        }
        $colorCode = self::get('COLOR_THEME');
        $colorListItem = self::getParamListVal('COLOR_THEME', $colorCode);

        return $colorListItem['COLOR'];
    }

    public static function getContentWidth()
    {
        return self::get('CONTENT_WIDTH');
    }

    public static function getDefaultHeaderFixedType()
    {
        return self::get('HEADERFIXED_TYPE');
    }

    public static function getDefaultHeaderMobileType()
    {
        return self::get('HEADERMOBILE_TYPE');
    }

    public static function isHeaderMobileSticky()
    {
        return self::get('HEADERMOBILE_FIXED');
    }

    public static function getDefaultMegaMenuType()
    {
        return self::get('MEGAMENU_TYPE');
    }

    public static function getDefaultMobileMenuType()
    {
        return self::get('MOBILEMENU_TYPE');
    }

    public static function isHeaderTransparent()
    {
        return self::get('HEADER_TRANSPARENT');
    }

    public static function isHeaderfixedEnabled()
    {
        return self::get('SHOW_HEADERFIXED');
    }

    public static function getFontsForSelect()
    {
        return [
            '1' => [
                'TITLE' => 'Open Sans',
                'ICON' => 'font_family/1.svg',
            ],
            '2' => [
                'TITLE' => 'IBM Plex Sans',
                'ICON' => 'font_family/2.svg',
            ],
            '3' => [
                'TITLE' => 'Montserrat',
                'ICON' => 'font_family/3.svg',
            ],
            '4' => [
                'TITLE' => 'Roboto',
                'ICON' => 'font_family/4.svg',
            ],
            '5' => [
                'TITLE' => 'PT Sans',
                'ICON' => 'font_family/5.svg',
            ],
            'custom' => [
                'TITLE' => Loc::getMessage('RX_LANDING_CONFIG_FONT_FAMILY_CUSTOM'),
                'ICON' => 'font_family/custom.svg',
            ],
        ];
    }

    public static function getFontWeightsForSelect()
    {
        return [
            'bold' => [
                'TITLE' => 'Bold',
                'ICON' => 'font_weight/bold.svg',
            ],
            'medium' => [
                'TITLE' => 'Medium',
                'ICON' => 'font_weight/medium.svg',
            ],
            'regular' => [
                'TITLE' => 'Regular',
                'ICON' => 'font_weight/regular.svg',
            ],
        ];
    }

    public static function getFontFamily()
    {
        return self::get('FONT_FAMILY');
    }

    public static function getFontFamilyName($val = '')
    {
        if (!$val) {
            $val = self::getFontFamily();
        }
        $fontFamilies = self::getFontsForSelect();
        return $fontFamilies[$val]['TITLE'];
    }

    public static function getFontFamilyCustom()
    {
        return self::get('FONT_FAMILY_CUSTOM');
    }

    public static function getFontFamilyCustomName($str = '')
    {
        if (!$str) {
            $str = self::getFontFamilyCustom();
        }

        if (preg_match('/\?family=([A-Za-z+]+)/ui', $str, $matches)) {
            if (!empty($matches[1])) {
                return str_replace('+', ' ', $matches[1]);
            }
        }
        return '';
    }

    public static function getTitleOptionCats()
    {
        return [
            'TITLE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_CONFIG_TITLE_CAT_TITLE'),
                'FONT_WEIGHT_DEFAULT' => 'bold',
                'FONT_SIZE_DEFAULT' => '38px',
                'LINE_HEIGHT_DEFAULT' => '45px',
            ],
            'CATTITLE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_CONFIG_TITLE_CAT_CATTITLE'),
                'FONT_SIZE_DEFAULT' => '11px',
                'LINE_HEIGHT_DEFAULT' => '20px',
            ],
            'SUBTITLE' => [
                'TITLE' => Loc::getMessage('RX_LANDING_CONFIG_TITLE_CAT_SUBTITLE'),
                'FONT_SIZE_DEFAULT' => '18px',
                'LINE_HEIGHT_DEFAULT' => '27px',
            ],
            'DESC' => [
                'TITLE' => Loc::getMessage('RX_LANDING_CONFIG_TITLE_CAT_DESC'),
                'FONT_SIZE_DEFAULT' => '15px',
                'LINE_HEIGHT_DEFAULT' => '24px',
            ],
        ];
    }

    public static function getTitleOptions()
    {
        $result = [];
        $cats = self::getTitleOptionCats();

        foreach ($cats as $prefix => $cat) {
            $result[$prefix . '_FONT_FAMILY_DEFAULT'] = [
                'TITLE' => Loc::getMessage('RX_LANDING_CONFIG_TITLE_FONT_FAMILY_DEFAULT'),
                'TYPE' => 'checkbox',
                'DEMO' => 'Y',
                'DEFAULT' => true,
            ];
            $result[$prefix . '_FONT_FAMILY'] = [
                'TITLE' => Loc::getMessage('RX_LANDING_CONFIG_TITLE_FONT_FAMILY'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => '2',
                'LIST' => self::getFontsForSelect(),
                'SHOW_IF' => [$prefix . '_FONT_FAMILY_DEFAULT' => false],
            ];
            $result[$prefix . '_FONT_FAMILY_CUSTOM'] = [
                'TITLE' => Loc::getMessage('RX_LANDING_CONFIG_TITLE_FONT_FAMILY_CUSTOM'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => '',
                'PLACEHOLDER' => htmlspecialcharsbx('<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,500;1,500&display=swap" rel="stylesheet">'),
                'SHOW_IF' => [$prefix . '_FONT_FAMILY' => 'custom', $prefix . '_FONT_FAMILY_DEFAULT' => false],
                'DOC' => 'https://help.landing-demo.ru/articles/209-210-328--kak-izmenit-shrift-ili-dobavit-svoj-shrift-s-google-fonts/',
            ];
            $result[$prefix . '_FONT_WEIGHT'] = [
                'TITLE' => Loc::getMessage('RX_LANDING_CONFIG_TITLE_FONT_WEIGHT'),
                'TYPE' => 'select',
                'DEMO' => 'Y',
                'DEFAULT' => $cat['FONT_WEIGHT_DEFAULT'] ?? 'regular',
                'LIST' => self::getFontWeightsForSelect(),
            ];
            $result[$prefix . '_FONT_SIZE'] = [
                'TITLE' => Loc::getMessage('RX_LANDING_CONFIG_TITLE_FONT_SIZE'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => $cat['FONT_SIZE_DEFAULT'] ?? '',
            ];
            $result[$prefix . '_LINE_HEIGHT'] = [
                'TITLE' => Loc::getMessage('RX_LANDING_CONFIG_TITLE_LINE_HEIGHT'),
                'TYPE' => 'string',
                'DEMO' => 'Y',
                'DEFAULT' => $cat['LINE_HEIGHT_DEFAULT'] ?? '',
            ];
        }

        return $result;
    }

    public static function getBlockInfo($code)
    {
        return self::$blocks['LIST'][$code] ?? [];
    }

    public static function getBlockTitle($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $code . ': ' . $blockInfo['NAME'];
    }

    public static function getFilteredBlockGroup($sectionType = false, $mode = false)
    {
        $list = self::$blocks['LIST'];

        // include/exclude in mode
        foreach ($list as $code => $block) {
            $modes = Landing::MODE_ALL;
            if (!empty($block['_EXCLUDE_MODE'])) {
                $modes = array_diff($modes, $block['_EXCLUDE_MODE']);
            }
            if (!empty($block['_INCLUDE_MODE'])) {
                $modes = array_merge($modes, $block['_INCLUDE_MODE']);
            }

            if (!empty($mode) && !in_array($mode, $modes)) {
                unset($list[$code]);
            }
        }

        if (!empty($sectionType)) {
            // section restriction
            foreach ($list as $code => $block) {
                if (empty($block['_SECTION_TYPE'])) {
                    continue;
                }

                if (!in_array($sectionType, $block['_SECTION_TYPE'])) {
                    unset($list[$code]);
                }
            }
        }

        // group blocks
        $groups = Config::$blocks['GROUPS'];
        foreach ($groups as $groupId => &$group) {
            if (!empty($group['_EXCLUDE_MODE']) && in_array($mode, $group['_EXCLUDE_MODE'])) {
                unset($groups[$groupId]);
                continue;
            }

            if (!is_array($group['BLOCKS'])) {
                $group['BLOCKS'] = [];
            }

            foreach ($group['BLOCKS'] as $i => $blockCode) {
                if (!empty($list[$blockCode])) {
                    $group['BLOCKS'][$blockCode] = $list[$blockCode];
                }

                unset($group['BLOCKS'][$i]);
            }

            if (empty($group['BLOCKS'])) {
                unset($groups[$groupId]);
            }
        }

        return $groups;
    }

    public static function getBlockGroupNames($sectionType = false, $mode = Landing::MODE_ELEMENT)
    {
        $res = [];

        $groups = self::getFilteredBlockGroup($sectionType, $mode);
        foreach ($groups as $groupId => $group) {
            $res[$groupId] = $group['TITLE'];
        }

        return $res;
    }

    public static function getBlockElementsFields($code)
    {
        $blockInfo = self::getBlockInfo($code);
        $fields = $blockInfo['ELEMENTS_FIELDS'] ?? [];
        $fieldsToAdd = [];
        foreach ($fields as $i => $field) {
            if ($field == '_BTN') {
                unset($fields[$i]);
                $fieldsToAdd = array_merge($fieldsToAdd, [
                    'PROPERTY_BTN_SHOW',
                    'PROPERTY_BTN_TYPE',
                    'PROPERTY_BTN_SIZE',
                    'PROPERTY_BTN_TEXT',
                    'PROPERTY_BTN_LINK_TYPE',
                    'PROPERTY_BTN_LINK',
                    'PROPERTY_BTN_GOAL',
                    'PROPERTY_BTN_CLASS',
                ]);
            } elseif ($field == '_BTN2') {
                unset($fields[$i]);
                $fieldsToAdd = array_merge($fieldsToAdd, [
                    'PROPERTY_BTN_SHOW_2',
                    'PROPERTY_BTN_TYPE_2',
                    'PROPERTY_BTN_SIZE_2',
                    'PROPERTY_BTN_TEXT_2',
                    'PROPERTY_BTN_LINK_TYPE_2',
                    'PROPERTY_BTN_LINK_2',
                    'PROPERTY_BTN_GOAL_2',
                    'PROPERTY_BTN_CLASS_2',
                ]);
            } elseif ($field == '_LINK') {
                unset($fields[$i]);
                $fieldsToAdd = array_merge($fieldsToAdd, [
                    'PROPERTY_LINK',
                    'PROPERTY_LINK_TYPE',
                ]);
            } elseif ($field == '_SOCIALS') {
                unset($fields[$i]);

                $socials = self::getBlockSocials($code);

                if (!empty($socials)) {
                    $socialProps = [];
                    foreach ($socials as $social) {
                        $socialProps[] = 'PROPERTY_' . $social;
                    }
                    $fieldsToAdd = array_merge($fieldsToAdd, $socialProps);
                }
            } elseif ($field == '_POPUP') {
                unset($fields[$i]);
                $fieldsToAdd[] = 'PROPERTY_POPUP_SHOW';
                $fieldsToAdd = array_merge($fieldsToAdd, $blockInfo['POPUP_ELEMENTS_FIELDS']);
            } elseif ($field == '_PRICE') {
                unset($fields[$i]);

                $fieldsToAdd = array_merge($fieldsToAdd, [
                    'PROPERTY_PRICE',
                    'PROPERTY_DISCOUNT_PRICE',
                ]);
            }
        }

        $fields = array_values($fields);
        $intervalTimePos = array_search(Fields\IntervalTime::getBlockConfigFieldCode(), $fields);
        if ($intervalTimePos !== false) {
            array_splice($fields, $intervalTimePos, 1, Fields\IntervalTime::getFullPropertyCodes());
        }

        $fields = array_merge($fields, $fieldsToAdd);
        return $fields;
    }

    public static function getBlockPopupElementsFields($code) {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['POPUP_ELEMENTS_FIELDS'] ?? [];
    }

    public static function getBlockDeprecatedElementsFields($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['DEPRECATED_ELEMENT_FIELDS'] ?? [];
    }

    public static function getBlockFieldsMess($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['FIELDS_MESS'] ?? [];
    }

    public static function getBlockFieldsOptions($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['FIELDS_OPTIONS'] ?? [];
    }

    public static function getBlockElementsFieldsMess($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['ELEMENTS_FIELDS_MESS'] ?? [];
    }

    public static function getBlockDesignFieldsMess($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['DESIGN_FIELDS_MESS'] ?? [];
    }

    public static function getBlockElementsFieldsOptions($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['ELEMENTS_FIELDS_OPTIONS'] ?? [];
    }

    public static function getBlockConfigSections($code)
    {
        $blockInfo = self::getBlockInfo($code);
        $res = self::$blocks['CONFIG']['_DEFAULT'];

        if (!empty($blockInfo['_INCLUDE'])) {
            $res = array_merge($res, $blockInfo['_INCLUDE']);
        }
        if (!empty($blockInfo['_EXCLUDE'])) {
            $res = array_diff($res, $blockInfo['_EXCLUDE']);
        }

        return $res;
    }

    public static function getBlockDefaultAlign($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['ALIGN_DEFAULT'] ?? 'center';
    }

    public static function getBlockDefaultPictureAlign($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['PICTURE_ALIGN_DEFAULT'] ?? '';
    }

    public static function getBlockAllowedPictureAlignOptions($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['ALLOWED_PICTURE_ALIGN_OPTIONS'];
    }

    public static function isFilterEnabled($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['USE_FILTER'] ?? false;
    }

    public static function getFilterTemplate($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['FILTER_TEMPLATE'] ?? 'rx_compact';
    }

    public static function isBlockFilter($code)
    {
        return \Ranx\Landing\Panel\Content\Filter::isInclude($code);
    }

    public static function getBlockFilterFields($code)
    {
        return \Ranx\Landing\Panel\Content\Filter::getFields($code);
    }

    public static function isSubsectionsIncluded($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['INCLUDE_SUBSECTIONS'] ?? false;
    }

    public static function isCategoriesIncluded($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['INCLUDE_CATEGORIES'] ?? false;
    }

    public static function isProductList($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['PRODUCT_LIST'] ?? false;
    }

    public static function isNewsList($code)
    {
        $blockInfo = self::getBlockInfo($code);
        return $blockInfo['NEWS_LIST'] ?? false;
    }

    public static function isAgreementEnabled()
    {
        return self::get('USE_AGREEMENT');
    }

    public static function getAgreementLink()
    {
        return self::get('AGREEMENT_LINK');
    }
    public static function isAgreementActive()
    {
        return self::get('AGREEMENT_ACTIVE');
    }

    public static function isWebFormsEnabled()
    {
        return self::get('USE_FORM_MODULE');
    }

    public static function getMaxFileSize()
    {
        $val = self::get('MAX_FILE_SIZE');
        if (empty($val) || !is_numeric($val)) {
            $val = 16;
        }
        return intval($val) * 1024 * 1024;
    }

    public static function getModalPosition()
    {
        return self::get('MODAL_POSITION');
    }

    public static function isBtnRounded()
    {
        return self::get('BTN_TYPE') == 'rounded';
    }

    public static function getFooterBg()
    {
        return self::get('FOOTER_BG');
    }

    public static function isRanxCopyEnabled()
    {
        return self::get('RANX_COPY');
    }

    public static function getSocials()
    {
        $result = [];

        foreach (self::$params['SOCIAL']['OPTIONS'] as $socialCode => $social) {
            $val = self::get($socialCode);
            if ($val) {
                if ($socialCode == 'VK' && strpos($val, 'vk.com') === false) {
                    $val = 'https://vk.com/' . $val;
                }
                if ($socialCode == 'OK' && strpos($val, 'ok.ru') === false) {
                    $val = 'https://ok.ru/' . $val;
                }
                if ($socialCode == 'FACEBOOK' && (strpos($val, 'facebook.com') === false && strpos($val, 'fb.com') === false)) {
                    $val = 'https://facebook.com/' . $val;
                }
                if ($socialCode == 'TWITTER' && strpos($val, 'twitter.com') === false) {
                    $val = 'https://twitter.com/' . $val;
                }
                if ($socialCode == 'INSTAGRAM' && strpos($val, 'instagram.com') === false) {
                    $val = 'https://instagram.com/' . $val;
                }
                if ($socialCode == 'ZEN' && strpos($val, 'zen.yandex.ru') === false) {
                    $val = 'https://zen.yandex.ru/' . $val;
                }
                if ($socialCode == 'TIKTOK' && strpos($val, 'tiktok.com') === false) {
                    $val = 'https://tiktok.com/@' . $val;
                }

                // add protocol if no one, but messengers, 'cause they have own
                if (!in_array($socialCode, ['WHATSAPP', 'VIBER', 'TELEGRAM']) && strpos($val, 'http') !== 0) {
                    $val = 'https://' . $val;
                }
                if ($socialCode == 'WHATSAPP' && strpos($val, 'http') === false) {
                    $val = 'https://wa.me/' . Helper::onlyDigits($val);
                }
                if ($socialCode == 'VIBER' && strpos($val, 'http') === false) {
                    $val = 'viber://chat?number=' . Helper::onlyDigits($val);
                }
                if ($socialCode == 'TELEGRAM' && strpos($val, 'http') === false) {
                    $val = 'https://t.me/' . $val;
                }

                $result[$socialCode] = [
                    'LINK' => $val,
                    'TITLE' => $social['TITLE'],
                ];
            }
        }

        return $result;
    }

    public static function getPublicEmail()
    {
        if (self::isRegionEnabled()) {
            if (self::useRegionBranches()) {
                $arBranch = Region::getCurrentBranch();
                if (!empty($arBranch['EMAIL'])) {
                    return $arBranch['EMAIL'];
                }
            }
            $arRegion = Region::getCurrent();
            if (!empty($arRegion) && !empty(array_filter($arRegion['PROPERTY_EMAIL_VALUE']))) {
                $emails = array_filter($arRegion['PROPERTY_EMAIL_VALUE']);
                return reset($emails);
            }
        }
        return self::get('EMAIL_PUBLIC');
    }

    public static function getCity()
    {
        if (self::isRegionEnabled()) {
            $arRegion = Region::getCurrent();
            return $arRegion['NAME'] ?? '';
        }
        return '';
    }

    public static function getAddress()
    {
        if (self::isRegionEnabled()) {
            if (self::useRegionBranches()) {
                $arBranch = Region::getCurrentBranch();
                if (!empty($arBranch['ADDRESS'])) {
                    return $arBranch['ADDRESS'];
                }
            }
            $arRegion = Region::getCurrent();
            if (!empty($arRegion['PROPERTY_ADDRESS_VALUE']['TEXT'])) {
                return $arRegion['PROPERTY_ADDRESS_VALUE']['TEXT'];
            }
        }
        return self::get('ADDRESS');
    }

    public static function getPoliticsLink()
    {
        return self::get('POLITICS_LINK');
    }

    public static function getPayoptions()
    {
        $result = [];
        $paramInfo = self::getParamInfo('PAYOPTIONS');
        $payoptions = self::get('PAYOPTIONS');

        foreach ($payoptions as $payoption) {
            $payoption = strtolower($payoption);
            $result[$payoption] = $paramInfo['LIST'][$payoption]['TITLE'];
        }

        return $result;
    }

    public static function getPhoneMask()
    {
        return self::get('PHONE_MASK');
    }

    public static function isPageTitleEnabled()
    {
        return self::get('SHOW_PAGETITLE');
    }

    public static function getPageTitleAlign()
    {
        return self::get('PAGETITLE_ALIGN');
    }

    public static function getSectionElementsCount()
    {
        $val = intval(self::get('SECTION_ELEMENTS_COUNT'));
        if ($val <= 0) {
            $val = self::getParamDefault('SECTION_ELEMENTS_COUNT');
        }
        return $val;
    }

    public static function getSectionProductsCount()
    {
        $val = intval(self::get('SECTION_PRODUCTS_COUNT'));
        if ($val <= 0) {
            $val = self::getParamDefault('SECTION_PRODUCTS_COUNT');
        }
        return $val;
    }

    public static function getSectionNewsCount()
    {
        $val = intval(self::get('SECTION_NEWS_COUNT'));
        if ($val <= 0) {
            $val = self::getParamDefault('SECTION_NEWS_COUNT');
        }
        return $val;
    }

    public static function isLazyLoadEnabled()
    {
        return !self::isEditMode() && self::get('USE_LAZYLOAD');
    }

    public static function getSchedule()
    {
        return self::get('SCHEDULE');
    }

    public static function getMapControls()
    {
        return self::get('MAP_CONTROLS');
    }

    public static function getInstagramToken()
    {
        return self::get('INSTAGRAM_TOKEN');
    }

    public static function getInstagramPostsCount()
    {
        $default = self::getParamDefault('INSTAGRAM_POSTS_COUNT');
        $var = self::get('INSTAGRAM_POSTS_COUNT');
        return !empty($var) ? $var : $default;
    }

    public static function getInstagramCacheTime()
    {
        $default = self::getParamDefault('INSTAGRAM_CACHE_TIME');
        $var = self::get('INSTAGRAM_CACHE_TIME');
        return !empty($var) ? $var : $default;
    }

    public static function getCurrency()
    {
        return self::get('CURRENCY');
    }

    public static function isSaleMode()
    {
        return ModuleManager::isModuleInstalled('sale');
    }

    public static function getAgreementId()
    {
        return self::get('AGREEMENT_ID');
    }

    public static function getPoliticsId()
    {
        return self::get('POLITICS_ID');
    }

    private static function getSupportedSolutions()
    {
        return [
            'aspro.max' => Loc::getMessage('RX_LANDING_CONFIG_SOLUTION_ASPRO_MAX'),
            'aspro.next' => Loc::getMessage('RX_LANDING_CONFIG_SOLUTION_ASPRO_NEXT'),
            'aspro.priority' => Loc::getMessage('RX_LANDING_CONFIG_SOLUTION_ASPRO_PRIORITY'),
            'aspro.allcorp2' => Loc::getMessage('RX_LANDING_CONFIG_SOLUTION_ASPRO_ALLCORP2'),
        ];
    }

    public static function getSolutionsForSelect()
    {
        $res = [];
        $solutions = self::getSupportedSolutions();

        foreach ($solutions as $solutionKey => $solutionTitle) {
            if (ModuleManager::isModuleInstalled($solutionKey)) {
                $res[$solutionKey] = [
                    'TITLE' => $solutionTitle,
                ];
            }
        }

        return $res;
    }

    public static function getAgreementsForSelect()
    {
        $agreements = [];

        $agreementsList = \Bitrix\Main\UserConsent\Agreement::getActiveList();
        ksort($agreementsList, SORT_NUMERIC);
        foreach ($agreementsList as $agreementId => $agreementName) {
            $agreements[(string)$agreementId] = [
                'TITLE' => $agreementName,
            ];
        }

        return $agreements;
    }

    public static function getMenuTypesForSelect()
    {
        $result = [];

        $defaultTypes = [
            'top' => Loc::getMessage('RX_LANDING_CONFIG_MENUTYPE_TOP'),
            'left' => Loc::getMessage('RX_LANDING_CONFIG_MENUTYPE_LEFT'),
        ];
        $bitrixTypes = GetMenuTypes();
        $types = array_merge($defaultTypes, $bitrixTypes);

        foreach ($types as $type => $typeName) {
            $result[$type] = [
                'TITLE' => $typeName
            ];
        }

        return $result;
    }

    public static function getSolution()
    {
        $res = self::get('SOLUTION');
        return $res !== 'none' ? $res : '';
    }

    public static function isRegionEnabled()
    {
        return self::get('USE_REGION');
    }

    public static function isRegionSubdomains()
    {
        return self::get('REGION_TYPE') === 'SUBDOMAINS';
    }

    public static function getBlockSocials($code = false)
    {
        $socials = self::$blocks['CONFIG']['SOCIALS'];

        if ($code) {
            $blockInfo = self::getBlockInfo($code);
            if (!empty($blockInfo['ELEMENTS_FIELDS']) && !in_array('_SOCIALS', $blockInfo['ELEMENTS_FIELDS'])) {
                return [];
            }
            if (!empty($blockInfo['SOCIALS'])) {
                $socials = array_merge($socials, $blockInfo['SOCIALS']);
            }
        }

        return $socials;
    }

    public static function getRegionsView()
    {
        return self::get('REGIONS_VIEW');
    }

    public static function isRegionByIpEnabled()
    {
        return self::get('USE_REGION_BY_IP');
    }

    public static function getUpdatesBlogUrl()
    {
        return 'https://ranx.ru/blog/landing/';
    }

    public static function getUpdatesRssUrl()
    {
        return 'https://ranx.ru/blog/landing/rss/';
    }

    public static function isDemoMode()
    {
        return !self::isIndexBot() && !self::isPageSpeedTest() && !self::isMobileDevice() && self::get('DEMO_MODE')
            && !$GLOBALS['USER']->CanDoOperation('rx_landing_settings_edit') && !$GLOBALS['USER']->CanDoOperation('rx_landing_block_edit');
    }

    public static function getMaxUploadFileSizeInDemoMode()
    {
        $val = self::get('DM_MAX_FILE_SIZE');
        return intval($val) * 1024 * 1024;
    }

    public static function isDemoLanding()
    {
        return self::isDemoMode() && $GLOBALS['APPLICATION']->GetProperty('RX_LANDING_DEMO_MODE') === 'Y';
    }

    public static function enableDemoLanding()
    {
        $GLOBALS['APPLICATION']->SetPageProperty('RX_LANDING_DEMO_MODE', 'Y');
    }

    public static function isIndexBot(){
        preg_match('/bot|curl|spider|google|yandex|twitter^$/i', $_SERVER['HTTP_USER_AGENT'], $matches);
    
        return (empty($matches)) ? false : true;
    }

    public static function isPageSpeedTest()
    {
        return isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false;
    }

    public static function isMobileDevice()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec'
            .'|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?'
            .'|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap'
            .'|windows (ce|phone)|xda|xiino/i', $userAgent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s'
            .'|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw'
            .'|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa'
            .'|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)'
            .'|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo'
            .'|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)'
            .'|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu'
            .'|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx'
            .'|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do'
            .'|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)'
            .'|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))'
            .'|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380'
            .'|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)'
            .'|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)'
            .'|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)'
            .'|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )'
            .'|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($userAgent, 0, 4));
    }

    public static function getPresetGroups()
    {
        $groups = self::$presets['GROUPS'];
        $list = self::$presets['LIST'];

        foreach ($groups as $groupCode => &$group) {
            
            $group['PRESETS'] = [];

            foreach ($group['LIST'] as $presetCode) {
                if (empty($list[$presetCode])) continue;

                if (empty($list[$presetCode]['PREVIEW'])) {
                    $list[$presetCode]['PREVIEW'] = Preset::DEFAULT_PREVIEW;
                }

                $group['PRESETS'][$presetCode] = $list[$presetCode];
            }

            unset($group['LIST']);
        }

        return $groups;
    }

    public static function updateParams($params, $mode = self::MODE_UPDATE_ALL, $siteId = SITE_ID)
    {
        if (empty($params) || empty($siteId) || (!$GLOBALS['USER']->CanDoOperation('rx_landing_settings_edit') && $mode !== self::MODE_UPDATE_DEMO)
            || !in_array($mode, [self::MODE_UPDATE_ALL, self::MODE_UPDATE_THEME, self::MODE_UPDATE_DEMO])) {
            return false;
        }

        foreach (self::$params as $groupCode => $groupInfo) {
            foreach ($groupInfo['OPTIONS'] as $code => $option) {
                if ($mode === self::MODE_UPDATE_THEME && $option['THEME'] === 'N'
                    || $mode === self::MODE_UPDATE_DEMO && $option['DEMO'] !== 'Y') {
                    continue;
                }

                $paramVal = $params[$code];

                // generate custom color if needed
                if ($code == 'COLOR_THEME_CUSTOM' && !empty($paramVal) && ($paramVal !== self::get($code))) {
                    Helpers\Color::generateThemeCustomCss($paramVal, $siteId);
                }

                switch ($option['TYPE']) {
                    case 'checkbox':
                        $value = !empty($paramVal) ? 1 : '';
                        self::set($code, $value, $siteId);
                        break;
                    case 'file':
                        if (!empty($paramVal['del']) && $paramVal['del'] === 'Y' || $paramVal === 'del') {
                            self::delete($code, $siteId);
                        } elseif (!empty($paramVal['tmp_name'])) {
                            self::updateParamFile($code, $paramVal, $siteId);
                        } elseif (File::isBase64($paramVal)) {
                            self::updateParamFileFromBase64($code, $paramVal, $siteId);
                        }
                        break;
                    case 'multitext':
                    case 'multiselect':
                    case 'aarray':
                        if (!is_array($paramVal)) {
                            $paramVal = [];
                        }

                        if ($option['TYPE'] == 'aarray') { // make sure to exclude empty elements
                            foreach ($paramVal as $j => $paramValOne) {
                                if (empty($paramValOne)) {
                                    unset($paramVal[$j]);
                                    continue;
                                }
                                // check second layer
                                $allKeysEmpty = true;
                                foreach ($paramValOne as $paramValTwo) {
                                    if (!empty($paramValTwo)) {
                                        $allKeysEmpty = false;
                                        break;
                                    }
                                }
                                if ($allKeysEmpty) {
                                    unset($paramVal[$j]);
                                }
                            }
                        }

                        self::set($code, $paramVal, $siteId);

                        break;
                    case 'string':
                    case 'text':
                        if ($paramVal && ($paramPattern = self::getParamPattern($code))) {
                            if (!preg_match($paramPattern, $paramVal)) {
                                break;
                            }
                        }
                    default:
                        self::set($code, $paramVal, $siteId);
                }
            }
        }

        self::cleanDependentCache();
    }

    public static function deleteParamFile($code, $siteId = SITE_ID)
    {
        $fileId = self::get($code, 0, $siteId);
        if ($fileId <= 0) {
            return;
        }

        \CFile::Delete($fileId);
    }

    public static function updateParamFile($code, $fileData, $siteId = SITE_ID)
    {
        $fileData['MODULE_ID'] = self::MODULE_ID;
        $fileData['tmp_name'] = $_SERVER['DOCUMENT_ROOT'] . '/upload/tmp' . $fileData['tmp_name'];

        $fileId = \CFile::SaveFile($fileData, self::FILES_PATH);

        if ($fileId <= 0) {
            throw new \Exception('Save file error!');
        }

        // before this, delete old file
        $oldFileId = self::get($code, 0, $siteId);
        if ($oldFileId > 0) {
            \CFile::Delete($oldFileId);
        }

        self::set($code, $fileId, $siteId);
    }

    public static function updateParamFileFromBase64($code, $fileData, $siteId = SITE_ID)
    {
        $paramInfo = self::getParamInfo($code);
        $allowedExts = $paramInfo['EXTS'] ?? [];
        $allowedType = $paramInfo['MIME_TYPE'] ?? '';

        $arFile = File::fromBase64($fileData, $allowedType, $allowedExts);
        $fileId = \CFile::SaveFile($arFile, self::FILES_PATH);

        if ($fileId > 0) {
            self::set($code, $fileId, $siteId);
        }
    }

    public static function restoreParams($mode, $siteId = SITE_ID)
    {
        if (empty($siteId) || (!$GLOBALS['USER']->CanDoOperation('rx_landing_settings_edit') && $mode !== self::MODE_UPDATE_DEMO)
            || !in_array($mode, [self::MODE_UPDATE_ALL, self::MODE_UPDATE_THEME, self::MODE_UPDATE_DEMO])) {
            return false;
        }

        if ($mode === self::MODE_UPDATE_DEMO) {
            unset($_SESSION['RX_SETTINGS'][$siteId]);
        } else {
            foreach (self::$params as $group) {
                if ($mode === self::MODE_UPDATE_THEME && $group['THEME'] === 'N') continue;

                foreach ($group['OPTIONS'] as $code => $option) {
                    if ($option['THEME'] === 'N') continue;

                    self::delete($code, $siteId);
                }
            }

            // for custom settings
            $settingId = Option::getSettingId();
            if (!empty($settingId)) {
                self::setRootMenuType('top_'.$settingId);
            }

            self::cleanDependentCache();
        }
    }

    protected static function cleanDependentCache()
    {
        \Bitrix\Main\Composite\Page::getInstance()->deleteAll();
        $GLOBALS['CACHE_MANAGER']->ClearByTag('rx_settings_dependent_blocks');
    }

    public static function useFontAwesome()
    {
        return self::get('USE_FONTAWESOME');
    }

    public static function useOnlyRegionSearch()
    {
        return self::get('USE_ONLY_REGION_SEARCH');
    }

    public static function isDevMode()
    {
        return defined('RX_DEV_MODE') && RX_DEV_MODE;
    }

    public static function useRegionBranches()
    {
        return self::get('USE_REGION_BRANCHES');
    }

    public static function useRegionBranchesOnMap()
    {
        return self::get('USE_REGION_BRANCHES_ON_MAP');
    }

    public static function useRegionsOnMap()
    {
        return self::get('USE_REGIONS_ON_MAP');
    }

    public static function useRegionFilterInEditMode()
    {
        return self::get('USE_REGION_FILTER_IN_EDIT_MODE');
    }

    public static function isAnchorsEnabled()
    {
        return self::get('SHOW_ANCHORS');
    }

    public static function getPartnerModulesForSelect()
    {
        $result = [];
        $modules = ModuleManager::getInstalledModules();

        if (!empty($modules)) {
            foreach ($modules as $moduleId => $module) {
                if (strpos($moduleId, '.') !== false && strpos($moduleId, 'bitrix.') === false
                    && $moduleId !== self::MODULE_ID) {
                    $result[$moduleId] = [
                        'TITLE' => $moduleId,
                    ];
                }
            }
        }

        return $result;
    }

    public static function getIncludeModules()
    {
        return self::get('INCLUDE_MODULES');
    }

    public static function isSupportTabs($code)
    {
        return in_array('CONTENT_TABS', self::getBlockConfigSections($code));
    }

    public static function enableParallaxVendor()
    {
        if (!defined('RX_PARALLAX_VENDOR')) {
            define('RX_PARALLAX_VENDOR', true);
        }
    }

    public static function isEnabledParallaxVendor()
    {
        return Config::isEditMode() || defined('RX_PARALLAX_VENDOR');
    }

    public static function includeMasonryVendor()
    {
        if (!defined('RX_MASONRY_VENDOR')) {
            define('RX_MASONRY_VENDOR', true);
        }
    }

    public static function isEnabledMasonryVendor()
    {
        return Config::isEditMode() || defined('RX_MASONRY_VENDOR');
    }

    public static function useVisualEditor()
    {
        return self::get('USE_VISUAL_EDITOR') && !self::isDemoMode();
    }

    public static function getGoogleTagManager()
    {
        return self::get('GTAGMANAGER');
    }

    public static function getYametrikaCounter()
    {
        return self::get('YAMETRIKA_COUNTER');
    }

    public static function useYametrikaGoals()
    {
        return self::get('YAMETRIKA_USE_GOALS');
    }

    public static function useYametrikaDebug()
    {
        return self::get('YAMETRIKA_USE_DEBUG');
    }

    public static function useGanalyticsEvents()
    {
        return self::get('GANALYTICS_USE_EVENTS');
    }

    public static function getGanalyticsResource()
    {
        return self::get('GANALYTICS_RESOURCE');
    }

    public static function useCssClasses()
    {
        return self::get('USE_CSS_CLASSES');
    }

    public static function getB24Forms()
    {
        $result = [];

        $forms = self::get('B24_FORMS');
        foreach ($forms as $form) {
            if (empty($form) || empty($form['CODE']) || empty($form['NAME'])) continue;

            $sid = 'ranx_landing_form_b24' . md5(trim($form['CODE']));
            $form['NAME'] = trim($form['NAME']);
            $form['SID'] = $sid;
            $result[$sid] = $form;
        }

        return $result;
    }

    public static function getB24FormsForSelect()
    {
        $forms = self::getB24Forms();
        return array_column($forms, 'NAME', 'SID');
    }

    public static function getDocArticleUrl($code) {
        return self::DOCS_URL . '/articles/' . $code . '/';
    }

    public static function isCookieConfirmationEnabled()
    {
        return self::get('COOKIES_SHOW_BANNER');
    }

    public static function isUpButtonEnabled()
    {
        return self::get('SHOW_UP_BUTTON');
    }

    public static function isMobileUpButtonHidden()
    {
        return self::get('HIDE_MOBILE_UP_BUTTON');
    }

    public static function getUpButtonType()
    {
        return self::get('UP_BUTTON_TYPE');
    }

    public static function getUpButtonLeftIdent()
    {
        return self::get('UP_BUTTON_LEFT_INDENT');
    }

    public static function getUpButtonRightIdent()
    {
        return self::get('UP_BUTTON_RIGHT_INDENT');
    }

    public static function getUpButtonBottomIdent()
    {
        return self::get('UP_BUTTON_BOTTOM_INDENT');
    }

    public static function getUpButtonLocation()
    {
        return self::get('UP_BUTTON_LOCATION');
    }

    public static function useSearch($siteId = SITE_ID)
    {
        return self::get('USE_SEARCH', false, $siteId) && !empty(self::getSearchPageLink($siteId));
    }

    public static function enableSearch($siteId = SITE_ID)
    {
        return self::set('USE_SEARCH', true, $siteId);
    }

    public static function disableSearch($siteId = SITE_ID)
    {
        self::delete('USE_SEARCH', $siteId);
    }

    public static function getSearchPageLink($siteId = SITE_ID)
    {
        return self::get('SEARCH_PAGE_LINK', false, $siteId);
    }

    public static function setSearchPageLink($link, $siteId = SITE_ID)
    {
        return self::set('SEARCH_PAGE_LINK', $link, $siteId);
    }

    public static function deleteSearchPageLink($siteId = SITE_ID)
    {
        self::delete('SEARCH_PAGE_LINK', $siteId);
    }

    public static function getSearchPageResultCount()
    {
        $val = intval(self::get('SEARCH_PAGE_RESULT_COUNT'));
        if ($val <= 0) {
            $val = self::getParamDefault('SEARCH_PAGE_RESULT_COUNT');
        }
        return $val;
    }

    public static function enableOrder($siteId = SITE_ID)
    {
        return self::set('ORDER_ENABLED', true, $siteId);
    }

    public static function disableOrder($siteId = SITE_ID)
    {
        self::delete('ORDER_ENABLED', $siteId);
    }

    public static function getOrderPageLink($siteId = SITE_ID)
    {
        return self::get('ORDER_PAGE_LINK', false, $siteId);
    }

    public static function setOrderPageLink($link, $siteId = SITE_ID)
    {
        return self::set('ORDER_PAGE_LINK', $link, $siteId);
    }

    public static function deleteOrderPageLink($siteId = SITE_ID)
    {
        self::delete('ORDER_PAGE_LINK', $siteId);
    }

    public static function getDelivery()
    {
        return self::get('DELIVERY');
    }

    public static function getPayment()
    {
        return self::get('PAYMENT');
    }

    public static function getInvoiceboxId()
    {
        return self::get('INVOICEBOX_ID');
    }

    public static function getInvoiceboxIdent()
    {
        return self::get('INVOICEBOX_IDENT');
    }

    public static function getInvoiceboxSecret()
    {
        return self::get('INVOICEBOX_SECRET');
    }

    public static function getInvoiceboxCurrency()
    {
        return self::get('INVOICEBOX_CURRENCY') ?: 'RUB';
    }

    public static function getInvoiceboxTestmode()
    {
        return self::get('INVOICEBOX_TESTMODE');
    }

    public static function isOneclickEnabled()
    {
        return self::get('ONECLICK_ENABLED');
    }

    public static function isAllFormsEnabled()
    {
        return self::get('USE_FORM_MODULE') && self::get('SHOW_ALL_FORMS');
    }

    public static function isAllowedServiceLink($code)
    {
        return self::getBlockInfo($code)['ALLOWED_SERVICE_LINK'] ?? false;
    }
}
