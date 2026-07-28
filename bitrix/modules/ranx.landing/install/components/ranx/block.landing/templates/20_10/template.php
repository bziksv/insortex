<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

global $APPLICATION;
$isAjaxRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAjaxRequest();
$rssProp = $APPLICATION->GetDirProperty('rss');
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">

    <?if (!empty($arResult['ITEMS'])):?>
        <? $arItem = reset($arResult['ITEMS']); ?>
        <div class="article">
            <?if (!$isAjaxRequest):?>
                <div class="article-breadcrumb">
                        <?$APPLICATION->IncludeComponent(
                            'bitrix:breadcrumb',
                            '',
                            [
                                'START_FROM' => '0',
                                'PATH' => '',
                                'SITE_ID' => SITE_ID,
                            ]
                        );?>
                </div>
            <?endif;?>
            <?if (!empty($arItem['NAME'])):?>
                <<?=$arResult['TITLE_TAG']?> class="article-title block-el-title">
                    <?= $arItem['NAME'] ?>
                </<?=$arResult['TITLE_TAG']?>>
            <?endif?>
            <div class="article-info">
                <?if(!empty($arItem['DISPLAY_ACTIVE_FROM'])):?>
                    <div class="article-date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></div>
                <?endif?>
                <?if(!empty($arItem['CATEGORY'])):?>
                    <div class="article-category"><?=$arItem['CATEGORY']['NAME']?></div>
                <?endif?>
                <?if (!empty($rssProp)):?>
                    <a class="rss" href="<?=$rssProp?>" target="_blank">
                        <?= Helper::svg('block/rss'); ?>
                    </a>
                <?endif?>
            </div>
            <?if (!empty($arItem['IMG'])):?>
                <div class="article-ing-wrap">
                    <img class="article-img" src="<?=$arItem['IMG']?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
                </div>
            <?endif?>
            <div class="article-text theme-border"><?= $arItem['DETAIL_TEXT'] ?></div>
        </div>
        <?if (!empty($arResult['SECTION_DIR'])):?>
            <a class="back theme-color-hover-parent" href="<?=$arResult['SECTION_DIR']?>">
                <?= Helper::svg('block/arrow_back'); ?>
                <span class="theme-color-hover"><?= Loc::getMessage('RX_BLOCK_20_10_BACK_TO_LIST') ?></span>
            </a>
        <?endif?>
    <?endif?>

</div>

<?= $arResult['BLOCK_END'] ?>
