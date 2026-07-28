<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Page,
    Ranx\Landing\Config,
    Ranx\Landing\Helpers\Helper;
?>

<div id="mobilemenu">
    <div class="mobilemenu-wrap">
        <div class="mobilemenu-block mobilemenu-block-header">
            <div class="header-logo header-logo-left">
                <a href="<?= SITE_DIR ?>"><? Page::showLogo(); ?></a>
            </div>
            <div class="mobilemenu-close js-mobilemenu-close theme-color-hover"><?= Helper::svg('header/close'); ?></div>
        </div>
        <div class="mobilemenu-block">
            <div class="mobilemenu-menu">
                <? Page::showLinksMenu(); ?>
            </div>
            <? Page::showHeaderBtn('mobilemenu-order-btn') ?>
        </div>
        <?if(Config::isOrderEnabled()):?>
        <div class="mobilemenu-block">
            <? Page::showBasketIcon('', true); ?>
        </div>
        <?endif?>
        <div class="mobilemenu-block">
            <div class="mobilemenu-list">
                <? Page::showMobileMenuCity(); ?>
                <? Page::showMobileMenuPhones(); ?>
            </div>
        </div>
    </div>
</div>
<div id="mobilemenu-overlay"></div>
