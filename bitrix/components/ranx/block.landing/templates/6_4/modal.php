<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

?>

<div class="modal6_4">
    <div class="modal6_4-title"><?=$arResult['NAME']?></div>

    <?if(!empty($arResult['PROPS']['DISCOUNT']) && !empty($arResult['DISPLAY_ACTIVE_PERIOD'])):?>
        <div class="modal6_4-sale-info">
            <div class="modal6_4-sale-tag"><?=$arResult['PROPS']['DISCOUNT']?></div>
            <div class="modal6_4-sale-date"><?=$arResult['DISPLAY_ACTIVE_PERIOD']?></div>
        </div>
    <?endif?>

    <?if(!empty($arResult['DETAIL_PICTURE_PATH'])):?>
        <div class="modal6_4-picture">
            <img src="<?=$arResult['DETAIL_PICTURE_PATH']?>" alt="<?=$arResult['NAME']?>" title="<?=$arResult['NAME']?>">
        </div>
    <?endif?>

    <?if(!empty($arResult['PREVIEW_TEXT'])):?>
        <div class="modal6_4-preview-text">
            <?=$arResult['PREVIEW_TEXT']?>
        </div>
    <?endif?>

    <?if(!empty($arResult['DETAIL_TEXT'])):?>
        <div class="modal6_4-detail-text">
            <?=$arResult['DETAIL_TEXT']?>
        </div>
    <?endif?>

</div>
