<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Ranx\Landing\Block;
use Ranx\Landing\Fields;
use Ranx\Landing\Config;
use Ranx\Landing\Helpers;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Engine\Contract\Controllerable;

class RanxBlockFilterLandingComponent extends CBitrixComponent implements Controllerable
{
    public function onPrepareComponentParams($arParams)
    {
        if (empty($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = '36000000';
        }

        return parent::onPrepareComponentParams($arParams);
    }

    protected function checkParams()
    {
        return $this->arParams['BLOCK_ID'] > 0;
    }

    public function executeComponent()
    {
        if (!$this->checkParams()) {
            echo 'Not set block id';
            return;
        }
        if (!$this->includeModule()) {
            echo 'Failed to include modules';
            return;
        }

        $cache = Cache::createInstance();
        $cacheId = 'ranx_block_filter_'.serialize($this->arParams ?? []);
        $cacheDir = 'ranx_block_landing/block_'.$this->arParams['BLOCK_ID'];
        if (!Config::isEditMode() && $cache->initCache($this->arParams['CACHE_TIME'], $cacheId, $cacheDir)) {
            $vars = $cache->getVars();
            $this->arResult = $vars['arResult'];
        }
        elseif ($cache->startDataCache()) {
            $this->getItems();
            $cache->endDataCache(['arResult' => $this->arResult]);
        }

        $this->includeComponentTemplate();

        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        if ($request->isAjaxRequest()) {
            $this->includeTemplateStyles();
            $this->includeTemplateScripts();
        }
    }

    protected function getItems()
    {
        if (isset($this->arResult['ITEMS'])) {
            return $this->arResult['ITEMS'];
        }

        $this->arResult['ITEMS'] = [];

        $blockCode = $this->getBlockCode();
        $fields = Config::getBlockFilterFields($blockCode);
        $fieldsMess = Config::getBlockElementsFieldsMess($blockCode);
        $propertiesInfo = $this->getPropertiesInfo($fields);
        $settings = $this->getSettings();

        if (empty($settings['INCLUDE_FIELDS'])) {
            return $this->arResult['ITEMS'];
        }

        $stringPropList = [];
        $stringFieldList = [];
        $enumList = [];

        foreach ($fields as $field) {
            if (empty($fieldsMess[$field]) || !$settings['INCLUDE_FIELDS'][$field]) {
                continue;
            }
            $arItem = [
                'TITLE' => $fieldsMess[$field],
            ];

            if ($field == Fields\IntervalTime::getBlockConfigFieldCode()) {
                $arItem['TYPE'] = 'TIME';
            }
            else if (Helpers\Property::checkByFullCode($field)) {
                $shortPropCode = Helpers\Property::getShortCodeByFull($field);
                $type = $propertiesInfo[$shortPropCode]['PROPERTY_TYPE'];

                /*if (Helpers\Property::isList($type)) {
                    $arItem['TYPE'] = 'ENUM';
                    $enumList[] = $shortPropCode;
                }*/
                if (Helpers\Property::isString($type)) {
                    $arItem['TYPE'] = 'LIST';
                    $stringPropList[] = $field;
                }
                elseif (Helpers\Property::isNumber($type)) {
                    $arItem['TYPE'] = 'NUMBER';
                }
                else {
                    continue;
                }
            }
            else {
                if ($field != 'NAME') {
                    continue;
                }

                $arItem['TYPE'] = 'LIST';
                $stringFieldList[] = $field;
            }

            $this->arResult['ITEMS'][$field] = $arItem;
        }

        $this->fillStringItemValues($stringFieldList, $stringPropList);
        $this->fillEnumItemValues($enumList);

        return $this->arResult['ITEMS'];
    }

    protected function fillStringItemValues($fieldCodes, $fullPropCodes)
    {
        $codes = array_merge($fieldCodes, $fullPropCodes);
        if (empty($codes)) {
            return;
        }

        $arSelect = array_merge(['ID', 'IBLOCK_ID'], $codes);
        $arFilter = [
            'ID' => $this->getElementIds(),
            'IBLOCK_ID' => $this->getElementIblockId(),
            'SECTION_ACTIVE' => 'Y',
            'ACTIVE' => 'Y',
        ];
        if (isset($this->arParams['TAB_ID'])) {
            $arFilter['IBLOCK_SECTION_ID'] = $this->arParams['TAB_ID'];
        }

        $dbValuesList = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $itemValues = array_fill_keys($codes, []);
        while ($valueObj = $dbValuesList->GetNextElement()) {
            $fieldValues = $valueObj->GetFields();
            $propValues = $valueObj->GetProperties();

            foreach ($fieldCodes as $field) {
                $itemValues[$field][] = $fieldValues[$field];
            }
            foreach ($fullPropCodes as $fullPropCode) {
                $shortPropCode = Helpers\Property::getShortCodeByFull($fullPropCode);
                $itemValues[$fullPropCode][] = $propValues[$shortPropCode]['VALUE'];
            }
        }

        foreach ($itemValues as $code => $values) {
            $this->arResult['ITEMS'][$code]['VALUES'] = array_filter(array_unique($values));
        }
    }

    protected function fillEnumItemValues($fullPropCodes)
    {
        if (empty($fullPropCodes)) {
            return;
        }

        $shortPropCodes = array_map(function ($value) {
            return Helpers\Property::getShortCodeByFull($value);
        }, $fullPropCodes);

        $arEnumValues = Helpers\Iblock::getListPropValues($shortPropCodes, $this->getElementIblockId());
        $itemValues = array_fill_keys($fullPropCodes, []);
        foreach ($arEnumValues as $arEnumValue) {
            $fullPropCode = Helpers\Property::getFullCodeByShort($arEnumValue['CODE']);
            if (!in_array($fullPropCode, $fullPropCodes)) {
                continue;
            }

            $itemValues[$fullPropCode][] = $arEnumValue['VALUE'];
        }

        foreach ($itemValues as $code => $values) {
            $this->arResult['ITEMS'][$code]['VALUES'] = $values;
        }
    }

    protected function getPropertiesInfo($fields)
    {
        $properties = [];

        foreach ($fields as $field) {
            if (Helpers\Property::checkByFullCode($field)) {
                $properties[] = Helpers\Property::getShortCodeByFull($field);
            }
        }

        if (!empty($properties)) {
            return Helpers\Iblock::getPropsInfoByCode($properties, $this->getElementIblockId());
        }

        return [];
    }

    protected function getElementIds()
    {
        $blockId = $this->arParams['BLOCK_ID'];
        $iblockId = $this->getBlockIblockId();
        return Helpers\Iblock::getMultiplePropValue($blockId, $iblockId, 'ELEMENTS');
    }

    protected function getBlockCode()
    {
        if (!empty($this->arResult['BLOCK_CODE'])) {
            return $this->arResult['BLOCK_CODE'];
        }

        if (!empty($this->arParams['BLOCK_CODE'])) {
            return $this->arResult['BLOCK_CODE'] = $this->arParams['BLOCK_CODE'];
        }

        $arItem = Helpers\Iblock::getElementById($this->arParams['BLOCK_ID']);
        return $this->arResult['BLOCK_CODE'] = $arItem['CODE'];
    }

    protected function getSettings()
    {
        if (!empty($this->arResult['SETTINGS'])) {
            return $this->arResult['SETTINGS'];
        }

        if (!empty($this->arParams['SETTINGS'])) {
            return $this->arResult['SETTINGS'] = $this->arParams['SETTINGS'];
        }

        $arSettings = Helpers\Iblock::getPropValue(
            $this->arParams['BLOCK_ID'],
            $this->arParams['BLOCK_IBLOCK_ID'],
            'FILTER_SETTINGS'
        );

        $arSettings = unserialize($arSettings ?? '') ?? [];
        return $this->arResult['SETTINGS'] = $arSettings;
    }

    protected function getBlockIblockId()
    {
        if (!empty($this->arParams['BLOCK_IBLOCK_ID'])) {
            return $this->arParams['BLOCK_IBLOCK_ID'];
        }

        return $this->arParams['BLOCK_IBLOCK_ID'] = Block::getIblockId();
    }

    protected function getElementIblockId()
    {
        if (!empty($this->arParams['ELEMENT_IBLOCK_ID'])) {
            return $this->arParams['ELEMENT_IBLOCK_ID'];
        }

        return $this->arParams['ELEMENT_IBLOCK_ID'] = Block::getElementsIblockId();
    }

    public function configureActions()
    {
        return [];
    }

    protected function includeModule()
    {
        $modules = ['iblock', 'ranx.landing'];
        $result = true;
        foreach ($modules as $module) {
            $result &= \Bitrix\Main\Loader::includeModule($module);
        }

        return $result;
    }

    protected function includeTemplateStyles()
    {
        $filePath = $this->__template->__folder . '/style.css';
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath)) {
            echo '<script>BX.loadCSS([\'' . $filePath . '\']);</script>';
        }
    }

    protected function includeTemplateScripts()
    {
        $filePath = $this->__template->__folder . '/script.js';
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath)) {
            echo '<script>' . file_get_contents($_SERVER['DOCUMENT_ROOT'] . $filePath) . '</script>';
        }
    }
}
