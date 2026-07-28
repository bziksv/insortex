<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$isEmpty = empty($arResult['TABS']);
?>

<div class="row block-tabs justify-content-center <?=$isEmpty ? 'empty' : ''?>">
    <?if($isEmpty):?>
        <div class="tab-button active" data-target-tab=""></div>
    <?endif?>

    <?foreach ($arResult['TABS'] as $i => $arTab):?>
        <? $isActive = $arTab['ID'] == $arResult['DEFAULT_TAB_ID']; ?>

        <?if($arTab['IS_EDITOR'] === 'Y'):?>
            <div class="tab-button-editor theme-color theme-border theme-bg js-block-tabs <?=$isActive ? 'active' : ''?>"
                 data-target-tab="<?=$arTab['ID']?>">
                <?=$arTab['NAME']?>
            </div>
        <?else:?>
            <div class="tab-button theme-bg  theme-border <?=$isActive ? 'active' : ''?>"
                 data-target-tab="<?=$arTab['ID']?>">
                <?=$arTab['NAME']?>
            </div>
        <?endif?>
    <?endforeach?>
</div>

