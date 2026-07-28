<?php


namespace Ranx\Landing;

use Ranx\Landing\Fields;
use Ranx\Landing\Helpers;
use Bitrix\Main\Data\Cache;

class BlockFilter
{
    const CACHE_TTL = 36000000;
    protected $arParams = [];
    protected $arFilter = [];
    protected $arSettings = [];

    public function __construct($arParams, $preFilter = [])
    {
        $this->arParams = $arParams;
        $this->arParams['PREFILTER'] = $preFilter;
        $this->arFilter = $this->convertPreFilterToConditions($preFilter);
        $this->arSettings = $this->getSettings($arParams);
    }

    protected function getSettings($arParams = false)
    {
        $arParams = $this->arParams;
        $cache = Cache::createInstance();
        $cacheId = 'ranx_block_filter_settings_'.$arParams['BLOCK_ID'];
        $cacheDir = 'ranx_block_landing/block_'.$arParams['BLOCK_ID'];

        if ($cache->initCache(self::CACHE_TTL, $cacheId, $cacheDir)) {
            $vars = $cache->getVars();
            return $vars['arSettings'];
        }
        elseif ($cache->startDataCache()) {
            $arSettings = Helpers\Iblock::getPropValue(
                $arParams['BLOCK_ID'],
                $arParams['BLOCK_IBLOCK_ID'],
                'FILTER_SETTINGS'
            );
            $arSettings = unserialize($arSettings ?? '');
            $cache->endDataCache(['arSettings' => $arSettings]);

            return $arSettings;
        }

        return [];
    }

    protected function convertPreFilterToConditions($preFilter)
    {
        $result = [];
        foreach ($preFilter as $fields => $value) {
            if ($fields == Fields\IntervalTime::getBlockConfigFieldCode()) {
                $result = array_merge($result, $this->convertIntervalTimeToFilterCondition($value));
            }
            else {
                if (empty($value)) {
                    continue;
                }
                $result[$fields] = $value;
            }
        }

        return $result;
    }

    protected function convertIntervalTimeToFilterCondition($value)
    {
        $result = [];

        ['FROM' => $fromValue, 'TO' => $toValue] = Fields\IntervalTime::encodeValue($value);
        [$fromPropCode, $toPropCode] = Fields\IntervalTime::getFullPropertyCodes();
        if (empty($fromValue) || empty($toValue)) {
            return $result;
        }

        $dayTime = 24 * 60;
        if ($toValue >= $dayTime) {
             $result[] = [
                 'LOGIC' => 'OR',
                 ['>='.$fromPropCode => $fromValue, '<='.$toPropCode => $toValue],
                 ['>='.$fromPropCode => 0, '<='.$toPropCode => ($toValue - $dayTime)]
             ];
        }
        else {
            $result['>='.$fromPropCode] = $fromValue;
            $result['<='.$toPropCode] = $toValue;
        }

        return $result;
    }

    protected function isInclude()
    {
        return $this->arSettings['INCLUDE'];
    }

    public function getConditions()
    {
        return $this->isInclude() ? $this->arFilter : [];
    }

    public function getHtmlCode()
    {
        if (!$this->isInclude()) {
            return '';
        }

        $this->arParams['SETTINGS'] = $this->arSettings;

        ob_start();
        \Ranx\Landing\Page::showBlockFilter($this->arParams);
        return ob_get_clean();
    }
}
