<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <?if(!empty($arResult['IMG'])):?>
    <div class="block14-3-bg-image lazy" <?if($useLazyLoad):?>data-bg="<?= $arResult['IMG'] ?>"<?else:?>style="background-image: url(<?= $arResult['IMG'] ?>);"<?endif?>></div>
    <?endif?>

    <div class="maxwidth-theme">
        <div class="row">

            <div class="col-lg-6 col-md-12 <?if($arResult['PICTURE_ALIGN'] == 'left'):?>offset-lg-6<?endif?>">
                <?= $arResult['BLOCK_TITLE'] ?>
            </div>
            
        </div>

    </div>

<?= $arResult['BLOCK_END'] ?>
