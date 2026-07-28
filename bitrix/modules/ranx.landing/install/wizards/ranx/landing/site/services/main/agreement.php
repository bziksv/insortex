<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/**
 * Auto create an agreement (if not exists) and set the param
 */

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\UserConsent\Agreement;

if (!Loader::includeModule('ranx.landing')) return;

Loc::loadMessages(WIZARD_SERVICE_ABSOLUTE_PATH . '/agreement.php');

$agreementId = 0;
$agreementCode = 'rx_landing_agreement';

$agreements = Agreement::getActiveList();
foreach ($agreements as $id => $name) {
    $curAgreement = new Agreement($id);
    if ($curAgreement->getData()['CODE'] === $agreementCode) {
        $agreementId = $id;
        break;
    }
}

if (!$agreementId) { // create new one
    $agreement = new Agreement(0);

    $data = [
        'NAME' => Loc::getMessage('RX_LANDING_WZ_MAIN_AGREEMENT_NAME'),
        'CODE' => $agreementCode,
        'TYPE' => Agreement::TYPE_STANDARD,
        'LANGUAGE_ID' => 'ru',
        'LABEL_TEXT' => Loc::getMessage('RX_LANDING_WZ_MAIN_AGREEMENT_LABEL_TEXT'),
        'FIELDS' => [
            'COMPANY_NAME' => Loc::getMessage('RX_LANDING_WZ_MAIN_AGREEMENT_COMPANY_NAME'),
            'EMAIL' => 'info@example.com',
        ],
    ];

    $agreement->mergeData($data);
    $agreement->save();

    $agreementId = $agreement->getId();
}

\Ranx\Landing\Config::enableParam('AGREEMENT_ID');
\Ranx\Landing\Config::set('AGREEMENT_ID', $agreementId, WIZARD_SITE_ID);
