<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Menu;
use Ranx\Landing\Helpers\Helper;
?>

<?if(!empty($arResult['SECTIONS'])):?>

    <?
    $mainSectionId = $arResult['MAIN_SECTION_ID'];
    $mainSection = $arResult['SECTIONS'][$mainSectionId];
    ?>

    <div class="mobilemenu-main-section">
        <?foreach($mainSection['ITEMS'] as $arItem):
            if ($arItem['PARAMS']['HIDDEN'] === 'Y') continue;
        ?>
        <div class="mobilemenu-links-item theme-color-hover-parent <?=($arItem['IS_PARENT'] ? 'js-open-mobilemenu-dropdown with-arrow-right' : '')?>"
             <?if(!empty($arItem['CLASS'])):?>data-class="open-<?=$arItem['CLASS']?>"<?endif?>
             <?if(!empty($arItem['SUBSECTION_ID'])):?>data-subsection="<?=$arItem['SUBSECTION_ID']?>"<?endif?>>
            <a <?=Menu::formatLink($arItem['LINK'])?> class="<?if($arItem['SELECTED']):?>active<?endif?>"><?=$arItem['TEXT']?></a>
        </div>
        <?endforeach?>
    </div>


    <?foreach ($arResult['SECTIONS'] as $key => $arSection):
        if ($key === $mainSectionId) {
            continue;
        }
    ?>
    <div class="mobilemenu-dropdown mobilemenu-dropdown-links" data-id="<?=$arSection['SECTION_ID']?>">

        <div class="mobilemenu-block mobilemenu-block-header">
            <div class="mobilemenu-dropdown-back theme-color-hover js-close-mobilemenu-dropdown">
                <?= Helper::svg('header/back') ?>
            </div>
            <div class="mobilemenu-dropdown-close theme-color-hover js-mobilemenu-close">
                <?= Helper::svg('header/close') ?>
            </div>
        </div>

        <div class="mobilemenu-links">
            <?if (!empty($arSection['PARENT'])):?>
            <div class="mobilemenu-links-title">
                <a <?=Menu::formatLink($arSection['PARENT']['LINK'])?> class="mobilemenu-links-item theme-color-hover-parent">
                    <?=$arSection['PARENT']['TEXT']?>
                </a>
            </div>
            <?endif?>

            <?foreach ($arSection['ITEMS'] as $arItem):?>
                <a class="mobilemenu-links-item theme-color-hover-parent <?if($arItem['SELECTED']):?>active<?endif?> <?=($arItem['IS_PARENT'] ? 'js-open-mobilemenu-dropdown with-arrow-right' : '')?>"
                   <?if(!empty($arItem['CLASS'])):?>data-class="open-<?=$arItem['CLASS']?>"<?endif?>
                   <?if(!empty($arItem['SUBSECTION_ID'])):?>data-subsection="<?=$arItem['SUBSECTION_ID']?>"<?endif?>
                    <?=Menu::formatLink($arItem['LINK'])?>>
                    <?=$arItem['TEXT']?>
                </a>
            <?endforeach?>
        </div>
    </div>
    <?endforeach?>

<?endif?>