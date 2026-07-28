<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">

    <?= $arResult['BLOCK_TITLE'] ?>

    <div class="row">
        <div class="col-lg-3 left-block">
            <div class="service-links sticky-top">
                <? foreach ($arResult["ITEMS"] as $arItem): ?>
                    <div class="service-link">
                        <a class="service-anchor-link" data-href="#anchor-<?=$arResult["ID"]."-".$arItem["ID"];?>"><?=$arItem["NAME"]?></a>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 right-block service-list">
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <div class="row service-block">
                    <div class="<?=($arItem["IMG"] ? "col-md-6" : "col-12")?> service-info">
                        <div class="service-anchor" data-name="#anchor-<?=$arResult["ID"]."-".$arItem["ID"];?>"></div>
                        <div class="service-title block-el-title">
                            <?=$arItem["NAME"];?>
                        </div>
                        <?if($arItem['PREVIEW_TEXT']):?>
                            <div class="service-desc">
                                <?=$arItem['PREVIEW_TEXT'];?>
                            </div>
                        <? endif; ?>
                        <? if ($arItem['PROPS']['CHARS']):?>
                            <div class="service-props theme-ul">
                                <ul>
                                    <? foreach ($arItem['PROPS']['CHARS'] as $arProp): ?>
                                        <li class="service-props-item">
                                            <?=$arProp;?>
                                        </li>
                                    <? endforeach; ?>
                                </ul>
                            </div>
                        <? endif;?>
                        <? if ($arItem['PRICE']): ?>
                            <div class="service-prices">
                                <div class="service-price">
                                    <?= Helper::money($arItem['PRICE']) ?>
                                </div>
                                <? if ($arItem['OLD_PRICE']): ?>
                                    <div class="service-old-price">
                                        <?= Helper::money($arItem['OLD_PRICE']) ?>
                                    </div>
                                <? endif; ?>
                            </div>
                        <? endif; ?>

                        <?if($arItem['BTN'] || !empty($arItem['PROPS']['POPUP_SHOW'])):?>
                            <div class="service-btn">
                                <?if(!empty($arItem['PROPS']['POPUP_SHOW'])):?>
                                    <div class="block-el-btns">
                                        <div>
                                            <a class="btn <?=$arItem['PROPERTIES']['BTN_TYPE']['VALUE_XML_ID']?> <?=$arItem['PROPERTIES']['BTN_SIZE']['VALUE_XML_ID']?> js-card-modal"
                                               href="#" data-code="<?=$arResult['CODE']?>" data-id="<?=$arItem['ID']?>">
                                                <?=$arItem['PROPS']['POPUP_BTN_TEXT']?>
                                            </a>
                                        </div>
                                    </div>
                                <?else:?>
                                    <?= $arItem['BTN'] ?>
                                <?endif?>
                            </div>
                        <?endif?>
                    </div>
                    <?if($arItem["IMG"]):?>
                        <div class="col-md-6 service-img">
                            <img class="lazy" alt="<?=$arItem['NAME'];?>" title="<?=$arItem['NAME'];?>"
                                <?if($useLazyLoad):?> data-src="<?=$arItem["IMG"]['src']?>"
                                <?else:?>src="<?=$arItem["IMG"]['src']?>"
                                <?endif?>>
                        </div>
                    <?endif;?>
                </div>
            <? endforeach; ?>
        </div>
    </div>
</div>

<?= $arResult['BLOCK_END'] ?>
