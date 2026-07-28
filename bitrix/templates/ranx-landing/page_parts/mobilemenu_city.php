<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var bool $isRegionEnabled */
/** @var string $cityName */
/** @var array $arRegions */
/** @var array $curRegion */

use Ranx\Landing\Helpers\Helper;
use Ranx\Landing\Config;
use Bitrix\Main\Localization\Loc;

$jsClass = '';
if (Config::getRegionsView() == 'select') $jsClass = 'js-open-mobilemenu-dropdown';
if (Config::getRegionsView() == 'popup_cities') $jsClass = 'js-open-cities-popup';

Loc::loadMessages(__FILE__);
?>

<div class="mobilemenu-list-item theme-color-hover-parent with-arrow-right <?=$jsClass?>" data-class="open-regions">
    <div class="mobilemenu-list-icon theme-color-hover"><?= Helper::svg('header/location') ?></div>
    <a href="#" class=""><?=!empty($curBranch) ? $curBranch['NAME'] : $cityName ?></a>
</div>

<?if(!empty($arRegions) && Config::getRegionsView() == 'select'):?>
    <div class="mobilemenu-dropdown mobilemenu-dropdown-regions">
        <div class="mobilemenu-block mobilemenu-block-header">
            <div class="mobilemenu-dropdown-back theme-color-hover js-close-mobilemenu-dropdown" data-class="open-regions"><?= Helper::svg('header/back') ?></div>
            <div class="mobilemenu-dropdown-close theme-color-hover js-mobilemenu-close"><?= Helper::svg('header/close') ?></div>
        </div>

        <div class="mobilemenu-regions">

            <div class="mobilemenu-regions-title"><?= Loc::getMessage('RX_PAGE_PARTS_MOBILEMENU_CITY_TITLE') ?></div>

            <?foreach($arRegions as $arRegion):?>
                <?if(!empty($arRegion['BRANCHES'])):?>
                    <?foreach($arRegion['BRANCHES'] as $arBranch):?>
                        <a href="#" class="mobilemenu-region js-change-city <?if($arBranch['ID'] == $curBranch['ID']):?>active<?endif?>"
                             data-id="<?=$arRegion['ID']?>" <?if(!empty($arRegion['URL'])):?>data-url="<?=$arRegion['URL']?>"<?endif?>
                             data-branch-id="<?= $arBranch['ID'] ?>">
                            <?=$arBranch['NAME']?>
                        </a>
                    <?endforeach?>
                <?else:?>
                    <a href="#" class="mobilemenu-region js-change-city <?if($arRegion['ID'] == $curRegion['ID']):?>active<?endif?>"
                         data-id="<?=$arRegion['ID']?>" <?if(!empty($arRegion['URL'])):?>data-url="<?=$arRegion['URL']?>"<?endif?>>
                        <?=$arRegion['NAME']?>
                    </a>
                <?endif?>
            <?endforeach?>
        </div>
    </div>
<?endif?>

