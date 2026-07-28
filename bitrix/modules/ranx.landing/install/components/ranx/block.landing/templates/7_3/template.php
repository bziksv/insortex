<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <?if(!empty($arResult['ITEMS'])):
            $col = ($arResult['COLS']) ? 12 / $arResult['COLS'] : 3;
        ?>

            <div class="row market-items">
            <?foreach($arResult['ITEMS'] as $i => $arItem):?>

                <div class="col-md-<?=$col?> col-sm-12">
                    
                    <?if(!empty($arItem['LINK'])):?>
                    <a class="market-item theme-color-hover-parent <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                    <?else:?>
                    <div class="market-item">
                    <?endif?>
                        
                        <div class="market-item-body">
                            <div class="market-item-title block-el-title <?if(!empty($arItem['LINK'])):?>theme-color-hover<?endif?>"><?= $arItem['~NAME'] ?></div>

                            <?if(!empty($arItem['PREVIEW_TEXT'])):?>
                                <div class="market-item-desc">
                                    <div class="dash theme-color"></div>
                                    <?= $arItem['~PREVIEW_TEXT'] ?>
                                </div>
                            <?endif?>
                        </div>

                        <?if(!empty($arItem['IMG'])):?>
                            <div class="market-item-img lazy" <?if($useLazyLoad):?>data-bg="<?= $arItem['IMG'] ?>"<?else:?>style="background-image: url(<?= $arItem['IMG'] ?>);"<?endif?>>
                                <div class="market-item-cover"></div>
                            </div>
                        <?endif?>

                    <?if(!empty($arItem['LINK'])):?>
                    </a>
                    <?else:?>
                    </div>
                    <?endif?>
                    
                </div>

            <?endforeach?>
            </div>

        <?endif?>


        <?=$arResult['BTN']?>

    </div>

<?= $arResult['BLOCK_END'] ?>
