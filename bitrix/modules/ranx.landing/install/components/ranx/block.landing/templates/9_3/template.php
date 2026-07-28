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

        <div class="employees-wrapper slider--hide-counter <?= $arResult['SLIDER'] ? 'employees--slider' : '' ?>">
            <?if ($arResult['SLIDER']):?>
                <a class="slick-arrow arrow-prev btn-transparent">
                    <?= Helper::svg('block/arrow_prev') ?>
                </a>
            <?endif;?>

            <div class="row employees">
                <?foreach($arResult['ITEMS'] as $i => $arItem):?>
                <div class="col-lg-<?= $arResult['COLS'] ? 12 / $arResult['COLS'] : '3'?> col-sm-6 employee-wrap js-save-height">
                    <div class="employee">
                        <div class="employee-inner">
                            <?if(!empty($arItem['IMG'])):?>
                                <div class="employee-photo-wrap">
                                    <div class="employee-photo lazy" <?if($arItem['IMG']):?>
                                        <?if($useLazyLoad):?> data-bg="<?=$arItem['IMG']?>"
                                        <?else:?> style="background-image: url(<?=$arItem['IMG']?>);"
                                        <?endif?>
                                    <?endif?>></div>
                                </div>
                            <?endif?>

                            <?if(!empty($arItem['PROPS']['POST'])):?>
                                <div class="employee-post"><?= $arItem['PROPS']['POST'] ?></div>
                            <?endif?>

                            <?if(!empty($arItem['NAME'])):?>
                                <div class="employee-name block-el-title"><?= $arItem['NAME'] ?></div>
                            <?endif?>

                            <?if(!empty($arItem['PREVIEW_TEXT'])):?>
                                <div class="employee-desc"><?= $arItem['PREVIEW_TEXT'] ?></div>
                            <?endif?>

                            <div class="employee-info">

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

                    </div>
                </div>
                <?endforeach;?>
            </div>

            <?if ($arResult['SLIDER']):?>
                <a class="slick-arrow arrow-next btn-transparent">
                    <?= Helper::svg('block/arrow_next') ?>
                </a>
            <?endif;?>
        </div>

        <?if ($arResult['SLIDER']):?>
            <div class="slider-counter">
                <span class="current-slide"></span>/<span class="total-slide"></span>
            </div>
        <?endif;?>

        <?=$arResult['BTN']?>

    </div>

<?= $arResult['BLOCK_END'] ?>

<?if ($arResult['SLIDER']):?>
    <script>
        $(document).ready(function () {
            window['block_<?=$arResult['ID']?>_slider'] = new Block9_3Slider(<?=$arResult['ID']?>, <?=$arResult['COLS'] ?: 4?>);
        });
    </script>
<?endif;?>
