<?php


namespace Ranx\Landing\Utils;

use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Utils\Iblock as RxIblock;
use Ranx\Landing\Utils\Property as RxProperty;

class XmlReader
{
    public $path = '';
    public $xmlObj = NULL;

    const DEFAULT_PROPERTIES = [
        'CML2_ACTIVE',
        'CML2_CODE',
        'CML2_SORT',
        'CML2_ACTIVE_FROM',
        'CML2_ACTIVE_TO',
        'CML2_PREVIEW_TEXT',
        'CML2_DETAIL_TEXT',
        'CML2_PREVIEW_PICTURE',
    ];

    public function __construct($code)
    {
        $fileName = $code;
        if (strpos($fileName, 'ranx_landing_') === 0) {
            $fileName = substr($fileName, strlen('ranx_landing_'));
        }
        $this->path = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/ranx.landing/install/wizards';
        $this->path .= '/ranx/landing/site/services/iblock/xml/ru/'.$fileName.'.xml';
    }

    public function readProperties()
    {
        $result = [];
        if (!$this->readFile()) {
            echo self::mess('READ_FILE_ERROR', ['#FILE#' => $this->path]);
            return $result;
        }

        $classifierTag = self::mess('METADATA');
        $propertiesTag = self::mess('PROPERTIES');
        $propertyTag = self::mess('PROPERTY');

        $properties = $this->xmlObj->{$classifierTag}->{$propertiesTag}->{$propertyTag};
        foreach ($properties as $property) {
            $propertyCode = (string)$property->{self::mess('ID')};
            if (in_array($propertyCode, self::DEFAULT_PROPERTIES)) {
                continue;
            }
            $formattedProperty = $this->formatProperty($property);
            $result[] = new RxProperty($formattedProperty);
        }
        return $result;
    }

    public function formatProperty($xmlProperty)
    {
        $result = [];

        foreach ($xmlProperty as $name => $value) {
            if ($name !== self::mess('CHOICE_VALUES')) {
                $value = reset($value);
            }

            switch ($name) {
                case self::mess('ID'):                       $result['XML_ID']             = $value; break;
                case self::mess('NAME'):                     $result['NAME']               = $value; break;
                case self::mess('MULTIPLE'):                 $result['MULTIPLE']           = $value === 'true' ? 'Y' : 'N'; break;
                case self::mess('BX_SORT'):                  $result['SORT']               = $value; break;
                case self::mess('BX_CODE'):                  $result['CODE']               = $value; break;
                case self::mess('BX_PROPERTY_TYPE'):         $result['PROPERTY_TYPE']      = $value; break;
                case self::mess('BX_ROWS'):                  $result['ROW_COUNT']          = $value; break;
                case self::mess('BX_COLUMNS'):               $result['COL_COUNT']          = $value; break;
                case self::mess('BX_LIST_TYPE'):             $result['LIST_TYPE']          = $value; break;
                case self::mess('BX_FILE_EXT'):              $result['FILE_TYPE']          = $value; break;
                case self::mess('BX_FIELDS_COUNT'):          $result['MULTIPLE_CNT']       = $value; break;
                case self::mess('BX_LINKED_IBLOCK'):         $result['LINK_IBLOCK_ID']     = RxIblock::getIblockIdByXmlId($value) ?? 0; break;
                case self::mess('BX_WITH_DESCRIPTION'):      $result['WITH_DESCRIPTION']   = $value === 'true' ? 'Y' : 'N'; break;
                case self::mess('BX_SEARCH'):                $result['SEARCHABLE']         = $value === 'true' ? 'Y' : 'N'; break;
                case self::mess('BX_FILTER'):                $result['FILTRABLE']          = $value === 'true' ? 'Y' : 'N'; break;
                case self::mess('BX_USER_TYPE'):             $result['USER_TYPE']          = $value; break;
                case self::mess('BX_DEFAULT_VALUE'):         $result['DEFAULT_VALUE']      = $value; break;
                case self::mess('SERIALIZED'):               $result['SERIALIZED']         = $value === '1' ? 'Y' : 'N'; break;
                case self::mess('BX_PROPERTY_FEATURE_LIST'): $result['FEATURES']           = unserialize(htmlspecialcharsBack($value)); break;
                case self::mess('BX_IS_REQUIRED'):           $result['IS_REQUIRED']        = $value === 'true' ? 'Y' : 'N'; break;
                case self::mess('BX_USER_TYPE_SETTINGS'):    $result['USER_TYPE_SETTINGS'] = unserialize(htmlspecialcharsBack($value)); break;
                case self::mess('CHOICE_VALUES'):            $result['VALUES']             = $this->formatPropertyEnum($value); break;
                default: echo self::mess('UNKNOWN_FIELD', ['#NAME#' => $name]); break;
            }
        }
        if ($result['SERIALIZED'] === 'Y') {
            $result['DEFAULT_VALUE'] = unserialize(htmlspecialcharsBack($result['DEFAULT_VALUE']));
        }
        // default
        $result['ACTIVE'] = 'Y';
        $result['TMP_ID'] = NULL;
        $result['VERSION'] = 1;

        return $result;
    }

    public function formatPropertyEnum($enums)
    {
        $result = [];

        foreach ($enums as $nodeName => $enum) {
            if ($nodeName === self::mess('VALUE')) {
                continue;
            }

            $arEnum = [];
            foreach ($enum as $name => $value) {
                $value = reset($value);
                switch ($name) {
                    case self::mess('ID'):         $arEnum['XML_ID'] = $value; break;
                    case self::mess('VALUE'):      $arEnum['VALUE']  = $value; break;
                    case self::mess('BY_DEFAULT'): $arEnum['DEF']    = $value === 'true' ? 'Y' : 'N'; break;
                    case self::mess('SORT'):       $arEnum['SORT']   = $value; break;
                }
            }
            $result[] = $arEnum;
        }

        return $result;
    }

    public function readFile()
    {
        if (!$this->isCorrectPath()) {
            return false;
        }
        if ($this->xmlObj != NULL) {
            return true;
        }

        $file = file_get_contents($this->path);
        $this->xmlObj = new \SimpleXmlElement($file);
        return $this->xmlObj != NULL;
    }

    public function isCorrectPath()
    {
        return file_exists($this->path);
    }

    protected static function mess($code, $replace = null)
    {
        return Loc::getMessage('RX_LANDING_LIB_XML_READER_'.$code, $replace);
    }
}
