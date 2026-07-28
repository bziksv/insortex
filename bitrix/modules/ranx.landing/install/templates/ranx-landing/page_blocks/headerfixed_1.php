<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Page;
?>

<div id="headerfixed">
    <div class="headerfixed-main">
        <div class="maxwidth-theme">
            <div class="header-v-center pull-left">
                <? Page::showHeaderBurger('pull-left'); ?>
                <a class="header-logo" href="<?=SITE_DIR?>">
                    <? Page::showLogo() ?>
                </a>
            </div>

            <div class="header-v-center pull-right">
                <? Page::showHeaderBtn('btn-sm headerfixed-order-btn'); ?>
                <? Page::showHeaderSearch('pull-right'); ?>
                <? Page::showHeaderBasket('pull-right'); ?>
            </div>

            <div class="headerfixed-menu-center">
                <? Page::showHeaderMenu('header-nav-wide header-nav-light'); ?>
            </div>
        </div>
    </div>
    <?$GLOBALS['APPLICATION']->ShowViewContent('header_anchors');?>
</div>
