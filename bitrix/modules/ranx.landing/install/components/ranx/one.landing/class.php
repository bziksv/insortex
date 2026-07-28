<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Iblock;
use Ranx\Landing\Page;
use Bitrix\Main\Loader;
use Ranx\Landing\Block;
use Ranx\Landing\Region;
use Ranx\Landing\Config;
use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Data\Cache;
use Ranx\Landing\Helpers\Domain;
use Bitrix\Iblock\InheritedProperty;

/**
 * Component that shows one landing
 */
class RanxOneLandingComponent extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['LANDING_ID'] = intval($arParams['LANDING_ID']);
        if (empty($arParams['MODE'])) {
            $arParams['MODE'] = Landing::MODE_ELEMENT;
        }
        if (empty($arParams['DEMO_MODE'])) {
            $arParams['DEMO_MODE'] = 'N';
        }
        if (empty($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = '36000000';
        }

        $arParams['SET_STATUS_404'] = $arParams['SET_STATUS_404'] ?? 'Y';
        $arParams['SHOW_404'] = $arParams['SHOW_404'] ?? 'Y';
        $arParams['FILE_404'] = $arParams['FILE_404'] ?? SITE_TEMPLATE_PATH . '/404.php';

        return parent::onPrepareComponentParams($arParams);
    }

    public function executeComponent()
    {
        Loader::includeModule('iblock');
        if (!Loader::includeModule('ranx.landing')) {
            die('no ranx.landing module');
        }

        global $APPLICATION;

        if (empty($this->arParams['LANDING_ID']) && empty($this->arParams['LANDING_CODE'])) {
            Iblock\Component\Tools::process404(
                ''
                ,($this->arParams['SET_STATUS_404'] === 'Y')
                ,($this->arParams['SET_STATUS_404'] === 'Y')
                ,($this->arParams['SHOW_404'] === 'Y')
                ,$this->arParams['FILE_404']
            );
            return;
        }

        $cacheId = 'ranx_one_landing_' . $this->arParams['MODE'] . '_' . ($this->arParams['LANDING_ID'] ?: $this->arParams['LANDING_CODE']);
        $cache = Cache::createInstance();

        if (Config::isRegionEnabled()) {
            $region = Region::getCurrent();
            $cacheId .= '_' . $region['ID'];
            if (Config::useRegionBranches()) {
                $branch = Region::getCurrentBranch();
                $cacheId .= '_' . $branch['ID'];
            }
        }

        if (!Config::isEditMode() && !Config::isDemoMode() && $cache->initCache($this->arParams['CACHE_TIME'], $cacheId, 'ranx_one_landing')) {
            $vars = $cache->getVars();
            $this->arResult = $vars['arResult'];
        } elseif ($cache->startDataCache()) {
            $this->getLanding();

            if (!$this->arResult['LANDING']) {
                $cache->abortDataCache();
            }

            $this->arResult['IS_DEMO_MODE'] = $this->arParams['DEMO_MODE'] === 'Y' && $this->arParams['MODE'] == Landing::MODE_ELEMENT && Config::isDemoMode();
            if ($this->arResult['IS_DEMO_MODE']) {
                Config::enableDemoLanding();
                $this->arParams['LANDING_ID'] = Landing::getSessCopy($this->arParams['IBLOCK_ID'], $this->arResult['LANDING']['NAME']);
                $this->getLanding();
            }

            $this->getRootSectionInfo();
            $this->getBlocks();

            if ($this->arResult['IS_DEMO_MODE'] && empty($this->arResult['BLOCKS']) && !empty($this->arParams['DEMO_PRESET'])) {
                \Ranx\Landing\Preset::apply($this->arParams['DEMO_PRESET'], $this->arParams['LANDING_ID'], $this->arParams['MODE']);
                $this->getBlocks();
            }

            if (in_array($this->arParams['MODE'], [Landing::MODE_SECTION, Landing::MODE_ELEMENT])) {
                $this->getSectionPath();
            }

            if ($this->arParams['MODE'] == Landing::MODE_ELEMENT) {
                $this->arResult['IPROP_VALUES'] = (new InheritedProperty\ElementValues($this->arResult['LANDING']['IBLOCK_ID'], $this->arResult['LANDING']['ID']))->getValues();
            } elseif ($this->arParams['MODE'] == Landing::MODE_SECTION) {
                $this->arResult['IPROP_VALUES'] = (new InheritedProperty\SectionValues($this->arResult['LANDING']['IBLOCK_ID'], $this->arResult['LANDING']['ID']))->getValues();
            }

            $cache->endDataCache(['arResult' => $this->arResult]);
        }

        if (!$this->arResult['LANDING'] || !$this->isDomainAllowed()) {
            Iblock\Component\Tools::process404(
                ''
                ,($this->arParams['SET_STATUS_404'] === 'Y')
                ,($this->arParams['SET_STATUS_404'] === 'Y')
                ,($this->arParams['SHOW_404'] === 'Y')
                ,$this->arParams['FILE_404']
            );
            return;
        }

        // if the first block is slider, then add class to body (to make header transparent if needed)
        $firstBlock = reset($this->arResult['BLOCKS']);
        $firstBlockInfo = Config::getBlockInfo($firstBlock['CODE']);
        if (Config::isEditMode() && Config::isHeaderTransparent()) {
            Page::addBodyClass('header-is-transparent--bg');
        }
        elseif ($firstBlockInfo['IS_UNDER_HEADER'] && Config::isHeaderTransparent() &&
            (!Config::isPageTitleEnabled() || $this->arParams['MODE'] == Landing::MODE_ELEMENT)) {
            Page::addBodyClass('header-is-transparent');
        }
        Page::addBodyClass('title-is-' . Config::getBlockTitlePosition());

        if (!empty($this->arResult['RX_LANDING_DEV_COLOR'])) {
            define('RX_LANDING_DEV_COLOR', $this->arResult['RX_LANDING_DEV_COLOR']);
        }

        $this->includeScripts();

        $this->includeComponentTemplate();

        $this->setSeoProperties();
        $this->addBreadcrumbs();
        Page::setOpengraphProperties();
        Page::setContentWidth();
    }

    private function getLanding()
    {
        $this->arResult['LANDING'] = [];
        if (!empty($this->arParams['LANDING_ID'])) {
            $this->arResult['LANDING'] = Landing::getById($this->arParams['LANDING_ID'], $this->arParams['MODE']);
        } elseif (!empty($this->arParams['LANDING_CODE'])) {
            $this->arResult['LANDING'] = Landing::getByCode($this->arParams['LANDING_CODE'], $this->arParams['IBLOCK_ID'], $this->arParams['MODE'], $this->arParams['SECTION_CODE']);
        }
        if (Config::isDevMode()) {
            $devColor = Landing::getDevColor($this->arResult['LANDING'], $this->arParams['MODE']);
            if ($devColor) {
                $this->arResult['RX_LANDING_DEV_COLOR'] = $devColor;
            }
        }
    }

    private function getRootSectionInfo()
    {
        $mode = $this->arParams['MODE'];
        if (in_array($mode, [Landing::MODE_ELEMENT, Landing::MODE_SECTION])) {
            $iblockId = $this->arResult['LANDING']['IBLOCK_ID'];
        }
        else if (in_array($mode, [Landing::MODE_SECTIONS, Landing::MODE_ROOT_SECTION])) {
            $iblockId = $this->arResult['LANDING']['ID'];
        }

        $this->arResult['SECTION'] = \Ranx\Landing\Section\Manager::getByIblockId($iblockId);
    }

    private function getBlocks()
    {
        $this->arResult['BLOCKS'] = Block::getByLanding($this->arResult['LANDING']['ID'], $this->arParams['MODE']);
        // find max sort
        $this->arResult['MAX_SORT'] = 0;
        foreach ($this->arResult['BLOCKS'] as $block) {
            if ($block['SORT'] > $this->arResult['MAX_SORT']) {
                $this->arResult['MAX_SORT'] = $block['SORT'];
            }
        }
    }

    private function includeScripts()
    {
        $needMaps = false;
        foreach ($this->arResult['BLOCKS'] as $block) {
            $blockInfo = Config::getBlockInfo($block['CODE']);
            if ($blockInfo['USE_MAPS']) {
                $needMaps = true;
            }
        }
        $needMaps = $needMaps || Config::isEditMode();

        if ($needMaps) {
            Page::includeMaps();
        }
    }

    private function setSeoProperties()
    {
        $ipropValues = $this->arResult['IPROP_VALUES'] ?? [];
        if ($this->arParams['MODE'] == Landing::MODE_ELEMENT) {
            if ($ipropValues['ELEMENT_PAGE_TITLE']) {
                $GLOBALS['APPLICATION']->SetTitle($ipropValues['ELEMENT_PAGE_TITLE']);
            } else {
                $GLOBALS['APPLICATION']->SetTitle($this->arResult['LANDING']['NAME']);
            }

            if ($ipropValues['ELEMENT_META_TITLE']) {
                $GLOBALS['APPLICATION']->SetPageProperty('title', $ipropValues['ELEMENT_META_TITLE']);
            }
            if ($ipropValues['ELEMENT_META_KEYWORDS']) {
                $GLOBALS['APPLICATION']->SetPageProperty('keywords', $ipropValues['ELEMENT_META_KEYWORDS']);
            }
            if ($ipropValues['ELEMENT_META_DESCRIPTION']) {
                $GLOBALS['APPLICATION']->SetPageProperty('description', $ipropValues['ELEMENT_META_DESCRIPTION']);
            }
        } elseif ($this->arParams['MODE'] == Landing::MODE_SECTION) {
            if ($ipropValues['SECTION_PAGE_TITLE']) {
                $GLOBALS['APPLICATION']->SetTitle($ipropValues['SECTION_PAGE_TITLE']);
            } else {
                $GLOBALS['APPLICATION']->SetTitle($this->arResult['LANDING']['NAME']);
            }

            if ($ipropValues['SECTION_META_TITLE']) {
                $GLOBALS['APPLICATION']->SetPageProperty('title', $ipropValues['SECTION_META_TITLE']);
            }
            if ($ipropValues['SECTION_META_KEYWORDS']) {
                $GLOBALS['APPLICATION']->SetPageProperty('keywords', $ipropValues['SECTION_META_KEYWORDS']);
            }
            if ($ipropValues['SECTION_META_DESCRIPTION']) {
                $GLOBALS['APPLICATION']->SetPageProperty('description', $ipropValues['SECTION_META_DESCRIPTION']);
            }
        } else {
            $GLOBALS['APPLICATION']->SetTitle($this->arResult['LANDING']['NAME']);
        }
    }

    private function getSectionPath()
    {
        $this->arResult['SECTION_PATH'] = [];
        $arFilter = [
            'GLOBAL_ACTIVE' => 'Y',
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
        ];
        $sectionId = $this->arParams['PARENT_SECTION'];
        $sectionCode = $this->arParams['PARENT_SECTION_CODE'];

        if ($this->arParams['MODE'] === Landing::MODE_ELEMENT) {
            $sectionId = $this->arParams['SECTION_ID'];
            $sectionCode = $this->arParams['SECTION_CODE'];
        }

        $this->arParams['PARENT_SECTION'] = \CIBlockFindTools::GetSectionID($sectionId, $sectionCode, $arFilter);
        if ($this->arParams['PARENT_SECTION'] > 0) {
            $rsPath = \CIBlockSection::GetNavChain($this->arParams['IBLOCK_ID'], $this->arParams['PARENT_SECTION']);
            while ($arPath = $rsPath->GetNext()) {
                $ipropValues = new Iblock\InheritedProperty\SectionValues($this->arParams['IBLOCK_ID'], $arPath['ID']);
                $arPath['IPROPERTY_VALUES'] = $ipropValues->getValues();
                $this->arResult['SECTION_PATH'][] = $arPath;
            }
        }
    }

    private function addBreadcrumbs()
    {
        if (!empty($this->arResult['SECTION_PATH'])) {
            foreach ($this->arResult['SECTION_PATH'] as $section) {
                if ($section['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']) {
                    $GLOBALS['APPLICATION']->AddChainItem($section['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'], $section['~SECTION_PAGE_URL']);
                } else {
                    $GLOBALS['APPLICATION']->AddChainItem($section['NAME'], $section['~SECTION_PAGE_URL']);
                }
            }
        }
    }

    private function isDomainAllowed()
    {
        $arSection = $this->arResult['SECTION'];
        if ($arSection['TYPE'] != SectionTable::TYPE_LANDING) {
            return true;
        }

        $landingId = $this->arResult['LANDING']['ID'];
        if ($arSection['LANDING_ID'] == $landingId) {
            $domain = $arSection['DOMAIN'];

            $context = \Bitrix\Main\Application::getInstance()->getContext();
            $host = strtok(Domain::format($context->getServer()->getHttpHost() ?: ''), ':');
            $section = $context->getRequest()->getRequestedPageDirectory();

            return empty($domain) || $host === $domain && (empty($section) || $section == '/');
        }

        return true;
    }
}
