<?php


namespace Ranx\Landing\Utils;

use Ranx\Landing\Utils\XmlReader as RxXmlReader;
use Ranx\Landing\Utils\ReportMaker as RxReportMaker;
use Ranx\Landing\Utils\Iblock as RxIblock;

class PropertySync
{
    public $iblockCode = '';
    public $iblock = NULL;
    public $reportMaker = NULL;

    public function __construct($iblockCode)
    {
        $this->iblockCode = $iblockCode;
        if (!empty($this->iblockCode)) {
            $this->iblock = new RxIblock($this->iblockCode);
            $this->reportMaker = new RxReportMaker($this->iblockCode);
        }
    }

    public function removeDuplicates()
    {
        if (empty($this->iblock)) {
            return false;
        }
        $properties = $this->iblock->getProperties();
        $duplicateCodes = array_column($this->reportMaker->makeDuplicatePropsReport(), 'CODE');
        $map = [];

        foreach ($properties as $property) {
            $code = $property->get('CODE');
            if (empty($map[$code])) {
                $map[$code] = $property;
                continue;
            }
            if (!in_array($code, $duplicateCodes)) {
                echo 'Error deleting duplicate with code '.$code;
                continue;
            }
            $this->iblock->removeProperty($property);
        }
        return true;
    }

    public function removeIrrelevant()
    {
        if (empty($this->iblock)) {
            return false;
        }
        $properties = $this->iblock->getProperties();
        $irrelevantCode = array_column($this->reportMaker->makeIrrelevantPropsReport(), 'CODE');

        foreach ($properties as $property) {
            $code = $property->get('CODE');
            if (!in_array($code, $irrelevantCode)) {
                continue;
            }
            $this->iblock->removeProperty($property);
        }
        return true;
    }

    public function addNew()
    {
        $reader = new RxXmlReader($this->iblockCode);
        if (empty($this->iblock) || !$reader->readFile()) {
            return false;
        }
        $properties = $reader->readProperties();
        $newCode = array_column($this->reportMaker->makeNewPropsReport(), 'CODE');

        foreach ($properties as $property) {
            $code = $property->get('CODE');
            if (!in_array($code, $newCode)) {
                continue;
            }
            $this->iblock->addProperty($property);
        }
        return true;
    }

    public function syncDifferences()
    {
        $reader = new RxXmlReader($this->iblockCode);
        if (empty($this->iblock) || !$reader->readFile()) {
            return false;
        }
        $fileProperties = $reader->readProperties();
        $iblockProperties = $this->iblock->getProperties();
        $diffCodes = array_column($this->reportMaker->makeDifferencesPropsReport(), 'CODE');

        foreach ($iblockProperties as $iblockProperty) {
            $code = $iblockProperty->get('CODE');
            $id = $iblockProperty->get('ID');
            $propertyForUpdate = NULL;
            foreach ($fileProperties as $fileProperty) {
                if ($code === $fileProperty->get('CODE')) {
                    $propertyForUpdate = $fileProperty;
                    break;
                }
            }
            if (!in_array($code, $diffCodes) || empty($propertyForUpdate) || empty($id)) {
                continue;
            }
            $this->iblock->updateProperty($id, $propertyForUpdate);
        }
        return true;
    }
}
