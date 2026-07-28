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
        <div class="row tariffs <?=$arGroup['CLASS']?>" <?=$arGroup['ATTR']?>>
            <? foreach ($arGroup['ITEMS'] as $arItem): ?>
                <div class="col-12 tariff <?=$arResult['BLOCK_CLASSES'];?>">
                    <div class="row">

                        <div class="col-md-8">
                            <div class="tariff-info">
                                <?if($arItem['IMG']):?>
                                    <div class="tariff-img">
                                        <img class="lazy" alt="<?=$arItem['NAME'];?>" title="<?=$arItem['NAME'];?>"
                                             <?if($useLazyLoad):?> data-src="<?=$arItem['IMG']?>"
                                             <?else:?> src="<?=$arItem['IMG']?>"
                                             <?endif?>>
                                    </div>
                                <?endif;?>
                                <div class="tariff-text">
                                    <div class="tariff-title__wrapper">
                                        <span class="tariff-title block-el-title">
                                            <?=$arItem['~NAME'];?>
                                        </span>
                                        <? if ($arItem['PROPERTIES']['MARKERS']['VALUE']): ?>
                                            <div class="tariff-stickers">
                                                <? foreach ($arItem['PROPERTIES']['MARKERS']['VALUE'] as $key => $stickerName): ?>
                                                    <div>
                                                        <div class="sticker_<?=strtolower($arItem['PROPERTIES']['MARKERS']['VALUE_XML_ID'][$key])?>"><?=$stickerName?></div>
                                                    </div>
                                                <? endforeach; ?>
                                            </div>
                                        <? endif; ?>
                                    </div>
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

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">

                                    <div class="tariff-prices">
                                        <div class="tariff-price"><?=Helper::money($arItem['PRICE'])?></div>
                                        <?if($arItem['OLD_PRICE']):?>
                                            <div class="tariff-old-price"><?=Helper::money($arItem['OLD_PRICE'])?></div>
                                        <?endif?>
                                    </div>
                                    <?if($arItem['OLD_PRICE']):?>
                                        <div class="tariff-discount">
                                            <div class="discount-value">-<?=$arItem['DISCOUNT_PERCENT']?></div>
                                            <div class="discount-text">
                                                <?=Loc::getMessage('RX_BLOCK_LANDING_17_5_ECONOMY')?> <?=Helper::money($arItem['DISCOUNT_PRICE'])?>
                                            </div>
                                        </div>
                                    <?endif?>

                                </div>
                                <div class="col-lg-6 col-md-12">

                                    <?if($arItem['BTN']):?>
                                        <div class="tariff-btn_wrapper">
                                            <?= $arItem['BTN'] ?>
                                        </div>
                                    <?endif?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>

    <?endforeach?>
        <?= $arResult['BTN'] ?>
    </div>

<?= $arResult['BLOCK_END'] ?>
