<?php


namespace Ranx\Landing\Section\Type;

use Bitrix\Iblock;
use Ranx\Landing\Block;
use Ranx\Landing\Landing;
use Ranx\Landing\Helpers;
use Ranx\Landing\BlockGroup;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Section\FieldValidator;

class Section extends Base
{
    public function __construct($sectionId = 0)
    {
        parent::__construct($sectionId);

        if ($this->arSection['ROOT_MODE'] == SectionTable::ROOT_MODE_ELEMENT) {
            $this->arSection->set('ROOT_MODE', $this->getDefaultRootMode());
        }
    }

    protected function getType()
    {
        return SectionTable::TYPE_SECTION;
    }

    protected function getDefaultRootMode()
    {
        return SectionTable::ROOT_MODE_ELEMENTS;
    }

    public function setRootMode($rootMode)
    {
        if ($rootMode == SectionTable::ROOT_MODE_ELEMENT) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_NOT_ALLOWED_ROOT_MODE'));
        }

        parent::setRootMode($rootMode);
    }

    protected function checkPath($path, $pathWithSiteDir, $fullPath, $isForceReplace)
    {
        parent::checkPath($path, $pathWithSiteDir, $fullPath, $isForceReplace);

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
        Helpers\UrlRewriter::update($siteId, $oldPath, $newPath);
    }


    protected function changeRootMode()
    {
        if (!$this->isChanged('ROOT_MODE')) {
            return false;
        }

        $landingId = $this->get('IBLOCK_ID');
        $oldRootMode = $this->arSection['ROOT_MODE'];
        $newRootMode = $this->newFields['ROOT_MODE'];

        $blocks = Block::getAllByLanding($landingId, $oldRootMode);
        foreach ($blocks as $block) {
            Helpers\Iblock::updateProperties($block['ID'], $block['IBLOCK_ID'], ['MODE' => $newRootMode]);
        }

        $groups = BlockGroup::getByLanding($landingId, $oldRootMode, true);
        foreach ($groups as $group) {
            Helpers\Iblock::updateSection($group['ID'], ['UF_MODE' => $newRootMode]);
        }
    }

    protected function addDefaultBlocks()
    {
        $iblockId = $this->get('IBLOCK_ID');
        $siteId = $this->get('SITE_ID');
        $rootMode = $this->get('ROOT_MODE');
        $blockCode = $rootMode == SectionTable::ROOT_MODE_ELEMENTS ? '20_2' : '20_1';

        Block::add($iblockId, $blockCode, [], $rootMode, $siteId);
    }

    protected function removeLandings()
    {
        parent::removeLandings();

        $rootMode = $this->get('ROOT_MODE');
        Block::deleteByLanding($this->get('IBLOCK_ID'), $rootMode);
    }

    protected function changeTitle()
    {
        parent::changeTitle();

        $iblockId = $this->get('IBLOCK_ID');
        $rootMode = $this->get('ROOT_MODE');
        Landing::cleanCache($iblockId, $rootMode);
    }
}
