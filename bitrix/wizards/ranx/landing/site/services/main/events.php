<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/**
 * Adding Event Type and Event Message for forms (for iblocks)
 */

use Bitrix\Main\Loader;
use Bitrix\Main\SiteTable;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('main')) return;

$site = SiteTable::getById(WIZARD_SITE_ID)->fetch();
$lang = $site['LANGUAGE_ID'] ?: 'ru';

// \WizardServices::IncludeServiceLang('forms.php', $lang); // this doesn't work with Loc class
Loc::loadMessages(WIZARD_SERVICE_ABSOLUTE_PATH . '/events.php');

// using old core, 'cause new tables are in 'Internal' namespace now
$eventType = 'RANX_LANDING_FORM';

// add Event Type
if ($dbRes = CEventType::GetList(['TYPE_ID' => $eventType])) {
    $eventTypeCount = $dbRes->SelectedRowsCount();
    if(!$eventTypeCount){
        $oEventType = new CEventType();
        $arFields = [
            'LID'         => $lang,
            'EVENT_NAME'  => $eventType,
            'EVENT_TYPE'  => 'email',
            'NAME'        => Loc::getMessage('EVENT_NAME'),
            'DESCRIPTION' => Loc::getMessage('EVENT_DESCRIPTION')
        ];
        $oEventTypeSrcID = $oEventType->Add($arFields);
    }
}

// add Event Message
$oEventMessage = new CEventMessage();
$by = 'id'; $order = 'asc';
$arFields = [
    'ACTIVE'     => 'Y',
    'EVENT_NAME' => $eventType,
    'LID'        => WIZARD_SITE_ID,
    'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
    'EMAIL_TO'   => '#DEFAULT_EMAIL_FROM#',
    'SUBJECT'    => Loc::getMessage('EVENT_MESSAGE_SUBJECT'),
    'MESSAGE'    => Loc::getMessage('EVENT_MESSAGE_MESSAGE'),
    'BODY_TYPE'  => 'html',
];
$dbRes = CEventMessage::GetList($by, $order, ['TYPE_ID' => $eventType, 'SITE_ID' => [WIZARD_SITE_ID]]);
$eventMessageCount = $dbRes->SelectedRowsCount();
if ($eventMessageCount > 0) {
    while($res = $dbRes->GetNext()) {
        $oEventMessage->Update($res['ID'], $arFields);
    }
} else {
    $oEventMessage->Add($arFields);
}

$eventType = 'RANX_LANDING_SALE_ORDER';

// add Event Type
if ($dbRes = CEventType::GetList(['TYPE_ID' => $eventType])) {
    $eventTypeCount = $dbRes->SelectedRowsCount();
    if(!$eventTypeCount){
        $oEventType = new CEventType();
        $arFields = [
            'LID'         => $lang,
            'EVENT_NAME'  => $eventType,
            'EVENT_TYPE'  => 'email',
            'NAME'        => Loc::getMessage('SALE_ORDER_EVENT_NAME'),
            'DESCRIPTION' => Loc::getMessage('SALE_ORDER_EVENT_DESCRIPTION')
        ];
        $oEventTypeSrcID = $oEventType->Add($arFields);
    }
}

// add Event Message
$oEventMessage = new CEventMessage();
$by = 'id'; $order = 'asc';
$arFields = [
    'ACTIVE'     => 'Y',
    'EVENT_NAME' => $eventType,
    'LID'        => WIZARD_SITE_ID,
    'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
    'EMAIL_TO'   => '#DEFAULT_EMAIL_FROM#',
    'SUBJECT'    => Loc::getMessage('SALE_ORDER_EVENT_MESSAGE_SUBJECT'),
    'MESSAGE'    => Loc::getMessage('SALE_ORDER_EVENT_MESSAGE_MESSAGE'),
    'BODY_TYPE'  => 'html',
];
$dbRes = CEventMessage::GetList($by, $order, ['TYPE_ID' => $eventType, 'SITE_ID' => [WIZARD_SITE_ID]]);
$eventMessageCount = $dbRes->SelectedRowsCount();
if ($eventMessageCount > 0) {
    while($res = $dbRes->GetNext()) {
        $oEventMessage->Update($res['ID'], $arFields);
    }
} else {
    $oEventMessage->Add($arFields);
}
