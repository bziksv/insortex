<?php


namespace Ranx\Landing\Section\Type;

use Ranx\Landing\Block;
use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;

class Catalog extends Section
{
    protected function getType()
    {
        return SectionTable::TYPE_CATALOG;
    }

    public static function addDefaultSectionBlocks($sectionId, $siteId = SITE_ID)
    {
        Block::add($sectionId, '20_6', [], Landing::MODE_SECTION, $siteId);
        Block::add($sectionId, '20_5', [], Landing::MODE_SECTION, $siteId);
    }

    public static function addDefaultElementBlocks($elementId, $siteId = SITE_ID)
    {
        Block::add($elementId, '20_7', [], Landing::MODE_ELEMENT, $siteId);
        Block::add($elementId, '20_8', [], Landing::MODE_ELEMENT, $siteId);
    }

    protected function addDefaultBlocks()
    {
        $iblockId = $this->get('IBLOCK_ID');
        $siteId = $this->get('SITE_ID');
        $rootMode = $this->get('ROOT_MODE');

        Block::add($iblockId, '20_6', [], $rootMode, $siteId);
        Block::add($iblockId, '20_5', [], $rootMode, $siteId);
    }

    protected function addDefaultProperties()
    {
        $iblockId = $this->get('IBLOCK_ID');
        $arIblock = \Ranx\Landing\Helpers\Iblock::getIblockById($iblockId);

        $iblock = new \Ranx\Landing\Utils\Iblock($arIblock);
        $reader = new \Ranx\Landing\Utils\XmlReader('catalog');

        if ($iblock->isEmpty() || !$reader->readFile()) {
            return false;
        }

        $properties = $reader->readProperties();
        foreach ($properties as $property) {
            $iblock->addProperty($property);
        }

        return true;
    }
}
