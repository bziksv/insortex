<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="block9-5-slider-wrap">
            <a class="slick-arrow arrow-prev btn-transparent">
                <?= Helper::svg('block/arrow_prev') ?>
            </a>
            <div class="block9-5-slider" id="block9-5-slider--<?= $arResult['ID'] ?>">
                <?foreach($arResult['ITEMS'] as $i => $arItem):?>
                    <div class="block9-5-slide-wrap">
                        <div class="employee">
                            <div class="employee-info-wrap">

                                <div class="employee-photo-wrap">
                                    <div class="employee-photo lazy" <?if($arItem['IMG']):?>
                                        <?if($useLazyLoad):?> data-bg="<?=$arItem['IMG']?>"
                                        <?else:?> style="background-image: url(<?=$arItem['IMG']?>);"
                                        <?endif?>
                                    <?endif?>></div>
                                </div>

                                <div class="employee-info">

                                    <?if(!empty($arItem['PROPS']['POST'])):?>
                                        <div class="employee-post"><?= $arItem['PROPS']['POST'] ?></div>
                                    <?endif?>

                                    <?if(!empty($arItem['NAME'])):?>
                                        <div class="employee-name block-el-title"><?= $arItem['NAME'] ?></div>
                                    <?endif?>

                                    <?if(!empty($arItem['PROPS']['PHONE']) || !empty($arItem['PROPS']['EMAIL'])):?>
                                        <div class="employee-props">

                                            <?if(!empty($arItem['PROPS']['PHONE'])):?>
                                                <div class="employee-prop">
                                                    <div class="employee-prop-name"><?= $arItem['PROPERTIES']['PHONE']['NAME'] ?></div>
                                                    <a href="tel:+<?= Helper::onlyDigits($arItem['PROPS']['PHONE']) ?>" class="employee-prop-value"><?= $arItem['PROPS']['PHONE'] ?></a>
                                                </div>
                                            <?endif?>

                                            <?if(!empty($arItem['PROPS']['EMAIL'])):?>
                                                <div class="employee-prop">
                                                    <div class="employee-prop-name"><?= $arItem['PROPERTIES']['EMAIL']['NAME'] ?></div>
                                                    <a href="mailto:<?= $arItem['PROPS']['EMAIL'] ?>" class="employee-prop-value"><?= $arItem['PROPS']['EMAIL'] ?></a>
                                                </div>
                                            <?endif?>

                                        </div>
                                    <?endif?>

                                    <?if(!empty($arItem['SOCIALS'])):?>
                                        <div class="employee-socials">
                                            <?foreach($arItem['SOCIALS'] as $social):?>
                                                <a href="<?=$social['LINK']?>" title="<?=$social['NAME']?>">
                                                    <?=$social['SVG']?>
                                                </a>
                                            <?endforeach;?>
                                        </div>
                                    <?endif?>
                                </div>

                            </div>

                            <?if(!empty($arItem['PREVIEW_TEXT'])):?>
                                <div class="employee-desc"><?= $arItem['PREVIEW_TEXT'] ?></div>
                            <?endif?>
                        </div>
                    </div>
                <?endforeach;?>
            </div>
            <a class="slick-arrow arrow-next btn-transparent">
                <?= Helper::svg('block/arrow_next') ?>
            </a>
        </div>

        <?=$arResult['BTN']?>

    </div>

<?= $arResult['BLOCK_END'] ?>

<script>
    $(document).ready(function () {
        let $slider = $('#block9-5-slider--<?= $arResult['ID'] ?>');

        $slider.on('init reInit afterChange', function(e, slick){
            slick.$slider.find('.slick-dots li').each(function () {
                $(this).addClass('theme-bg');
            });
            if (slick.$slider.find('.slick-track').children().length < 3) {
                slick.$slider.addClass('block9-5-oneslide');
            }
        });

        $slider.slick({
            infinite: true,
            dots: true,
            slidesToShow: 2,
            slidesToScroll: 1,
            adaptiveHeight: true,
            prevArrow: $slider.siblings('.arrow-prev'),
            nextArrow: $slider.siblings('.arrow-next'),
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>
