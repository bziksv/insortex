<?php


namespace Ranx\Landing\Utils;

use Bitrix\Iblock\PropertyFeatureTable;

class Property
{
    const NO_IMPORTED_FIELDS = ['ID', 'TIMESTAMP_X', 'IBLOCK_ID', 'HINT'];
    public $info = [];

    public function __construct($arProperty)
    {
        $this->info = $arProperty;

        if ($this->get('FEATURES') === NULL) {
            $this->info['FEATURES'] = $this->getFeatures();
        }
        if ($this->isListType() && $this->get('VALUES') === NULL) {
            $this->info['VALUES'] = $this->getEnumValues();
        }
    }

    public function isListType()
    {
        return $this->get('PROPERTY_TYPE') === 'L';
    }

    public function get($fieldName)
    {
        return $this->info[$fieldName];
    }

    public function toStringField($fieldName)
    {
        return $fieldName.': <pre>'.print_r($this->get($fieldName), true).'</pre><br>';
    }

    public function getEnumValues()
    {
        $result = [];

        $dbObj = \CIBlockPropertyEnum::GetList([], [
            'IBLOCK_ID' => $this->info['IBLOCK_ID'],
            'CODE' => $this->info['CODE']
        ]);
        while ($enumValue = $dbObj->Fetch()) {
            $result[] = [
                'XML_ID' => $enumValue['XML_ID'],
                'VALUE' => $enumValue['VALUE'],
                'DEF' => $enumValue['DEF'],
                'SORT' => $enumValue['SORT'],
            ];
        }

        return $result;
    }

    public function getFeatures()
    {
        $features = PropertyFeatureTable::getList([
            'select' => ['ID', 'PROPERTY_ID', 'MODULE_ID', 'FEATURE_ID', 'IS_ENABLED'],
            'filter' => ['PROPERTY_ID' => $this->info['ID']],
            'order' => ['PROPERTY_ID' => 'ASC']
        ])->fetchAll();
        foreach ($features as &$feature) {
            unset($feature['ID']);
            unset($feature['PROPERTY_ID']);
        }
        return $features;
    }

    public function compare($otherProperty)
    {
        $diffFields = [];
        foreach ($this->info as $code => $value) {
            if (in_array($code, self::NO_IMPORTED_FIELDS)) {
                continue;
            }

            $value1 = $value;
            $value2 = $otherProperty->get($code);
            if (is_array($value1)) {
                array_multisort($value1);
            }
            if (is_array($value2)) {
                array_multisort($value2);
            }

            if ($value1 != $value2) {
                $diffFields[] = $code;
            }
        }
        return $diffFields;
    }

    public function copy()
    {
        return new self($this->info);
    }

    public function formatToSave()
    {
        foreach (self::NO_IMPORTED_FIELDS as $field) {
            unset($this->info[$field]);
        }
    }
}
