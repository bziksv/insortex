<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="block12-3-slider" id="block12-3-slider-<?= $arResult['ID'] ?>">
            <a class="slick-arrow arrow-prev btn-transparent">
                <?= Helper::svg('block/arrow_prev') ?>
            </a>
            <div class="block12-3-slides">
                <? if (!empty($arResult['ITEMS'])) : ?>
                    <? foreach ($arResult['ITEMS'] as $i => $arItem) :
                        $isUser = $arItem['IMG'] || !empty($arItem['NAME']) || !empty($arItem['PROPS']['POST']);
                        $hideStars = $arItem['PROPS']['CHECK'] === 'Y';
                        ?>

                        <div class="block12-3-slide-wrap">
                            <div class="block12-3-slide">

                                <div class="block12-3-top <?if(!$isUser):?>justify-content-end<?endif?> <?if(!$isUser && $hideStars):?>d-none<?endif?>">

                                    <?if($isUser):?>

                                        <div class="block12-3-user">
                                            <?if($arItem['IMG']):?>
                                                <div class="block12-3-avatar lazy"
                                                     <?if($useLazyLoad):?> data-bg="<?=$arItem['IMG']?>"
                                                     <?else:?> style="background-image: url(<?=$arItem['IMG']?>);"
                                                     <?endif?>>
                                                </div>
                                            <?endif?>

                                            <div class="block12-3-info">
                                                <?if(!empty($arItem['PROPS']['POST'])):?>
                                                    <div class="block12-3-post"><?=$arItem['PROPS']['POST']?></div>
                                                <?endif?>

                                                <?if(!empty($arItem['NAME'])):?>
                                                    <div class="block12-3-name block-el-title"><?=$arItem['NAME']?></div>
                                                <?endif?>
                                            </div>
                                        </div>

                                    <?endif?>

                                    <?if(!$hideStars):?>
                                        <div class="block12-3-stars">
                                            <?for($i = 0; $i < $arItem['MARK']; $i++):?>
                                                <div class="block12-3-star block12-3-star--on"><?= Helper::svg('block/star') ?></div>
                                            <?endfor?>
                                            <?for($i = 0; $i < (5 - $arItem['MARK']); $i++):?>
                                                <div class="block12-3-star block12-3-star--off"><?= Helper::svg('block/star') ?></div>
                                            <?endfor?>
                                        </div>
                                    <?endif?>

                                </div>

                                <div class="d-flex">

                                    <div class="block12-3-quotes d-none d-md-block"><?= Helper::svg('block/quotes') ?></div>

                                    <div class="block-12-3-review">
                                        <div class="block12-3-text">
                                            <?= $arItem['PREVIEW_TEXT'] ?>
                                        </div>

                                        <?if(!empty($arItem['PROPS']['POPUP_SHOW'])):?>
                                            <a class="btn btn-transparent block12-3-more js-card-modal" href="#" data-code="<?=$arResult['CODE']?>" data-id="<?=$arItem['ID']?>">
                                                <?=$arItem['PROPS']['POPUP_BTN_TEXT']?>
                                            </a>
                                        <?endif?>

                                    </div>

                                </div>

                            </div>
                        </div>

                    <? endforeach ?>
                <? endif ?>
            </div>
            <a class="slick-arrow arrow-next btn-transparent">
                <?= Helper::svg('block/arrow_next') ?>
            </a>
        </div>

        <?= $arResult['BTN'] ?>

    </div>

<?= $arResult['BLOCK_END'] ?>

<script>
    $(document).ready(function () {

        let $slider = $('#block12-3-slider-<?= $arResult['ID'] ?>').find('.block12-3-slides');

        $slider.on('init reInit breakpoint', function(e, slick){
            slick.$slider.find('.slick-dots li').each(function () {
                $(this).addClass('theme-bg');
            });
            if (slick.$slides.length <= 2) {
                slick.$slider.addClass('block12-3-oneslide');
            }
        });

        $slider.slick({
            arrows: true,
            dots: true,
            cssEase: 'linear',
            prevArrow: $slider.siblings('.arrow-prev'),
            nextArrow: $slider.siblings('.arrow-next'),
            slidesToShow: 2,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ],
        });

    });
</script>
