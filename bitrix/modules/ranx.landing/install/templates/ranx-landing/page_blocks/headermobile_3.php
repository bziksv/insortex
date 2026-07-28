<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Page;
?>

<div id="headermobile">
    <div class="headermobile-main">
        <div class="maxwidth-theme">
            <div class="header-v-center pull-left">
                <? Page::showMobileHeaderBurger(); ?>
            </div>

            <div class="header-v-center pull-right">
                <? Page::showHeaderPhoneIcon('pull-right'); ?>
                <? Page::showHeaderSearch('pull-right'); ?>
                <? Page::showBasketIcon('pull-right'); ?>
            </div>

            <div class="header-v-center headermobile-logo-left">
                <a class="header-logo" href="<?=SITE_DIR?>">
                    <? Page::showLogo() ?>
                </a>
            </div>
        </div>
    </div>
</div>
