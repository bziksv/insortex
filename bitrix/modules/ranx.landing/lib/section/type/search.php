<?php

namespace Ranx\Landing\Section\Type;

use Ranx\Landing\Block;
use Ranx\Landing\Config;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Section\FieldValidator;

class Search extends Main
{
    protected function getType()
    {
        return SectionTable::TYPE_SEARCH;
    }

    protected function checkPath($path, $pathWithSiteDir, $fullPath, $isForceReplace)
    {
        Base::checkPath($path, $pathWithSiteDir, $fullPath, $isForceReplace);

        if (FieldValidator::isMainPath($path)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_PATH_MAIN_ONLY_MAIN'));
        }
    }

    protected function changePath()
    {
        if (!parent::changePath()) {
            return false;
        }

        $siteId = $this->get('SITE_ID');
        $oldPath = $this->arSection['PATH'];
        $newPath = $this->newFields['PATH'];
        $searchPageLink = Config::getSearchPageLink($siteId);

        if (empty($searchPageLink) || $searchPageLink == $oldPath) {
            Config::setSearchPageLink($newPath, $siteId);

            if (empty(Config::useSearch($siteId))) {
                Config::enableSearch($siteId);
            }
        }

        return true;
    }

    protected function addDefaultBlocks()
    {
        $landingId = $this->get('LANDING_ID');
        $siteId = $this->get('SITE_ID');
        $rootMode = $this->get('ROOT_MODE');

        Block::add($landingId, '20_12', [], $rootMode, $siteId);
    }

    protected function removeDirectory()
    {
        Base::removeDirectory();
    }

    protected function removeParams()
    {
        $siteId = $this->get('SITE_ID');
        $path = $this->get('PATH');
        $searchPageLink = Config::getSearchPageLink($siteId);

        if ($path == $searchPageLink) {
            Config::deleteSearchPageLink($siteId);
            Config::disableSearch($siteId);
        }
    }
}
