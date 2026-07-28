<?php


namespace Ranx\Landing\Helpers;

use Ranx\Landing\Landing;

class SmartFilter
{
    const FILTER_TEMPLATE = 'filter/#SMART_FILTER_PATH#/apply/';
    const CACHE_TTL = 36000000;
    const CACHE_DIR = 'ranx_block_landing/filter_params';

    protected $blockId = false;
    protected $arParams = [];
    protected $arFilter = [];
    protected $template = '';
    protected $requestUrl = false;
    protected $html = '';
    protected $hasWorked = false;

    public function __construct($blockId, $template = '', $requestUrl = false)
    {
        $this->blockId = $blockId;
        $this->template = $template;
        $this->requestUrl = $this->formatUrl($requestUrl);

        $this->initParams();
    }

    public function getParams()
    {
        return $this->arParams;
    }

    public function getConditions()
    {
        $this->run();

        return $this->arFilter;
    }

    public function getHtmlCode()
    {
        $this->run();

        return $this->html;
    }

    protected function initParams()
    {
        $cache = \Bitrix\Main\Data\Cache::createInstance();
        $cacheId = 'ranx_filter_params_'.$this->blockId;

        if ($cache->initCache(self::CACHE_TTL, $cacheId, self::CACHE_DIR)) {
            $vars = $cache->getVars();
        }
        elseif ($cache->startDataCache()) {
            $GLOBALS['CACHE_MANAGER']->StartTagCache(self::CACHE_DIR);

            $vars = $this->getLandingParams();

            if (empty($vars)) {
                $GLOBALS['CACHE_MANAGER']->AbortTagCache();
                $cache->abortDataCache();
                return;
            }

            $GLOBALS['CACHE_MANAGER']->EndTagCache();
            $cache->endDataCache($vars);
        }

        $iblockId = $vars['BLOCK_ID'];
        $sectionId = $vars['SECTION_ID'];
        $urlTemplate = $vars['URL_TEMPLATE'];
        $folderTemplate = $vars['FOLDER_TEMPLATE'];
        $baseUrl = $vars['BASE_URL'];

        if (empty($iblockId) || empty($urlTemplate) || empty($folderTemplate)) {
            return;
        }

        if (mb_strpos($urlTemplate, $folderTemplate) === 0) {
            $urlTemplate = mb_substr($urlTemplate, strlen($folderTemplate));
        }
        $urlTemplate .= self::FILTER_TEMPLATE;
        $folder = str_replace('#SITE_DIR#', SITE_DIR, $folderTemplate);
        $filterPath = $this->getFilterPath($folder, $urlTemplate, $this->requestUrl) ?? '';

        $this->arParams = [
            'IBLOCK_TYPE' => 'ranx_landing',
            'IBLOCK_ID' => $iblockId,
            'SECTION_ID' => $sectionId,
            'BLOCK_ID' => $this->blockId,
            'BASE_URL' => $baseUrl,
            'SEF_RULE' => $folder.$urlTemplate,
            'SEF_RULE_FILTER' => $urlTemplate,
            'SMART_FILTER_PATH' => $filterPath,
            'FILTER_NAME' => 'FILTER_'.$this->blockId,
        ];
    }

    protected function getLandingParams()
    {
        ['ID' => $id, 'MODE' => $mode] = Landing::getByBlockId($this->blockId);
        if (!is_numeric($id) || intval($id) <= 0 || $mode == Landing::MODE_ELEMENT) {
            return [];
        }
        $arLanding = Landing::getById($id, $mode);

        if ($mode === Landing::MODE_SECTION) {
            $iblockId = $arLanding['IBLOCK_ID'];
            $sectionId = $id;
            $urlTemplate = $arLanding['SECTION_PAGE_URL'];
            $folderTemplate = $arLanding['LIST_PAGE_URL'];
            $baseUrl = \CIBlock::ReplaceSectionUrl($urlTemplate, $arLanding, true, 'S');
        } else if (in_array($mode, [Landing::MODE_ROOT_SECTION, Landing::MODE_SECTIONS])) {
            $iblockId = $id;
            $sectionId = 0;
            $urlTemplate = $arLanding['LIST_PAGE_URL'];
            $folderTemplate = $arLanding['LIST_PAGE_URL'];
            $baseUrl = \CIBlock::ReplaceDetailUrl($urlTemplate, $arLanding, true, false);
        }

        return [
            'BLOCK_ID' => $iblockId,
            'SECTION_ID' => $sectionId,
            'URL_TEMPLATE' => $urlTemplate,
            'FOLDER_TEMPLATE' => $folderTemplate,
            'BASE_URL' => $baseUrl,
        ];
    }

    protected function getFilterPath($folder, $urlTemplate, $requestUrl)
    {
        $engine = new \CComponentEngine();
        $arVariables = [];

        $engine->addGreedyPart('#SECTION_CODE_PATH#');
        $engine->addGreedyPart("#SMART_FILTER_PATH#");
        $engine->setResolveCallback(['CIBlockFindTools', 'resolveComponentEngine']);
        $engine->guessComponentPath($folder, ['section' => $urlTemplate], $arVariables, $requestUrl);
        return urldecode($arVariables['SMART_FILTER_PATH']);
    }

    protected function formatUrl($url)
    {
        if (empty($url)) {
            return false;
        }

        if (mb_strpos($url, '/', -1) !== false) {
            $url .= 'index.php';
        }

        return $url;
    }

    protected function run()
    {
        if ($this->hasWorked) {
            return;
        }
        $this->hasWorked = true;

        if (empty($this->arParams)) {
            return;
        }

        ob_start();
        \Ranx\Landing\Page::showSmartFilter($this->arParams, $this->template);
        $this->html = ob_get_clean();

        $filterName = $this->arParams['FILTER_NAME'];
        $this->arFilter = $GLOBALS[$filterName] ?? [];
    }
}
