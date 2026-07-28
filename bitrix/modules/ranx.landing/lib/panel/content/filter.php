<?php


namespace Ranx\Landing\Panel\Content;

use Ranx\Landing\Config;

class Filter
{
    public static function getPropertyCode()
    {
        return 'FILTER_SETTINGS';
    }

    public static function isInclude($codeBlock, $sections = false)
    {
        if (empty($codeBlock))
            return false;

        if ($sections === false) {
            $sections = Config::getBlockConfigSections($codeBlock);
        }

        return in_array('CONTENT_FILTER', $sections);
    }

    public static function getFields($codeBlock)
    {
        return Config::getBlockInfo($codeBlock)['FILTER_FIELDS'] ?? [];
    }

    public static function getDefaultValue($codeBlock)
    {
        $result = [
            'INCLUDE' => false,
            'INCLUDE_FIELDS' => [],
        ];

        if (empty($codeBlock)) {
            return $result;
        }

        $blockInfo = Config::getBlockInfo($codeBlock);
        $filterFields = $blockInfo['FILTER_FIELDS'];
        if (empty($filterFields) || !is_array($filterFields)) {
            return $result;
        }

        $result['INCLUDE_FIELDS'] = array_fill_keys($filterFields, false);
        if (!isset($blockInfo['DEMO']) || !isset($blockInfo['DEMO']['BLOCK'])) {
            return $result;
        }

        $demoValue = $blockInfo['DEMO']['BLOCK']['FILTER'];
        if (empty($demoValue)) {
            return $result;
        }

        if (isset($demoValue['INCLUDE']))
            $result['INCLUDE'] = $demoValue['INCLUDE'];

        if (empty($demoValue['INCLUDE_FIELDS'])) {
            return $result;
        }

        foreach ($filterFields as $field) {
            if (!isset($demoValue['INCLUDE_FIELDS'][$field]))
                continue;

            $result['INCLUDE_FIELDS'][$field] = $demoValue['INCLUDE_FIELDS'][$field];
        }

        return $result;
    }

    public static function preparePostDataToSave($data, $codeBlock)
    {
        $result = [
            'INCLUDE' => false,
            'INCLUDE_FIELDS' => [],
        ];

        if (empty($data)) {
            return $result;
        }
        $result['INCLUDE'] = isset($data['INCLUDE']);

        if (empty($codeBlock)) {
            return $result;
        }
        $blockInfo = Config::getBlockInfo($codeBlock);

        $filterFields = $blockInfo['FILTER_FIELDS'];
        if (empty($filterFields) || !is_array($filterFields)) {
            return $result;
        }
        if (!isset($data['INCLUDE_FIELDS'])) {
            $data['INCLUDE_FIELDS'] = [];
        }

        foreach ($filterFields as $field) {
            $result['INCLUDE_FIELDS'][$field] = isset($data['INCLUDE_FIELDS'][$field]);
        }

        return $result;
    }
}
