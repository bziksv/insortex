<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 */
$this->setFrameMode(true);
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

    </div>

    <div class="sections-2">
        <div class="maxwidth-theme">
            <div class="row no-gutters">
                <?if($arResult['ITEMS']):?>
                    <?foreach($arResult['ITEMS'] as $arItem):
                        $resizedImg = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 300, 'height' => 300]);
                        $imgSrc = $resizedImg['src'] ?? '';
                        ?>
                        <a class="col-lg-3 col-md-4 col-sm-6 section-item <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                            <?if($imgSrc):?>
                                <div class="section-img" style="background-image: url(<?=$imgSrc?>);"></div>
                            <?endif?>
                            <div class="section-title block-el-title"><?=$arItem['NAME']?></div>
                        </a>
                    <?endforeach;?>
                <?endif?>
            </div>
        </div>
    </div>

    <div class="maxwidth-theme">
        <?= $arResult['NAV_STRING'] ?>
        <?=$arResult['BTN']?>
    </div>

<?= $arResult['BLOCK_END'] ?>
