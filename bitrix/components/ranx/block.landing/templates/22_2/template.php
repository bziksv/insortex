<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">
    <?=$arResult['BLOCK_TITLE']?>

    <div class="slider" id="slider_<?=$arResult['ID']?>">
        <a class="slick-arrow arrow-prev btn-transparent">
            <?= Helper::svg('block/arrow_prev') ?>
        </a>

        <div class="insta-cards">
            <?foreach ($arResult['ITEMS'] as $arItem):?>
            <div class="insta-card">
                <a class="card-link theme-exclude-hover" href="<?=$arItem['LINK']?>" target="_blank">
                    <div class="card-img lazy" <?if($useLazyLoad):?>data-bg="<?=$arItem['IMG']?>"
                         <?else:?>style="background-image: url('<?=$arItem['IMG']?>');"<?endif?>></div>
                    <div class="card-body">
                        <div class="card-header">
                            <div class="card-icon">
                                <?=Helper::svg('block/social', 'instagram_block')?>
                            </div>
                            <div class="card-date">
                                <?=$arItem['DATE']?>
                            </div>
                        </div>
                        <div class="card-text"><?=$arItem['TEXT']?></div>
                    </div>
                </a>
            </div>
            <?endforeach?>
        </div>

        <a class="slick-arrow arrow-next btn-transparent">
            <?= Helper::svg('block/arrow_next') ?>
        </a>
    </div>

    <?= $arResult['BTN'] ?>
</div>

<?= $arResult['BLOCK_END'] ?>

<script type="text/javascript">
    $(document).ready(function() {
        let $slider = $('#slider_<?=$arResult['ID']?> .insta-cards');

        function updateDots(slick) {
            slick.$slider.find('.slick-dots li').each(function () {
                $(this).addClass('theme-bg');
            });

            if (slick.$slider.find('.slick-track').children().length < 5) {
                slick.$slider.addClass('block22-2-hide-dots');
            }
        }

        function updateSimpleBar_22_2() {
            // without a timer, the simplebar does not work. Maybe the problem
            // is related to rendering in the event loop
            setTimeout(function() {
                $('#block_<?=$arResult['ID']?> .card-body').each(function() {
                    new SimpleBar(this);
                });
            });
        }

        $slider.on('init reInit', function(e, slick){
            updateDots(slick);
            updateSimpleBar_22_2();
        });

        $slider.on('breakpoint', function(e, slick) {
            updateDots(slick);
            updateSimpleBar_22_2();
        });

        $slider.slick({
            dots: true,
            adaptiveHeight: true,
            cssEase: 'linear',
            prevArrow: $slider.siblings('.arrow-prev'),
            nextArrow: $slider.siblings('.arrow-next'),
            slidesToShow: 4,
            slidesToScroll: 2,
            responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                }
            },
                {
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 550,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });
    });
</script>
