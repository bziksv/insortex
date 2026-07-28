<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">
    <?= $arResult['BLOCK_TITLE'] ?>

    <div class="video-wrapper">
    <?if (!empty($arResult['VIDEO_ID']) && !$arResult['HIDE_VIDEO']):?>
        <?if (!empty($arResult['VIDEO_POPUP_SHOW'])):?>
        <a class="video-preview theme-before-bg js-video-modal" href="#"
           data-code="<?=$arResult['CODE']?>" data-id="<?=$arResult['ID']?>">
        <?else:?>
        <a class="video-preview theme-before-bg js-play-video" href="#">
        <?endif?>
            <img class="lazy"
                 <?if($useLazyLoad):?>data-src="<?=$arResult['PREVIEW_PICTURE']?>"
                 <?else:?>src="<?= $arResult['PREVIEW_PICTURE'] ?>"<?endif?>
                 alt="<?=$arResult['NAME']?>">
        </a>
        <div class="video hidden" data-video-id="<?=$arResult['VIDEO_ID']?>"></div>
        <div class="video-note">
            <?=$arResult['VIDEO_NOTE']?>
        </div>
    <?endif?>
    </div>

    <?= $arResult['BTN'] ?>
</div>

<?= $arResult['BLOCK_END'] ?>
