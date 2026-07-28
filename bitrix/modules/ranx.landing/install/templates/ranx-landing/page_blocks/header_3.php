<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Page;
use Ranx\Landing\Config;
?>

<header>
    <div class="header-top">
        <div class="maxwidth-theme">

            <? Page::showHeaderCity('pull-left'); ?>
            <div class="header-address pull-left"><?= Config::getAddress(); ?></div>
            <? Page::showHeaderPhones('pull-left'); ?>

            <? Page::showHeaderBasket('pull-right'); ?>
            <? Page::showHeaderSearch('pull-right'); ?>
            <? Page::showHeaderBtn('btn-xs header-order-btn pull-right'); ?>
            <? Page::showHeaderSocial('pull-right'); ?>

        </div>
    </div>
    <div class="header-main">
        <div class="maxwidth-theme">
            <div class="row">
                <div class="col-md-4 col-xl-2">
                    <a class="header-logo header-logo-left" href="<?=SITE_DIR?>">
                        <? Page::showLogo() ?>
                    </a>
                </div>
                <div class="col-md-2 d-none d-xl-block">
                    <div class="header-description">
                        <?= Config::getSlogan(); ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <? Page::showHeaderMenu('header-nav-light pull-right', ''); ?>
                </div>
            </div>
        </div>
    </div>
</header>
