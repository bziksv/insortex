<?php


namespace Ranx\Landing\Utils;

use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Utils\XmlReader as RxXmlReader;
use Ranx\Landing\Utils\Iblock as RxIblock;

class ReportMaker
{
    public $iblockCode = '';
    public $propsFromIblock = NULL;
    public $propsFromFile = NULL;

    public function __construct($iblockCode)
    {
        $this->iblockCode = $iblockCode;
    }

    public function makeReport()
    {
        $report = [];

        $report = array_merge($report, $this->makeDuplicatePropsReport());
        $report = array_merge($report, $this->makeIrrelevantPropsReport());
        $report = array_merge($report, $this->makeNewPropsReport());
        $report = array_merge($report, $this->makeDifferencesPropsReport());

        return $report;
    }

    public function makeDuplicatePropsReport()
    {
        $this->loadPropertiesFromIblock();
        $propertyCodes = $this->selectField($this->propsFromIblock, 'CODE');

        $duplicateCodes = [];
        foreach (array_count_values($propertyCodes) as $code => $count) {
            if ($count > 1) {
                $duplicateCodes[] = $code;
            }
        }

        $report = [];
        foreach ($duplicateCodes as $code) {
            $report[] = [
                'TYPE' => Loc::getMessage('RX_LANDING_LIB_REPORT_MAKER_DUPLICATE'),
                'CODE' => $code,
            ];
        }
        return $report;
    }

    public function makeIrrelevantPropsReport()
    {
        $this->loadPropertiesFromIblock();
        $this->loadPropertiesFromXmlFile();
        $iblockPropertyCodes = array_unique($this->selectField($this->propsFromIblock, 'CODE'));
        $filePropertyCodes = $this->selectField($this->propsFromFile, 'CODE');
        $irrelevantPropertyCodes = array_diff($iblockPropertyCodes, $filePropertyCodes);

        $report = [];
        foreach ($irrelevantPropertyCodes as $code) {
            $report[] = [
                'TYPE' => Loc::getMessage('RX_LANDING_LIB_REPORT_MAKER_IRRELEVANT_PROP'),
                'CODE' => $code,
            ];
        }
        return $report;
    }

    public function makeNewPropsReport()
    {
        $this->loadPropertiesFromIblock();
        $this->loadPropertiesFromXmlFile();
        $iblockPropertyCodes = $this->selectField($this->propsFromIblock, 'CODE');
        $filePropertyCodes = $this->selectField($this->propsFromFile, 'CODE');
        $newPropertyCodes = array_diff($filePropertyCodes, $iblockPropertyCodes);

        $report = [];
        foreach ($newPropertyCodes as $code) {
            $report[] = [
                'TYPE' => Loc::getMessage('RX_LANDING_LIB_REPORT_MAKER_MISSING_PROP'),
                'CODE' => $code,
            ];
        }
        return $report;
    }

    public function makeDifferencesPropsReport()
    {
        $this->loadPropertiesFromIblock();
        $this->loadPropertiesFromXmlFile();
        $filePropertyCodes = $this->selectField($this->propsFromFile, 'CODE');

        $verifiedPropertyCodes = [];
        $report = [];
        foreach ($this->propsFromIblock as $iblockProperty) {
            $propertyCode = $iblockProperty->get('CODE');
            $key = array_search($propertyCode, $filePropertyCodes);
            if ($key === false || in_array($propertyCode, $verifiedPropertyCodes)) {
                continue;
            }

            $fileProperty = $this->propsFromFile[$key];
            $diffFields = $iblockProperty->compare($fileProperty);
            if (!empty($diffFields)) {
                $iblockValues = '';
                $fileValues = '';
                foreach ($diffFields as $diffField) {
                    $iblockValues .= $iblockProperty->toStringField($diffField);
                    $fileValues .= $fileProperty->toStringField($diffField);
                }

                $report[] = [
                    'TYPE' => Loc::getMessage('RX_LANDING_LIB_REPORT_MAKER_DIFFERENCES_PROP'),
                    'CODE' => $propertyCode,
                    'DIFF_FIELDS' => $diffFields,
                    'IBLOCK_VALUES' => $iblockValues,
                    'FILE_VALUES' => $fileValues,
                ];
            }
            $verifiedPropertyCodes[] = $propertyCode;
        }

        return $report;
    }

    public function loadPropertiesFromIblock()
    {
        $iblock = new RxIblock($this->iblockCode);
        $this->propsFromIblock = $iblock->getProperties();
    }

    public function loadPropertiesFromXmlFile()
    {
        if ($this->propsFromFile !== NULL) {
            return;
        }

        $reader = new RxXmlReader($this->iblockCode);
        $this->propsFromFile = $reader->readProperties();
    }

    public function selectField($properties, $fieldName)
    {
        $result = [];
        foreach ($properties as $property) {
            $result[] = $property->get($fieldName);
        }
        return $result;
    }
}
