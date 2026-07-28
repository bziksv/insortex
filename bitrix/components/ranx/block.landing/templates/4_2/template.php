<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="row service-list <?if(!$arResult['INDENT_ELEMENTS']):?>no-gutters<?endif?>">
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <div class="row-item <?=$arResult['BLOCK_CLASSES'];?>">

                    <div class="service-item">
                        <div class="service-container">
                            <div class="row">
                                <div class="service-info-wrap <?=($arItem['IMG_PATH'] ? "col-9" : "col-12")?>">
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
                                <?if($arItem['IMG_PATH'] && Helper::isSvg($arItem['IMG_INFO']['CONTENT_TYPE'])):?>
                                    <div class="col-3">
                                        <div class="service-img theme-color">
                                            <?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . $arItem['IMG_PATH'])?>
                                        </div>
                                    </div>
                                <?elseif($arItem['IMG']):?>
                                    <div class="col-3">
                                        <div class="service-img">
                                            <img class="lazy" alt="<?=$arItem['NAME'];?>" title="<?=$arItem['NAME'];?>"
                                                <?if($useLazyLoad):?> data-src="<?= $arItem['IMG'] ?>"
                                                <?else:?>src="<?= $arItem['IMG'] ?>"
                                                <?endif?>>
                                        </div>
                                    </div>
                                <?endif;?>
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

        <?=$arResult['BTN'];?>

    </div>

<?= $arResult['BLOCK_END'] ?>

<script>
    $(document).ready(function () {
        $('.block4-2 .service-item').hover(function() {
            $(this).css('height', $(this).outerHeight());

            const $info = $(this).find('.service-info-wrap');
            $info.css('height', $info.innerHeight());

            $(this).addClass('hover');
        }, function () {
            $(this).css('height', '');
            $(this).find('.service-info-wrap').css('height', '');
            $(this).removeClass('hover');
        });
    });
</script>
