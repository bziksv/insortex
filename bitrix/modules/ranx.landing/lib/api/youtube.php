<?php


namespace Ranx\Landing\Api;

use Ranx\Landing\Helpers\Helper;

class Youtube
{
    public static function getVideoId($link)
    {
        if (empty($link)) {
            return '';
        }

        if(preg_match('#(\.be/|/embed/|/v/|/watch\?v=)(?P<id>[A-Za-z0-9_-]{5,11})#', $link, $matches)) {
            return $matches['id'] ?? '';
        }

        if (preg_match('/^(?P<id>[A-Za-z0-9_-]{5,11})$/', $link, $matches)) {
            return $matches['id'] ?? '';
        }

        return '';
    }

    public static function getPreviewLinkByVideoId($videoId)
    {
        if (empty($videoId)) {
            return '';
        }

        return '//img.youtube.com/vi/'.$videoId.'/maxresdefault.jpg';
    }

    public static function getRatioByVideoId($videoId)
    {
        if (empty($videoId)) {
            return 0;
        }

        $requestUrl = 'https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v='.$videoId;
        $videoInfo = json_decode(Helper::getDataByUrl($requestUrl), true);
        if (empty($videoInfo) || !empty($videoInfo['error'])) {
            return 0;
        }

        try {
            $ratio = $videoInfo['width'] / $videoInfo['height'];
        }
        catch (\Exception $e) {
            return 0;
        }

        return $ratio;
    }

    public static function generateVideoLink($videoId, $params = [])
    {
        if (empty($videoId)) {
            return '';
        }

        $link = 'https://www.youtube.com/embed/'.$videoId;
        $isFirst = true;
        foreach ($params as $name => $value) {
            $link .= ($isFirst ? '?' : '&').$name.'='.$value;
            $isFirst = false;
        }

        return $link;
    }
}
