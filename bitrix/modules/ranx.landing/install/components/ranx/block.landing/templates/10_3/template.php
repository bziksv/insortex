<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">
        <?= $arResult['BLOCK_TITLE'] ?>
    </div>

    <div class="block10-3-gallery">
        <? if (!empty($arResult['ITEMS'])) : ?>
            <div class="row block10-3-items no-gutters">
                <? foreach ($arResult['ITEMS'] as $i => $arItem) : ?>
                    <div class="col-sm-12 col-md-6 col-xl-3 block10-3-item-card no-gutters">
                            <? if ($arItem['DETAIL_IMG']) : ?>
                                <a class="fancybox lazy" href="<?= $arItem['DETAIL_IMG'] ?>"
                                data-fancybox="gallery<?=$arResult['ID']?>" alt="<?=$arItem['PROPS']['PICTURE_ALT']?>"
                                rel="gallery<?=$arResult['ID']?>" title="<?=$arItem['PROPS']['PICTURE_TITLE']?>"
                                data-caption="<?= $arItem['PREVIEW_TEXT'] ?>"
                                <?if($useLazyLoad):?> data-bg="<?=$arItem['IMG']?>"
                                <?else:?> style="background-image: url(<?=$arItem['IMG']?>);"
                                <?endif?>>
                                    <div class="dark-hover"></div>
                                </a>
                            <? endif ?>

                    </div>
                <? endforeach ?>
            </div>
        <? endif ?>
    </div>

    <div class="maxwidth-theme">
        <?= $arResult['BTN'] ?>
    </div>

<?= $arResult['BLOCK_END'] ?>
