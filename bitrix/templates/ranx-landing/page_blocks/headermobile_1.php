<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Page;
?>

<div id="headermobile">
    <div class="headermobile-main">
        <div class="maxwidth-theme">
            <div class="header-v-center pull-left">
                <a class="header-logo header-logo-left" href="<?=SITE_DIR?>">
                    <? Page::showLogo() ?>
                </a>
            </div>
            <div class="header-v-center pull-right">
                <? Page::showMobileHeaderBurger(); ?>
            </div>
        </div>
    </div>
</div>
