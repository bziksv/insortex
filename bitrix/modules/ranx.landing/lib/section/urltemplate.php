<?php


namespace Ranx\Landing\Section;

use Ranx\Landing\SectionTable;

class UrlTemplate
{
    public static function get($rootMode, $path)
    {
        $path = trim($path, '/ ');

        switch ($rootMode) {
            case SectionTable::ROOT_MODE_SECTIONS:
                return self::getForSectionsMode($path);
            case SectionTable::ROOT_MODE_ELEMENTS:
                return self::getForElementsMode($path);
            default:
                return self::getForDefaultMode($path);
        }
    }

    protected static function getForSectionsMode($path)
    {
        return [
            'LIST_PAGE_URL'    => '#SITE_DIR#'.$path.'/',
            'SECTION_PAGE_URL' => '#SITE_DIR#'.$path.'/#SECTION_CODE_PATH#/',
            'DETAIL_PAGE_URL'  => '#SITE_DIR#'.$path.'/#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
        ];
    }

    protected static function getForElementsMode($path)
    {
        return [
            'LIST_PAGE_URL'    => '#SITE_DIR#'.$path.'/',
            'SECTION_PAGE_URL' => '#SITE_DIR#'.$path.'/',
            'DETAIL_PAGE_URL'  => '#SITE_DIR#'.$path.'/#ELEMENT_CODE#/',
        ];
    }

    protected static function getForDefaultMode($path)
    {
        return [
            'LIST_PAGE_URL'    => '#SITE_DIR#'.$path.'/',
            'SECTION_PAGE_URL' => '#SITE_DIR#'.$path.'/',
            'DETAIL_PAGE_URL'  => '#SITE_DIR#'.$path.'/',
        ];
    }
}
