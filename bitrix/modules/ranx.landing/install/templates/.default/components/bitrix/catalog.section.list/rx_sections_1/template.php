<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

//$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
//$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
//$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
?>

<div class="sections-1">
    <div class="maxwidth-theme">
        <div class="row no-gutters">

            <?if($arResult['SECTIONS']):?>
                <?foreach($arResult['SECTIONS'] as $arSection):
//                    $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
//                    $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

                    $resizedImg = CFile::ResizeImageGet($arSection['PICTURE'], ['width' => 300, 'height' => 300]);
                    $imgSrc = $resizedImg['src'] ?? '';
                ?>
                <a id="<? echo $this->GetEditAreaId($arSection['ID']); ?>" class="col-lg-3 col-md-4 col-sm-6 section-item" href="<?=$arSection['SECTION_PAGE_URL']?>">

                    <?if(Config::isEditMode()):?>
                    <div class="section-remove theme-color-hover js-section-remove" data-id="<?=$arSection['ID']?>"><?= Helper::svg('panel', 'remove') ?></div>
                    <?endif?>

                    <?if($imgSrc):?>
                    <div class="section-img" style="background-image: url(<?=$imgSrc?>);"></div>
                    <?endif?>
                    <div class="section-title block-el-title"><?=$arSection['NAME']?></div>
                </a>
                <?endforeach;?>
            <?endif?>

            <?if(Config::isEditMode()):?>
                <a class="col-lg-3 col-md-4 col-sm-6 section-item section-item-add" href="#" data-open-panel="#panelSectionAdd">
                    <div class="section-img"><?= Helper::svg('block/section_add'); ?></div>
                    <div class="section-title"><?= Loc::getMessage('RX_LANDING_COMP_BCSL_ADD') ?></div>
                </a>
            <?endif?>
        </div>
    </div>
</div>
