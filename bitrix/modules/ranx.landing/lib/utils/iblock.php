<?php


namespace Ranx\Landing\Utils;

use Bitrix\Iblock\IblockTable;
use Ranx\Landing\Utils\Property as RxProperty;

class Iblock
{
    const IBLOCK_TYPE = 'ranx_landing';
    public $info = [];

    public function __construct($iblock)
    {
        if (empty($iblock)) {
            return;
        }

        if (is_array($iblock)) {
            $this->info = $iblock;
            return;
        }

        $this->info = \Ranx\Landing\Helpers\Iblock::getIblockByCode($iblock);
    }

    public function isEmpty()
    {
        return empty($this->info);
    }

    public function getProperties()
    {
        $result = [];

        $dbObj = \CIBlockProperty::GetList(['ID' => 'ASC'], ['IBLOCK_ID' => $this->info['ID']]);
        while ($property = $dbObj->Fetch()) {
            $result[] = new RxProperty($property);
        }

        return $result;
    }

    public static function getIblocksInfo()
    {
        return IblockTable::getList([
            'filter' => [
                'IBLOCK_TYPE_ID' => self::IBLOCK_TYPE,
                '!CODE' => 'ranx_landing_list_%',
            ]
        ])->fetchAll();
    }

    public static function getIblockIdByXmlId($xmdId)
    {
        if (empty($xmdId)) {
            return NULL;
        }

        $iblock = IblockTable::getList([
            'filter' => [
                'XML_ID' => $xmdId,
                'IBLOCK_TYPE_ID' => self::IBLOCK_TYPE
            ]
        ])->fetch();
        return !empty($iblock) ? $iblock['ID'] : NULL;
    }

    public function addProperty($property)
    {
        $property = $property->copy();
        $property->formatToSave();
        $arFields = $property->info;
        $arFields['IBLOCK_ID'] = $this->info['ID'];

        $iblockPropertyObj = new \CIBlockProperty();
        return $iblockPropertyObj->Add($arFields);
    }

    public function removeProperty($property)
    {
        \CIBlockProperty::Delete($property->get('ID'));
    }

    public function updateProperty($propertyId, $property)
    {
        $property = $property->copy();
        $property->formatToSave();
        $arFields = $property->info;

        $iblockPropertyObj = new \CIBlockProperty();
        return $iblockPropertyObj->Update($propertyId, $arFields);
    }
}
