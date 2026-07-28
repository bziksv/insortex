<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Ranx\Landing\Preset;
use Ranx\Landing\Landing;
use Ranx\Landing\Helpers;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Section\Manager as SectionManager;

$wizard =& $this->GetWizard();
$siteType = $wizard->GetVar('RX_SITE_TYPE');
$code = $wizard->GetVar('RX_MAIN_PRESET');

if (!Loader::includeModule('iblock')) return;
if (empty($siteType) || $siteType == 'empty') return;
if (!defined('WIZARD_SITE_ID')) return;

Helpers\SiteTemplate::set(WIZARD_SITE_ID);
$newSectionId = SectionManager::add(
    [
        'TYPE' => SectionTable::TYPE_MAIN,
        'ROOT_MODE' => SectionTable::ROOT_MODE_ELEMENT,
        'SITE_ID' => WIZARD_SITE_ID,
        'TITLE' => Loc::getMessage('RX_LANDING_WZ_SERVICES_MAIN_TITLE'),
        'PATH' => '/',
    ],
    [
        'PATH_FORCE_REPLACE' => true,
    ]
);

if (empty($code) || $code == 'empty' || empty($newSectionId)) {
    return;
}

$arSection = SectionTable::getByPrimary($newSectionId)->fetchObject();
$landingId = $arSection['LANDING_ID'];
$mode = Landing::MODE_ELEMENT;

Preset::apply($code, $landingId, $mode, WIZARD_SITE_ID);
