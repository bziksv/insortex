<?php

namespace Ranx\Landing\Sale;

use Exception;
use Bitrix\Main\Loader;
use Ranx\Landing\Event;
use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Ranx\Landing\Helpers\Iblock;
use Bitrix\Main\Localization\Loc;

class Order
{
    const FORM_CODE = 'ranx_landing_sale_order';

    public static function create($data)
    {
        if (Config::isWebFormsEnabled()) {
            return self::createWebForm($data);
        }
        return self::createIblock($data);
    }

    private static function prepareData($data)
    {
        $name = $data['NAME'];
        if (!empty($data['PHONE'])) {
            $name .= ' (' . $data['PHONE'] . ')';
        }
        if (!empty($data['EMAIL'])) {
            $name .= ' <' . $data['EMAIL'] . '>';
        }

        $productName = '';

        if (!empty($data['IS_ONECLICK'])) {
            $product = Basket::fetchItemData($data['PRODUCT_ID']);

            $productName = (string)($product['NAME'] ?? '');
            $products = $productName . ' (' . Helper::money($product['PRICE'], '') . ' x 1)' . "\n";
            $total = $product['PRICE'];

            $data['COMMENT'] .= "\n\n" . Loc::getMessage('RX_LANDING_LIB_SALE_ORDER_COMMENT_ONECLICK');
        } else {
            $products = Basket::getStr();
            if (!$products) {
                return false;
            }

            // First line of basket string is usually "Name (price x qty)"
            $firstLine = trim(explode("\n", $products)[0]);
            $productName = preg_replace('/\s*\(.*$/', '', $firstLine) ?: $firstLine;

            $deliveryName = self::getDeliveryName();
            $deliverySum = self::getDeliverySum();

            $total = Basket::getTotalPrice() + $deliverySum;
        }

        return array_merge($data, [
            'ELEMENT_NAME' => $name,
            'PRODUCT_NAME' => $productName,
            'PRODUCTS' => $products,
            'DELIVERY_NAME' => $deliveryName ?? '',
            'DELIVERY_SUM' => $deliverySum ?? '',
            'TOTAL' => $total,
        ]);
    }

    private static function createWebForm($data)
    {
        Loader::includeModule('form');

        if (!($data = self::prepareData($data))) {
            return false;
        }

        $form = \CForm::GetBySID(self::FORM_CODE)->Fetch();
        if (empty($form)) {
            throw new Exception('Form is not found');
        }

        $formFields = [];
        $by = 's_sort';
        $order = 'asc';
        $isFiltered = false;
        $rsFields = \CFormField::GetList($form['ID'], 'N', $by, $order, ['ACTIVE' => 'Y'], $isFiltered);
        while ($arField = $rsFields->Fetch()) {
            $formFields[$arField['SID']] = $arField;

            $rsAnswers = \CFormAnswer::GetList($arField['ID'], $by, $order, ['ACTIVE' => 'Y'], $isFiltered);
            if ($arAnswer = $rsAnswers->Fetch()) {
                $formFields[$arField['SID']] = $arAnswer['ID'];
            }
        }

        $values = [
            'form_text_' . $formFields['NAME'] => $data['NAME'],
            'form_text_' . $formFields['PHONE'] => $data['PHONE'],
            'form_text_' . $formFields['EMAIL'] => $data['EMAIL'],
            'form_text_' . $formFields['COMPANY'] => $data['COMPANY'],
            'form_textarea_' . $formFields['COMMENT'] => $data['COMMENT'],
            'form_textarea_' . $formFields['PRODUCTS'] => $data['PRODUCTS'],
            'form_text_' . $formFields['DELIVERY'] => $data['DELIVERY_NAME'],
            'form_text_' . $formFields['ADDRESS'] => $data['ADDRESS'],
            'form_text_' . $formFields['DELIVERY_SUM'] => $data['DELIVERY_SUM'],
            'form_text_' . $formFields['TOTAL'] => $data['TOTAL'],
        ];

        Event::removeOtherResultAddEvents();
        $id = \CFormResult::Add($form['ID'], $values);

        \CFormCRM::onResultAdded($form['ID'], $id);
        \CFormResult::SetEvent($id);
        \CFormResult::Mail($id);

        return $id > 0 ? $id : false;
    }

    private static function createIblock($data)
    {
        Loader::includeModule('iblock');

        if (!($data = self::prepareData($data))) {
            return false;
        }

        $fields = [
            'NAME' => $data['NAME'],
            'PHONE' => $data['PHONE'],
            'EMAIL' => $data['EMAIL'],
            'COMPANY' => $data['COMPANY'],
            'COMMENT' => $data['COMMENT'],
            'PRODUCTS' => $data['PRODUCTS'],
            'DELIVERY' => $data['DELIVERY_NAME'],
            'ADDRESS' => $data['ADDRESS'],
            'DELIVERY_SUM' => $data['DELIVERY_SUM'],
            'TOTAL' => $data['TOTAL'],
        ];

        $el = new \CIBlockElement;
        $id = $el->Add([
            'NAME' => $data['ELEMENT_NAME'],
            'IBLOCK_ID' => self::getIblockId(),
            'PROPERTY_VALUES' => $fields,
        ]);

        $fields['ELEMENT_ID'] = $id;
        $fields['IBLOCK_ID'] = self::getIblockId();
        $fields['PRODUCT_NAME'] = $data['PRODUCT_NAME'] ?? '';
        $fields['DELIVERY_SUM'] = Helper::money($fields['DELIVERY_SUM']);
        $fields['TOTAL'] = Helper::money($fields['TOTAL']);

        \Bitrix\Main\Mail\Event::sendImmediate([
            'EVENT_NAME' => 'RANX_LANDING_SALE_ORDER', 
            'LID' => SITE_ID,
            'C_FIELDS' => $fields,
            'DUPLICATE' => 'Y',
        ]);

        return $id > 0 ? $id : false;
    }

    public static function setDelivery($i)
    {
        $userId = self::getUserId();

        return $_SESSION[SITE_ID][$userId]['DELIVERY'] = intval($i);
    }

    public static function getDelivery()
    {
        $userId = self::getUserId();

        return $_SESSION[SITE_ID][$userId]['DELIVERY'] ?? 0;
    }

    public static function getDeliveryName()
    {
        $delivery = self::getDelivery();

        $deliveryOption = Config::getDelivery();
        return $deliveryOption[$delivery]['NAME'] ?? '';
    }


    public static function getDeliverySum()
    {
        $delivery = self::getDelivery();

        $deliveryOption = Config::getDelivery();
        return $deliveryOption[$delivery]['COST'] ?? 0;
    }

    private static function getUserId()
    {
        return $GLOBALS['USER']->IsAuthorized() ? $GLOBALS['USER']->GetID() : 0;
    }

    private static function getIblockId()
    {
        return Iblock::getIblockIdByCode(self::FORM_CODE);
    }
}
