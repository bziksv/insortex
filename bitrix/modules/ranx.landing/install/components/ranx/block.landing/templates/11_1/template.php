<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$themeColor = Config::getThemeColor();
$mapControls = Config::getMapControls();
$isOneItem = count($arResult['ITEMS']) === 1;
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <? if ($arResult['ITEMS']): ?>
            <div class="row map-block <?if($isOneItem):?>one-item<?endif?>">
                <div class="col-lg-3 col-12 pl-0 pr-0">
                    <div class="contacts-wrapper">
                        <div class="contacts-list" data-list_items_wrapper>
                            <? foreach ($arResult['ITEMS'] as $arItem): ?>
                                <div class="item" data-list_item="<?=$arItem['ID']?>" data-coordinates="<?=$arItem['PROPERTIES']['MAP']['VALUE']?>">
                                    <div class="item-icon theme-color">
                                        <?= Helper::svg('block/contact_icon') ?>
                                    </div>
                                    <div class="item-title block-el-title">
                                        <?=$arItem['~NAME']?>
                                    </div>
                                    <?if(!empty($arItem['PROPERTIES']['PHONES']['VALUE'])):?>
                                        <div class="item-info">
                                            <div class="properties">
                                                <?if(is_array($arItem['PROPERTIES']['PHONES']['VALUE'])):?>
                                                    <?foreach($arItem['PROPERTIES']['PHONES']['VALUE'] as $phone):?>
                                                        <? $phone = Helper::formatAsproPhone($phone); ?>
                                                        <div class="property telephone">
                                                            <a href="tel:<?= Helper::phone($phone);?>" class="black"><?=$phone;?></a>
                                                        </div>
                                                    <?endforeach;?>
                                                <?endif;?>
                                            </div>
                                        </div>
                                    <?endif;?>
                                </div>
                            <? endforeach; ?>
                        </div>
                        <div class="contacts-detail_items" data-detail_list_wrapper>
                            <div class="detail-close theme-color-hover" data-detail_close>
                                <?= Helper::svg('block/contact_close'); ?>
                            </div>
                            <? foreach ($arResult['ITEMS'] as $arItem): ?>
                                <div class="contacts-detail" data-detail_item="<?=$arItem['ID']?>">
                                    <div class="detail-title block-el-title">
                                        <?=$arItem['~NAME']?>
                                    </div>
                                    <div class="detail-info">
                                        <div class="properties">
                                            <?if(!empty($arItem['PROPERTIES']['METRO']['VALUE'])):?>
                                                <div class="property metro">
                                                    <div class="title">
                                                        <?=$arItem['PROPERTIES']['METRO']['NAME'];?>
                                                    </div>
                                                    <div class="value">
                                                        <?=$arItem['PROPERTIES']['METRO']['~VALUE'];?>
                                                    </div>
                                                </div>
                                            <?endif;?>
                                            <?if(!empty($arItem['PROPERTIES']['SCHEDULE']['VALUE'])):?>
                                                <div class="property schedule">
                                                    <div class="title">
                                                        <?=$arItem['PROPERTIES']['SCHEDULE']['NAME'];?>
                                                    </div>
                                                    <div class="value">
                                                        <?=$arItem['PROPERTIES']['SCHEDULE']['~VALUE'];?>
                                                    </div>
                                                </div>
                                            <?endif;?>
                                            <?if(!empty($arItem['PROPERTIES']['PHONES']['VALUE'])):?>
                                                <div class="property telephone">
                                                    <div class="title">
                                                        <?=$arItem['PROPERTIES']['PHONES']['NAME'];?>
                                                    </div>
                                                    <?if(is_array($arItem['PROPERTIES']['PHONES']['VALUE'])):?>
                                                        <?foreach($arItem['PROPERTIES']['PHONES']['VALUE'] as $phone):?>
                                                            <? $phone = Helper::formatAsproPhone($phone); ?>
                                                            <div class="value">
                                                                <a href="tel:<?=Helper::phone($phone);?>"><?=$phone;?></a>
                                                            </div>
                                                        <?endforeach;?>
                                                    <?endif;?>
                                                </div>
                                            <?endif;?>
                                            <?if(!empty($arItem['PROPERTIES']['EMAIL']['VALUE'])):?>
                                                <div class="property email">
                                                    <div class="title">
                                                        <?=$arItem['PROPERTIES']['EMAIL']['NAME'];?>
                                                    </div>
                                                    <?if (is_array($arItem['PROPERTIES']['EMAIL']['VALUE'])):?>
                                                        <?foreach ($arItem['PROPERTIES']['EMAIL']['VALUE'] as $email):?>
                                                            <div class="value">
                                                                <a href="mailto:<?=$email?>"><?=$email?></a>
                                                            </div>
                                                        <?endforeach?>
                                                    <?else:?>
                                                    <div class="value">
                                                        <a href="mailto:<?=$arItem['PROPERTIES']['EMAIL']['VALUE'];?>"><?=$arItem['PROPERTIES']['EMAIL']['VALUE'];?></a>
                                                    </div>
                                                    <?endif?>
                                                </div>
                                            <?endif;?>
                                            <?if(!empty($arItem['PROPERTIES']['LOCATION']['VALUE'])):?>
                                                <div class="property address">
                                                    <div class="title">
                                                        <?=Loc::getMessage('RX_BLOCK_LANDING_11_1_LOCATION_PROP_NAME')?>
                                                    </div>
                                                    <div class="value">
                                                        <?=$arItem['PROPERTIES']['LOCATION']['~VALUE'];?>
                                                    </div>
                                                </div>
                                            <?endif?>
                                        </div>
                                    </div>
                                    <?if (!empty($arItem['BTN'])):?>
                                        <div class="detail-btn">
                                            <?=$arItem['BTN']?>
                                        </div>
                                    <?endif?>
                                </div>
                            <? endforeach; ?>
                        </div>
                        <div class="contacts-balloon_items">
                            <?foreach ($arResult['ITEMS'] as $arItem):?>
                                <? if (empty($arItem['PROPS']['POPUP_SHOW'])) continue; ?>

                                <div class="contacts-balloon_item" data-balloon_item="<?=$arItem['ID']?>">
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
                            <?endforeach?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-12 pl-0 pr-0">
                    <div class="map-wrapper">
                        <div class="map" id="map_<?=$arResult['ID']?>" style="width: 100%; height: 500px"></div>
                        <div class="map-empty">
                            <div class="map-empty-icon theme-color"><?= Helper::svg('block/map_empty') ?></div>
                            <div class="map-empty-title"><?= Loc::getMessage('RX_BLOCK_LANDING_11_1_MAP_EMPTY_TITLE') ?></div>
                            <div class="map-empty-desc"><?= Loc::getMessage('RX_BLOCK_LANDING_11_1_MAP_EMPTY_DESC') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <? endif; ?>

    </div>

<?= $arResult['BLOCK_END'] ?>

<script type="text/javascript">
    $(document).ready(function() {
        if (typeof ymaps !== 'undefined') {
            ymaps.ready(block11_1_init);
        } else {
            $('#block_<?=$arResult['ID']?>').find('.map-wrapper').addClass('empty');
        }

        function block11_1_init() {
            let $component = $('#block_<?=$arResult['ID']?>').find('.block'),
                $listItemsWrap = $component.find('[data-list_items_wrapper]'),
                $listItems = $component.find('[data-list_item]'),
                $detailListItemsWrap = $component.find('[data-detail_list_wrapper]'),
                $detailListItems = $component.find('[data-detail_item]'),
                $detailClose = $component.find('[data-detail_close]'),
                animationTime = 200;

            $listItems.on('click', function(e, hasBalloon) {
                let _this = $(this),
                    itemID = _this.data('list_item'),
                    $detailItem = $component.find('[data-detail_item="' + itemID + '"]'),
                    arCord = _this.data('coordinates').split(',');

                if (_this.data('coordinates') && !hasBalloon) {
                    closeBalloons11_1(geoObjects);
                    map.setCenter([arCord[0], arCord[1]], 17, {
                        checkZoomRange: true
                    });
                }

                $listItemsWrap.fadeOut(animationTime, function() {
                    $detailListItemsWrap.show();

                    $detailListItems.hide();
                    $detailItem.fadeIn(animationTime);
                })
            });

            $detailClose.on('click', function() {
                closeBalloons11_1(geoObjects);

                if (points.length){
                    map.setBounds(clusterer.getBounds(), {
                        checkZoomRange: true
                    });
                }

                let $detailItemActive = $component.find('[data-detail_item]:visible');
                $detailItemActive.fadeOut(animationTime, function() {
                    $detailListItemsWrap.hide();

                    $listItemsWrap.fadeIn(animationTime);
                });
            });

            let points = <?=\Bitrix\Main\Web\Json::encode($arResult['PLACEMARKS'])?>,
                itemIds = <?=\Bitrix\Main\Web\Json::encode(array_column($arResult['ITEMS'], 'ID'))?>,

                map = new ymaps.Map('map_<?=$arResult['ID']?>', {
                    center: [55.76, 37.64],
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
                }),

                clusterIcons = [{
                    size: [56, 56],
                    offset: [-28, -28]
                }],

                markerSVG = '<div class="marker_custom">' +
                    '<style>.cls-marker{position: absolute;bottom: 0;left: 0;}</style>' +
                    '<svg class="cls-marker" width="46" height="58" viewBox="0 0 46 58" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                    '<path d="M43 23C43 31 35.5 39 30.5 45C25.5 51 23 55 23 55C23 55 20.6033 51.09 15.5 45C10.6078 39.1619 2.99998 31.3205 3 23C3.00002 11.9543 11.9543 3 23 3C34.0457 3 43 11.9543 43 23Z" fill="<?=$themeColor?>"/>' +
                    '<path d="M22.9935 56.5C22.4741 56.4977 21.9929 56.2269 21.7213 55.7842L21.7213 55.7842L22.9935 56.5ZM22.9935 56.5C23.5128 56.5022 23.9963 56.2357 24.2717 55.7954M22.9935 56.5L24.2717 55.7954M24.272 55.795L24.2724 55.7943L24.2765 55.7878L24.2956 55.7576C24.3132 55.7299 24.3406 55.6871 24.3776 55.6296C24.4518 55.5147 24.5648 55.3413 24.7168 55.1133C25.0208 54.6573 25.4804 53.9831 26.0956 53.1219C27.3261 51.3992 29.1784 48.929 31.6523 45.9603C32.1237 45.3947 32.6228 44.8051 33.1399 44.1943C35.3147 41.6254 37.8061 38.6825 39.8731 35.5821C42.4269 31.7513 44.5 27.4468 44.5 23C44.5 11.1259 34.8741 1.5 23 1.5C11.1259 1.5 1.50002 11.1259 1.5 23C1.49999 27.6044 3.59699 31.9484 6.17145 35.7691C8.18196 38.7529 10.5873 41.5592 12.683 44.0042C13.268 44.6868 13.8289 45.3412 14.3503 45.9634C16.8748 48.976 18.7257 51.4444 19.9415 53.1533C20.5494 54.0077 20.9985 54.6721 21.2935 55.1196C21.441 55.3433 21.55 55.5128 21.6211 55.6246C21.6566 55.6805 21.6827 55.7221 21.6993 55.7487L21.7172 55.7776L21.7209 55.7836L21.7211 55.7839L24.272 55.795ZM24.272 55.795C24.2719 55.7951 24.2719 55.7952 24.2719 55.7952M24.272 55.795L24.2719 55.7952M24.2717 55.7954C24.2718 55.7954 24.2718 55.7953 24.2719 55.7952M24.2717 55.7954L24.2719 55.7952" stroke="white" stroke-opacity="0.4" stroke-width="3" stroke-linejoin="round"/>' +
                    '<circle cx="23" cy="23" r="12" fill="white"/>' +
                    '</svg>' +
                    '</div>',

                clusterSVG = '<div class="cluster_custom"><span>$[properties.geoObjects.length]</span>' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56">' +
                    '<defs><style>.cls-cluster, .cls-cluster3 {fill: #fff;}.cls-cluster {opacity: 0.5;}.cls-cluster2 {fill: <?=$themeColor?>;}</style></defs>' +
                    '<circle class="cls-cluster" cx="28" cy="28" r="28"/>' +
                    '<circle data-name="Ellipse 275 copy 2" class="cls-cluster2" cx="28" cy="28" r="25"/>' +
                    '<circle data-name="Ellipse 276 copy" class="cls-cluster3" cx="28" cy="28" r="18"/>' +
                    '</svg>' +
                    '</div>',

                clusterer = new ymaps.Clusterer({
                    clusterIcons: clusterIcons,
                    clusterIconContentLayout: ymaps.templateLayoutFactory.createClass(clusterSVG),
                }),

                geoObjects = [];

            for (let i = 0, len = points.length; i < len; i++) {
                if (!points[i] || !points[i].length) {
                    continue;
                }
                let $balloonItem = $component.find('[data-balloon_item="'+itemIds[i]+'"]');

                geoObjects.push(new ymaps.Placemark(points[i], {
                    balloonContent: $balloonItem.html(),
                }, {
                    iconLayout: ymaps.templateLayoutFactory.createClass(markerSVG),
                    iconShape: {
                        type: 'Rectangle',
                        coordinates: [
                            [-23, -58],
                            [23, 0]
                        ]
                    },
                    balloonMaxWidth: $(window).width() < 576 ? 255 : 450,
                }));
                geoObjects[geoObjects.length - 1].events.add('click', function (e) {
                    $component.find('[data-list_item="'+itemIds[i]+'"]').trigger('click', [!!$balloonItem.length]);
                });
            }

            clusterer.add(geoObjects);
            map.geoObjects.add(clusterer);

            map.behaviors.disable('scrollZoom');


            if (points.length) {
                map.setBounds(clusterer.getBounds(), {
                    checkZoomRange: true
                });
            }
        }

        function closeBalloons11_1(objects) {
            $.each(objects, function (i, object) {
                if (object.balloon.isOpen()) {
                    object.balloon.close();
                }
            });
        }
    });
</script>
