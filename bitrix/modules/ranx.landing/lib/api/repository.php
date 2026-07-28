<?php


namespace Ranx\Landing\Api;

use Ranx\Landing\Preset;
use Ranx\Landing\Helpers\Helper;

class Repository
{
    const URL = 'https://soft.landing-demo.ru/api/v1/';
    const CACHE_ID = 'ranx_landing_repository';
    const CACHE_TTL = 86400; // 1 day

    private static function makeRequest($method, $args = [])
    {
        $params = '';
        if (!empty($args)) {
            $params = '?'.http_build_query($args);
        }

        $cache = \Bitrix\Main\Application::getInstance()->getManagedCache();
        $cacheId = self::CACHE_ID.'_'.$method.$params;
        if ($cache->read(self::CACHE_TTL, $cacheId)) {
            return $cache->get($cacheId);
        }

        $link = self::URL.$method.'.php'.$params;
        $data = Helper::getDataByUrl($link);
        $jsonData = json_decode($data, 1) ?? [];

        $cache->set($cacheId, $jsonData);
        return $jsonData;
    }

    public static function getPresetInfo()
    {
        return self::makeRequest('getPresetInfo');
    }

    public static function getPagesInfo()
    {
        return self::makeRequest('getPagesInfo');
    }

    public static function download($src, $pathToSave)
    {
        $data = Helper::getDataByUrl($src) ?? '';

        if (!empty($data)) {
            file_put_contents($pathToSave, $data);
        }

        return $data;
    }
}
