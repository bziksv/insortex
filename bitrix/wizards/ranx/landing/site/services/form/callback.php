<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/**
 * Adding callback form
 */

use Bitrix\Main\Loader;
use Bitrix\Main\SiteTable;
use Bitrix\Main\GroupTable;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('main')) return;
if (!Loader::includeModule('form')) return;

$site = SiteTable::getById(WIZARD_SITE_ID)->fetch();
$lang = $site['LANGUAGE_ID'] ?: 'ru';

Loc::loadMessages(WIZARD_SERVICE_ABSOLUTE_PATH . '/forms.php');

// using old core, 'cause new tables are in 'Internal' namespace now
$formCode = 'ranx_landing_form_callback';
$eventType = 'FORM_FILLING_' . $formCode;

// add Event Type
if (!CEventType::GetList(['TYPE_ID' => $eventType])->SelectedRowsCount()) {
    $oEventType = new CEventType();
    $arFields = [
        'LID'         => $lang,
        'EVENT_NAME'  => $eventType,
        'EVENT_TYPE'  => 'email',
        'NAME'        => Loc::getMessage('CALLBACK_EVENT_NAME'),
        'DESCRIPTION' => Loc::getMessage('CALLBACK_EVENT_DESCRIPTION')
    ];
    $oEventTypeSrcID = $oEventType->Add($arFields);
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
    'SUBJECT'    => Loc::getMessage('CALLBACK_EVENT_MESSAGE_SUBJECT'),
    'MESSAGE'    => Loc::getMessage('CALLBACK_EVENT_MESSAGE_MESSAGE'),
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

// get event message IDs
$arEventMessageIds = [];
$by = 'id'; $order = 'asc';
if ($dbRes = CEventMessage::GetList($by, $order, ['TYPE_ID' => $eventType])) {
    while ($res = $dbRes->GetNext()) {
        $arEventMessageIds[] = $res['ID'];
    }
}

// get form and it's site ids
$formId = false;
$arFormSiteIds = [];

if ($arForm = CForm::GetBySID($formCode)->Fetch()) {
    if (($formId = $arForm['ID']) > 0) { // form exists
        $arFormSiteIds = CForm::GetSiteArray($arForm['ID']);
    }
}
$arFormSiteIds[] = WIZARD_SITE_ID;
$arFormSiteIds = array_unique($arFormSiteIds);

// update or add new form
if ($formId) {
    $arFields = [
        'arSITE'          => $arFormSiteIds,
        'arMAIL_TEMPLATE' => $arEventMessageIds,
    ];

    $formId = CForm::Set($arFields, $formId, 'N');
    if ($formId < 0) {
        return;
    }
} else {
    $extraPerms = [];
    $group = GroupTable::getList([
        'filter' => [
            'STRING_ID' => 'rx_landing_editor',
        ],
    ])->fetch();
    if (!empty($group['ID'])) {
        $extraPerms[$group['ID']] = '30'; // full
    }

    // add new
    $arFields = [
        'NAME'				=> Loc::getMessage('CALLBACK_FORM_NAME'),
        'SID'				=> $formCode,
        'C_SORT'			=> 300,
        'BUTTON'			=> Loc::getMessage('CALLBACK_BUTTON_NAME'),
        'DESCRIPTION'		=> Loc::getMessage('CALLBACK_FORM_DESCRIPTION'),
        'DESCRIPTION_TYPE'	=> 'text',
        'STAT_EVENT1'		=> 'form',
        'STAT_EVENT2'		=> '',
        'arSITE'			=> $arFormSiteIds,
        'arMENU'			=> ['ru' => Loc::getMessage('CALLBACK_FORM_NAME')],
        'arGROUP'			=> ['2' => '10'] + $extraPerms,
        'arMAIL_TEMPLATE'	=> $arEventMessageIds,
    ];
    $formId = CForm::Set($arFields);
    if($formId < 0){
        return;
    }

    // add questions
    $answer = [];
    $answer[] = [
        'MESSAGE'     => ' ',
        'C_SORT'      => 100,
        'ACTIVE'      => 'Y',
        'FIELD_TYPE'  => 'text',
        'FIELD_PARAM' => '',
    ];
    $arFields = [
        'FORM_ID'    => $formId,
        'ACTIVE'     => 'Y',
        'TITLE'      => Loc::getMessage('CALLBACK_FORM_QUESTION_1'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'NAME',
        'C_SORT'     => 100,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'N',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    $answer = [];
    $answer[] = [
        'MESSAGE'     => ' ',
        'C_SORT'      => 100,
        'ACTIVE'      => 'Y',
        'FIELD_TYPE'  => 'text',
        'FIELD_PARAM' => 'class=\'phone\'',
    ];
    $arFields = [
        'FORM_ID'    => $formId,
        'ACTIVE'     => 'Y',
        'TITLE'      => Loc::getMessage('CALLBACK_FORM_QUESTION_2'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'PHONE',
        'C_SORT'     => 200,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'Y',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    $answer = [];
    $answer[] = [
        'MESSAGE'     => ' ',
        'C_SORT'      => 100,
        'ACTIVE'      => 'Y',
        'FIELD_TYPE'  => 'text',
        'FIELD_PARAM' => '',
    ];
    $arFields = [
        'FORM_ID'    => $formId,
        'ACTIVE'     => 'Y',
        'TITLE'      => Loc::getMessage('CALLBACK_FORM_QUESTION_3'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'SOURCE',
        'C_SORT'     => 300,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'N',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    // add status
    $arFields = [
        'FORM_ID'             => $formId,
        'C_SORT'              => 100,
        'ACTIVE'              => 'Y',
        'TITLE'               => 'DEFAULT',
        'DEFAULT_VALUE'       => 'Y',
        'arPERMISSION_VIEW'   => [2],
        'arPERMISSION_MOVE'   => [2],
        'arPERMISSION_EDIT'   => [2],
        'arPERMISSION_DELETE' => [2],
    ];
    (new CFormStatus)->Set($arFields);
}
