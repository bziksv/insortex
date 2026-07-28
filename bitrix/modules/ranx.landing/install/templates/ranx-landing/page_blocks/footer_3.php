<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Page;

$footerBg = Config::getFooterBg();

$phones = Config::getPhones();
$phone = !empty($phones) ? reset($phones)['NUMBER'] : '';
$email = Config::getPublicEmail();
$address = Config::getAddress();
$politicsLink = Config::getPoliticsLink();
$hasFooterButton = Config::get('FOOTER_BTN_SHOW');
?>

<footer class="<?if($footerBg):?>footer-<?=$footerBg?><?endif?>">
    <div class="maxwidth-theme">

        <div class="row">
            <div class="<?if(!$hasFooterButton):?>col-xl-9<?else:?>col-xl-7<?endif?> col-lg-12">

                <? Page::showFooterMenu(); ?>
                <? Page::showFooterSocial(); ?>

            </div>

            <div class="col-xl-3 <?if(!$hasFooterButton):?>col-lg-12<?else:?>col-lg-6<?endif?> col-md-12">
                <?if($address || $phone || $email):?>
                <div class="footer-items">

                    <?if($address):?>
                    <div class="footer-item justify-content-xl-end">
                        <div class="footer-item-text"><?= $address ?></div>
                    </div>
                    <?endif?>

                    <?if($phone):?>
                    <div class="footer-item justify-content-xl-end">
                        <a class="footer-item-text" href="tel:<?= Helper::phone($phone) ?>"><?= $phone ?></a>
                    </div>
                    <?endif?>

                    <?if($email && strpos($email, '@') !== false):?>
                    <div class="footer-item justify-content-xl-end">
                        <a href="mailto:<?= $email ?>" class="footer-item-text"><?= $email ?></a>
                    </div>
                    <?endif?>

                </div>
                <?endif?>
            </div>
            <?if($hasFooterButton):?>
            <div class="col-xl-2 col-lg-6 col-md-12">
                <div class="footer-order-btn-wrap"><? Page::showFooterBtn('footer-order-btn'); ?></div>
            </div>
            <?endif?>

        </div>

        <div class="footer-line"></div>

        <div class="row">
            <div class="col-md-8">
                <? Page::showFooterCopyright(); ?>
                <? Page::showFooterPayoptions(); ?>
            </div>
            <div class="col-md-4">
                <div class="footer-links">
                    <?if(Config::getPoliticsId()):?>
                        <a href="#" class="footer-link js-form-politics"><?=Loc::getMessage('RX_FOOTER_POLITICS')?></a>
                    <?elseif($politicsLink):?>
                        <a href="<?= $politicsLink ?>" target="_blank" class="footer-link"><?=Loc::getMessage('RX_FOOTER_POLITICS')?></a>
                    <?endif?>
                    <? Page::showRanxLogo(); ?>
                </div>
            </div>
        </div>

    </div>
</footer>
