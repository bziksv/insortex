<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 * @var array $arParams
 */
$this->setFrameMode(true);

use Ranx\Landing\Helpers\FormHelper;
use Ranx\Landing\Page;
use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">
        <div class="row">
            <div class="col-lg-6 contact-info">

                <?= $arResult['BLOCK_TITLE'] ?>

                <div class="row contact-items">

                    <?if($phone = Config::getFirstPhone()):?>
                    <div class="col-sm-5 contact-item">
                        <div class="contact-item-name"><?= Loc::getMessage('RX_BLOCK_LANDING_18_2_PHONE') ?></div>
                        <div class="contact-item-value">
                            <a href="tel:<?= Helper::phone($phone['NUMBER'])?>"><?= $phone['NUMBER'] ?></a>
                        </div>
                    </div>
                    <?endif?>

                    <?if($email = Config::getPublicEmail()):?>
                        <div class="col-sm-5 contact-item">
                            <div class="contact-item-name"><?= Loc::getMessage('RX_BLOCK_LANDING_18_2_EMAIL') ?></div>
                            <div class="contact-item-value"><a href="mailto:<?= $email?>"><?= $email ?></a></div>
                        </div>
                    <?endif?>

                    <?if($address = Config::getAddress()):?>
                        <div class="col-sm-5 contact-item">
                            <div class="contact-item-name"><?= Loc::getMessage('RX_BLOCK_LANDING_18_2_ADDRESS') ?></div>
                            <div class="contact-item-value"><?= $address ?></div>
                        </div>
                    <?endif?>

                    <?if($schedule = Config::getSchedule()):?>
                        <div class="col-sm-5 contact-item">
                            <div class="contact-item-name"><?= Loc::getMessage('RX_BLOCK_LANDING_18_2_SCHEDULE') ?></div>
                            <div class="contact-item-value"><?= $schedule ?></div>
                        </div>
                    <?endif?>

                </div>

                <div class="block-18-2-socials">
                    <? Page::showBlockSocial();?>
                </div>

            </div>
            <div class="col-lg-6">

                <?if($arResult['FORM']):?>
                <div class="form-wrap form-btn-center" <?if(FormHelper::isB24Form($arResult['FORM'])):?>style="padding: 0;"<?endif?>>
                    <?$GLOBALS['APPLICATION']->IncludeComponent(
                        'ranx:form.landing',
                        '',
                        [
                            'FORM_CODE' => $arResult['FORM'],
                            'BTN_TEXT'  => $arResult['FORM_BTN_TEXT'],
                            'BLOCK_ID' => $arResult['ID'], // fixed a bug with agreement checkbox
                        ],
                        false,
                        [
                            'HIDE_ICONS' => 'Y',
                        ]
                    );?>
                </div>
                <?endif?>

            </div>
        </div>
    </div>

<?= $arResult['BLOCK_END'] ?>

<?if (\Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAjaxRequest()):?>
    <script>
        initMasks();
        initForms();
    </script>
<?endif?>
