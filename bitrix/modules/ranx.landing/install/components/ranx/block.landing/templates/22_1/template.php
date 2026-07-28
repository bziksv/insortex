<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">
    <?=$arResult['BLOCK_TITLE']?>
</div>

<div class="container-fluid p-0 insta-cards">
    <div class="row m-0 no-gutters">
        <?foreach ($arResult['ITEMS'] as $arItem):?>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12 m-0 insta-card">
                <a class="card-link theme-exclude-hover" href="<?=$arItem['LINK']?>" target="_blank">
                    <div class="card-img lazy" <?if($useLazyLoad):?>data-bg="<?=$arItem['IMG']?>"
                        <?else:?>style="background-image: url('<?=$arItem['IMG']?>');"<?endif?>></div>
                    <div class="card-body js-simplebar">
                        <div class="card-header">
                            <div class="card-icon">
                                <?=Helper::svg('block/social', 'instagram_block')?>
                            </div>
                            <div class="card-date">
                                <?=$arItem['DATE']?>
                            </div>
                        </div>
                        <div class="card-text"><?=$arItem['TEXT']?></div>
                    </div>
                </a>
            </div>
        <?endforeach?>
    </div>
</div>

<div class="maxwidth-theme">
    <?= $arResult['BTN'] ?>
</div>

<?= $arResult['BLOCK_END'] ?>
