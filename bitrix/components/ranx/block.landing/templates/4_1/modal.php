<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Ranx\Landing\Helpers\Helper;
?>

<div class="modal4_1">
    <div class="modal4_1-title"><?=$arResult['NAME']?></div>

    <?if(!empty($arResult['IMG'])):?>
        <div class="modal4_1-picture">
            <img src="<?=$arResult['IMG']?>" alt="<?=$arResult['NAME']?>" title="<?=$arResult['NAME']?>">
        </div>
    <?endif?>

    <div class="modal4_1-service">
        <div class="icon theme-color">
            <?= Helper::svg('block/note_icon'); ?>
        </div>

        <?if(!empty($arResult['PROPS']['POPUP_NOTE'])):?>
            <div class="desc">
                <?=$arResult['PROPS']['POPUP_NOTE']?>
            </div>
        <?endif?>

        <div class="wrap">
            <?if ($arResult['PRICE']): ?>
                <div class="prices">
                    <div class="cur-price">
                        <?= Helper::money($arResult['PRICE']) ?>
                    </div>
                    <?if ($arResult['OLD_PRICE']): ?>
                        <div class="old-price">
                            <?= Helper::money($arResult['OLD_PRICE']) ?>
                        </div>
                    <?endif?>
                </div>
            <?endif?>

            <?if(!empty($arResult['BTN'])):?>
                <div class="order-button">
                    <?= $arResult['BTN'] ?>
                </div>
            <?endif?>
        </div>
    </div>

    <?if(!empty($arResult['PREVIEW_TEXT'])):?>
        <div class="modal4_1-preview-text">
            <?=$arResult['PREVIEW_TEXT']?>
        </div>
    <?endif?>

    <?if(!empty($arResult['DETAIL_TEXT'])):?>
        <div class="modal4_1-detail-text">
            <?=$arResult['DETAIL_TEXT']?>
        </div>
    <?endif?>

    <?if($arResult['PROPS']['CHARS']):?>
        <div class="modal4_1-chars">
            <div class="title">
                <?=$arResult['PROPERTIES']['CHARS']['NAME']?>
            </div>

            <?foreach ($arResult['PROPS']['CHARS'] as $char):?>
                <div class="char"><?=$char?></div>
            <?endforeach?>
        </div>
    <?endif?>

</div>
