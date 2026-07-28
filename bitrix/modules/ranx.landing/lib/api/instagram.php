<?php

namespace Ranx\Landing\Api;

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Data\Cache;

class Instagram
{
    const API_URL = 'https://graph.instagram.com';
    const FIELDS = 'caption,media_url,permalink,timestamp,thumbnail_url';

    private $token;
    private $postsCount;
    private $cacheTime;
    private $cache;

    public function __construct()
    {
        $this->token = Config::getInstagramToken();
        $this->postsCount = Config::getInstagramPostsCount();
        $this->cacheTime = Config::getInstagramCacheTime();
        $this->cache = Cache::createInstance();
    }

    private function checkToken()
    {
        return !empty($this->token);
    }

    private static function makeRequest($method, $data = [])
    {
        $url = self::API_URL . $method . '?' . http_build_query($data);
        $data = Helper::getDataByUrl($url);
        return json_decode($data, true);
    }

    private function preparePostData($arData)
    {
        if (empty($arData['data'])) {
            return [];
        }

        $posts = [];
        foreach ($arData['data'] as $item) {
            $posts[] = [
                'TEXT' => $item['caption'] ?? '',
                'IMG' => $item['thumbnail_url'] ?? ($item['media_url'] ?? ''),
                'LINK' => $item['permalink'] ?? '',
                'DATE' => strtolower(\FormatDate('d.m.Y', strtotime($item['timestamp']))),
            ];
        }

        return $posts;
    }

    public function getPosts()
    {
        if (!$this->checkToken()) {
            return [];
        }

        $this->posts = [];

        if ($this->getPostsFromCache()) {
            return $this->posts;
        }

        if ($this->cache->startDataCache()) {
            $res = self::makeRequest('/me/media', [
                'access_token' => $this->token,
                'fields' => self::FIELDS,
                'limit' => $this->postsCount,
            ]);

            if (!empty($res['error'])) {
                $this->cache->abortDataCache();
                return [];
            }

            $this->posts = $this->preparePostData($res);
            $this->cache->endDataCache(['POSTS' => $this->posts]);

            return $this->posts;
        }

        return [];
    }

    private function getPostsFromCache()
    {
        if (Config::isEditMode()) {
            return false;
        }

        $cacheId = 'ranx_block_landing_instagram_'.$this->token.'_'.$this->postsCount;
        if ($this->cache->initCache($this->cacheTime, $cacheId, 'ranx_block_landing')) {
            $vars = $this->cache->getVars();
            $this->posts = $vars['POSTS'];
            return true;
        }

        return false;
    }

    public static function refreshToken()
    {
        $sites = \Bitrix\Main\SiteTable::getList()->fetchAll();
        
        foreach ($sites as $site) {
            $token = Config::get('INSTAGRAM_TOKEN', null, $site['LID']);
            if (!$token) {
                continue;
            }

            $res = self::makeRequest('/refresh_access_token', [
                'grant_type' => 'ig_refresh_token',
                'access_token' => $token,
            ]);

            if (!empty($res['access_token'])) {
                Config::set('INSTAGRAM_TOKEN', $res['access_token'], $site['LID']);
            }
        }

        return '\\'.__METHOD__.'();';
    }
}
