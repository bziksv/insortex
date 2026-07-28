<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $id
 * @var bool $withCounter
 * @var string $basketLink
 */

use Ranx\Landing\Page;
use Ranx\Landing\Sale\Basket;
use Bitrix\Main\Localization\Loc;
?>

<div class="product-buy <?if(Basket::has($id)):?>in-basket<?endif?>">

    <?if(!empty($withCounter)):?>
        <? Page::showCounter($id); ?>
    <?endif?>

    <div class="product-buy-btn btn btn-primary btn-block js-basket-add" data-id="<?= $id ?>"><?= Loc::getMessage('RX_PAGE_PARTS_BASKET_BTN_TO_BASKET') ?></div>
    <a href="<?= $basketLink ?>" class="product-buy-basket-btn btn btn-primary btn-block"><?= Loc::getMessage('RX_PAGE_PARTS_BASKET_BTN_IN_BASKET') ?></a>
</div>
