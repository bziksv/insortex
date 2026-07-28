<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Iblock;
use Bitrix\Main\Loader;
use Ranx\Landing\Page;

/**
 * Complex component for making landing sections with SEF urls
 */
class RanxPubLandingComponent extends \CBitrixComponent
{
    private $page;
    private $arUrlTemplates = [
        'sections' => '',
        'root_smart_filter' => 'filter/#SMART_FILTER_PATH#/apply/',
        'section' => '#SECTION_CODE_PATH#/',
        'smart_filter' => '#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/',
        'detail' => '#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
    ];
    private $arUrlTemplateAliases = [
        'smart_filter' => 'section',
        'root_smart_filter' => 'sections',
    ];
    private $arComponentVariables = [
        'SECTION_ID',
        'SECTION_CODE',
        'ELEMENT_ID',
        'ELEMENT_CODE',
    ];
    private $arVariables = [];

    public function onPrepareComponentParams($arParams)
    {
        $arParams['STRICT_SECTION_CHECK'] = 'Y';
        $arParams['DETAIL_STRICT_SECTION_CHECK'] = 'Y';

        $arParams['SET_STATUS_404'] = $arParams['SET_STATUS_404'] ?? 'Y';
        $arParams['SHOW_404'] = $arParams['SHOW_404'] ?? 'Y';
        $arParams['FILE_404'] = $arParams['FILE_404'] ?? SITE_TEMPLATE_PATH . '/404.php';

        $arParams['URL_TEMPLATE_ALIASES'] = $arParams['URL_TEMPLATE_ALIASES'] ?? [];

        return $arParams;
    }

    public function executeComponent()
    {
        Loader::includeModule('iblock');
        if (!Loader::includeModule('ranx.landing')) {
            die('no ranx.landing module');
        }

        if (!empty($this->arParams['SEF_URL_TEMPLATES']))
            $this->arUrlTemplates = array_merge($this->arUrlTemplates, $this->arParams['SEF_URL_TEMPLATES']);

        if (!isset($this->arParams['SEF_URL_TEMPLATES']['root_smart_filter']))
            unset($this->arUrlTemplates['root_smart_filter']);
        if (!isset($this->arParams['SEF_URL_TEMPLATES']['smart_filter']))
            unset($this->arUrlTemplates['smart_filter']);

        if (!empty($this->arParams['URL_TEMPLATE_ALIASES']))
            $this->arUrlTemplateAliases = array_merge($this->arUrlTemplateAliases, $this->arParams['URL_TEMPLATE_ALIASES']);

        $this->getPage();
        $this->resolve404();

        \CComponentEngine::initComponentVariables($this->page, $this->arComponentVariables, [], $this->arVariables);
        $this->arResult = [
            'FOLDER' => $this->arParams['SEF_FOLDER'],
            'URL_TEMPLATES' => $this->arUrlTemplates,
            'VARIABLES' => $this->arVariables,
        ];

        $this->setRssProperty();

        $this->includeComponentTemplate($this->page);
    }

    private function getPage()
    {
        $engine = new \CComponentEngine($this);

        $engine->addGreedyPart('#SECTION_CODE_PATH#');
        $engine->addGreedyPart("#SMART_FILTER_PATH#");
        $engine->setResolveCallback(['CIBlockFindTools', 'resolveComponentEngine']);

        $this->page = $engine->guessComponentPath(
            $this->arParams['SEF_FOLDER'],
            $this->arUrlTemplates,
            $this->arVariables
        );

        if (!empty($this->arUrlTemplateAliases[$this->page])) {
            $this->page = $this->arUrlTemplateAliases[$this->page];
        }

        if (!$this->page && !$this->arUrlTemplates['section']) {
            $this->page = 'section';
        }
    }

    private function setRssProperty()
    {
        $rssTemplate = $this->arResult['URL_TEMPLATES']['rss'];

        if (isset($rssTemplate)) {
            $GLOBALS['APPLICATION']->SetDirProperty('rss', $this->arResult['FOLDER'].$rssTemplate);
        }
    }

    private function resolve404()
    {
        $b404 = false;
        if(!$this->page)
        {
            $this->page = 'sections';
            $b404 = true;
        }

        if($this->page == 'section')
        {
            if (isset($this->arVariables['SECTION_ID']))
                $b404 |= (intval($this->arVariables['SECTION_ID']).'' !== $this->arVariables['SECTION_ID']);
            else if (isset($this->arVariables['SMART_FILTER_PATH']))
                $b404 |= empty($this->arVariables['SMART_FILTER_PATH']);
            else
                $b404 |= !isset($this->arVariables['SECTION_CODE']);
        }

        if($b404)
        {
            $folder404 = str_replace('\\', '/', $this->arParams['SEF_FOLDER']);
            if ($folder404 != '/')
                $folder404 = '/'.trim($folder404, "/ \t\n\r\0\x0B").'/';
            if (substr($folder404, -1) == '/')
                $folder404 .= 'index.php';

            if ($folder404 != $GLOBALS['APPLICATION']->GetCurPage(true))
            {
                Iblock\Component\Tools::process404(
                    ''
                    ,($this->arParams['SET_STATUS_404'] === 'Y')
                    ,($this->arParams['SET_STATUS_404'] === 'Y')
                    ,($this->arParams['SHOW_404'] === 'Y')
                    ,$this->arParams['FILE_404']
                );
            }
        }
    }
}
