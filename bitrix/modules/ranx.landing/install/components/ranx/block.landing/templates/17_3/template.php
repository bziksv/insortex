<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();


Loc::loadMessages(__FILE__);
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>
        <?= $arResult['BLOCK_TABS'] ?>

    <?foreach ($arResult['GROUPS'] as $arGroup):?>
        <div class="tariffs <?=$arGroup['CLASS']?>" <?=$arGroup['ATTR']?>>
            <span data-prev-slide class="slick-arrow arrow-prev btn-transparent">
                <?= Helper::svg('block/arrow_prev') ?>
            </span>
            <div data-cols="<?=$arResult['COLS']?>" data-tariffs_slider>
                <? foreach ($arGroup['ITEMS'] as $arItem): ?>
                    <div class="tariff <?=$arResult['BLOCK_CLASSES'];?>">
                        <div class="tariff_wrapper">
                            <? if ($arItem['PROPERTIES']['MARKERS']['VALUE']): ?>
                                <div class="tariff-stickers">
                                    <? foreach ($arItem['PROPERTIES']['MARKERS']['VALUE'] as $key => $stickerName): ?>
                                        <div>
                                            <div class="sticker_<?=strtolower($arItem['PROPERTIES']['MARKERS']['VALUE_XML_ID'][$key])?>"><?=$stickerName?></div>
                                        </div>
                                    <? endforeach; ?>
                                </div>
                            <? endif; ?>

                            <?if(!empty($arItem['IMG'])):?>
                            <div class="tariff-img lazy"
                                 <?if($useLazyLoad):?> data-bg="<?=$arItem['IMG']?>"
                                 <?else:?> style="background-image: url(<?=$arItem['IMG']?>);"
                                 <?endif?>>
                            </div>
                            <?endif?>

                            <div class="tariff-info">
                                <div class="tariff-text">
                                    <span class="tariff-title block-el-title">
                                        <?=$arItem['~NAME'];?>
                                    </span>
                                    <? if ($arItem['PREVIEW_TEXT']): ?>
                                        <div class="tariff-desc">
                                            <?=$arItem['~PREVIEW_TEXT'];?>
                                        </div>
                                    <? endif; ?>
                                    <? if ($arItem['PROPS']['CHARS']): ?>
                                        <div class="tariff-props">
                                            <div class="theme-ul">
                                                <ul>
                                                    <? foreach ($arItem['PROPS']['CHARS'] as $prop): ?>
                                                        <li>
                                                            <?=$prop?>
                                                        </li>
                                                    <? endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <? endif; ?>
                                </div>

                                <div class="tariff-prices_wrapper">
                                    <div class="tariff-prices text-center">
                                        <div class="tariff-price"><?=Helper::money($arItem['PRICE'])?></div>
                                        <?if($arItem['OLD_PRICE']):?>
                                            <div class="tariff-old-price"><?=Helper::money($arItem['OLD_PRICE'])?></div>
                                        <?endif?>
                                    </div>
                                    <?if($arItem['OLD_PRICE']):?>
                                        <div class="tariff-discount text-center">
                                            <div class="discount-value">-<?=$arItem['DISCOUNT_PERCENT']?></div>
                                            <div class="discount-text">
                                                <?=Loc::getMessage('RX_BLOCK_LANDING_17_3_ECONOMY')?> <?=Helper::money($arItem['DISCOUNT_PRICE'])?>
                                            </div>
                                        </div>
                                    <?endif?>
                                </div>

                                <?if($arItem['BTN']):?>
                                <div class="tariff-btn_wrapper text-center">
                                    <?= $arItem['BTN'] ?>
                                </div>
                                <?endif?>
                            </div>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
            <span data-next-slide class="slick-arrow arrow-next btn-transparent">
                <?= Helper::svg('block/arrow_next') ?>
            </span>
        </div>

    <?endforeach?>
        <?= $arResult['BTN'] ?>
    </div>

<?= $arResult['BLOCK_END'] ?>
