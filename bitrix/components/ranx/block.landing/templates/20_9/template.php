<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

$useLazyLoad = \Ranx\Landing\Config::isLazyLoadEnabled();
$rssProp = $GLOBALS['APPLICATION']->GetDirProperty('rss');
$isRightPart = $arResult['MODE'] != \Ranx\Landing\Landing::MODE_ROOT_SECTION;

$bigBlockClasses   = ($isRightPart ? '' : 'col-xl-6 ').'col-md-8 big-article';
$smallBlockClasses = ($isRightPart ? '' : 'col-xl-3 ').'col-md-4 col-sm-6 article';
$periodBigBlock    = ($isRightPart ? 5 : 7);
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">
    <?= $arResult['BLOCK_TITLE'] ?>

    <div class="row">
        <div class="<?= $isRightPart ? 'col-lg-9' : 'col-12' ?> articles-wrap">
            <div class="articles row">
                <?foreach ($arResult['ITEMS'] as $i => $arItem):?>
                    <div class="<?=($i % $periodBigBlock) == 0 ? $bigBlockClasses : $smallBlockClasses?> article item">
                        <div class="article-wrap shadow-hover">
                            <a class="article-image-wrap <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                                <img class="lazy" <?if($useLazyLoad):?>data-<?endif?>src="<?=$arItem['IMG']?>"
                                     alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
                            </a>
                            <div class="article-info">
                                <?if(!empty($arItem['CATEGORY'])):?>
                                    <div class="article-category"><?=$arItem['CATEGORY']['NAME']?></div>
                                <?endif?>

                                <?if(!empty($arItem['NAME'])):?>
                                    <div class="article-name-wrap">
                                        <a class="article-name <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                                            <?=$arItem['NAME']?>
                                        </a>
                                    </div>
                                <?endif?>

                                <?if(!empty($arItem['DISPLAY_ACTIVE_FROM'])):?>
                                    <div class="article-date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></div>
                                <?endif?>
                            </div>
                        </div>
                    </div>
                <?endforeach?>
            </div>
        </div>
        <?if ($isRightPart):?>
        <div class="col-lg-3 right-part">
            <div class="categories-block">
                <div class="categories-title-wrap">
                    <div class="categories-title"><?=Loc::getMessage('RX_BLOCK_LANDING_20_9_CATEGORIES')?></div>
                    <?if (!empty($rssProp)):?>
                        <a class="rss" href="<?=$rssProp?>" target="_blank">
                            <?= Helper::svg('block/rss'); ?>
                        </a>
                    <?endif?>
                </div>
                <ul class="categories">
                    <?foreach ($arResult['CATEGORIES'] as $arCategory):?>
                        <?if ($arCategory['DEPTH_LEVEL'] != 1) continue;?>

                        <li class="category">
                            <a class="category-link <?= !empty($arCategory['SELECTED']) ? 'selected' : ''?>"
                               href="<?=$arCategory['SECTION_PAGE_URL']?>">
                                <?=$arCategory['NAME']?>
                            </a>
                            <span class="category-count"><?=$arCategory['ELEMENT_CNT']?></span>
                        </li>
                    <?endforeach?>
                </ul>
            </div>
        </div>
        <?endif?>
    </div>

    <div class="pagination-wrap"><?= $arResult['NAV_STRING'] ?></div>
</div>

<?= $arResult['BLOCK_END'] ?>
