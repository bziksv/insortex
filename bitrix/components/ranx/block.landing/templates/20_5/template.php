<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 * @var CBitrixComponent $rootComponent
 */
$this->setFrameMode(true);

use Ranx\Landing\Page;
use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

$useLazyLoad = Config::isLazyLoadEnabled();
$useBasket = Config::isOrderEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">
    <?= $arResult['BLOCK_TITLE'] ?>

    <?if (!empty($arResult['BLOCK_FILTER'])):?>
        <div class="filter-wrap"><?= $arResult['BLOCK_FILTER'] ?></div>
    <?endif?>

    <?if(isset($arResult['ITEMS'])):
        $col = ($arResult['COLS']) ? 12 / $arResult['COLS'] : 3;
    ?>

    <div class="row <?if(empty($arResult['INDENT_ELEMENTS'])):?>no-gutters<?endif?> products block-loading-content">
        <?foreach($arResult['ITEMS'] as $i => $arItem):?>

        <div class="col-xl-<?=$col?> col-lg-4 col-sm-6">
            <div class="product" data-id="<?= $arItem['ID'] ?>">

                <?if(!empty($arItem['PROPERTIES']['MARKERS']['VALUE'])):?>
                    <div class="product-stickers">
                        <?foreach($arItem['PROPERTIES']['MARKERS']['VALUE_XML_ID'] as $j => $sticker):?>
                            <div class="product-sticker product-sticker-<?=strtolower($sticker)?>"><?=$arItem['PROPERTIES']['MARKERS']['VALUE'][$j]?></div>
                        <?endforeach?>
                    </div>
                <?endif?>

                <?if (!empty($arItem['LINK'])):?>
                    <a class="product-img <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                <?else:?>
                    <div class="product-img">
                <?endif?>

                    <img class="lazy" <?if($useLazyLoad):?>data-src="<?=$arItem['IMG']?>" <?else:?>src="<?=$arItem['IMG']?>" <?endif?>
                         alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">

                <?if(!empty($arItem['LINK'])):?>
                    </a>
                    <a class="product-name <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>><?=$arItem['NAME']?></a>
                <?else:?>
                    </div>
                    <div class="product-name"><?=$arItem['NAME']?></div>
                <?endif?>

                <div class="product-info">
                    <?if (isset($arItem['PROPS']['AVAILABLE'])):?>
                        <?if($arItem['PROPS']['AVAILABLE'] === 'Y'):?>
                            <div class="product-available product-available-yes">
                                <?= Loc::getMessage('RX_BLOCK_LANDING_20_5_AVAILABLE') ?>
                            </div>
                        <?else:?>
                            <div class="product-available product-available-no">
                                <?= Loc::getMessage('RX_BLOCK_LANDING_20_5_UNAVAILABLE') ?>
                            </div>
                        <?endif?>
                    <?endif?>

                    <?if(!empty($arItem['MARK'])):?>
                        <div class="product-rating">
                            <?for($i = 0; $i < $arItem['MARK']; $i++):?>
                                <div class="product-star product-star--on"><?= Helper::svg('block/star') ?></div>
                            <?endfor?>
                            <?for($i = 0; $i < (5 - $arItem['MARK']); $i++):?>
                                <div class="product-star product-star--off"><?= Helper::svg('block/star') ?></div>
                            <?endfor?>
                        </div>
                    <?endif?>
                </div>

                <div class="product-bottom">
                    <?if(!empty($arItem['PRICE'])):?>
                        <div class="product-prices">
                            <div>
                                <div class="product-price"><?=Helper::money($arItem['PRICE'])?></div>
                                <?if($arItem['OLD_PRICE']):?>
                                    <div class="product-price-old"><?=Helper::money($arItem['OLD_PRICE'])?></div>
                                <?endif?>
                            </div>
                            <?if($arItem['OLD_PRICE']):?>
                                <div>
                                    <div class="product-economy-percent">-<?=$arItem['DISCOUNT_PERCENT']?></div>
                                    <div class="product-economy-money"><?= Loc::getMessage('RX_BLOCK_LANDING_20_5_ECONOMY') ?> <?=Helper::money($arItem['DISCOUNT_PRICE'])?></div>
                                </div>
                            <?endif?>
                        </div>
                    <?endif?>

                    <?if(!$useBasket && $arItem['BTN']):?>
                        <div class="block-el-btns"><?=$arItem['BTN']?></div>
                    <?endif?>
                </div>

                <?if($useBasket):?>
                    <div class="product-buy">
                        <?if(!empty($arItem['LINK'])):?>
                            <a class="product-buy-detail btn" <?=$arItem['LINK']['ATTRS']?>>
                                <?= Loc::getMessage('RX_BLOCK_LANDING_20_5_DETAIL_BTN') ?>
                            </a>
                        <?endif?>
                        <a href="#"
                           class="product-buy-consult btn btn-primary js-form-modal"
                           data-form-code="ranx_landing_form_oneclick"
                           data-product-id="<?= $arItem['ID'] ?>">
                            <?= Loc::getMessage('RX_BLOCK_LANDING_20_5_CONSULT_BTN') ?>
                        </a>
                    </div>
                <?endif?>
            </div>
        </div>

        <?endforeach?>
    </div>
    <?endif?>

    <div class="pagination-wrap"><?= $arResult['NAV_STRING'] ?></div>
</div>

<script>
    $(document).ready(function() {
        $('#block_<?= $arResult['ID'] ?>').on('rxFilterItems', function (e, data) {
            $(this).find('.products').html($(data.html).find('.products').html());
            $(this).find('.pagination-wrap').html($(data.html).find('.pagination-wrap').html());

            if (typeof lazyLoadInstance !== 'undefined') {
                lazyLoadInstance.update();
            }

            endBlockLoad($(this).data('id'));
        });
    });
</script>

<?= $arResult['BLOCK_END'] ?>
