<?php

namespace Ranx\Landing;

use Bitrix\Main\ORM\Data;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\Application;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Relations\Reference;

Loc::loadMessages(__FILE__);

class SectionTable extends Data\DataManager
{
    const TYPE_SECTION = 2;
    const TYPE_LANDING = 3;
    const TYPE_MAIN = 4;
    const TYPE_CATALOG = 5;
    const TYPE_NEWS = 6;
    const TYPE_SEARCH = 7;
    const TYPE_ORDER = 8;

    const ROOT_MODE_ELEMENT = \Ranx\Landing\Landing::MODE_ELEMENT;
    const ROOT_MODE_ELEMENTS = \Ranx\Landing\Landing::MODE_ROOT_SECTION;
    const ROOT_MODE_SECTIONS = \Ranx\Landing\Landing::MODE_SECTIONS;

    public static function getTableName()
    {
        return 'ranx_landing_section';
    }

    public static function getTypes()
    {
        return [
            self::TYPE_SECTION => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE_2'),
            self::TYPE_LANDING => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE_3'),
            self::TYPE_MAIN => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE_4'),
            self::TYPE_CATALOG => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE_5'),
            self::TYPE_NEWS => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE_6'),
            self::TYPE_SEARCH => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE_7'),
            self::TYPE_ORDER => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE_8'),
        ];
    }

    public static function getRootModes()
    {
        return [
            self::ROOT_MODE_ELEMENT => Loc::getMessage('RANX_LANDING_SECTION_FILED_ROOT_MODE_ELEMENT'),
            self::ROOT_MODE_SECTIONS => Loc::getMessage('RANX_LANDING_SECTION_FILED_ROOT_MODE_SECTIONS'),
            self::ROOT_MODE_ELEMENTS => Loc::getMessage('RANX_LANDING_SECTION_FILED_ROOT_MODE_ELEMENTS'),
        ];
    }

    public static function getMap()
    {
        $types = array_keys(self::getTypes());
        $rootModes = array_keys(self::getRootModes());

        return [
            'ID' => new Fields\IntegerField('ID', [
				'primary'      => true,
				'autocomplete' => true,
				'title'        => 'ID',
            ]),
            'SITE_ID' => new Fields\StringField('SITE_ID', [
                'title'    => Loc::getMessage('RANX_LANDING_SECTION_FIELD_SITE_ID'),
                'required' => true,
            ]),
            'TITLE' => new Fields\StringField('TITLE', [
                'title'    => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TITLE'),
                'required' => true,
            ]),
            'PATH' => new Fields\StringField('PATH', [
                'title'    => Loc::getMessage('RANX_LANDING_SECTION_FIELD_PATH'),
                'required' => true,
            ]),
            'TYPE' => new Fields\EnumField('TYPE', [
				'title'         => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE'),
				'values'        => $types,
                'default_value' => self::TYPE_SECTION,
                'required'      => true,
            ]),
            'IBLOCK_ID' => new Fields\IntegerField('IBLOCK_ID', [
                'title'    => Loc::getMessage('RANX_LANDING_SECTION_FIELD_IBLOCK_ID'),
                'required' => true,
            ]),
            'LANDING_ID' => new Fields\IntegerField('LANDING_ID', [
                'title'    => Loc::getMessage('RANX_LANDING_SECTION_FIELD_LANDING_ID'),
                'required' => true,
            ]),
            'DOMAIN' => new Fields\StringField('DOMAIN', [
                'title'    => Loc::getMessage('RANX_LANDING_SECTION_FIELD_DOMAIN'),
                'required' => false,
            ]),
            'OWN_SETTINGS' => new Fields\BooleanField('OWN_SETTINGS', [
                'title'    => Loc::getMessage('RANX_LANDING_SECTION_FIELD_OWN_SETTING'),
                'values'   => ['N', 'Y'],
                'default_value' => 'N',
                'required' => false,
            ]),
            'ROOT_MODE' => new Fields\EnumField('ROOT_MODE', [
                'title'         => Loc::getMessage('RANX_LANDING_SECTION_FIELD_ROOT_MODE'),
                'values'        => $rootModes,
                'default_value' => self::ROOT_MODE_ELEMENT,
                'required'      => true,
            ]),
        ];
    }

    public static function createTable()
    {
        $query = '
            CREATE TABLE IF NOT EXISTS ' . self::getTableName() . '(
                ID           int NOT NULL AUTO_INCREMENT,
                SITE_ID      varchar(255) NOT NULL,
                TITLE        varchar(255) NOT NULL,
                PATH         varchar(255) NOT NULL,
                IBLOCK_ID    int NOT NULL,
                LANDING_ID   int NOT NULL,
                TYPE         int NOT NULL,
                DOMAIN       varchar(255),
                OWN_SETTINGS char(1) NOT NULL DEFAULT "N",
                ROOT_MODE    varchar(255) NOT NULL DEFAULT "'.self::ROOT_MODE_ELEMENT.'",
                PRIMARY KEY(`ID`)
            );';

        $connection = Application::getInstance()->getConnection();
        $connection->query($query);
    }

    public static function dropTable()
    {
        $connection = Application::getInstance()->getConnection();
        $connection->dropTable(self::getTableName());
    }

    public static function isTableExists()
    {
        $connection = Application::getInstance()->getConnection();
        return $connection->isTableExists(self::getTableName());
    }

    public static function getDefaultPath()
    {
        return [
            self::TYPE_MAIN => '/',
            self::TYPE_CATALOG => '/catalog/',
            self::TYPE_NEWS => '/news/',
            self::TYPE_SEARCH => '/search/',
            self::TYPE_ORDER => '/order/',
        ];
    }

    public static function getDefaultTitle()
    {
        return [
            self::TYPE_MAIN => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE_4'),
            self::TYPE_CATALOG => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE_5'),
            self::TYPE_NEWS => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE_6'),
            self::TYPE_SEARCH => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE_7'),
            self::TYPE_ORDER => Loc::getMessage('RANX_LANDING_SECTION_FIELD_TYPE_8'),
        ];
    }
}
