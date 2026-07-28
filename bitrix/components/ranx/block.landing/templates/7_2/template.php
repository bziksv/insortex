<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">
        <?= $arResult['BLOCK_TITLE'] ?>
    </div>

    <div class="container-fluid p-0">
        <?if(!empty($arResult['ITEMS'])):
            $col = ($arResult['COLS']) ? 12 / $arResult['COLS'] : 3;
        ?>

            <div class="row no-gutters">
            <?foreach($arResult['ITEMS'] as $i => $arItem):?>

                <div class="col-md-<?=$col?> col-sm-12">
                    
                    <?if(!empty($arItem['LINK'])):?>
                    <a class="market-item lazy <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?> <?if($useLazyLoad):?>data-bg="<?= $arItem['IMG'] ?>"<?else:?>style="background-image: url(<?= $arItem['IMG'] ?>);"<?endif?>>
                    <?else:?>
                    <div class="market-item lazy" <?if($useLazyLoad):?>data-bg="<?= $arItem['IMG'] ?>"<?else:?>style="background-image: url(<?= $arItem['IMG'] ?>);"<?endif?>>
                    <?endif?>

                        <div class="market-item-shadow"></div>
                        <div class="market-item-cover"></div>
                        
                        <div class="market-item-body">
                            <div class="market-item-border theme-bg"></div>
                            <div class="market-item-title block-el-title"><?= $arItem['~NAME'] ?></div>

                            <?if(!empty($arItem['PREVIEW_TEXT'])):?>
                                <div class="market-item-desc"><?= $arItem['~PREVIEW_TEXT'] ?></div>
                            <?endif?>
                        </div>

                    <?if(!empty($arItem['LINK'])):?>
                    </a>
                    <?else:?>
                    </div>
                    <?endif?>
                    
                </div>

            <?endforeach?>
            </div>

        <?endif?>

    </div>

    <div class="maxwidth-theme">
        <?=$arResult['BTN']?>
    </div>

<?= $arResult['BLOCK_END'] ?>
