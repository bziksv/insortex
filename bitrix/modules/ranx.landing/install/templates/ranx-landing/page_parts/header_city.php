<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var string $classes */
/** @var string $cityName */
/** @var mixed $arRegions */
/** @var mixed $curRegion */
/** @var mixed $curBranch */
/** @var mixed $regionByIp */
/** @var bool $isRegionEnabled */
/** @var bool $showRegionConfirm */

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<div class="header-city-wrap <?= $classes ?>">
    <a href="#" class="header-city js-change-city theme-color-hover-parent <?= ($isRegionEnabled ? 'with-arrow-down' : '')?>">
        <div class="header-city-icon theme-color-hover"><?= Helper::svg('header/location') ?></div>
        <?= $cityName ?>
        <div class="header-city-icon theme-color-hover"><?= Helper::svg('header/arrow_down') ?></div>
    </a>

    <?if(!empty($curBranch)):?>
        <span><?= $curBranch['NAME'] ?></span>
    <?endif?>

    <?if(!empty($arRegions) && Config::getRegionsView() == 'select'):?>
        <div class="header-city-dropdown js-simplebar">

            <?foreach($arRegions as $arRegion):?>
                <?if(!empty($arRegion['BRANCHES'])):?>
                    <?foreach($arRegion['BRANCHES'] as $arBranch):?>
                        <div class="header-city-item js-change-city <?if($arBranch['ID'] == $curBranch['ID']):?>active<?endif?>"
                             data-id="<?=$arRegion['ID']?>" <?if(!empty($arRegion['URL'])):?>data-url="<?=$arRegion['URL']?>"<?endif?>
                            data-branch-id="<?= $arBranch['ID'] ?>">
                            <?=$arRegion['NAME']?><span><?= $arBranch['NAME'] ?></span>
                        </div>
                    <?endforeach?>
                <?else:?>
                    <div class="header-city-item js-change-city <?if($arRegion['ID'] == $curRegion['ID']):?>active<?endif?>"
                         data-id="<?=$arRegion['ID']?>" <?if(!empty($arRegion['URL'])):?>data-url="<?=$arRegion['URL']?>"<?endif?>>
                        <?=$arRegion['NAME']?>
                    </div>
                <?endif?>
            <?endforeach?>

        </div>
    <?endif?>

    <?if($showRegionConfirm):?>
        <div class="header-city-confirm">
            <div class="header-city-confirm-text"><?= Loc::getMessage('RX_PAGE_PARTS_HEADER_CITY_CONFIRM_TEXT', ['#CITY#' => $regionByIp['NAME']]) ?></div>
            <div class="header-city-confirm-btns">
                <div>
                    <a href="#" class="btn btn-primary js-change-city" data-id="<?=$regionByIp['ID']?>" data-url="<?=$regionByIp['URL']?>">
                        <?= Loc::getMessage('RX_PAGE_PARTS_HEADER_CITY_CONFIRM_YES') ?>
                    </a>
                </div>
                <div><a href="#" class="btn btn-transparent js-change-city"><?= Loc::getMessage('RX_PAGE_PARTS_HEADER_CITY_CONFIRM_EDIT') ?></a></div>
            </div>
        </div>
    <?endif?>
</div>
