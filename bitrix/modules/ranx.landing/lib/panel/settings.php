<?php

namespace Ranx\Landing\Panel;

use Ranx\Landing\Block;
use Ranx\Landing\Config;

class Settings
{
    public static function get($blockId)
    {
        $block = Block::get($blockId);
        $blockInfo = Config::getBlockInfo($block['CODE']);
        $values = unserialize($block['~DETAIL_TEXT']) ?? [];

        $result = $blockInfo['SETTINGS'];
        foreach ($result as $key => &$setting) {
            $setting['VALUE'] = $setting['DEFAULT'];
            if (isset($values[$key])) {
                $setting['VALUE'] = $values[$key];
            }
        }

        return $result;
    }

    public static function getValues($blockId)
    {
        $result = self::get($blockId);
        foreach ($result as &$setting) {
            $setting = $setting['VALUE'];
        }
        return $result;
    }

    public static function fillValues($blockCode, $values)
    {
        $blockInfo = Config::getBlockInfo($blockCode);

        $settings = $blockInfo['SETTINGS']?? [];
        $result = [];
        foreach ($settings as $key => &$setting) {
            $result[$key] = $setting['DEFAULT'];
            if (isset($values[$key])) {
                $result[$key] = $values[$key];
            }
        }

        return $result;
    }

    public static function update($blockId, $values)
    {
        if (empty($values) || !is_array($values)) {
            $values = [];
        }
        $block = Block::get($blockId);
        $blockInfo = Config::getBlockInfo($block['CODE']);
        $settings = $blockInfo['SETTINGS'];

        foreach ($settings as $key => $setting) {
            if (!isset($values[$key])) {
                $values[$key] = $setting['DEFAULT'];
                continue;
            }
            if ($setting['TYPE'] === 'checkbox') {
                $values[$key] = !empty($values[$key]);
            }
        }

        $el = new \CIBlockElement;
        $el->Update($blockId, [
            'DETAIL_TEXT' => serialize($values),
        ]);
    }
}
