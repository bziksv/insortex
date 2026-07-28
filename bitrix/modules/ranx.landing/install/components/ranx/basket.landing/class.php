<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

use Bitrix\Main\Loader;
use Ranx\Landing\Config;
use Ranx\Landing\Sale\Basket;
use Bitrix\Main\Engine\Contract\Controllerable;

class RanxBasketLandingComponent extends CBitrixComponent implements Controllerable
{
    private function prepareResult()
    {
        $this->arResult['LINK'] = Config::getOrderPageLink();
        $this->arResult['ITEMS'] = Basket::get() ?? [];
        $this->arResult['ITEMS_COUNT'] = Basket::getCount();
        $this->arResult['TOTAL_PRICE'] = Basket::getTotalPrice();
    }

    public function executeComponent()
    {
        $this->prepareResult();

        $this->includeComponentTemplate();
    }

    public function configureActions()
    {
        Loader::includeModule('ranx.landing');

        return [
            'add' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'addCustom' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'remove' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'quantity' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            'refresh' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
        ];
    }

    public function addAction($id, $quantity = 1)
    {
        $item = Basket::add($id, $quantity);

        if (empty($item['PICTURE'])) {
            $item['PICTURE'] = $this->getDefaultPicture();
        }

        return $item;
    }

    public function addCustomAction($name, $price, $discount = '')
    {
        $item = Basket::addCustom($name, $price, $discount);

        if (empty($item['PICTURE'])) {
            $item['PICTURE'] = $this->getDefaultPicture();
        }

        return $item;
    }

    public function removeAction($id)
    {
        return Basket::remove($id);
    }

    public function quantityAction($id, $quantity = 1)
    {
        return Basket::quantity($id, $quantity);
    }

    public function refreshAction($template = 'header')
    {
        $this->prepareResult();

        ob_start();
        $this->setTemplateName($template);
        $this->includeComponentTemplate();
        $html = ob_get_clean();

        return $html;
    }

    private function getDefaultPicture()
    {
        return $this->__path . '/img/noimage.png';
    }
}
