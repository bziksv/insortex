<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="block10-2-gallery block10-2-gallery--<?=$arResult['COLS']?>">
            <? if (!empty($arResult['ITEMS'])) : ?>
                <div class="row block10-2-items">
                    <? foreach ($arResult['ITEMS'] as $i => $arItem) : ?>
                        <div class="col-sm-12 col-md-6 <?if($arResult['COLS'] == 4):?>col-xl-3<?else:?>col-xl-4<?endif?> block10-2-item-card">
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

        <?= $arResult['BTN'] ?>
    </div>

<?= $arResult['BLOCK_END'] ?>
