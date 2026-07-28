<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
?>

<div class="block-title row">

    <?if(!empty($arResult['CATTITLE']) || !empty($arResult['NAME'])):?>
    <div class="col-lg-10 block-title-col">
        <?if(!empty($arResult['CATTITLE'])):?>
        <div class="block-cattitle"><?= $arResult['CATTITLE'] ?></div>
        <?endif?>

        <?if(!empty($arResult['NAME'])):?>
            <? $title = str_replace('#TITLE#', $GLOBALS['APPLICATION']->GetTitle(), $arResult['NAME']); ?>
            <<?=$arResult['TITLE_TAG']?> class="block-title-text"><?= $title ?></<?=$arResult['TITLE_TAG']?>>
        <?endif?>
    </div>
    <?endif?>

    <?if(!empty($arResult['SUBTITLE']) || ($showDesc && !empty($arResult['DESC']['TEXT']))):?>
    <div class="col-lg-8 block-desc-col">
        <?if(!empty($arResult['SUBTITLE'])):?>
        <div class="block-subtitle"><?= $arResult['SUBTITLE'] ?></div>
        <?endif?>

        <?if($showDesc && !empty($arResult['DESC']['TEXT'])):?>
            <?if(strpos($arResult['DESC']['TEXT'], '<') !== 0):?>
            <p class="block-desc">
            <?else:?>
            <div class="block-desc">
            <?endif?>
            <?= $arResult['DESC']['TEXT'] ?>
            <?if(strpos($arResult['DESC']['TEXT'], '<') !== 0):?>
            </p>
            <?else:?>
            </div>
            <?endif?>
        <?endif?>
    </div>
    <?endif?>

    <?if($showBtn && !empty($arResult['BTN_TITLE'])):?>
        <div class="col-lg-12">
            <?= $arResult['BTN_TITLE'] ?>
        </div>
    <?endif?>
    
</div>
