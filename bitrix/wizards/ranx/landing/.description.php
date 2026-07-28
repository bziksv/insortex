<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if(!defined('WIZARD_DEFAULT_SITE_ID') && !empty($_REQUEST['wizardSiteID']))
    define('WIZARD_DEFAULT_SITE_ID', $_REQUEST['wizardSiteID']);

define('RX_PARTNER_NAME', 'ranx');
define('RX_MODULE_NAME_SHORT', 'landing');
define('RX_MODULE_NAME', RX_PARTNER_NAME . '.' . RX_MODULE_NAME_SHORT);

if (!Loader::includeModule(RX_MODULE_NAME)) {
    die(RX_MODULE_NAME . ' is not installed!');
}

$arWizardDescription = [
    'NAME'        => Loc::getMessage('PORTAL_WIZARD_NAME'),
    'DESCRIPTION' => Loc::getMessage('PORTAL_WIZARD_DESC'),
    'VERSION'     => '1.0.0',
    'START_TYPE'  => 'WINDOW',
    'WIZARD_TYPE' => 'INSTALL',
    'IMAGE'       => '/images/' . LANGUAGE_ID . '/solution.png',
    'PARENT'      => 'wizard_sol',
    'TEMPLATES'   => [
        ['SCRIPT' => 'scripts/template.php', 'CLASS' => 'RanxTemplate'],
    ],
    'STEPS'       => [],
];

$arWizardDescription['STEPS'] = [
    'SelectSiteTypeStep',
    'MainConfigStep',
    'PagesConfigStep',
    'PrepareInstallStep',
    'DataInstallStep',
    'FinishStep'
];

if (!defined('WIZARD_DEFAULT_SITE_ID')) {
    array_unshift($arWizardDescription['STEPS'], 'SelectSiteStep');
}
