<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
use Ranx\Landing\Config;
use Bitrix\Main\Localization\Loc;

$themeColor = Config::getThemeColor();
$mapControls = Config::getMapControls();
?>

<?=$arResult['BLOCK_START']?>

    <div class="maxwidth-theme">
        <?=$arResult['BLOCK_TITLE']?>

        <div class="slider" id="slider_<?=$arResult['ID']?>">
            <a class="slick-arrow arrow-prev btn-transparent">
                <?= Helper::svg('block/arrow_prev') ?>
            </a>

            <div class="map-blocks">
                <?if ($arResult['ITEMS']):?>
                    <?foreach ($arResult['ITEMS'] as $arItem):?>
                    <div class="map-block">
                        <div class="row ml-0 mr-0">
                            <div class="col-lg-6 col-12 pl-0 pr-0">
                                <div class="contact">
                                    <?if (!empty($arItem['PROPS']['CATTITLE'])):?>
                                    <div class="contact-top-text"><?=$arItem['PROPS']['CATTITLE']?></div>
                                    <?endif?>

                                    <div class="contact-title block-el-title"><?=$arItem['NAME']?></div>
                                    <div class="row contact-properties">
                                        <?$metro = $arItem['PROPERTIES']['METRO'];?>
                                        <?if ($metro && strlen($metro['VALUE'])):?>
                                        <div class="col-sm-6 col-12 property metro">
                                            <div class="name"><?=$metro['NAME']?></div>
                                            <div class="value"><?=$metro['VALUE']?></div>
                                        </div>
                                        <?endif?>

                                        <?$schedule = $arItem['PROPERTIES']['SCHEDULE'];?>
                                        <?if ($schedule && strlen($schedule['VALUE'])):?>
                                            <div class="col-sm-6 col-12 property schedule">
                                                <div class="name"><?=$schedule['NAME']?></div>
                                                <div class="value"><?=$schedule['VALUE']?></div>
                                            </div>
                                        <?endif?>

                                        <?$email = $arItem['PROPERTIES']['EMAIL'];?>
                                        <?if ($email && $email['VALUE']):?>
                                            <div class="col-sm-6 col-12 property email">
                                                <div class="name"><?=$email['NAME']?></div>
                                                <?if(is_array($email['VALUE'])):?>
                                                    <?foreach ($email['VALUE'] as $value):?>
                                                        <div class="value">
                                                            <a href="mailto:<?=$value?>"><?=$value?></a>
                                                        </div>
                                                    <?endforeach?>
                                                <?else:?>
                                                <div class="value">
                                                    <a href="mailto:<?=$email['VALUE'];?>"><?=$email['VALUE'];?></a>
                                                </div>
                                                <?endif?>
                                            </div>
                                        <?endif?>

                                        <?$phones = $arItem['PROPERTIES']['PHONES'];?>
                                        <?if ($phones && $phones['VALUE']):?>
                                            <div class="col-sm-6 col-12 property phones">
                                                <div class="name"><?=$phones['NAME']?></div>
                                                <?if (is_array($phones['VALUE'])) :?>
                                                    <?foreach ($phones['VALUE'] as $phone):?>
                                                        <? $phone = Helper::formatAsproPhone($phone); ?>
                                                    <div class="value">
                                                        <a href="tel:<?=Helper::phone($phone);?>"><?=$phone?></a>
                                                    </div>
                                                    <?endforeach?>
                                                <?endif?>
                                            </div>
                                        <?endif?>

                                        <?$address = $arItem['PROPERTIES']['LOCATION'];?>
                                        <?if ($address && $address['VALUE']):?>
                                            <div class="col-sm-6 col-12 property address">
                                                <div class="name">
                                                    <?=Loc::getMessage('RX_BLOCK_LANDING_11_3_LOCATION_PROP_NAME')?>
                                                </div>
                                                <div class="value"><?=$address['VALUE']?></div>
                                            </div>
                                        <?endif?>
                                    </div>

                                    <?if(!empty($arItem['SOCIALS'])):?>
                                    <div class="contact-social">
                                        <?foreach($arItem['SOCIALS'] as $social):?>
                                        <a href="<?=$social['LINK']?>" title="<?=$social['NAME']?>" target="_blank">
                                            <?=$social['SVG']?>
                                        </a>
                                        <?endforeach;?>
                                    </div>
                                    <?endif?>
                                    <div class="contact-btn"><?= $arItem['BTN'] ?></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 pl-0 pr-0">
                                <div class="map-wrapper">
                                    <div class="map" id="map_<?=$arItem['ID']?>"
                                         data-coordinates="<?=$arItem['PROPERTIES']['MAP']['VALUE']?>"
                                         style="width: 100%; min-height: 500px; height: 100%">
                                    </div>
                                    <div class="map-empty">
                                        <div class="map-empty-icon theme-color"><?= Helper::svg('block/map_empty') ?></div>
                                        <div class="map-empty-title"><?= Loc::getMessage('RX_BLOCK_LANDING_11_3_MAP_EMPTY_TITLE') ?></div>
                                        <div class="map-empty-desc"><?= Loc::getMessage('RX_BLOCK_LANDING_11_3_MAP_EMPTY_DESC') ?></div>
                                    </div>
                                </div>

                                <?if (!empty($arItem['PROPS']['POPUP_SHOW'])):?>
                                    <div class="balloon_item">
                                        <div class="balloon-wrap">
                                            <?if (!empty($arItem['NAME'])):?>
                                                <div class="balloon-name block-el-title"><?=$arItem['NAME']?></div>
                                            <?endif?>
                                            <?if (!empty($arItem['IMG'])):?>
                                                <img class="balloon-img" src="<?=$arItem['IMG']?>"
                                                     alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
                                            <?endif?>
                                            <?if (!empty($arItem['PREVIEW_TEXT'])):?>
                                                <div class="balloon-preview"><?=$arItem['PREVIEW_TEXT']?></div>
                                            <?endif?>
                                            <?if (!empty($arItem['BTN'])):?>
                                                <div class="balloon-btn"><?=$arItem['BTN']?></div>
                                            <?endif?>
                                        </div>
                                    </div>
                                <?endif?>
                            </div>
                        </div>
                    </div>
                    <?endforeach?>
                <?endif?>
            </div>

            <a class="slick-arrow arrow-next btn-transparent">
                <?= Helper::svg('block/arrow_next') ?>
            </a>
        </div>
    </div>

<?=$arResult['BLOCK_END']?>

<script type="text/javascript">
    $(document).ready(function() {
        initSlider11_3();

        if (typeof ymaps !== 'undefined') {
            ymaps.ready(initMaps11_3);
        } else {
            let $mapBlocks = getMapBlocks11_3();
            $mapBlocks.find('.map-wrapper').addClass('empty');
        }

        function initMaps11_3() {
            let $mapBlocks = getMapBlocks11_3();
            $mapBlocks.each(function () {
                let $mapBlock = $(this).find('.map');
                let mapId = $mapBlock.attr('id');
                if (!mapId || mapId.length === 0){
                    return;
                }

                if (!$mapBlock.data('coordinates')) {
                    return;
                }

                let cord = $mapBlock.data('coordinates').split(',');
                let map = new ymaps.Map(mapId, {
                    center: cord,
                    zoom: 17,
                    controls: <?= \Bitrix\Main\Web\Json::encode($mapControls) ?>
                }, {
                    geolocationControlFloat: 'right',
                    zoomControlFloat: 'none',
                    zoomControlPosition: {
                        right: '10px',
                        top: '50px',
                    },
                    maxZoom: 20,
                });

                let geoObject = new ymaps.Placemark(cord, {
                    balloonContent: $(this).find('.balloon_item').html(),
                }, {
                    iconLayout: ymaps.templateLayoutFactory.createClass(getTemplateMarker11_3()),
                    iconShape: {
                        type: 'Rectangle',
                        coordinates: [
                            [-23, -58],
                            [23, 0]
                        ]
                    },
                    balloonMaxWidth: $(window).width() < 576 ? 255 : 450,
                });
                map.geoObjects.add(createClusterer11_3().add(geoObject));
                map.behaviors.disable('scrollZoom');
            });
        }

        function createClusterer11_3() {
            let clusterIcons = [{
                size: [56, 56],
                offset: [-28, -28]
            }];

            return new ymaps.Clusterer({
                clusterIcons: clusterIcons,
                clusterIconContentLayout: ymaps.templateLayoutFactory.createClass(getTemplateCluster11_3()),
            });
        }

        function getTemplateMarker11_3() {
            return '<div class="marker_custom">' +
                '<style>.cls-marker{position: absolute;bottom: 0;left: -23px;}</style>' +
                '<svg class="cls-marker" width="46" height="58" viewBox="0 0 46 58" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                '<path d="M43 23C43 31 35.5 39 30.5 45C25.5 51 23 55 23 55C23 55 20.6033 51.09 15.5 45C10.6078 39.1619 2.99998 31.3205 3 23C3.00002 11.9543 11.9543 3 23 3C34.0457 3 43 11.9543 43 23Z" fill="<?=$themeColor?>"/>' +
                '<path d="M22.9935 56.5C22.4741 56.4977 21.9929 56.2269 21.7213 55.7842L21.7213 55.7842L22.9935 56.5ZM22.9935 56.5C23.5128 56.5022 23.9963 56.2357 24.2717 55.7954M22.9935 56.5L24.2717 55.7954M24.272 55.795L24.2724 55.7943L24.2765 55.7878L24.2956 55.7576C24.3132 55.7299 24.3406 55.6871 24.3776 55.6296C24.4518 55.5147 24.5648 55.3413 24.7168 55.1133C25.0208 54.6573 25.4804 53.9831 26.0956 53.1219C27.3261 51.3992 29.1784 48.929 31.6523 45.9603C32.1237 45.3947 32.6228 44.8051 33.1399 44.1943C35.3147 41.6254 37.8061 38.6825 39.8731 35.5821C42.4269 31.7513 44.5 27.4468 44.5 23C44.5 11.1259 34.8741 1.5 23 1.5C11.1259 1.5 1.50002 11.1259 1.5 23C1.49999 27.6044 3.59699 31.9484 6.17145 35.7691C8.18196 38.7529 10.5873 41.5592 12.683 44.0042C13.268 44.6868 13.8289 45.3412 14.3503 45.9634C16.8748 48.976 18.7257 51.4444 19.9415 53.1533C20.5494 54.0077 20.9985 54.6721 21.2935 55.1196C21.441 55.3433 21.55 55.5128 21.6211 55.6246C21.6566 55.6805 21.6827 55.7221 21.6993 55.7487L21.7172 55.7776L21.7209 55.7836L21.7211 55.7839L24.272 55.795ZM24.272 55.795C24.2719 55.7951 24.2719 55.7952 24.2719 55.7952M24.272 55.795L24.2719 55.7952M24.2717 55.7954C24.2718 55.7954 24.2718 55.7953 24.2719 55.7952M24.2717 55.7954L24.2719 55.7952" stroke="white" stroke-opacity="0.4" stroke-width="3" stroke-linejoin="round"/>' +
                '<circle cx="23" cy="23" r="12" fill="white"/>' +
                '</svg>' +
                '</div>';
        }

        function getTemplateCluster11_3() {
            return '<div class="cluster_custom"><span>$[properties.geoObjects.length]</span>' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56">' +
            '<defs><style>.cls-cluster, .cls-cluster3 {fill: #fff;}.cls-cluster {opacity: 0.5;}.cls-cluster2 {fill: <?=$themeColor?>;}</style></defs>' +
            '<circle class="cls-cluster" cx="28" cy="28" r="28"/>' +
            '<circle data-name="Ellipse 275 copy 2" class="cls-cluster2" cx="28" cy="28" r="25"/>' +
            '<circle data-name="Ellipse 276 copy" class="cls-cluster3" cx="28" cy="28" r="18"/>' +
            '</svg>' +
            '</div>';
        }

        function getMapBlocks11_3() {
            return $('#block_<?=$arResult['ID']?> .map-blocks .map-block');
        }
        
        function initSlider11_3() {
            let $slider = $('#slider_<?=$arResult['ID']?>').find('.map-blocks');

            $slider.on('init reInit', function(e, slick){
                if (slick.$slides.length <= 1) {
                    slick.$slider.find('.slick-dots').hide();
                }
                slick.$slider.find('.slick-dots li').each(function () {
                    $(this).addClass('theme-bg');
                });
            });

            $slider.slick({
                dots: true,
                cssEase: 'linear',
                adaptiveHeight: true,
                swipe: false,
                prevArrow: $slider.siblings('.arrow-prev'),
                nextArrow: $slider.siblings('.arrow-next'),
                slidesToShow: 1,
            });
        }
    });
</script>
