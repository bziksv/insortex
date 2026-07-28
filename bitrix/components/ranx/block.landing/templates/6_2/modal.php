<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

?>

<div class="modal6_2">
    <div class="modal6_2-title"><?=$arResult['NAME']?></div>

    <?if(!empty($arResult['DISPLAY_ACTIVE_FROM'])):?>
        <div class="modal6_2-date"><?=$arResult['DISPLAY_ACTIVE_FROM']?></div>
    <?endif?>

    <?if(!empty($arResult['DETAIL_PICTURE_PATH'])):?>
        <div class="modal6_2-picture">
            <img src="<?=$arResult['DETAIL_PICTURE_PATH']?>" alt="<?=$arResult['NAME']?>" title="<?=$arResult['NAME']?>">
        </div>
    <?endif?>

    <?if(!empty($arResult['PREVIEW_TEXT'])):?>
        <div class="modal6_2-preview-text">
            <?=$arResult['PREVIEW_TEXT']?>
        </div>
    <?endif?>

    <?if(!empty($arResult['DETAIL_TEXT'])):?>
        <div class="modal6_2-detail-text">
            <?=$arResult['DETAIL_TEXT']?>
        </div>
    <?endif?>

</div>
