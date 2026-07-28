<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

use Bitrix\Main\Loader;
use Ranx\Landing\Event;
use Ranx\Landing\Config;
use Ranx\Landing\Sale\Order;
use Ranx\Landing\Sale\Basket;
use Ranx\Landing\Sale\Payment;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserConsent\Agreement;
use Bitrix\Main\Engine\Contract\Controllerable;

class RanxOrderLandingComponent extends CBitrixComponent implements Controllerable
{
    private function prepareResult()
    {
        // agreement
        $this->arResult['USE_AGREEMENT'] = Config::isAgreementEnabled();
        $this->arResult['AGREEMENT_LINK'] = Config::getAgreementLink();
        $this->arResult['AGREEMENT_ACTIVE'] = Config::isAgreementActive();

        // basket
        $this->arResult['BASKET_ITEMS'] = Basket::get();

        foreach ($this->arResult['BASKET_ITEMS'] as $arItem) {
            $this->arResult['PRODUCTS_PRICE'] += ($arItem['OLD_PRICE'] ?: (is_numeric($arItem['PRICE']) ? $arItem['PRICE'] : 0)) * $arItem['QUANTITY'];
            $this->arResult['DISCOUNT_PRICE'] += ($arItem['DISCOUNT_PRICE'] ?? 0) * $arItem['QUANTITY'];
            $this->arResult['TOTAL_PRICE'] += is_numeric($arItem['PRICE']) ? $arItem['PRICE'] * $arItem['QUANTITY'] : 0;
        }

        // delivery
        $this->arResult['DELIVERY_ITEMS'] = Config::getDelivery();
        $this->arResult['DELIVERY'] = Order::getDelivery();
        $this->arResult['DELIVERY_PRICE'] = Order::getDeliverySum();

        $this->arResult['TOTAL_PRICE'] += $this->arResult['DELIVERY_PRICE'];

        // payment
        $this->arResult['PAYMENT'] = $this->arResult['TOTAL_PRICE'] ? Config::getPayment() : '';
    }

    public function executeComponent()
    {
        $this->prepareResult();

        $this->includeComponentTemplate();
    }

    public function configureActions()
    {
        if (!Loader::includeModule('ranx.landing')) {
            throw new Exception('No ranx.landing module');
        }
        Loader::includeModule('iblock');
        if (!defined('RX_LANDING_TEMPLATE')) {
            define('RX_LANDING_TEMPLATE', 1);
        }
        Config::defineSettingId();

        return [
            'refresh' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'delivery' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'submit' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'agreement' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
        ];
    }

    public function refreshAction()
    {
        $this->prepareResult();

        if (empty($this->arResult['BASKET_ITEMS'])) {
            return false;
        }

        ob_start();
        $this->includeComponentTemplate('include/result');
        $html = ob_get_clean();

        return $html;
    }

    public function deliveryAction($index = 0)
    {
        return Order::setDelivery($index);
    }

    public function submitAction($fields)
    {
        if (empty($fields) || !is_array($fields)) {
            throw new Exception('Wrong data');
        }

        // create new order
        if (!($orderId = Order::create($fields))) {
            throw new Exception('Cannot create order');
        }

        // write user consent
        if (Config::isAgreementEnabled() && ($agreementId = Config::getAgreementId())) {
            \Bitrix\Main\UserConsent\Consent::addByContext($agreementId, 'ranx.landing/order', $orderId, [
                'URL' => Config::getOrderPageLink(),
            ]);
        }

        $result = [
            'order_id' => $orderId,
        ];

        // include payment
        if (Config::getPayment() === 'INVOICEBOX') {
            $result['payment'] = Payment\Invoicebox::getHtml($orderId, $fields);
        }

        // do not allow to pay for free order
        if (!Basket::getTotalPrice()) {
            unset($result['payment']);
        }

        // clear basket
        Basket::empty();

        return $result;
    }

    public function agreementAction()
    {
        Event::removeOtherEvents();

        $fields = [
            Loc::getMessage('RX_ORDER_LANDING_FULL_NAME_FIELD_TITLE'),
            Loc::getMessage('RX_ORDER_LANDING_PHONE_FIELD_TITLE'),
            Loc::getMessage('RX_ORDER_LANDING_EMAIL_FIELD_TITLE')
        ];

        $agreementId = Config::getAgreementId();
        if (intval($agreementId) <= 0) {
            throw new Exception(Loc::getMessage('RX_ORDER_LANDING_AGREEMENT_NOT_FOUND'));
        }

        $agreement = new Agreement((int)$agreementId);
        $agreement->setReplace(['fields' => $fields]);

        $aData = $agreement->getData();
        $aText = $agreement->getText(true);

        if ($aData['TYPE'] == Agreement::TYPE_STANDARD) {
            $aTitle = $agreement->getTitle();
        } else {
            $aTitle = $aData['NAME'];
        }

        $aText = str_replace("\n", '<br>', trim($aText));

        return [
            'title' => $aTitle,
            'body' => $aText,
        ];
    }
}
