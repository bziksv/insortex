<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <?= $arResult['BLOCK_TITLE'] ?>
            </div>
                
            <div class="col-md-6 col-sm-12">

                <?if(!empty($arResult['IMG'])):?>
                    <img class="block14-2-img lazy" <?if($useLazyLoad):?>data-src="<?= $arResult['IMG'] ?>"<?else:?>src="<?= $arResult['IMG'] ?>"<?endif?> alt="<?= htmlspecialchars($arResult['NAME']) ?>">
                <?endif?>

                <?if(!empty($arResult['DESC'])):?>
                    <?= $arResult['DESC']['TEXT'] ?>
                <?endif?>

            </div>

        </div>

    </div>

<?= $arResult['BLOCK_END'] ?>
