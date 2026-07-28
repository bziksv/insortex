<?php

namespace Ranx\Landing;

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Ranx\Landing\Api\Repository;
use Ranx\Landing\Helpers\Helper;
use Ranx\Landing\Helpers\File;
use Ranx\Landing\Helpers\Iblock;
use Ranx\Landing\BlockTabs;

class Preset
{
    public const DEFAULT_PREVIEW = '/bitrix/images/' . Config::MODULE_ID . '/custom_preset.png';

    public const PATH = '/bitrix/modules/' . Config::MODULE_ID . '/demo/presets/';
    public const CUSTOM_PATH = '/upload/' . Config::MODULE_ID . '/presets/';
    public const DOWNLOAD_PATH = '/upload/' . Config::MODULE_ID . '/download/presets/';

    public const FILE_EXT = '.rxlanding';

    public static function initList()
    {
        self::initExternalList();
        self::initCustomList();

        // mark available by version
        $moduleVersion = Config::getVersion();
        foreach (Config::$presets['LIST'] as &$preset) {
            $preset['AVAILABLE'] = empty($preset['VERSION']) || Helper::compareVersion($preset['VERSION'], $moduleVersion) >= 0;
        }

        // remove empty groups
        foreach (Config::$presets['GROUPS'] as $code => $group) {
            if (empty($group['LIST']) && $code != 'CUSTOM') {
                unset(Config::$presets['GROUPS'][$code]);
            }
        }
    }

    public static function initExternalList()
    {
        $response = Repository::getPresetInfo();
        if (!empty($response['GROUPS']) && !empty($response['LIST'])) {
            Config::$presets['GROUPS'] = array_merge(Config::$presets['GROUPS'], $response['GROUPS']);
            Config::$presets['LIST'] = array_merge(Config::$presets['LIST'], $response['LIST']);
        }
    }

    public static function initCustomList()
    {
        if (Config::isDemoMode()) {
            return;
        }

        $presets = [];
        $presetsFolder = $_SERVER['DOCUMENT_ROOT'] . Preset::CUSTOM_PATH;

        if ($handle = @opendir($presetsFolder)) {
            while (($file = @readdir($handle)) !== false) {
                if ($file == '.' || $file == '..' || strpos($file, self::FILE_EXT) === false) {
                    continue;
                }

                $filePath = $presetsFolder . $file;
                $fileData = unserialize(file_get_contents($filePath), ['allowed_classes' => false]);
                if (empty($fileData['TITLE'])) {
                    continue;
                }

                [$fileName, ] = explode('.', $file);

                $presets[] = [
                    'FILENAME' => $fileName,
                    'MTIME' => filemtime($filePath),
                    'DATE_MODIFY' => date('d.m.Y H:i:s', filemtime($filePath)),
                    'TITLE' => $fileData['TITLE'],
                ];
            }
        }

        usort($presets, function($a, $b){
            if ($a['MTIME'] == $b['MTIME']) {
                return 0;
            }
            return $a['MTIME'] > $b['MTIME'] ? -1 : 1;
        });

        foreach ($presets as $preset) {
            Config::$presets['LIST'][$preset['FILENAME']] = [
                'TITLE' => $preset['TITLE'] . ' (' . $preset['DATE_MODIFY'] . ')',
            ];
            Config::$presets['GROUPS']['CUSTOM']['LIST'][] = $preset['FILENAME'];
        }
    }

    public static function getInfo($code)
    {
        return Config::$presets['LIST'][$code] ?? false;
    }

    public static function add($data)
    {
        if (empty($data) || strpos($data, 'data:') !== 0) {
            return false;
        }

        $base64 = substr($data, strpos($data, ';base64,') + 8);
        $preset = unserialize(base64_decode($base64), ['allowed_classes' => false]);

        if (empty($preset) || empty($preset['BLOCKS'])) {
            return false;
        }

        $uniqueId = md5($base64);

        $presetDir = $_SERVER['DOCUMENT_ROOT'] . self::CUSTOM_PATH;
        if (!is_dir($presetDir)) {
            self::createCustomDir();
        }

        if (file_exists($presetDir . $uniqueId . self::FILE_EXT)) {
            return true;
        }

        return file_put_contents($presetDir . $uniqueId . self::FILE_EXT, serialize($preset));
    }

    public static function apply($code, $landingId, $mode, $siteId = SITE_ID)
    {
        $preset = self::get($code);
        if (!$preset || empty($preset['BLOCKS'])) {
            return false;
        }

        $presetAvailable = Config::$presets['LIST'][$code]['AVAILABLE'];
        if (isset($presetAvailable) && !$presetAvailable) {
            return false;
        }

        $el = new \CIBlockElement();
        $blocksIblockId = Block::getIblockId($siteId);
        $elementsIblockId = Block::getElementsIblockId($siteId);

        $firstBlockProps = array_keys($preset['BLOCKS'][0]['PROPERTY_VALUES']);
        $blockPropsInfo = Iblock::getPropsInfoByCode($firstBlockProps, $blocksIblockId);

        $groupsById = [];
        if (!empty($preset['GROUPS'])) {
            $groupsById = self::applyGroups($preset['GROUPS'], $landingId, $mode, $siteId);
        }

        $addedBlocksMap = []; // ORIG_ID => ID
        $addedElementsMap = [];
        foreach ($preset['BLOCKS'] as $block) {

            // do not add block that is not existing in current env
            if (!Block::isExists($block['CODE'])) {
                continue;
            }

            $tabsById = [];
            if (!empty($block['TABS'])) {
                $tabsById = self::applyTabs($block['TABS'], $siteId);
                unset($block['TABS']);
            }

            $elementIds = [];
            if (!empty($block['ELEMENTS'])) {

                $firstElementProps = array_keys($block['ELEMENTS'][0]['PROPERTY_VALUES']);
                $elementPropsInfo = Iblock::getPropsInfoByCode($firstElementProps, $elementsIblockId);

                foreach ($block['ELEMENTS'] as $element) {

                    if (!empty($element['PREVIEW_PICTURE'])) {
                        $element['PREVIEW_PICTURE'] = File::fromBase64($element['PREVIEW_PICTURE']);
                    }
                    if (!empty($element['DETAIL_PICTURE'])) {
                        $element['DETAIL_PICTURE'] = File::fromBase64($element['DETAIL_PICTURE']);
                    }

                    foreach ($element['PROPERTY_VALUES'] as $propKey => &$prop) {
                        if (empty($elementPropsInfo[$propKey])) {
                            unset($element['PROPERTY_VALUES'][$propKey]);
                            continue;
                        }

                        if ($elementPropsInfo[$propKey]['PROPERTY_TYPE'] == 'F' && !empty($prop)) {
                            if (is_array($element['PROPERTY_VALUES'][$propKey])) {
                                $element['PROPERTY_VALUES'][$propKey] = array_map(
                                    function ($file) { return File::fromBase64($file); },
                                    $prop
                                );
                            }
                            else {
                                $element['PROPERTY_VALUES'][$propKey] = File::fromBase64($prop);
                            }
                        } elseif ($elementPropsInfo[$propKey]['PROPERTY_TYPE'] == 'L') {
                            $propValues = Iblock::getListPropValuesByXmlId($propKey, $elementsIblockId);
                            if (is_array($element['PROPERTY_VALUES'][$propKey])) {
                                $element['PROPERTY_VALUES'][$propKey] = array_map(
                                    function ($xmlId) use ($propValues) { return $propValues[$xmlId] ?? false; },
                                    $prop
                                );
                            }
                            else {
                                $element['PROPERTY_VALUES'][$propKey] = $propValues[$prop] ?? false;
                            }
                        }
                    }


                    if (!empty($element['ORIG_TAB_ID'])) {
                        $tabId = $tabsById[$element['ORIG_TAB_ID']];
                        if (empty($tabId)) {
                            $tabId = false;
                        }
                        $element['IBLOCK_SECTION_ID'] = $tabId;
                    }

                    $element['IBLOCK_ID'] = $elementsIblockId;

                    if ($elId = $el->Add($element)) {
                        $elementIds[] = $elId;
                        $addedElementsMap[$element['ORIG_ID']] = $elId;
                    }
                }

                unset($block['ELEMENTS']);
            }

            if (!empty($block['PREVIEW_PICTURE'])) {
                $block['PREVIEW_PICTURE'] = File::fromBase64($block['PREVIEW_PICTURE']);
            }
            if (!empty($block['DETAIL_PICTURE'])) {
                $block['DETAIL_PICTURE'] = File::fromBase64($block['DETAIL_PICTURE']);
            }

            foreach ($block['PROPERTY_VALUES'] as $propKey => &$prop) {
                if (empty($blockPropsInfo[$propKey])) {
                    unset($block['PROPERTY_VALUES'][$propKey]);
                    continue;
                }
                if ($mode === Landing::MODE_ELEMENT && $propKey === 'AUTO_BLOCK') {
                    $block['PROPERTY_VALUES'][$propKey] = false;
                }

                if ($blockPropsInfo[$propKey]['PROPERTY_TYPE'] == 'F' && !empty($prop)) {
                    if (is_array($block['PROPERTY_VALUES'][$propKey])) {
                        $block['PROPERTY_VALUES'][$propKey] = array_map(
                            function ($file) { return File::fromBase64($file); },
                            $prop
                        );
                    }
                    else {
                        $block['PROPERTY_VALUES'][$propKey] = File::fromBase64($prop);
                    }
                } elseif ($blockPropsInfo[$propKey]['PROPERTY_TYPE'] == 'L') {
                    $propValues = Iblock::getListPropValuesByXmlId($propKey, $blocksIblockId);
                    if (is_array($block['PROPERTY_VALUES'][$propKey])) {
                        $block['PROPERTY_VALUES'][$propKey] = array_map(
                            function ($xmlId) use ($propValues) { return $propValues[$xmlId] ?? false; },
                            $prop
                        );
                    }
                    else {
                        $block['PROPERTY_VALUES'][$propKey] = $propValues[$prop] ?? false;
                    }
                }

                if ($propKey == 'DESC' && !empty($prop['TEXT']) && !empty($prop['TYPE'])) {
                    // Important that TEXT and TYPE go in this order
                    $block['PROPERTY_VALUES'][$propKey] = [
                        'TEXT' => $prop['TEXT'],
                        'TYPE' => $prop['TYPE'],
                    ];
                }
            }

            $block['PROPERTY_VALUES']['LANDING'] = $landingId;
            $block['PROPERTY_VALUES']['MODE'] = $mode;
            $block['PROPERTY_VALUES']['ELEMENTS'] = $elementIds;
            $block['PROPERTY_VALUES']['TABS'] = array_values($tabsById);
            $block['IBLOCK_ID'] = $blocksIblockId;

            if (!empty($block['GROUP_ID']) && !empty($groupsById[$block['GROUP_ID']])) {
                $block['IBLOCK_SECTION_ID'] = $groupsById[$block['GROUP_ID']];
            }
            unset($block['GROUP_ID']);

            $addedBlockId = $el->Add($block);

            if ($addedBlockId) {
                $addedBlocksMap[$block['ORIG_ID']] = $addedBlockId;
            }
        }

        // loop another one in order to update anchors
        foreach ($preset['BLOCKS'] as $block) {

            $updateProps = [];
            foreach ($block['PROPERTY_VALUES'] as $propKey => $propVal) {
                if (!is_string($propVal)) continue;

                if (strpos($propVal, '#block_') === 0) {
                    $origBlockId = intval(substr($propVal, 7));
                    $newBlockId = $addedBlocksMap[$origBlockId] ?? 0;
                    if (!$newBlockId) continue;

                    $updateProps[$propKey] = '#block_' . $newBlockId;
                }
            }

            $curBlockId = $addedBlocksMap[$block['ORIG_ID']] ?? 0;

            if ($curBlockId)
                \CIBlockElement::SetPropertyValuesEx($curBlockId, $blocksIblockId, $updateProps);

            foreach ($block['ELEMENTS'] as $element) {
                $updateProps = [];
                foreach ($element['PROPERTY_VALUES'] as $propKey => $propVal) {
                    if (!is_string($propVal)) continue;

                    if (strpos($propVal, '#block_') === 0) {
                        $origBlockId = intval(substr($propVal, 7));
                        $newBlockId = $addedBlocksMap[$origBlockId] ?? 0;
                        if (!$newBlockId) continue;

                        $updateProps[$propKey] = '#block_' . $newBlockId;
                    }
                }

                $curElementId = $addedElementsMap[$element['ORIG_ID']] ?? 0;

                if ($curElementId)
                    \CIBlockElement::SetPropertyValuesEx($curElementId, $elementsIblockId, $updateProps);
            }
        }

        File::removeTemp();
        Landing::cleanCache($landingId, $mode);
    }

    private static function applyGroups($groups, $landingId, $mode, $siteId = SITE_ID)
    {
        $result = [];
        $iblockId = Block::getIblockId($siteId);
        $bs = new \CIBlockSection();

        foreach ($groups as $group) {
            $arFields = array_merge($group, [
                'IBLOCK_ID' => $iblockId, 
                'UF_LANDING' => $landingId, 
                'UF_MODE' => $mode,
            ]);
            unset($arFields['ORIG_ID']);

            if ($groupId = $bs->Add($arFields)) {
                $result[$group['ORIG_ID']] = $groupId;
            }
        }

        return $result;
    }

    private static function applyTabs($tabs, $siteId = SITE_ID)
    {
        $elementsIblockId = Block::getElementsIblockId($siteId);
        $dbSectionObj = new \CIBlockSection;

        $result = [];
        foreach ($tabs as $tab) {
            $tab['IBLOCK_ID'] = $elementsIblockId;
            $origId = $tab['ORIG_ID'];
            unset($tab['ORIG_ID']);

            if ($tabId = $dbSectionObj->Add($tab)) {
                $result[$origId] = $tabId;
            }
        }

        return $result;
    }

    public static function get($code)
    {
        $modulePresetPath = $_SERVER['DOCUMENT_ROOT'] . self::PATH . $code . self::FILE_EXT;
        $customPresetPath = $_SERVER['DOCUMENT_ROOT'] . self::CUSTOM_PATH . $code . self::FILE_EXT;
        $downloadPresetPath = $_SERVER['DOCUMENT_ROOT'] . self::DOWNLOAD_PATH . $code . self::FILE_EXT;
        $src = Config::$presets['LIST'][$code]['SRC'] ?? '';
        $md5 = Config::$presets['LIST'][$code]['MD5'] ?? '';

        $data = '';
        if (file_exists($modulePresetPath)) {
            $data = unserialize(file_get_contents($modulePresetPath), ['allowed_classes' => false]);
        } elseif (file_exists($customPresetPath)) {
            $data = unserialize(file_get_contents($customPresetPath), ['allowed_classes' => false]);
        } elseif (!empty($src)) { // try to download
            if (file_exists($downloadPresetPath) && $md5 === md5_file($downloadPresetPath)) {
                $data = unserialize(file_get_contents($downloadPresetPath), ['allowed_classes' => false]);
            }
            else {
                self::createDownloadDir();
                $data = unserialize(Repository::download($src, $downloadPresetPath), ['allowed_classes' => false]);
            }
        }

        if (!empty($data['TITLE']) && isset($data['BLOCKS'])) {
            return $data;
        }

        return false;
    }

    public static function deleteCustom($code)
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'].self::CUSTOM_PATH.$code.self::FILE_EXT;

        if (!file_exists($filePath) || !unlink($filePath)) {
            return false;
        }

        return true;
    }

    public static function generateFromLanding($landingId, $mode)
    {
        Loader::includeModule('iblock');

        $landing = Landing::getById($landingId, $mode);

        if (empty($landingId)) {
            return '';
        }

        $blockGroups = BlockGroup::getByLanding($landingId, $mode, true);
        $blockGroupIds = array_column($blockGroups, 'ID');

        $groups = [];
        foreach ($blockGroups as $blockGroup) { // only these keys
            $groups[] = [
                'ORIG_ID' => $blockGroup['ID'],
                'NAME' => $blockGroup['NAME'],
                'ACTIVE' => $blockGroup['ACTIVE'],
                'SORT' => $blockGroup['SORT'],
            ];
        }

        $groupBlocks = BlockGroup::getBlocks(
            $blockGroupIds, 
            [
                '_PROPERTIES', 
                'PREVIEW_TEXT', 
                'DETAIL_TEXT', 
                'PREVIEW_PICTURE', 
                'DETAIL_PICTURE',
            ]
        );
        $rootBlocks = Block::getRootBlocksData($landingId, $mode, true);

        $allBlocks = self::getBlocksInfo(array_merge($groupBlocks, $rootBlocks));

        $version = ModuleManager::getVersion(Config::MODULE_ID);

        $preset = [
            'TITLE' => $landing['NAME'],
            'VERSION' => $version,
            'GROUPS' => $groups,
            'BLOCKS' => $allBlocks,
        ];

        return serialize($preset);
    }

    private static function getBlocksInfo($data = [])
    {
        if (empty($data)) {
            return [];
        }

        $result = [];

        foreach ($data as $arBlock) {
            $arBlockProps = $arBlock['PROPERTIES'];

            $elementsInfo = [];
            if (!empty($arBlockProps['ELEMENTS']['VALUE'])) {
                $elementsInfo = self::getElementsInfo($arBlock['CODE'], $arBlockProps['ELEMENTS']['VALUE']);
            }

            $tabsInfo = [];
            if (!empty($arBlockProps['TABS']['VALUE'])) {
                $tabsInfo = self::getTabsInfo($arBlockProps['TABS']['VALUE']);
            }

            $propsInfo = [];
            foreach ($arBlockProps as $arBlockPropKey => $arBlockProp) {
                if (in_array($arBlockPropKey, ['LANDING', 'MODE', 'ELEMENTS', 'THROUGH_ID'])) {
                    continue;
                }

                if ($arBlockProp['PROPERTY_TYPE'] == 'L') {
                    $propsInfo[$arBlockPropKey] = $arBlockProp['VALUE_XML_ID'];
                } elseif ($arBlockProp['PROPERTY_TYPE'] === 'F') {
                    if ($arBlockProp['MULTIPLE'] === 'Y' && is_array($arBlockProp['VALUE'])) {
                        $propsInfo[$arBlockPropKey] = array_map(
                            function ($fileId) { return File::toBase64($fileId); },
                            $arBlockProp['VALUE']
                        );
                    }
                    else {
                        $propsInfo[$arBlockPropKey] = File::toBase64($arBlockProp['VALUE']);
                    }
                }
                else {
                    $propsInfo[$arBlockPropKey] = $arBlockProp['~VALUE'];
                }
            }

            $blockInfo = [
                'ORIG_ID' => $arBlock['ID'],
                'GROUP_ID' => $arBlock['IBLOCK_SECTION_ID'],
                'CODE' => $arBlock['CODE'],
                'NAME' => $arBlock['~NAME'],
                'ACTIVE' => $arBlock['ACTIVE'],
                'SORT' => $arBlock['SORT'],
                'PREVIEW_TEXT' => $arBlock['PREVIEW_TEXT'],
                'PREVIEW_TEXT_TYPE' => 'html',
                'DETAIL_TEXT' => $arBlock['DETAIL_TEXT'],
                'DETAIL_TEXT_TYPE' => 'html',
                'PREVIEW_PICTURE' => File::toBase64($arBlock['PREVIEW_PICTURE']),
                'DETAIL_PICTURE' => File::toBase64($arBlock['DETAIL_PICTURE']),
                'PROPERTY_VALUES' => $propsInfo,
                'ELEMENTS' => $elementsInfo,
                'TABS' => $tabsInfo,
            ];

            $result[] = $blockInfo;
        }

        return $result;
    }

    private static function getElementSelect($code)
    {
        $arElementSelect = [
            'ID',
            'IBLOCK_ID',
            'IBLOCK_SECTION_ID',
            'NAME',
            'SORT',
            'ACTIVE',
            'ACTIVE_FROM',
            'ACTIVE_TO',
            'PREVIEW_PICTURE',
            'PREVIEW_TEXT',
            'DETAIL_TEXT',
            'DETAIL_PICTURE',
        ];

        $elementsFields = Config::getBlockElementsFields($code);

        $deprecatedElementsFields = Config::getBlockDeprecatedElementsFields($code);
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

    private static function getElementsInfo($code, $ids)
    {
        if (empty($ids)) {
            return [];
        }
        $result = [];

        $arElementSelect = self::getElementSelect($code);

        $rsElements = \CIBlockElement::GetList(
            ['SORT' => 'ASC', 'ID' => 'ASC'],
            ['ID' => $ids],
            false,
            false,
            $arElementSelect['FIELDS']
        );

        while ($obElement = $rsElements->GetNextElement()) {
            $arElement = $obElement->GetFields();
            $arElementProps = $obElement->GetProperties();

            $propsInfo = [];
            foreach ($arElementProps as $arElementPropKey => $arElementProp) {
                if (!in_array($arElementPropKey, $arElementSelect['PROPS'])) {
                    continue;
                }

                $isMultiple = $arElementProp['MULTIPLE'] === 'Y';
                $withDesc   = $arElementProp['WITH_DESCRIPTION'] === 'Y';

                if ($arElementProp['PROPERTY_TYPE'] === 'L') {
                    $propsInfo[$arElementPropKey] = $arElementProp['VALUE_XML_ID'];
                } elseif ($arElementProp['PROPERTY_TYPE'] === 'F') {
                    if ($isMultiple && is_array($arElementProp['VALUE'])) {
                        $propsInfo[$arElementPropKey] = array_map(
                            function ($fileId) { return File::toBase64($fileId); },
                            $arElementProp['VALUE']
                        );
                    }
                    else {
                        $propsInfo[$arElementPropKey] = File::toBase64($arElementProp['VALUE']);
                    }
                } elseif ($arElementProp['PROPERTY_TYPE'] === 'S' && !$isMultiple && $withDesc) {
                    $propsInfo[$arElementPropKey] = ['VALUE' => $arElementProp['~VALUE'], 'DESCRIPTION' => $arElementProp['~DESCRIPTION']];
                } else {
                    $propsInfo[$arElementPropKey] = $arElementProp['~VALUE'];
                }
            }

            $elementInfo = [
                'ORIG_ID' => $arElement['ID'],
                'ORIG_TAB_ID' => $arElement['IBLOCK_SECTION_ID'],
                'NAME' => $arElement['~NAME'],
                'ACTIVE' => $arElement['ACTIVE'],
                'ACTIVE_FROM' => $arElement['ACTIVE_FROM'],
                'ACTIVE_TO' => $arElement['ACTIVE_TO'],
                'SORT' => $arElement['SORT'],
                'PREVIEW_TEXT' => $arElement['PREVIEW_TEXT'],
                'DETAIL_TEXT' => $arElement['DETAIL_TEXT'],
                'PREVIEW_TEXT_TYPE' => 'html',
                'DETAIL_TEXT_TYPE' => 'html',
                'PREVIEW_PICTURE' => File::toBase64($arElement['PREVIEW_PICTURE']),
                'DETAIL_PICTURE' => File::toBase64($arElement['DETAIL_PICTURE']),
                'PROPERTY_VALUES' => $propsInfo,
            ];

            $result[] = $elementInfo;
        }

        return $result;
    }

    private static function getTabsInfo($tabIds)
    {
        $tabs = BlockTabs::getByIds($tabIds);

        $result = [];
        foreach ($tabs as $tab) {
            $result[] = [
                'ORIG_ID' => $tab['ID'],
                'NAME' => $tab['NAME'],
                'ACTIVE' => $tab['ACTIVE'],
                'SORT' => $tab['SORT'],
            ];
        }

        return $result;
    }

    private static function createCustomDir()
    {
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/upload/' . Config::MODULE_ID)) {
            @mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload/' . Config::MODULE_ID);
        }
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . self::CUSTOM_PATH)) {
            @mkdir($_SERVER['DOCUMENT_ROOT'] . self::CUSTOM_PATH);
        }
    }

    private static function createDownloadDir()
    {
        $parts = explode('/', self::DOWNLOAD_PATH);
        $dir = $_SERVER['DOCUMENT_ROOT'];
        foreach($parts as $part) {
            $dir .= $part.'/';
            if (!is_dir($dir)) {
                mkdir($dir);
            }
        }
    }
}
