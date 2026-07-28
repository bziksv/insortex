<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Page,
    Ranx\Landing\Helpers\Helper;
?>

<div id="megamenu">
    <a href="#" class="megamenu-close theme-color-hover js-megamenu-close">
        <?= Helper::svg('header/close'); ?>
    </a>

    <div class="megamenu-content">
        <div class="header-logo">
            <a href="<?= SITE_DIR ?>">
                <? Page::showLogo(); ?>
            </a>
        </div>
        <div class="megamenu-menu">
            <? Page::showMegamenu(); ?>
        </div>
        <?/*megamenu-order-btn*/?>
        <? Page::showBasketIcon('', true); ?>
        <? Page::showHeaderCity(); ?>
        <? Page::showHeaderPhones(); ?>
    </div>
</div>
