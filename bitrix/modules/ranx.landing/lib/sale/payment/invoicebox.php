<?php

namespace Ranx\Landing\Sale\Payment;

use Ranx\Landing\Config;
use Ranx\Landing\Sale\Order;
use Ranx\Landing\Sale\Basket;

class Invoicebox
{
    const API_URL = 'https://go.invoicebox.ru/module_inbox_auto.u';

    public static function getHtml($orderId, $fields)
    {
        $html = '<form action="' . self::API_URL . '" method="POST">';

        $html .= self::field('participant_id', Config::getInvoiceboxId());
        $html .= self::field('participant_ident', Config::getInvoiceboxIdent());
        $html .= self::field('currency_ident', Config::getInvoiceboxCurrency());
        $html .= self::field('testmode', Config::getInvoiceboxTestmode() ? 1 : 0);

        $html .= self::field('language_ident', 'RUS');
        $html .= self::field('body_type', 'PRIVATE');
        $html .= self::field('person_name', $fields['NAME']);
        $html .= self::field('person_email', $fields['EMAIL']);
        $html .= self::field('person_phone', $fields['PHONE']);

        $orderAmount = Basket::getTotalPrice() + Order::getDeliverySum();

        $html .= self::field('order_id', $orderId);
        $html .= self::field('order_amount', $orderAmount);
        $html .= self::field('order_description', 'TEST');

        $basketItems = Basket::get();
        $i = 1;
        foreach ($basketItems as $item) {
            $html .= self::field('item' . $i . '_name', $item['NAME']);
            $html .= self::field('item' . $i . '_quantity', $item['QUANTITY']);
            $html .= self::field('item' . $i . '_price', $item['PRICE']);

            $i++;
        }

        $html .= self::field('participant_sign', self::sign($orderId, $orderAmount));

        $html .= '</form>';

        return $html;
    }

    public static function getHtmlForServiceForm($name, $price, $orderId)
    {
        $orderId = 'service_'.$orderId;

        $html = '<form class="payment-form hidden" action="'.self::API_URL.'" method="POST" target="_blank">';

        $html .= self::field('participant_id', Config::getInvoiceboxId());
        $html .= self::field('participant_ident', Config::getInvoiceboxIdent());
        $html .= self::field('currency_ident', Config::getInvoiceboxCurrency());
        $html .= self::field('testmode', Config::getInvoiceboxTestmode() ? 1 : 0);
        $html .= self::field('language_ident', 'RUS');
        $html .= self::field('body_type', 'PRIVATE');
        $html .= self::field('order_amount', $price);
        $html .= self::field('order_description', 'TEST');

        $html .= self::field('order_id', $orderId);
        $html .= self::field('item1_name', $name);
        $html .= self::field('item1_quantity', 1);
        $html .= self::field('item1_price', $price);
        $html .= self::field('item1_type', 'service');

        $html .= self::field('participant_sign', self::sign($orderId, $price));
        $html .= '</form>';

        return $html;
    }

    private static function field($name, $value)
    {
        return '<input type="hidden" name="itransfer_' . $name . '" value="' . trim($value) . '" />';
    }

    private static function sign($orderId, $orderAmount)
    {
        return md5(
            Config::getInvoiceboxId() . 
            $orderId . 
            $orderAmount . 
            Config::getInvoiceboxCurrency() . 
            Config::getInvoiceboxSecret()
        );
    }
}
