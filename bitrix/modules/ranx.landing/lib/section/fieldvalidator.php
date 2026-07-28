<?php


namespace Ranx\Landing\Section;

use Bitrix\Main\IO;
use Ranx\Landing\SectionTable;

class FieldValidator
{
    public static function isValidSiteId($siteId)
    {
        $iblock = \Bitrix\Iblock\IblockTable::getList([
            'filter' => [
                'IBLOCK_TYPE_ID' => 'ranx_landing',
                'CODE' => 'ranx_landing_blocks',
            ],
            'select' => ['ID'],
        ])->fetch();

        $iblockSites = \Bitrix\Iblock\IblockSiteTable::getList([
            'filter' => [
                'IBLOCK_ID' => $iblock['ID'],
            ],
            'select' => ['SITE_ID'],
        ])->fetchAll();

        return in_array($siteId, array_column($iblockSites, 'SITE_ID'));
    }

    public static function isCorrectPathFormat($path)
    {
        return !empty($path) && strpos($path, '..') === false;
    }

    public static function containInvalidDir($path)
    {
        $temp = array_values(array_filter(explode('/', $path)));
        return in_array($temp[0], ['bitrix', 'local', 'upload']);
    }

    public static function isExistPathInTable($pathWithSiteDir, $siteId)
    {
        $existingSection = SectionTable::getList([
            'filter' => [
                'PATH' => $pathWithSiteDir,
                'SITE_ID' => $siteId,
            ]
        ])->fetch();

        if ($existingSection) {
            return $existingSection['TITLE'] . ' (' . $existingSection['PATH'] . ')';
        }

        return false;
    }

    public static function isExistPath($fullPath, $isForceReplace)
    {
        return !$isForceReplace && IO\Directory::isDirectoryExists($fullPath);
    }

    public static function isMainPath($path)
    {
        return $path == '/';
    }

    public static function isAllowedRootMode($rootMode)
    {
        return in_array($rootMode, array_keys(SectionTable::getRootModes()));
    }
}
