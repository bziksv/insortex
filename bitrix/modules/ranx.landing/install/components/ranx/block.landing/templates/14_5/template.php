<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <?= $arResult['BLOCK_TITLE'] ?>
            </div>
                
            <div class="col-md-6 col-sm-12">
                <?if(!empty($arResult['ITEMS'])): ?>
                    <div class="block14-5-slider" id="block14-5-slider-<?=$arResult['ID'] ?>">
                        <div class="block14-5-items">
                            <?foreach($arResult['ITEMS'] as $arItem):?>
                                <div class="block14-5-item">
                                    <div class="block14-5-item-name block-el-title"><?=$arItem['NAME']?></div>
                                    <div class="block14-5-item-desc"><?=$arItem['PREVIEW_TEXT']?></div>
                                </div>
                            <?endforeach?>
                        </div>

                        <div class="block14-5-arrows">
                            <a class="slick-arrow arrow-prev btn-transparent">
                                <?= Helper::svg('block/arrow_prev') ?>
                            </a>
                            <a class="slick-arrow arrow-next btn-transparent">
                                <?= Helper::svg('block/arrow_next') ?>
                            </a>
                        </div>
                    </div>
                <?endif?>
            </div>

        </div>
    </div>

<?= $arResult['BLOCK_END'] ?>

<script>
    $(document).ready(function () {

        let $slider = $('#block14-5-slider-<?=$arResult['ID'] ?>').find('.block14-5-items');

        $slider.slick({
            cssEase: 'linear',
            prevArrow: $slider.siblings('.block14-5-arrows').find('.arrow-prev'),
            nextArrow: $slider.siblings('.block14-5-arrows').find('.arrow-next'),
            slidesToShow: 1,
            slidesToScroll: 1,
        });

        function setHeight () {
            const $row = $('#block_<?=$arResult['ID']?>').find('.row');

            $row.find('.block-title-text').css('height','auto');
            let height = $row.find('.block-title-text').height();
            let cattitle = $row.find('.block-title-text').outerHeight(true) + $row.find('.block-cattitle').height() - height;
            if (!cattitle) {
                cattitle = 0;
            }

            $row.find('.block14-5-item-name').each(function() {
                $(this).css('height','auto');
                height = Math.max(height, $(this).height());
            });

            if ($(window).width() > 767) {
                $row.find('.block-title-text').height(height);
                $row.find('.block14-5-item-name').each(function() {
                    $(this).height(height + cattitle);
                });
            }
        }

        $(window).on('resize', setHeight);
        setHeight();
    });
</script>
