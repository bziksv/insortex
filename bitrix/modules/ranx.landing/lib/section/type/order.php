<?php

namespace Ranx\Landing\Section\Type;

use Ranx\Landing\Block;
use Ranx\Landing\Config;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Section\FieldValidator;

class Order extends Main
{
    protected function getType()
    {
        return SectionTable::TYPE_ORDER;
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
        $orderPageLink = Config::getOrderPageLink($siteId);

        if (empty($orderPageLink) || $orderPageLink == $oldPath) {
            Config::setOrderPageLink($newPath, $siteId);

            if (empty(Config::isOrderEnabled($siteId))) {
                Config::enableOrder($siteId);
            }
        }

        return true;
    }

    protected function addDefaultBlocks()
    {
        $landingId = $this->get('LANDING_ID');
        $siteId = $this->get('SITE_ID');
        $rootMode = $this->get('ROOT_MODE');

        Block::add($landingId, '20_13', [], $rootMode, $siteId);
    }

    protected function removeDirectory()
    {
        Base::removeDirectory();
    }
}
