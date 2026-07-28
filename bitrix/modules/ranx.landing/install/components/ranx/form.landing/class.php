<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Iblock;
use Bitrix\Main\Mail;
use Bitrix\Main\Loader;
use Ranx\Landing\Event;
use Ranx\Landing\Config;
use Ranx\Landing\Helpers;
use Ranx\Landing\Sale\Payment;
use Ranx\Landing\Sale\Order;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Helpers\FormHelper;
use Bitrix\Main\UserConsent\Agreement;
use Ranx\Landing\Captcha\CaptchaManager;
use Bitrix\Main\Engine\Contract\Controllerable;

/**
 * Component for showing forms on landing
 */
class RanxFormLandingComponent extends CBitrixComponent implements Controllerable
{
    /**
     * NOTE! It is very IMPORTANT to add actions here
     * else they will not work on unauthorized users!
     *
     * @return array
     */
    public function configureActions()
    {
        return [
            'submit' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'getModal' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'getAgreement' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'getPolitics' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
        ];
    }

    public function onPrepareComponentParams($arParams)
    {
        $arParams['FORM_CODE'] = trim($arParams['FORM_CODE']);

        return parent::onPrepareComponentParams($arParams);
    }

    public function executeComponent()
    {
        if (!Loader::includeModule('ranx.landing')) {
            return false;
        }

        $this->arResult = [];

        $this->prepareResult();
        $this->getFormData();

        $this->includeComponentTemplate();
    }

    private function prepareResult()
    {
        // for unique ids
        $this->arResult['RAND'] = $this->randString();

        // agreement
        $this->arResult['USE_AGREEMENT'] = Config::isAgreementEnabled();
        $this->arResult['AGREEMENT_LINK'] = Config::getAgreementLink();
        $this->arResult['AGREEMENT_ACTIVE'] = Config::isAgreementActive();

        // captcha
        $this->arResult['USE_CAPTCHA'] = CaptchaManager::isCaptchaEnabled();
        if ($this->arResult['USE_CAPTCHA'])
            $this->arResult['CAPTCHA_TYPE'] = CaptchaManager::getCurrentCaptchaClass()::getCode();

        // default btn text
        $this->arResult['BTN_TEXT'] = $this->arParams['BTN_TEXT'];

        // max file size
        $this->arResult['MAX_FILE_SIZE'] = Config::getMaxFileSize();

        // oneclick
        $this->arParams['IS_ONECLICK'] = false;
        if ($this->arParams['FORM_CODE'] === 'ranx_landing_form_oneclick') {
            $this->arParams['FORM_CODE'] = 'ranx_landing_sale_order';
            $this->arParams['IS_ONECLICK'] = true;
        }

        //service form
        $this->arParams['IS_SERVICE'] = false;
        if ($this->arParams['FORM_CODE'] == 'ranx_landing_service') {
            $this->arParams['IS_SERVICE'] = true;
        }
    }

    private function getFormData()
    {
        $this->arResult['FIELDS'] = [];

        try {
            if (FormHelper::isB24Form($this->arParams['FORM_CODE'])) {
                $this->arResult['B24'] = $this->getB24Form();
            } elseif (Config::isWebFormsEnabled()) {
                $this->arResult['FIELDS'] = $this->getFieldsWebForm();
            } else {
                $this->arResult['FIELDS'] = $this->getFieldsIblock();
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">' . Loc::getMessage('FORM_IS_NOT_FOUND') . '</div>';
        }

        if ($this->arParams['IS_ONECLICK']) {
            $this->formatFieldsOneclick();
        }
    }

    private function getB24Form()
    {
        $forms = Config::getB24Forms();
        if (!isset($forms[$this->arParams['FORM_CODE']])) {
            throw new Exception();
        }

        return $forms[$this->arParams['FORM_CODE']];
    }

    private function getFieldsIblock()
    {
        $result = [];

        $iblock = Helpers\Iblock::getIblockByCode($this->arParams['FORM_CODE']);
        if (!$iblock) {
            throw new Exception();
        }

        $props = Iblock\PropertyTable::getList([
            'order' => [
                'SORT' => 'ASC',
                'ID' => 'ASC',
            ],
            'filter' => [
                'IBLOCK_ID' => $iblock['ID'],
                'ACTIVE' => 'Y',
            ],
        ])->fetchAll();

        if (empty($this->arResult['BTN_TEXT'])) {
            $this->arResult['BTN_TEXT'] = Loc::getMessage('DEFAULT_FORM_BTN_TEXT');
        }

        // get values from properties with type list
        foreach ($props as $prop) {
            $field = [
                'CODE' => $prop['CODE'],
                'NAME' => $prop['NAME'],
                'IS_REQUIRED' => $prop['IS_REQUIRED'],
            ];

            if ($prop['PROPERTY_TYPE'] == 'L') {
                if ($prop['LIST_TYPE'] == 'C') {
                    $field['TYPE'] = $prop['MULTIPLE'] == 'Y' ? 'checkbox' : 'radio';
                } else {
                    $field['TYPE'] = $prop['MULTIPLE'] == 'Y' ? 'multiselect' : 'dropdown';
                }
            } elseif ($prop['PROPERTY_TYPE'] == 'F') {
                $field['TYPE'] = 'file';
                $field['MAX_SIZE'] = $this->arResult['MAX_FILE_SIZE'];
                $field['FILE_TYPE'] = Helpers\File::formatExt($prop['FILE_TYPE']);
            } elseif ($prop['USER_TYPE'] == 'HTML') {
                $field['TYPE'] = 'textarea';
            } else {
                $field['TYPE'] = 'text';
            }

            $field['IS_PHONE'] = strpos($prop['CODE'], 'PHONE') !== false;
            $field['IS_EMAIL'] = strpos($prop['CODE'], 'EMAIL') !== false;
            $field['IS_CITY'] = strpos($field['CODE'], 'CITY') !== false;
            $field['IS_SOURCE'] = strpos($prop['CODE'], 'SOURCE') === 0;
            $field['IS_DISABLED'] = false;

            if ($prop['CODE'] == 'SUBJECT' && !empty($this->arParams['SUBJECT'])) {
                $field['IS_DISABLED'] = true;
                $field['VALUE'] = trim(strip_tags($this->arParams['SUBJECT']));
            }

            if ($prop['PROPERTY_TYPE'] == 'L') {
                $field['VALUES'] = Iblock\PropertyEnumerationTable::getList([
                    'order' => [
                        'SORT' => 'ASC',
                        'ID' => 'ASC',
                    ],
                    'filter' => [
                        'PROPERTY_ID' => $prop['ID'],
                    ],
                ])->fetchAll();
            }

            $result[$prop['CODE']] = $field;
        }

        return $result;
    }

    private function getFieldsWebForm()
    {
        if (!Loader::includeModule('form')) {
            throw new Exception();
        }

        $form = \CForm::GetBySID($this->arParams['FORM_CODE'])->Fetch();
        if (!$form) {
            throw new Exception();
        }

        if (empty($this->arResult['BTN_TEXT'])) {
            $this->arResult['BTN_TEXT'] = !empty($form['BUTTON']) ? $form['BUTTON'] : Loc::getMessage('DEFAULT_FORM_BTN_TEXT');
        }

        $result = [];
        $isFiltered = false;

        $by = 's_sort';
        $order = 'asc';
        $rsQuestions = \CFormField::GetList($form['ID'], 'N', $by, $order, ['ACTIVE' => 'Y'], $isFiltered);
        while ($arQuestion = $rsQuestions->Fetch()) {

            $code = 'form_text_' . $arQuestion['SID']; // code by default
            $tmpRow = [
                'IS_REQUIRED' => $arQuestion['REQUIRED'],
                'NAME' => $arQuestion['TITLE'],
                'CODE' => $code,
                'TYPE' => 'text',
                'IS_PHONE' => strpos($arQuestion['SID'], 'PHONE') !== false,
                'IS_EMAIL' => strpos($arQuestion['SID'], 'EMAIL') !== false,
                'IS_SOURCE' => strpos($arQuestion['SID'], 'SOURCE') === 0,
                'IS_CITY' => strpos($arQuestion['SID'], 'CITY') !== false,
                'IS_DISABLED' => false,
            ];

            if ($arQuestion['SID'] == 'SUBJECT' && !empty($this->arParams['SUBJECT'])) {
                $tmpRow['IS_DISABLED'] = true;
                $tmpRow['VALUE'] = trim(strip_tags($this->arParams['SUBJECT']));
            }

            $answers = [];
            $by = 's_sort';
            $order = 'asc';
            $rsAnswers = \CFormAnswer::GetList($arQuestion['ID'], $by, $order, ['ACTIVE' => 'Y'], $isFiltered);
            while ($arAnswer = $rsAnswers->Fetch()) {
                $answers[] = [
                    'ID' => $arAnswer['ID'],
                    'TYPE' => $arAnswer['FIELD_TYPE'],
                    'VALUE' => $arAnswer['MESSAGE'],
                ];
            }

            if (empty($answers[0])) {
                continue;
            }

            $tmpRow['TYPE'] = $answers[0]['TYPE'];

            $tmpRow['VALUES'] = $answers;
            if (!empty($answers[0]['TYPE'])) {
                if (in_array($answers[0]['TYPE'], ['radio', 'dropdown', 'checkbox', 'multiselect'])) {
                    $tmpRow['CODE'] =  'form_' . $answers[0]['TYPE'] . '_' . $arQuestion['SID'];
                } else {
                    $tmpRow['CODE'] =  'form_' . $answers[0]['TYPE'] . '_' . $answers[0]['ID'];
                }
            }

            // get extensions and file size from validators
            if ($answers[0]['TYPE'] === 'file') {
                $extensions = '';
                $maxSize = $this->arResult['MAX_FILE_SIZE'];

                $arFilter = ['ACTIVE' => 'Y'];
                $rsValidators = \CFormValidator::GetList($arQuestion['ID'], $arFilter, $by, $order);
                while ($arValidator = $rsValidators->Fetch()) {
                    if ($arValidator['NAME'] === 'file_type') {
                        if (!empty($arValidator['PARAMS']['EXT'])) {
                            $extensions .= ','.$arValidator['PARAMS']['EXT'];
                        }
                        if (!empty($arValidator['PARAMS']['EXT_CUSTOM'])) {
                            $extensions .= ','.$arValidator['PARAMS']['EXT_CUSTOM'];
                        }
                    }

                    if ($arValidator['NAME'] === 'file_size') {
                        if (!empty($arValidator['PARAMS']['SIZE_TO'])) {
                            $maxSize = min($maxSize, intval($arValidator['PARAMS']['SIZE_TO']));
                        }
                    }
                }

                $tmpRow['MAX_SIZE'] = $maxSize;
                $tmpRow['FILE_TYPE'] = Helpers\File::formatExt($extensions);
            }

            // get checkbox validators
            if ($answers[0]['TYPE'] === 'checkbox') {
                $arFilter = ['ACTIVE' => 'Y'];
                $rsValidators = \CFormValidator::GetList($arQuestion['ID'], $arFilter, $by, $order);
                while ($arValidator = $rsValidators->Fetch()) {
                    $tmpRow['NUMBER_FROM'] = $arValidator['PARAMS']['NUMBER_FROM'];
                    $tmpRow['NUMBER_TO'] = $arValidator['PARAMS']['NUMBER_TO'];
                }
            }

            $result[$arQuestion['SID']] = $tmpRow;
        }

        return $result;
    }

    private function submitIblock($post, $formCode)
    {
        Loader::includeModule('iblock');

        $iblockInfo = Helpers\Iblock::getIblockByCode($formCode);

        // first, get iblock props
        $propsInfo = Iblock\PropertyTable::getList([
            'filter' => [
                'IBLOCK_ID' => $iblockInfo['ID'],
                'ACTIVE' => 'Y',
            ],
        ])->fetchAll();

        $props = [];
        foreach ($propsInfo as $prop) {
            $propCode = $prop['CODE'];
            if (empty($post[$propCode])) {
                continue;
            }

            if ($prop['PROPERTY_TYPE'] === 'F' && $prop['MULTIPLE'] === 'Y') {
                $post[$propCode] = [$post[$propCode]];
            }

            if ($prop['USER_TYPE'] == 'HTML') {
                $props[$propCode] = [
                    'VALUE' => [
                        'TEXT' => $post[$propCode],
                        'TYPE' => 'text',
                    ],
                ];
            } else {
                $props[$propCode] = $post[$propCode];
            }
        }

        $el = new \CIBlockElement;

        $arFields = [
            'ACTIVE' => 'N',
            'IBLOCK_ID' => $iblockInfo['ID'],
            'NAME' => 'Message from ' . date('d.m.Y'),
            'PROPERTY_VALUES' => $props,
        ];

        $beforeSendFormEvent = new \Bitrix\Main\Event(Config::MODULE_ID, 'onRanxBeforeFormSend', $arFields);
        $beforeSendFormEvent->send();
        if ($eventResults = $beforeSendFormEvent->getResults()) {
            foreach ($eventResults as $evenResult) {
                if ($evenResult->getType() == \Bitrix\Main\EventResult::SUCCESS) {
                    $arFields = $evenResult->getParameters();
                }
            }
        }

        $elId = $el->Add($arFields);
        if (!$elId) {
            throw new Exception('Error: ' . $el->LAST_ERROR);
        }

        $arFields['RESULT_ID'] = $elId;
        $afterSendFormEvent = new \Bitrix\Main\Event(Config::MODULE_ID, 'onRanxAfterFormSend', $arFields);
        $afterSendFormEvent->send();

        $formName = trim(str_replace([Loc::getMessage('RX_LANDING_FORM_REPLACE'), 'Form:'], '', $iblockInfo['NAME']));
        $this->sendMailFromIblockElement($elId, $iblockInfo['ID'], $formName);

        if ($post['PAYMENT'] == 'Y') {
            return [
                'html' => Payment\Invoicebox::getHtmlForServiceForm(
                    $post['SERVICE_NAME'],
                    $post['SERVICE_PRICE'],
                    $elId
                )
            ];
        }
    }

    private function submitWebForm($post, $formCode)
    {
        if (!Loader::includeModule('form')) {
            throw new Exception();
        }

        $form = \CForm::GetBySID($formCode)->Fetch();
        if (!$form) {
            throw new Exception(Loc::getMessage('FORM_IS_NOT_FOUND'));
        }

        // collect values
        $values = [];
        foreach ($post as $key => $val) {
            if (strpos($key, 'form_') !== 0) continue;

            $values[$key] = $val;
        }

        $eventFields = ['FORM_ID' => $form['ID'], 'VALUES' => $values];
        $beforeSendFormEvent = new \Bitrix\Main\Event(Config::MODULE_ID, 'onRanxBeforeFormSend', $eventFields);
        $beforeSendFormEvent->send();
        if ($eventResults = $beforeSendFormEvent->getResults()) {
            foreach ($eventResults as $evenResult) {
                if ($evenResult->getType() == \Bitrix\Main\EventResult::SUCCESS) {
                    $eventFields = $evenResult->getParameters();
                }
            }
        }
        $values = $eventFields['VALUES'];

        Event::removeOtherResultAddEvents();
        $resultId = \CFormResult::Add($form['ID'], $values);
        if (!$resultId) {
            throw new Exception($GLOBALS['strError']);
        }

        \CFormCRM::onResultAdded($form['ID'], $resultId);

        $eventFields['RESULT_ID'] = $resultId;
        $afterSendFormEvent = new \Bitrix\Main\Event(Config::MODULE_ID, 'onRanxAfterFormSend', $eventFields);
        $afterSendFormEvent->send();

        \CFormResult::SetEvent($resultId);
        \CFormResult::Mail($resultId);

        if ($post['PAYMENT'] == 'Y') {
            return [
                'html' => Payment\Invoicebox::getHtmlForServiceForm(
                    $post['SERVICE_NAME'],
                    $post['SERVICE_PRICE'],
                    $resultId
                )
            ];
        }
    }

    private function sendMailFromIblockElement($elementId, $iblockId = false, $formName = '')
    {
        $fields = [];

        if (!$iblockId) {
            $iblockId = Helpers\Iblock::getIblockIdByElementId($elementId);
        }

        $props = [];
        $dbRes = \CIBlockElement::GetList([], ['ID' => $elementId, 'IBLOCK_ID' => $iblockId], false, false, ['ID', 'IBLOCK_ID']);
        if ($obRes = $dbRes->GetNextElement()) {
            $props = $obRes->GetProperties();
        }

        $fields['FORM_NAME'] = $formName;

        $propStr = '';
        foreach ($props as $prop) {
            if ($prop['PROPERTY_TYPE'] == 'F') {
                if (is_array($prop['VALUE'])) {
                    $prop['VALUE'] = reset($prop['VALUE']);
                }
                $prop['VALUE'] = self::getFileSrc($prop['VALUE']);
            }

            if (isset($prop['VALUE']['TEXT'])) {
                $propVal = $prop['VALUE']['TEXT'];
            } elseif (is_array($prop['VALUE'])) {
                $propVal = implode(', ', $prop['VALUE']);
            } else {
                $propVal = $prop['VALUE'];
            }
            
            $propStr .= $prop['NAME'] . ': ' . $propVal . "\n";
        }
        $fields['FORM_DATA'] = $propStr;

        Mail\Event::sendImmediate([
            'EVENT_NAME' => 'RANX_LANDING_FORM', 
            'LID' => SITE_ID,
            'C_FIELDS' => $fields,
            'DUPLICATE' => 'Y',
        ]);
    }

    private function checkFormCode($code)
    {
        return $code
            && (Config::isAllFormsEnabled()
                || strpos(strtolower($code), 'ranx_landing_form_') === 0
                || strtolower($code) === 'ranx_landing_service')
            && strpos(strtolower($code), 'ranx_landing_sale_') === false;
    }

    private function prepareFiles(&$post)
    {
        foreach ($post as &$val) {
            if (!is_array($val) || $val['type'] !== 'file') {
                continue;
            }

            $val = Helpers\File::fromBase64($val['data']);
        }
    }

    private function getFileSrc($fileId)
    {
        $protocol = \CMain::IsHTTPS() ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $path = \CFile::GetPath($fileId);

        return !empty($path) ? $protocol.$host.$path : '';
    }

    private function getFormNameByCode($code)
    {
        $formName = Loc::getMessage('DEFAULT_FORM_TITLE');

        if ($code === 'ranx_landing_form_oneclick') {
            return Loc::getMessage('RX_LANDING_FORM_ONECLICK_TITLE');
        }

        if (Config::isWebFormsEnabled()) {
            if (!Loader::includeModule('form')) {
                return $formName;
            }

            $form = \CForm::GetBySID($code)->Fetch();
            if (!empty($form['NAME'])) {
                $formName = $form['NAME'];
            }
        } else {
            if (!Loader::includeModule('iblock')) {
                return $formName;
            }

            $form = Helpers\Iblock::getIblockByCode($code);
            if (!empty($form['NAME'])) {
                $formName = trim(str_replace([Loc::getMessage('RX_LANDING_FORM_REPLACE'), 'Form:'], '', $form['NAME']));
            }
        }

        return $formName;
    }

    private function getFieldNamesByFormCode($code)
    {
        $names = [];

        if (Config::isWebFormsEnabled()) {
            if (!Loader::includeModule('form')) {
                return $names;
            }

            $form = \CForm::GetBySID($code)->Fetch();
            if (!empty($form['ID'])) {
                $isFiltered = false;
                $by = 's_sort';
                $order = 'asc';
                $dbRes = \CFormField::GetList($form['ID'], 'N', $by, $order, ['ACTIVE' => 'Y'], $isFiltered);
                while ($arRes = $dbRes->Fetch()) {
                    if ($arRes['SID'] == 'SOURCE') continue;
                    $names[] = $arRes['TITLE'];
                }
            }
        } else {
            if (!Loader::includeModule('iblock')) {
                return $names;
            }

            $dbRes = \CIBlockProperty::GetList(['sort' => 'asc'], ['IBLOCK_CODE' => $code, 'ACTIVE' => 'Y']);
            while ($arRes = $dbRes->Fetch()) {
                if ($arRes['CODE'] == 'SOURCE') continue;
                $names[] = $arRes['NAME'];
            }
        }

        return $names;
    }

    private static function prepareAgreement($agreement)
    {
        $aData = $agreement->getData();
        $aText = $agreement->getText(true);

        if ($aData['TYPE'] == Agreement::TYPE_STANDARD) {
            $aTitle = $agreement->getTitle();
        }
        else {
            $aTitle = $aData['NAME'];
        }

        $aText = str_replace("\n", '<br>', trim($aText));

        return [
            'title' => $aTitle,
            'body' => $aText,
        ];
    }

    private function formatFieldsOneclick()
    {
        foreach ($this->arResult['FIELDS'] as $code => $field) {
            if (!in_array($code, ['NAME', 'PHONE', 'EMAIL', 'COMMENT'])) {
                unset($this->arResult['FIELDS'][$code]);
                continue;
            }

            $this->arResult['FIELDS'][$code]['CODE'] = $code;
        }
    }

    /* ======== ACTIONS ======== */
    public function ajaxActionBefore()
    {
        if (!Loader::includeModule('ranx.landing')) {
            throw new Exception('No ranx.landing module');
        }
        Loader::includeModule('iblock');
        if (!defined('RX_LANDING_TEMPLATE')) {
            define('RX_LANDING_TEMPLATE', 1);
        }
        Config::defineSettingId();
    }

    public function submitAction($post)
    {
        $this->ajaxActionBefore();

        $formCode = trim($post['FORM_CODE']);
        $isOneclick = $formCode === Order::FORM_CODE;

        if (!$isOneclick && !$this->checkFormCode($formCode) || !bitrix_sessid_post()) {
            throw new Exception(Loc::getMessage('FORM_IS_NOT_FOUND'));
        }

        if (Config::isAgreementEnabled()) {
            if (empty($post['AGREEMENT'])) {
                throw new Exception(Loc::getMessage('RX_FORM_LANDING_AGREEMENT_REQUIRED'));
            }

            if ($agreementId = Config::getAgreementId()) {
                $source = trim($post['SOURCE']);

                if (Config::isWebFormsEnabled() && Loader::includeModule('form')) {
                    $form = \CForm::GetBySID($formCode)->Fetch();
                    $sourceFieldId = \CFormField::GetBySID('SOURCE', $form['ID'])->Fetch()['ID'];
                    if ($sourceFieldId > 0) {
                        $by = 's_sort'; $order = 'asc'; $isFiltered = false;
                        $rsAnswers = \CFormAnswer::GetList($sourceFieldId, $by, $order, ['ACTIVE' => 'Y'], $isFiltered);
                        if ($arAnswer = $rsAnswers->Fetch()) {
                            $source = trim($post['form_text_' . $arAnswer['ID']]);
                        }
                    }
                }

                \Bitrix\Main\UserConsent\Consent::addByContext($agreementId, 'ranx.landing/forms', $formCode, [
                    'URL' => $source,
                ]);
            }
        }

        // check captcha
        if (
            CaptchaManager::isCaptchaEnabled()
            && !CaptchaManager::getCurrentCaptchaClass()::verifyFormPost($post)
        ) {
            throw new Exception('captcha'); // do not change this message
        }

        if ($isOneclick) {
            return Order::create($post);
        }

        $this->prepareFiles($post);

        if (Config::isWebFormsEnabled()) {
            $result = $this->submitWebForm($post, $formCode);
        } else {
            $result = $this->submitIblock($post, $formCode);
        }

        Helpers\File::removeTemp();

        if (isset($result)) {
            return $result;
        }
        return true;
    }

    public function getModalAction($post)
    {
        $this->ajaxActionBefore();
        Event::removeOtherEvents();

        $formCode = trim($post['formCode']);
        $subject = trim(strip_tags($post['subject']));
        $template = $formCode === 'ranx_landing_service' ? 'service' : '';

        if (!$this->checkFormCode($formCode)) {
            throw new Exception(Loc::getMessage('FORM_IS_NOT_FOUND'));
        }

        $formName = $this->getFormNameByCode($formCode);

        ob_start();
        $GLOBALS['APPLICATION']->IncludeComponent(
            'ranx:form.landing',
            $template,
            [
                'FORM_CODE' => $formCode,
                'SUBJECT' => $subject,
                'PRODUCT_ID' => intval($post['productId']),
            ],
            false,
            ['HIDE_ICONS' => 'Y']
        );
        $html = ob_get_clean();

        return [
            'title' => $formName,
            'class' => FormHelper::isB24Form($formCode) ? 'modal-form-b24' : '',
            'body' => $html,
        ];
    }

    public function getAgreementAction($post)
    {
        $this->ajaxActionBefore();
        Event::removeOtherEvents();

        $formCode = trim($post['formCode']);
        if (!$this->checkFormCode($formCode)) {
            throw new Exception(Loc::getMessage('FORM_IS_NOT_FOUND'));
        }

        // get field names
        $fields = $this->getFieldNamesByFormCode($formCode);

        if (empty($fields)) {
            $fields = [
                Loc::getMessage('RX_FORM_LANDING_FULL_NAME_FIELD_TITLE'),
                Loc::getMessage('RX_FORM_LANDING_PHONE_FIELD_TITLE'),
                Loc::getMessage('RX_FORM_LANDING_EMAIL_FIELD_TITLE')
            ];
        }

        $agreementId = Config::getAgreementId();
        if (intval($agreementId) <= 0) {
            throw new Exception(Loc::getMessage('RX_FORM_LANDING_AGREEMENT_NOT_FOUND'));
        }

        $agreement = new Agreement((int)$agreementId);
        $agreement->setReplace(['fields' => $fields]);

        return self::prepareAgreement($agreement);
    }

    public function getPoliticsAction()
    {
        $this->ajaxActionBefore();
        Event::removeOtherEvents();

        $politicsId = Config::getPoliticsId();
        if (intval($politicsId) <= 0) {
            throw new Exception(Loc::getMessage('RX_FORM_LANDING_POLITICS_NOT_FOUND'));
        }

        $agreement = new Agreement((int)$politicsId);
        return self::prepareAgreement($agreement);
    }
}
