<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

$modalPosition = Config::getModalPosition();
?>

<?if(Config::isOrderEnabled()):?>
<div class="position-fixed top-0 right-0 p-3" style="z-index: 200; right: 0; top: 70px;">
    <div id="basket_add_notice" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <?= Helper::svg('header/close') ?>
        </button>
        <div class="toast-body">
            <a href="<?= Config::getOrderPageLink() ?>" class="notice-item">
                <div class="notice-item-img">
                    <img src="" alt="">
                </div>
                <div class="notice-item-info">
                    <div class="notice-item-title"><?= Loc::getMessage('RX_LANDING_FOOTER_MODAL_BASKET_ADDED') ?></div>
                    <div class="notice-item-desc"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<?endif?>

<div id="cardModal" class="modal modal-<?= $modalPosition ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close theme-stroke-hover" data-dismiss="modal">
                    <?= Helper::svg('form/close'); ?>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <a href="#" class="modal-card-back theme-color-hover-parent" data-dismiss="modal">
                    <i class="modal-card-back-icon theme-color-hover"><?= Helper::svg('block/modal_back') ?></i>
                    <?= Loc::getMessage('RX_LANDING_FOOTER_MODAL_CARD_BACK') ?>
                </a>
            </div>
        </div>
    </div>
</div>

<div id="formModal" class="modal modal-<?= $modalPosition ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"></div>
                <button type="button" class="close theme-stroke-hover" data-dismiss="modal">
                    <?= Helper::svg('form/close'); ?>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<div id="agreementModal" class="modal modal-center" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"></div>
                <button type="button" class="close theme-stroke-hover" data-dismiss="modal">
                    <?= Helper::svg('form/close'); ?>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<div id="videoModal" class="modal modal-center" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close theme-stroke-hover" data-dismiss="modal">
                <?= Helper::svg('form/close'); ?>
            </button>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<?if(Config::isRegionEnabled() && Config::getRegionsView() == 'popup_cities'):?>

    <?$APPLICATION->IncludeComponent('ranx:region.popup.landing', '', [], false, ['HIDE_ICONS' => 'Y']);?>

<?endif?>
