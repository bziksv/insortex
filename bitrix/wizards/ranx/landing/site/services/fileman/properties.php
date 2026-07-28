<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/**
 * Adding property types for site
 */

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(WIZARD_SERVICE_ABSOLUTE_PATH . '/properties.php');

$serviceId = 'fileman';
$isDifferentSetting = Option::get($serviceId, 'different_set', '', '');
$siteId = $isDifferentSetting === 'Y' ? WIZARD_SITE_ID : '';
$addingPropTypesList = ['MENU_SHOW_SECTIONS', 'MENU_SHOW_ELEMENTS'];

$propTypes = Option::get($serviceId, 'propstypes', '', $siteId);
$arPropTypes = unserialize(stripslashes($propTypes));
if (empty($arPropTypes)) {
    return;
}

foreach ($addingPropTypesList as $types) {
    if (empty($arPropTypes[$types])) {
        $arPropTypes[$types] = Loc::getMessage($types.'_TITLE');
    }
}

$newPropTypes = serialize($arPropTypes);
Option::set($serviceId, 'propstypes', $newPropTypes, $siteId);
