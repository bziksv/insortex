<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 */
$this->setFrameMode(true);

use Ranx\Landing\Config;
?>

<?if($arResult['ITEMS'] || Config::isEditMode()):?>

    <?= $arResult['BLOCK_START'] ?>
        <div class="maxwidth-theme">
            <div class="row">
                <?foreach($arResult['ITEMS'] as $arItem):
                    $resizedImg = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 40, 'height' => 40]);
                    $imgSrc = $resizedImg['src'] ?? $this->__folder.'/img/empty.png';
                ?>
                    <a class="col-xl-3 col-lg-4 col-sm-6 section-item <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                        <div class="section-container">
                            <img class="section-img" src="<?=$imgSrc?>" alt="<?=$arItem['NAME']?>">
                            <div class="section-title block-el-title"><?=$arItem['NAME']?></div>
                        </div>
                    </a>
                <?endforeach;?>
            </div>
        </div>

    <?= $arResult['NAV_STRING'] ?>
    <?= $arResult['BLOCK_END'] ?>

<?endif?>
