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

            <div class="row brand-items">
            <?foreach($arResult['ITEMS'] as $i => $arItem):?>

                <div class="col-xl-<?=$col?> col-md-3 col-sm-4 col-6">
                    
                    <?if(!empty($arItem['LINK'])):?>
                    <a class="brand-item <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>
                        <?if($arItem['PROPS']['BG_COLOR']):?>style="background: <?=$arItem['PROPS']['BG_COLOR']?>"<?endif?>>
                    <?else:?>
                    <div class="brand-item" <?if($arItem['PROPS']['BG_COLOR']):?>style="background: <?=$arItem['PROPS']['BG_COLOR']?>"<?endif?>>
                    <?endif?>

                        <img class="lazy" <?if($useLazyLoad):?>data-src="<?=$arItem['LOGO']?>"<?else:?>src="<?=$arItem['LOGO']?>"<?endif?>
                             alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
                        <div class="brand-item-name"><?=$arItem['NAME']?></div>
                        <div class="brand-item-preview"><?=$arItem['PREVIEW_TEXT']?></div>

                    <?if(!empty($arItem['LINK'])):?>
                    </a>
                    <?else:?>
                    </div>
                    <?endif?>
                    
                </div>

            <?endforeach?>
            </div>

        <?endif?>

        <?= $arResult['BTN'] ?>

    </div>

<?= $arResult['BLOCK_END'] ?>
