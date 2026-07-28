<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Helpers\Helper;

$isUser = $arResult['IMG'] || !empty($arResult['NAME']) || !empty($arResult['PROPS']['POST']);
$hideStars = $arResult['PROPS']['CHECK'] === 'Y';
?>

<div class="modal12-2">

    <?if($isUser || !$hideStars):?>
        <div class="modal12-2-top">

            <?if($isUser):?>
                <div class="modal12-2-user">

                    <?if($arResult['IMG']):?>
                        <div class="modal12-2-avatar" style="background-image: url('<?= $arResult['IMG'] ?>');"></div>
                    <?endif?>

                    <div class="modal12-2-info">
                        <?if(!empty($arResult['PROPS']['POST'])):?>
                            <div class="modal12-2-post"><?=$arResult['PROPS']['POST']?></div>
                        <?endif?>

                        <?if(!empty($arResult['NAME'])):?>
                            <div class="modal12-2-name"><?=$arResult['NAME']?></div>
                        <?endif?>
                    </div>

                </div>
            <?endif?>

            <?if(!$hideStars):?>
                <div class="modal12-2-stars">
                    <?for($i = 0; $i < $arResult['MARK']; $i++):?>
                        <div class="modal12-2-star modal12-2-star--on"><?= Helper::svg('block/star') ?></div>
                    <?endfor?>
                    <?for($i = 0; $i < (5 - $arResult['MARK']); $i++):?>
                        <div class="modal12-2-star modal12-2-star--off"><?= Helper::svg('block/star') ?></div>
                    <?endfor?>
                </div>
            <?endif?>

        </div>
    <?endif?>

    <div class="modal12-2-review">
        <?= $arResult['DETAIL_TEXT'] ?>
    </div>
</div>
