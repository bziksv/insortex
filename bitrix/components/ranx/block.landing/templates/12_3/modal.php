<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Helpers\Helper;

$isUser = $arResult['IMG'] || !empty($arResult['NAME']) || !empty($arResult['PROPS']['POST']);
$hideStars = $arResult['PROPS']['CHECK'] === 'Y';
?>

<div class="modal12-3">

    <?if($isUser || !$hideStars):?>
        <div class="modal12-3-top">

            <?if($isUser):?>
                <div class="modal12-3-user">

                    <?if($arResult['IMG']):?>
                        <div class="modal12-3-avatar" style="background-image: url('<?= $arResult['IMG'] ?>');"></div>
                    <?endif?>

                    <div class="modal12-3-info">
                        <?if(!empty($arResult['PROPS']['POST'])):?>
                            <div class="modal12-3-post"><?=$arResult['PROPS']['POST']?></div>
                        <?endif?>

                        <?if(!empty($arResult['NAME'])):?>
                            <div class="modal12-3-name"><?=$arResult['NAME']?></div>
                        <?endif?>
                    </div>

                </div>
            <?endif?>

            <?if(!$hideStars):?>
                <div class="modal12-3-stars">
                    <?for($i = 0; $i < $arResult['MARK']; $i++):?>
                        <div class="modal12-3-star modal12-3-star--on"><?= Helper::svg('block/star') ?></div>
                    <?endfor?>
                    <?for($i = 0; $i < (5 - $arResult['MARK']); $i++):?>
                        <div class="modal12-3-star modal12-3-star--off"><?= Helper::svg('block/star') ?></div>
                    <?endfor?>
                </div>
            <?endif?>

        </div>
    <?endif?>

    <div class="modal12-3-review">
        <?= $arResult['DETAIL_TEXT'] ?>
    </div>
</div>
