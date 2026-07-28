<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
?>

<a class="header-basket theme-stroke-hover <?= $classes ?>" href="<?= $basketLink ?>">
    <div class="header-basket-icon"><?= Helper::svg('header/cart') ?></div>

    <?if($showTitle):?>
        <span><?=Loc::getMessage('RX_PAGE_PARTS_BASKET_ICON_CART')?></span>
    <?endif?>

    <div class="header-basket-count theme-bg <?= (!$itemsCount ? 'empty' : '') ?>"><?= $itemsCount ?></div>
</a>
