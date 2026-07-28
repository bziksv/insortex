<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Main\Loader;
use Ranx\Landing\Event;
use Ranx\Landing\Config;
use Ranx\Landing\Region;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\Contract\Controllerable;

class RanxRegionPopupLandingComponent extends CBitrixComponent implements Controllerable
{
    public function configureActions()
    {
        return [
            'search' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
        ];
    }

    public function executeComponent()
    {
        if (!Config::isRegionEnabled()) {
            return;
        }

        $this->arResult = [];

        $this->arResult['REGIONS'] = Region::getRegions();
        $this->arResult['CURRENT'] = Region::getCurrent();
        $this->arResult['FAVORITE'] = $this->getFavorite();

        if (Config::useRegionBranches()) {
            $this->arResult['CURRENT_BRANCH'] = Region::getCurrentBranch();
        }

        $this->arResult['MODAL_POSITION'] = Config::getModalPosition();
        $this->arResult['ONLY_SEARCH'] = Config::useOnlyRegionSearch();

        $this->includeComponentTemplate();
    }

    public function searchAction($post)
    {
        Loader::includeModule('ranx.landing');
        Event::removeOtherEvents();

        $query = mb_strtolower(trim($post['query']));

        if (empty($query) || mb_strlen($query) < 2 || !Config::isRegionEnabled()) {
            return '';
        }

        $result = [];

        $regions = Region::getRegions();
        foreach ($regions as $region) {
            if (strpos(mb_strtolower($region['NAME']), $query) === 0) {
                $result[] = $region;
            }
        }

        $this->arResult = [];
        
        if (!empty($result)) {
            $this->arResult['MODAL_POSITION'] = Config::getModalPosition();

            ob_start();
            foreach ($result as $arRegion) {
                if (!empty($arRegion['BRANCHES'])) {
                    $this->arResult['REGION'] = $arRegion;
                    foreach ($arRegion['BRANCHES'] as $arBranch) {
                        $this->arResult['BRANCH'] = $arBranch;
                        $this->includeComponentTemplate('region');
                    }
                } else {
                    $this->arResult['REGION'] = $arRegion;
                    $this->includeComponentTemplate('region');
                }
            }
            $html = ob_get_clean();
        } else {
            $html = '<div class="col-12">'.Loc::getMessage('RX_REGION_POPUP_LANDING_SEARCH_NOTHING').'</div>';
        }

        return [
            'html' => $html,
        ];
    }

    private function getFavorite()
    {
        $result = [];

        foreach ($this->arResult['REGIONS'] as $region) {
            if ($region['PROPERTY_FAVORIT_LOCATION_VALUE'] === 'Y') {
                $result[] = $region;
            }
        }

        return $result;
    }
}
