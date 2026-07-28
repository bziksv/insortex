<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
/**
 * @var array $arParams
 * @var array $arResult
 */

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<style>
    @media (min-width: 1280px) {
        .block1-3-height--<?=$arResult['ID']?> {
            height: <?=$arResult['BLOCK_HEIGHT']?>px !important;
        }
    }
    <?foreach ($arResult['ITEMS'] as $i => $arItem):?>
        <?if(!empty($arItem['VIDEO_RATIO']) && !empty($arItem['VIDEO_HEIGHT'])):?>
            .block1-3-bg-video-<?=$arItem['ID']?> {
                padding-top: calc(<?= 1 / $arItem['VIDEO_RATIO'] ?> * 100%);
            }
            @media (max-width: <?=($arItem['VIDEO_HEIGHT'] * $arItem['VIDEO_RATIO']).'px'?>) {
                .block1-3-bg-video-<?=$arItem['ID']?> {
                    width: calc(<?=$arItem['VIDEO_RATIO']?> * <?=$arItem['VIDEO_HEIGHT']?>px);
                    padding-top: 0;
                    height: <?=$arItem['VIDEO_HEIGHT']?>px;
                }
            }
        <?endif?>
    <?endforeach?>
</style>

<?= $arResult['BLOCK_START'] ?>

    <div class="block1-3-slider block-sort-<?= $arResult['SORT'] ?>" data-autoplay="<?= $arParams['SETTINGS']['AUTOPLAY'] ?>">
        <? if (!empty($arResult['ITEMS'])) : ?>
            <? foreach ($arResult['ITEMS'] as $i => $arItem) : ?>
                <? $additionalClass = '';
                $additionalClass .= !empty($arItem['VIDEO_ID']) ? 'block1-3-video' : '';
                $additionalClass .= ' block1-3-mobile-type--'.$arItem['MOBILE_TYPE'];
                $additionalClass .= ' block1-3-height--'.$arResult['ID'];
                ?>
                <div class="block1-3-bg-image lazy <?=$additionalClass?>" style="
                    <?if($arItem['PROPS']['BG_COLOR']):?>background-color: <?=$arItem['PROPS']['BG_COLOR']?>;<?endif?>
                    <?if($arItem['BG_IMG']):?>
                        <?if($useLazyLoad):?>" data-bg="<?=$arItem['BG_IMG']?>
                        <?else:?> background-image: url(<?=$arItem['BG_IMG']?>);<?endif?>
                    <?endif?>">

                    <?if(!empty($arItem['VIDEO_ID'])):?>
                        <div class="block1-3-bg-video block1-3-bg-video-<?=$arItem['ID']?>">
                            <? //don't remove "frameborder" ?>
                            <iframe src="<?=$arItem['VIDEO_SRC']?>" frameborder="0" allowfullscreen></iframe>
                        </div>
                    <?endif?>

                    <?if($arResult['TINT_COLOR']):?>
                        <div class="toner-block" style="background-color: <?= $arResult['TINT_COLOR'] ?>"></div>
                    <?endif?>
                    <div class="maxwidth-theme height-inherit">
                        <div class="row height-fill">
                            <? $arLightTextClasses = preg_filter('/^/', 'text-light-', $arItem['PROPERTIES']['TEXT_LIGHT']['VALUE_XML_ID']); ?>
                            <? $lightTextClasses = implode(' ', is_array($arLightTextClasses) ? $arLightTextClasses : []); ?>
                            <div class="block-title lazy col-md-<?= !empty($arItem['IMG']) ? '6' : '8' ?> block1-3-align-content <?= $lightTextClasses ?> header-light-target-<?= $arResult['SORT'] ?>-<?= $i ?>"
                                <?if ($arItem['MOBILE_TYPE'] === 'text_on_image'):?>style="
                                    <?if($arItem['PROPS']['BG_COLOR']):?>background-color:<?=$arItem['PROPS']['BG_COLOR']?>;<?endif?>
                                    <?if($useLazyLoad):?>" data-bg="<?=$arItem['MOBILE_IMG'] ?: $arItem['BG_IMG']?>
                                    <?else:?> background-image: url(<?=$arItem['MOBILE_IMG'] ?: $arItem['BG_IMG']?>);
                                    <?endif?>
                                "<?endif;?>
                            >
                                <?if(!empty($arItem['PROPS']['CATTITLE'])):?>
                                    <span class="block-cattitle"><?=$arItem['PROPS']['CATTITLE']?></span>
                                <?endif?>
                                <? if (!empty($arItem['NAME'])) : ?>
                                    <<?=$arResult['TITLE_TAG']?> class="block-title-text"><?= $arItem['~NAME'] ?></<?=$arResult['TITLE_TAG']?>>
                                <? endif ?>
                                <? if (!empty($arItem['PREVIEW_TEXT'])) : ?>
                                    <div class="block-subtitle">
                                    <?if(strpos($arItem['PREVIEW_TEXT'], '<') === 0): // has own tags ?>
                                        <?= $arItem['~PREVIEW_TEXT'] ?>
                                    <?else:?>
                                        <p><?= $arItem['~PREVIEW_TEXT'] ?></p>
                                    <?endif?>
                                    </div>
                                <? endif ?>
                                <? if (!empty($arItem['PRICE']) || !empty($arItem['OLD_PRICE'])) : ?>
                                    <div class="block1-3-prices">
                                        <? if (!empty($a3rItem['PRICE'])) : ?>
                                            <div class="block1-3-price"><?= Helper::money($arItem['PRICE']) ?></div>
                                        <? endif ?>
                                        <? if (!empty($arItem['OLD_PRICE'])) : ?>
                                            <div class="block1-3-price-old"><?= Helper::money($arItem['OLD_PRICE']) ?></div>
                                        <? endif ?>
                                    </div>
                                <? endif ?>

                                <?=$arItem['BTN']?>

                            </div>

                            <? if ($arItem['MOBILE_TYPE'] === 'only_image'):?>
                                <?if (!empty($arItem['LINK'])):?>
                                    <a class="col-md-6 col-flex-rw p-0 lazy block1-3-image block1-3-mobile-img <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                                <?else:?>
                                    <div class="col-md-6 col-flex-rw p-0 lazy block1-3-image block1-3-mobile-img">
                                <?endif;?>

                                    <img class="lazy" alt="<?=htmlspecialchars(strip_tags($arItem['NAME']))?>"
                                        <?if($useLazyLoad):?> data-src="<?=$arItem['MOBILE_IMG']?>"
                                        <?else:?> src="<?=$arItem['MOBILE_IMG']?>"<?endif?>
                                    />

                                <?if (!empty($arItem['LINK'])):?>
                                    </a>
                                <?else:?>
                                    </div>
                                <?endif;?>
                            <?endif?>

                            <? if (!empty($arItem['IMG']) || !empty($arItem['BG_IMG']) || !empty($arItem['MOBILE_IMG'])) : ?>
                                <? $additionalClass = '';
                                $additionalClass .= empty($arItem['IMG']) ? 'block1-3-no-image' : '';
                                $additionalClass .= !empty($arItem['MOBILE_IMG']) ? ' block1-3-set-mobile-img' : ''; ?>
                                <div class="col-md-6 col-flex-rw p-0 lazy block1-3-image <?= $additionalClass ?>" style="
                                    <?if($arItem['PROPS']['BG_COLOR']):?>background-color:<?=$arItem['PROPS']['BG_COLOR']?>;<?endif?>
                                    <?if($arItem['BG_IMG'] || $arItem['MOBILE_IMG']):?>
                                        <?if($useLazyLoad):?>" data-bg="<?= $arItem['MOBILE_IMG'] ?: $arItem['BG_IMG']?>
                                        <?else:?> background-image: url(<?= $arItem['MOBILE_IMG'] ?: $arItem['BG_IMG']?>);<?endif?>
                                    <?endif?>">

                                    <? if (!empty($arItem['IMG'])) : ?>
                                        <img class="lazy" alt="<?=htmlspecialchars(strip_tags($arItem['NAME']))?>"
                                             <?if($useLazyLoad):?> data-src="<?=$arItem['IMG']?>"
                                             <?else:?> src="<?=$arItem['IMG']?>"<?endif?>>
                                    <? endif ?>
                                </div>
                            <? endif ?>
                        </div>
                    </div>
                </div>
            <? endforeach ?>
        <? endif ?>
    </div>

<?= $arResult['BLOCK_END'] ?>
