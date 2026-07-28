<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Page,
    Ranx\Landing\Config;
?>

<header>
    <div class="header-main">
        <div class="maxwidth-theme">
            <div class="row">
                <div class="col-md-2">
                    <a class="header-logo header-logo-left" href="<?=SITE_DIR?>">
                        <? Page::showLogo() ?>
                    </a>
                </div>
                <div class="col-xl-2 d-none d-xl-block">
                    <div class="header-description">
                        <?= Config::getSlogan(); ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="header-v-center">
                        <? Page::showHeaderCity('pull-left'); ?>
                    </div>
                </div>
                <div class="col-md-8 col-xl-6">
                    <div class="header-v-center justify-content-end">
                        <? Page::showHeaderPhones('', true); ?>
                        <? Page::showHeaderBtn('header-order-btn'); ?>
                        <? Page::showHeaderSearch('pull-right'); ?>
                        <? Page::showHeaderBasket('pull-right'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-menu theme-bg">
        <div class="maxwidth-theme">
            <? Page::showHeaderMenu('header-nav-wide header-nav-dark', 'theme-bg theme-bg-hover theme-exclude-hover'); ?>
        </div>
    </div>
</header>
