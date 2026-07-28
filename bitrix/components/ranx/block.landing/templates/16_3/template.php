<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

<? if (!empty($arResult['ITEMS'])) : ?>
    <?  $arItem = $arResult['ITEMS'][0]; ?>

    <?if(!empty($arItem['LINK'])):?>
        <a class="block16-3-banner-item <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
    <?else:?>
        <div class="block16-3-banner-item">
    <?endif?>

    <?if(!empty($arItem['IMG'])):?>
        <div class="block-16-3-img-wrapper">
            <img class="lazy" <?if($useLazyLoad):?>data-src="<?=$arItem['IMG']?>"<?else:?>src="<?=$arItem['IMG']?>"<?endif?>
                 alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
        </div>
    <?endif?>

    <?if(!empty($arItem['LINK'])):?>
        </a>
    <?else:?>
        </div>
    <?endif?>
<?endif?>

<?= $arResult['BLOCK_END'] ?>
