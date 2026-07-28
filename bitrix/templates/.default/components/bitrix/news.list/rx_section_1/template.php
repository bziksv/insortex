<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
?>

<div class="section-1">
    <div class="maxwidth-theme">
        <div class="row no-gutters">
            <?if($arResult['ITEMS']):?>
                <?foreach($arResult['ITEMS'] as $arItem):
//                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
//                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                    $resizedImg = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 300, 'height' => 300]);
                    $imgSrc = $resizedImg['src'] ?? '';
                    ?>
                    <a id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="col-lg-3 col-md-4 col-sm-6 section-item" href="<?=$arItem['DETAIL_PAGE_URL']?>">

                        <?if(Config::isEditMode()):?>
                            <div class="section-remove theme-color-hover js-element-remove" data-id="<?=$arItem['ID']?>"><?= Helper::svg('panel', 'remove') ?></div>
                        <?endif?>

                        <?if($imgSrc):?>
                            <div class="section-img" style="background-image: url(<?=$imgSrc?>);"></div>
                        <?endif?>
                        <div class="section-title block-el-title"><?=$arItem['NAME']?></div>
                    </a>
                <?endforeach;?>
            <?endif?>

            <?if(Config::isEditMode()):?>
                <a class="col-lg-3 col-md-4 col-sm-6 section-item section-item-add" href="#" data-open-panel="#panelElementAdd">
                    <div class="section-img"><?= Helper::svg('block/element_add'); ?></div>
                    <div class="section-title"><?= Loc::getMessage('RX_LANDING_COMP_BNL_ADD') ?></div>
                </a>
            <?endif?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $arResult['NAV_STRING'] ?>
            </div>
        </div>
    </div>
</div>
