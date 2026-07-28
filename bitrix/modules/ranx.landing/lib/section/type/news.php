<?php


namespace Ranx\Landing\Section\Type;


use Ranx\Landing\Block;
use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;

class News extends Section
{
    protected function getType()
    {
        return SectionTable::TYPE_NEWS;
    }

    public static function addDefaultSectionBlocks($sectionId, $siteId = SITE_ID)
    {
        Block::add($sectionId, '20_9', [], Landing::MODE_SECTION, $siteId);
    }

    public static function addDefaultElementBlocks($elementId, $siteId = SITE_ID)
    {
        Block::add($elementId, '20_10', [], Landing::MODE_ELEMENT, $siteId);
    }

    protected function addDefaultBlocks()
    {
        $iblockId = $this->get('IBLOCK_ID');
        $siteId = $this->get('SITE_ID');
        $rootMode = $this->get('ROOT_MODE');

        Block::add($iblockId, '20_9', [], $rootMode, $siteId);
    }
}
