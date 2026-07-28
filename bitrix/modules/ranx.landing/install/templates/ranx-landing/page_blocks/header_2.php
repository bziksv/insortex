<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Page;
?>

<header>
    <div class="header-main header-main-fluid">
        <div class="row">
            <div class="col-md-4 col-xl-5">
                <div class="header-v-center">
                    <? Page::showHeaderBurger('pull-left'); ?>
                    <? Page::showHeaderCity('pull-left d-none d-xl-block'); ?>
                    <? Page::showHeaderPhones('pull-left'); ?>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <a class="header-logo" href="<?=SITE_DIR?>">
                    <? Page::showLogo() ?>
                </a>
            </div>
            <div class="col-md-4 col-xl-5">
                <div class="header-v-center justify-content-end">
                    <? Page::showHeaderBtn('header-order-btn'); ?>
                    <? Page::showHeaderSearch('pull-right'); ?>
                    <? Page::showHeaderBasket('pull-right'); ?>
                </div>
            </div>
        </div>
    </div>
</header>
