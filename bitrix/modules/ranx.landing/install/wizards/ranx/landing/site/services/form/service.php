<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\SiteTable;
use Bitrix\Main\GroupTable;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('main')) return;
if (!Loader::includeModule('form')) return;

$site = SiteTable::getById(WIZARD_SITE_ID)->fetch();
$lang = $site['LANGUAGE_ID'] ?: 'ru';

Loc::loadMessages(WIZARD_SERVICE_ABSOLUTE_PATH . '/forms.php');

$formCode = 'ranx_landing_service';
$eventType = 'FORM_FILLING_' . $formCode;

// add Event Type
if (!CEventType::GetList(['TYPE_ID' => $eventType])->SelectedRowsCount()) {
    $oEventType = new CEventType();
    $arFields = [
        'LID'         => $lang,
        'EVENT_NAME'  => $eventType,
        'EVENT_TYPE'  => 'email',
        'NAME'        => Loc::getMessage('SERVICE_EVENT_NAME'),
        'DESCRIPTION' => Loc::getMessage('SERVICE_EVENT_DESCRIPTION')
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
    'SUBJECT'    => Loc::getMessage('SERVICE_EVENT_MESSAGE_SUBJECT'),
    'MESSAGE'    => Loc::getMessage('SERVICE_EVENT_MESSAGE_MESSAGE'),
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
        'NAME'				=> Loc::getMessage('SERVICE_FORM_NAME'),
        'SID'				=> $formCode,
        'C_SORT'			=> 300,
        'BUTTON'			=> Loc::getMessage('SERVICE_BUTTON_NAME'),
        'DESCRIPTION'		=> Loc::getMessage('SERVICE_FORM_DESCRIPTION'),
        'DESCRIPTION_TYPE'	=> 'text',
        'STAT_EVENT1'		=> 'form',
        'STAT_EVENT2'		=> '',
        'arSITE'			=> $arFormSiteIds,
        'arMENU'			=> ['ru' => Loc::getMessage('SERVICE_FORM_NAME')],
        'arGROUP'			=> ['2' => '10'] + $extraPerms,
        'arMAIL_TEMPLATE'	=> $arEventMessageIds,
    ];
    $formId = CForm::Set($arFields);
    if($formId < 0){
        return;
    }

    // add questions WEEK_DAY
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
        'TITLE'      => Loc::getMessage('SERVICE_FORM_QUESTION_1'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'WEEK_DAY',
        'C_SORT'     => 100,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'N',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    // add questions TAB
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
        'TITLE'      => Loc::getMessage('SERVICE_FORM_QUESTION_2'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'TAB',
        'C_SORT'     => 200,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'N',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    // add questions CATEGORY
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
        'TITLE'      => Loc::getMessage('SERVICE_FORM_QUESTION_3'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'CATEGORY',
        'C_SORT'     => 300,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'N',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    // add questions YEARS
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
        'TITLE'      => Loc::getMessage('SERVICE_FORM_QUESTION_4'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'YEARS',
        'C_SORT'     => 400,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'N',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    // add questions INTERVAL_TIME
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
        'TITLE'      => Loc::getMessage('SERVICE_FORM_QUESTION_5'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'INTERVAL_TIME',
        'C_SORT'     => 500,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'N',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    // add questions PERSON_NAME
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
        'TITLE'      => Loc::getMessage('SERVICE_FORM_QUESTION_6'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'PERSON_NAME',
        'C_SORT'     => 600,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'N',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    // add questions NAME
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
        'TITLE'      => Loc::getMessage('SERVICE_FORM_QUESTION_7'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'NAME',
        'C_SORT'     => 700,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'Y',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    // add questions FIO
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
        'TITLE'      => Loc::getMessage('SERVICE_FORM_QUESTION_8'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'FIO',
        'C_SORT'     => 800,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'Y',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    // add questions EMAIL
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
        'TITLE'      => Loc::getMessage('SERVICE_FORM_QUESTION_9'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'EMAIL',
        'C_SORT'     => 900,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'N',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    // add questions PHONE
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
        'TITLE'      => Loc::getMessage('SERVICE_FORM_QUESTION_10'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'PHONE',
        'C_SORT'     => 1000,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'Y',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    // add questions COMMENT
    $answer = [];
    $answer[] = [
        'MESSAGE'     => ' ',
        'C_SORT'      => 100,
        'ACTIVE'      => 'Y',
        'FIELD_TYPE'  => 'textarea',
        'FIELD_PARAM' => '',
    ];
    $arFields = [
        'FORM_ID'    => $formId,
        'ACTIVE'     => 'Y',
        'TITLE'      => Loc::getMessage('SERVICE_FORM_QUESTION_11'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'COMMENT',
        'C_SORT'     => 1100,
        'ADDITIONAL' => 'N',
        'REQUIRED'   => 'N',
        'arANSWER'   => $answer,
    ];
    (new CFormField)->Set($arFields);

    // add questions SOURCE
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
        'TITLE'      => Loc::getMessage('SERVICE_FORM_QUESTION_12'),
        'TITLE_TYPE' => 'text',
        'SID'        => 'SOURCE',
        'C_SORT'     => 1200,
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
