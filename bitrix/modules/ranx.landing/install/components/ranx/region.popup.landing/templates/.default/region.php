<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
if (empty($arResult)) return;
if (empty($arRegion)) {
    $arRegion = $arResult['REGION'];
}
if (empty($arBranch) && !empty($arResult['BRANCH'])) {
    $arBranch = $arResult['BRANCH'];
}
$isActive = empty($arBranch) && $arRegion['ID'] == $arResult['CURRENT']['ID']
    || !empty($arBranch) && $arBranch['ID'] == $arResult['CURRENT_BRANCH']['ID'];
?>

<div class="<?if($arResult['MODAL_POSITION'] == 'right'):?>col-md-4<?else:?>col-md-3<?endif?> col-sm-6 modal-region">
    <a href="#" class="modal-regions-item js-change-city <?if($isActive):?>active<?endif?>"
        data-id="<?= $arRegion['ID'] ?>" <?if(!empty($arRegion['URL'])):?>data-url="<?=$arRegion['URL']?>"<?endif?>
        <?if(!empty($arBranch)):?>data-branch-id="<?= $arBranch['ID'] ?>"<?endif?>>

        <?= $arRegion['NAME'] ?>

        <?if(!empty($arBranch)):?>
            <span><?= $arBranch['NAME'] ?></span>
        <?endif?>
    </a>
</div>
