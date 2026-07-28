<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Page;
use Ranx\Landing\Config;

$slogan = Config::getSlogan();
?>

<header>
    <div class="header-main header-main-fluid">
        <div class="row">
            <div class="<?if(!empty($slogan)):?>col-md-3<?else:?>col-md-2<?endif?>">
                <div class="header-v-center header-margin-left">
                    <a class="header-logo" href="<?=SITE_DIR?>">
                        <? Page::showLogo() ?>
                    </a>
                    <?if(!empty($slogan)):?>
                    <div class="header-description">
                        <?= $slogan ?>
                    </div>
                    <?endif?>
                </div>
            </div>
            <div class="<?if(!empty($slogan)):?>col-md-9<?else:?>col-md-10<?endif?>">
                <div class="header-v-center header-margin-right justify-content-end">
                    <? Page::showHeaderMenu('header-nav-light'); ?>
                    <? Page::showHeaderPhones('pull-left'); ?>
                    <? Page::showHeaderCity('pull-left d-none d-xl-block'); ?>
                    <? Page::showHeaderBtn('header-order-btn'); ?>
                    <? Page::showHeaderSearch('pull-right'); ?>
                    <? Page::showHeaderBasket('pull-right'); ?>
                </div>
            </div>
        </div>
    </div>
</header>
