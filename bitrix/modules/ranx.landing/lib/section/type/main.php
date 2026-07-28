<?php


namespace Ranx\Landing\Section\Type;

use Bitrix\Iblock;
use Ranx\Landing\Helpers;
use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Section\FieldValidator;

class Main extends Base
{
    protected function getType()
    {
        return SectionTable::TYPE_MAIN;
    }

    protected function checkPath($path, $pathWithSiteDir, $fullPath, $isForceReplace)
    {
        parent::checkPath($path, $pathWithSiteDir, $fullPath, $isForceReplace);

        if (!FieldValidator::isMainPath($path)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_WRONG_MAIN_PATH'));
        }
    }

    protected function createIblock()
    {
        parent::createIblock();

        $createdLandingId = Helpers\Iblock::addElement([
            'IBLOCK_ID' => $this->get('IBLOCK_ID'),
            'ACTIVE'    => 'Y',
            'NAME'      => $this->get('TITLE'),
            'CODE'      => '',
        ]);
        $this->arSection->set('LANDING_ID', $createdLandingId);
    }

    protected function changeTitle()
    {
        parent::changeTitle();

        $landingId = $this->get('LANDING_ID');
        $newName = $this->get('TITLE');

        $result = Helpers\Iblock::updateElement($landingId, ['NAME' => $newName]);
        if ($result) {
            Landing::cleanCache($landingId);
        }
    }

    protected function removeDirectory()
    {
        // Dont remove main page directory
    }
}
