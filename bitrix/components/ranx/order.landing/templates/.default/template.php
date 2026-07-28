<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
?>

<?if(!empty($arResult['BASKET_ITEMS'])):?>
<div class="order-form">
    <form class="form js-order-form" method="POST" novalidate>    
        <div class="row">
            <div class="col-12 col-md-8">
                <?$GLOBALS['APPLICATION']->IncludeComponent(
                    'ranx:basket.landing',
                    'order',
                    []
                );?>

                <div class="order-contact">
                    <div class="order-title"><?= Loc::getMessage('RX_ORDER_LANDING_CONTACT_TITLE') ?></div>

                    <div class="row">
                        <div class="col-12 col-md-7">
                            <div class="form-group">
                                <label><?= Loc::getMessage('RX_ORDER_LANDING_CONTACT_NAME') ?> <span>*</span></label>
                                <input type="text" class="form-control empty" name="NAME" required>
                            </div>
                            <div class="form-group">
                                <label><?= Loc::getMessage('RX_ORDER_LANDING_CONTACT_PHONE') ?> <span>*</span></label>
                                <input type="text" class="form-control phone empty" name="PHONE" required>
                            </div>
                            <div class="form-group">
                                <label><?= Loc::getMessage('RX_ORDER_LANDING_CONTACT_EMAIL') ?> <span>*</span></label>
                                <input type="email" class="form-control empty" name="EMAIL" required>
                                <div class="invalid-feedback">
                                    <?= Loc::getMessage('RX_ORDER_LANDING_CONTACT_EMAIL_INVALID') ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= Loc::getMessage('RX_ORDER_LANDING_CONTACT_COMPANY') ?></label>
                                <input type="text" class="form-control empty" name="COMPANY">
                            </div>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="form-group">
                                <label><?= Loc::getMessage('RX_ORDER_LANDING_CONTACT_COMMENT') ?></label>
                                <textarea class="form-control empty" name="COMMENT" maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

				<?/*?>
                <div class="order-delivery">
                    <div class="order-title"><?= Loc::getMessage('RX_ORDER_LANDING_DELIVERY_TITLE') ?></div>

<?if(!empty($arResult['DELIVERY_ITEMS'])):?>
                        <div class="order-delivery-options">

                            <?foreach($arResult['DELIVERY_ITEMS'] as $i => $delivery):?>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="order_delivery_<?= $i ?>" name="ORDER_DELVIERY" data-index="<?= $i ?>" data-show-address="<?= (bool)$delivery['SHOW_ADDRESS'] ?>"
                                        class="custom-control-input" <?if($i === $arResult['DELIVERY']):?>checked<?endif?>>
                                    <label class="custom-control-label" for="order_delivery_<?= $i ?>"><?= $delivery['NAME'] ?></label>
                                </div>
                            <?endforeach?>

                        </div>
                    <?endif?>


                    <div class="order-delivery-address form-group"
                        <?if(!empty($arResult['DELIVERY_ITEMS']) && !$arResult['DELIVERY_ITEMS'][$arResult['DELIVERY']]['SHOW_ADDRESS']):?>style="display: none"<?endif?>>
                        <label><?= Loc::getMessage('RX_ORDER_LANDING_DELIVERY_ADDRESS') ?></label>
                        <input type="text" class="form-control empty" name="ADDRESS">
                    </div>

                    <?if(!empty($arResult['DELIVERY_ITEMS'])):?>
                            
                        <div class="order-delivery-cost">

                            <?foreach($arResult['DELIVERY_ITEMS'] as $i => $delivery):?>
                                <div id="order_delivery_cost_<?= $i ?>" class="order-delivery-cost-item" <?if($i === $arResult['DELIVERY']):?>style="display: block"<?endif?>>
                                    <?if(!$delivery['COST']):?>
                                        <?= Loc::getMessage('RX_ORDER_LANDING_FREE') ?>
                                    <?else:?>
                                        <?= Loc::getMessage('RX_ORDER_LANDING_FROM') ?> <?= Helper::money($delivery['COST']) ?>
                                    <?endif?>
                                </div>
                            <?endforeach?>

                        </div>

                        <div class="order-delivery-caption">

                            <?foreach($arResult['DELIVERY_ITEMS'] as $i => $delivery):?>
                                <div id="order_delivery_caption_<?= $i ?>" class="order-delivery-caption-item" <?if($i === $arResult['DELIVERY']):?>style="display: block"<?endif?>>
                                    <?= $delivery['CAPTION'] ?>
                                </div>
                            <?endforeach?>

                        </div>

                    <?endif?>
                </div>
				<?*/?>
            </div>
            <div class="col-12 col-md-4">
                <div class="sticky-top">
                    <div class="order-result">
                        
                        <?php
                            include 'include/result.php';
                        ?>

                        <div class="order-result-bottom">

                            <button type="submit" class="order-btn btn btn-lg btn-primary btn-block">
                                <?= ($arResult['PAYMENT'] ? Loc::getMessage('RX_ORDER_LANDING_PAY_BTN') : Loc::getMessage('RX_ORDER_LANDING_ORDER_BTN')) ?>
                            </button>
                        </div>

                        <div class="order-result-loading"><div class="spinner-grow theme-color"></div></div>
                    </div>

                    <?if($arResult['USE_AGREEMENT']):?>
                    <div class="custom-control custom-checkbox" style="margin-bottom: 20px;">
                        <input type="checkbox" class="custom-control-input" id="order_agreement" <?if($arResult['AGREEMENT_ACTIVE']):?>checked<?endif?> required>
                        <label class="custom-control-label" for="order_agreement">
                            <?if(Config::getAgreementId()):?>
                                <?= Loc::getMessage('RX_ORDER_LANDING_AGREEMENT_ID_FIELD_LABEL') ?>
                            <?else:?>
                                <?= Loc::getMessage('RX_ORDER_LANDING_AGREEMENT_FIELD_LABEL', ['#LINK#' => $arResult['AGREEMENT_LINK']]) ?>
                            <?endif?>
                        </label>
                        <div class="invalid-feedback">
                            <?= Loc::getMessage('RX_ORDER_LANDING_AGREEMENT_FIELD_INVALID') ?>
                        </div>
                    </div>





                    <?endif?>
                </div>
            </div>
        </div>
    </form>

    <?if($arResult['PAYMENT']):?>
    <div class="order-payment"></div>
    <?endif?>

    <div class="order-complete-result">
        <div class="order-complete-icon">
            <svg width="198" height="248" viewBox="0 0 198 248" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 20C4 11.1634 11.1634 4 20 4H178C186.837 4 194 11.1634 194 20V228C194 236.837 186.837 244 178 244H20C11.1634 244 4 236.837 4 228V20Z" fill="#333333" fill-opacity="0.1" stroke="#333333" stroke-width="8"/>
                <circle cx="148" cy="44" r="11" fill="#333333"/>
                <circle cx="50" cy="44" r="11" fill="#333333"/>
                <path d="M47 49V72C47 100.719 70.2812 124 99 124V124C127.719 124 151 100.719 151 72V49" stroke="#333333" stroke-width="8" stroke-linecap="round"/>
                <path d="M50 44V72C50 99.062 71.938 121 99 121V121C126.062 121 148 99.062 148 72V44" stroke="white" stroke-width="8" stroke-linecap="round"/>
                <path class="order-complete-icon-success" fill-rule="evenodd" clip-rule="evenodd" d="M99 210C117.225 210 132 195.225 132 177C132 158.775 117.225 144 99 144C80.7746 144 66 158.775 66 177C66 195.225 80.7746 210 99 210ZM113.402 169.233L109.348 165.517L96.0202 180.056L88.4147 173.537L84.8353 177.713L96.4798 187.694L113.402 169.233Z" fill="#55B390"/>
                <path class="order-complete-icon-error" fill-rule="evenodd" clip-rule="evenodd" d="M99 210C117.225 210 132 195.225 132 177C132 158.775 117.225 144 99 144C80.7746 144 66 158.775 66 177C66 195.225 80.7746 210 99 210ZM86.0557 186.056L95.1112 177L86.0557 167.945L89.9448 164.056L99.0002 173.111L108.056 164.056L111.945 167.945L102.889 177L111.945 186.056L108.056 189.945L99.0002 180.889L89.9448 189.945L86.0557 186.056Z" fill="#FF685F"/>
            </svg>
        </div>
        <div class="order-complete-title order-complete-title-success"><?= Loc::getMessage('RX_ORDER_LANDING_ORDER_COMPLETE_SUCCESS') ?></div>
        <div class="order-complete-title order-complete-title-error"><?= Loc::getMessage('RX_ORDER_LANDING_ORDER_COMPLETE_ERROR') ?></div>
        <a href="<?= SITE_DIR ?>" class="order-complete-btn btn btn-primary btn-lg"><?= Loc::getMessage('RX_ORDER_LANDING_TO_MAINPAGE') ?></a>
    </div>
</div>
<?else:?>

    <div class="order-empty">
        <div class="order-empty-icon"><?= Helper::svg('basket', 'empty') ?></div>
        <div class="order-empty-title"><?= Loc::getMessage('RX_ORDER_LANDING_BASKET_EMPTY') ?></div>
    </div>

<?endif?>
