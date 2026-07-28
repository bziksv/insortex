<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var boolean $showNames
 * 
 * @var string $priceName
 * @var float|string $priceValue
 * @var string $discountPriceName
 * @var float|string $discountPriceValue
 * 
 * @var string $discountTypeName
 * 
 * @var string $priceRowAttrs
 */

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

if (is_null($showNames)) {
    $showNames = true;
}

$isDiscountTypePercent = strpos($discountPriceValue, '%') !== false || !$discountPriceValue;
$totalPrice = Helper::getTotalPrice($priceValue, $discountPriceValue);
?>

<div class="form-row form-row--prices" <?=($priceRowAttrs ?? '')?>>
    <div class="form-group col-md-6">
        <label><?=Loc::getMessage('RX_PANEL_LANDING_FIELD_PRICE_TITLE')?></label>
        <input type="text"
            name="<?=($showNames ? $priceName : '')?>"
            data-link-name="<?=$priceName?>"
            class="form-control js-panel-card-price"
            value="<?=$priceValue?>"
        />
    </div>

    <div class="form-group col-md-6">
        <label><?=Loc::getMessage('RX_PANEL_LANDING_FIELD_PRICE_DISCOUNT_TITLE')?></label>
        <div class="form-row">
            <div class="form-group col-8 mb-0">
                <input type="text" 
                    name="<?=($showNames ? $discountPriceName : '')?>"
                    data-link-name="<?=$discountPriceName?>"
                    class="form-control js-panel-card-discount-price"
                    value="<?=str_replace('%', '', $discountPriceValue)?>"
                />
            </div>
            <div class="form-group col-4 mb-0">
                <select class="form-control js-panel-card-discount-type" 
                    name="<?= ($showNames && $discountTypeName ? $discountTypeName : '') ?>" 
                    data-link-name="<?= $discountTypeName ?>"
                >
                    <option value="percent" <?if($isDiscountTypePercent):?>selected<?endif?>>%</option>
                    <option value="currency" <?if(!$isDiscountTypePercent):?>selected<?endif?>>
                        <?= Config::getCurrency() ?>
                    </option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group col-12 form-group--pricetotal" <?if(!$priceValue || !floatval($discountPriceValue)):?>style="display: none;"<?endif?>>
        <?= Loc::getMessage('RX_PANEL_LANDING_FIELD_PRICE_TOTAL', ['#PRICE#' => Helper::money($totalPrice, '')]) ?> <?= Config::getCurrency() ?>
    </div>
</div>
