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
?>

<footer class="<?if($footerBg):?>footer-<?=$footerBg?><?endif?>">
    <div class="maxwidth-theme">

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">

                <?if($address || $phone || $email):?>
                <div class="footer-items">

                    <?if($address):?>
                    <div class="footer-item">
                        <div class="footer-item-icon"><?= Helper::svg('footer/icons', 'address') ?></div>
                        <div class="footer-item-text"><?= $address ?></div>
                    </div>
                    <?endif?>

                    <?if($phone):?>
                    <div class="footer-item">
                        <div class="footer-item-icon"><?= Helper::svg('footer/icons', 'phone') ?></div>
                        <a class="footer-item-text" href="tel:<?= Helper::phone($phone) ?>"><?= $phone ?></a>
                    </div>
                    <?endif?>

                    <?if($email && strpos($email, '@') !== false):?>
                    <div class="footer-item">
                        <div class="footer-item-icon"><?= Helper::svg('footer/icons', 'email') ?></div>
                        <a href="mailto:<?= $email ?>" class="footer-item-text"><?= $email ?></a>
                    </div>
                    <?endif?>

                </div>
                <?endif?>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <? Page::showFooterSocial(); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <? Page::showFooterPayoptions(); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <div class="footer-links d-flex flex-wrap justify-content-center">
                    <? Page::showFooterCopyright(); ?>

                    <?if(Config::getPoliticsId()):?>
                        <a href="#" class="footer-link js-form-politics"><?=Loc::getMessage('RX_FOOTER_POLITICS')?></a>
                    <?elseif($politicsLink):?>
                        <a href="<?= $politicsLink ?>" target="_blank" class="footer-link"><?=Loc::getMessage('RX_FOOTER_POLITICS')?></a>
                    <?endif?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <? Page::showRanxLogo(); ?>
            </div>
        </div>

    </div>
</footer>
