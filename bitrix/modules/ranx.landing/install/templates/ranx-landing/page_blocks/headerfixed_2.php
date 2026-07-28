<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Page;
?>

<div id="headerfixed">
    <div class="headerfixed-main">
        <div class="maxwidth-theme">
            <div class="row">
                <div class="col-md-5">
                    <div class="header-v-center">
                        <? Page::showHeaderBurger('pull-left'); ?>
                        <? Page::showHeaderCity('pull-left d-none d-xl-block'); ?>
                        <? Page::showHeaderPhones('pull-left'); ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <a class="header-logo" href="<?=SITE_DIR?>">
                        <? Page::showLogo() ?>
                    </a>
                </div>
                <div class="col-md-5">
                    <div class="header-v-center justify-content-end">
                        <? Page::showHeaderBtn('btn-sm headerfixed-order-btn'); ?>
                        <? Page::showHeaderSearch('pull-right'); ?>
                        <? Page::showHeaderBasket('pull-right'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?$GLOBALS['APPLICATION']->ShowViewContent('header_anchors');?>
</div>
