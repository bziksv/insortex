<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>
        
        <?if(!empty($arResult['IMG'])):?>
        <div class="row">
            <div class="col-12">
                <img class="block14-4-img lazy" <?if($useLazyLoad):?>data-src="<?= $arResult['IMG'] ?>"<?else:?>src="<?= $arResult['IMG'] ?>"<?endif?> alt="<?= htmlspecialchars($arResult['NAME']) ?>">
            </div>
        </div>
        <?endif?>

        <?if(!empty($arResult['DESC'])):?>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12 block-text">
                <?= $arResult['DESC']['TEXT'] ?>
            </div>
        </div>
        <?endif?>


        <?= $arResult['BTN'] ?>

    </div>

<?= $arResult['BLOCK_END'] ?>
