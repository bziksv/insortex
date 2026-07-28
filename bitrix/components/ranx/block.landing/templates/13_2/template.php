<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
/**
 * @var array $arParams
 * @var array $arResult
 */

use Ranx\Landing\Helpers\Helper;
use Ranx\Landing\Config;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">
    <?= $arResult['BLOCK_TITLE'] ?>

    <?if(!empty($arResult['ITEMS'])): ?>
    <div class="block13-2-slider" id="block13-2-slider-<?= $arResult['ID'] ?>">
        <a class="slick-arrow arrow-prev btn-transparent">
            <?= Helper::svg('block/arrow_prev') ?>
        </a>

        <div class="block13-2-brand-items">

            <?foreach($arResult['ITEMS'] as $i => $arItem):?>
            <div class="block13-2-slide-wrap">
                <?if(!empty($arItem['LINK'])):?>
                <a class="block13-2-brand-item <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                    <?else:?>
                    <div class="block13-2-brand-item">
                        <?endif?>

                        <img class="lazy" <?if($useLazyLoad):?>data-src="<?=$arItem['LOGO']?>"<?else:?>src="<?=$arItem['LOGO']?>"<?endif?>
                             alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">

                        <?if(!empty($arItem['LINK'])):?>
                </a>
                <?else:?>
            </div>
        <?endif?>
        </div>
        <?endforeach?>
    </div>

    <a class="slick-arrow arrow-next btn-transparent">
        <?= Helper::svg('block/arrow_next') ?>
    </a>
</div>
<?endif?>

<?= $arResult['BTN'] ?>
</div>

<?= $arResult['BLOCK_END'] ?>

<script>
    $(document).ready(function () {

        let $slider = $('#block13-2-slider-<?= $arResult['ID'] ?>').find('.block13-2-brand-items');

        $slider.on('init reInit afterChange', function(){
            if (typeof lazyLoadInstance !== 'undefined') {
                lazyLoadInstance.update();
            }
        });

        $slider.slick({
            <?if($arParams['SETTINGS']['AUTOPLAY']):?>autoplay: true,<?endif?>
            cssEase: 'linear',
            prevArrow: $slider.siblings('.arrow-prev'),
            nextArrow: $slider.siblings('.arrow-next'),
            slidesToShow: 6,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1090,
                    settings: {
                        slidesToShow: 4,
                    }
                },
                {
                    breakpoint: 770,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 450,
                    settings: {
                        slidesToShow: 1,
                    }
                }
            ],
        });

    });
</script>
