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

    <div class="service-slider">
        <a class="slick-arrow arrow-prev btn-transparent">
            <?= Helper::svg('block/arrow_prev') ?>
        </a>
        <div class="service-list row">
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <div class="service-item">
                    <div class="service-inner-wrap">
                        <div class="service-container">
                            <div class="service-info-wrap">
                                <?if($arItem['IMG']):?>
                                    <div class="service-img lazy"
                                         <?if($useLazyLoad):?> data-bg="<?=$arItem['IMG']['src']?>"
                                         <?else:?> style="background-image: url(<?=$arItem['IMG']['src']?>);"
                                         <?endif?>>
                                    </div>
                                <?endif;?>

                                <div class="service-info">
                                    <div class="service-name block-el-title">
                                        <?=$arItem['NAME'];?>
                                    </div>
                                    <?if($arItem['PREVIEW_TEXT']):?>
                                        <div class="service-desc">
                                            <?=$arItem['~PREVIEW_TEXT'];?>
                                        </div>
                                    <? endif; ?>
                                    <? if ($arItem['PROPS']['CHARS']):?>
                                        <div class="service-props">
                                            <? foreach ($arItem['PROPS']['CHARS'] as $arProp): ?>
                                                <div class="service-props-item">
                                                    <?=$arProp;?>
                                                </div>
                                            <? endforeach; ?>
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
                                </div>
                            </div>

                            <?if($arItem['BTN']):?>
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
                    </div>
                </div>
            <? endforeach; ?>
        </div>
        <a class="slick-arrow arrow-next btn-transparent">
            <?= Helper::svg('block/arrow_next') ?>
        </a>
    </div>

    <?=$arResult['BTN'];?>
</div>

<?= $arResult['BLOCK_END'] ?>

<script>
    $(document).ready(function () {
        $('.block4-3 .service-inner-wrap').hover(function() {
            $(this).css('height', $(this).outerHeight());

            const $info = $(this).find('.service-info-wrap');
            $info.css('height', $info.innerHeight());

            $(this).addClass('hover');
        }, function () {
            $(this).css('height', '');
            $(this).find('.service-info-wrap').css('height', '');
            $(this).removeClass('hover');
        });

        let $slider = $('#block_<?=$arResult['ID']?> .service-list');
        $slider.slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: '<?=$arResult["COLS"]?>',
            slidesToScroll: 1,
            swipeToSlide: true,
            prevArrow: '#block_<?=$arResult['ID']?> .arrow-prev',
            nextArrow: '#block_<?=$arResult['ID']?> .arrow-next',
            responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: false
                }
            },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        })
    });
</script>
