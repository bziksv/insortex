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

            <div class="row">
            <?foreach($arResult['ITEMS'] as $i => $arItem):?>

                <div class="col-md-<?=$col?> col-sm-6 col-xs-12">
                    
                    <?if(!empty($arItem['LINK'])):?>
                    <a class="brand-item <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                    <?else:?>
                    <div class="brand-item">
                    <?endif?>

                        <img class="lazy" <?if($useLazyLoad):?>data-src="<?=$arItem['LOGO']?>"<?else:?>src="<?=$arItem['LOGO']?>"<?endif?>
                             alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">

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
