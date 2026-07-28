<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Page;
use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<div class="panel-tab-desc">
    <div class="panel-tab-title"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_TAB_TITLE') ?></div>
</div>

<div class="panel-tab-desc">
    <div class="row">
        <div class="col-8 panel-support-block-desc m-0"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_DOCS_DESC') ?></div>
        <div class="col-4 text-right">
            <a href="<?= Config::DOCS_URL ?>" target="_blank" rel="nofollow" class="btn btn-primary"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_DOCS') ?></a>
        </div>
    </div>
    <div class="theme-ul panel-support-faq">
        <ul>
            <li><a href="<?= Config::getDocArticleUrl('206-338--kak-sozdat-glavnuyu-stranicu') ?>" target="_blank"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_DOCS_FAQ_1') ?></a></li>
            <li><a href="<?= Config::getDocArticleUrl('209-258-496--yandex-metrika-goals') ?>" target="_blank"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_DOCS_FAQ_2') ?></a></li>
            <li><a href="<?= Config::getDocArticleUrl('185--regionality') ?>" target="_blank"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_DOCS_FAQ_3') ?></a></li>
        </ul>
    </div>
</div>

<?if(!Page::includePartnerSupport()):?>
    <div class="panel-support-block">
        <div class="panel-support-block-support">
            <div>
                <div class="panel-support-block-icon theme-color"><?= Helper::svg('panel', 'support_error') ?></div>
                <div class="panel-support-block-title"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_ERROR_TITLE') ?></div>
            </div>
            <div class="panel-support-idea">
                <div class="panel-support-block-icon theme-color"><?= Helper::svg('panel', 'support_idea') ?></div>
                <div class="panel-support-block-title"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_IDEA_TITLE') ?></div>
            </div>
        </div>
        <div class="panel-support-block-support-btn">
            <a href="https://ranx.ru/support/" class="btn btn-primary" target="_blank"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_BTN_MESSAGE') ?></a>
        </div>
    </div>

    <div class="panel-support-block">
        <div class="panel-support-block-left">
            <div class="panel-support-block-icon theme-color"><?= Helper::svg('panel', 'support_partner') ?></div>
            <div class="panel-support-block-title"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_PARTNER_TITLE') ?></div>
        </div>
        <div class="panel-support-block-right">
            <a href="https://landing-demo.ru/partners/" class="btn btn-primary" target="_blank"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_PARTNER_BTN') ?></a>
        </div>
    </div>

    <div class="panel-support-block">
        <div class="panel-support-block-left">
            <div class="panel-support-block-icon theme-color"><?= Helper::svg('panel', 'support_services') ?></div>
            <div class="panel-support-block-title"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_SERVICES_TITLE') ?></div>
        </div>
        <div class="panel-support-block-desc">
            <?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_TAB_TEXT') ?>
        </div>
        <div class="panel-support-services">
            <div class="panel-support-service">
                <div class="panel-support-service-title theme-before-bg"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_SERVICE_1') ?></div>
                <div class="panel-support-service-btn">
                    <a href="https://ranx.ru/site/" class="btn btn-mr btn-transparent" target="_blank"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_SERVICE_ORDER') ?></a>
                </div>
            </div>
            <div class="panel-support-service">
                <div class="panel-support-service-title theme-before-bg"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_SERVICE_2') ?></div>
                <div class="panel-support-service-btn">
                    <a href="https://ranx.ru/ecommerce/contextual-advertising/" class="btn btn-mr btn-transparent" target="_blank"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_SERVICE_ORDER') ?></a>
                </div>
            </div>
            <div class="panel-support-service">
                <div class="panel-support-service-title theme-before-bg"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_SERVICE_3') ?></div>
                <div class="panel-support-service-btn">
                    <a href="https://ranx.ru" class="btn btn-mr btn-transparent" target="_blank"><?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_SERVICE_ORDER') ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-support-customize">
        <?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_CUSTOMIZE_TEXT') ?>
        <a href="<?= Config::getDocArticleUrl('186-504--custom-support-info-for-partners') ?>" target="_blank">
            <?= Loc::getMessage('RX_PANEL_LANDING_SUPPORT_CUSTOMIZE_LINK') ?>
        </a>
    </div>
<?endif?>
