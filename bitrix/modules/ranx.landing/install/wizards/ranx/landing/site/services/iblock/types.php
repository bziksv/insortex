<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Iblock\TypeTable;
use Bitrix\Iblock\TypeLanguageTable;
use Bitrix\Main\Localization\LanguageTable;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('iblock')) return;

$arTypes = [
    [
        'ID'       => 'ranx_landing',
        'SECTIONS' => 'Y',
        'IN_RSS'   => 'N',
        'SORT'     => 10,
        'LANG'     => [],
    ],
];

// get languages
$arLanguages = LanguageTable::getList()->fetchAll();
$arLanguageLids = array_column($arLanguages, 'LID');

// add iblock types
foreach ($arTypes as $arType) {
    // add language vars
    foreach ($arLanguageLids as $langLid) {
        $lang = in_array($langLid, ['ru', 'en']) ? $langLid : 'en';

        Loc::loadMessages(WIZARD_SERVICE_ABSOLUTE_PATH . '/types.php');

        $code = strtoupper($arType['ID'] . '_' . $lang);

        if (TypeLanguageTable::getByPrimary(['IBLOCK_TYPE_ID' => $arType['ID'], 'LANGUAGE_ID' => $lang])->fetch()) {
            TypeLanguageTable::update(
                [
                    'IBLOCK_TYPE_ID'  => $arType['ID'],
                    'LANGUAGE_ID'     => $lang,
                ],
                [
                    'NAME'            => Loc::getMessage($code . '_TYPE_NAME'),
                    'ELEMENTS_NAME'   => Loc::getMessage($code . '_ELEMENTS_NAME'),
                    'SECTIONS_NAME'   => Loc::getMessage($code . '_SECTIONS_NAME'),
                ]
            );
        } else {
            TypeLanguageTable::add([
                'IBLOCK_TYPE_ID'  => $arType['ID'],
                'LANGUAGE_ID'     => $lang,
                'NAME'            => Loc::getMessage($code . '_TYPE_NAME'),
                'ELEMENTS_NAME'   => Loc::getMessage($code . '_ELEMENTS_NAME'),
                'SECTIONS_NAME'   => Loc::getMessage($code . '_SECTIONS_NAME'),
            ]);
        }
    }

    if (TypeTable::getById($arType['ID'])->fetch()) continue; // already exists

    TypeTable::add($arType);
}

// clear cache (d7 doesn't do that)
$cache = \Bitrix\Main\Application::getInstance()->getManagedCache();
$cache->cleanDir('b_iblock_type');

// set combined list mode in iblocks
//Option::set('iblock', 'combined_list_mode', 'Y');
