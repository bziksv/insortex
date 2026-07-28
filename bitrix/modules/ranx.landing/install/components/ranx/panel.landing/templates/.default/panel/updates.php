<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Helpers\Helper;

Loc::loadMessages(__FILE__);
?>

<div class="panel-tab-updates">
    <div class="updates-previews">
        <div class="updates-top">
            <div class="updates-header">
                <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_UPDATES_TITLE') ?>
            </div>
            <a class="updates-link" href="<?=$arResult['UPDATES_BLOG_LINK']?>" target="_blank">
                <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_UPDATES_BLOG_LINK') ?>
            </a>
        </div>
        <?foreach ($arResult['ITEMS'] as $i => $arItem):?>
        <div class="updates-card" data-code="<?=$i?>">
            <a class="updates-img js-updates-show" title="<?=$arItem['TITLE']?>">
                <img src="<?=$arItem['IMG_TO_BASE64']?>" alt="<?=$arItem['TITLE']?>">
            </a>
            <div class="updates-info">
                <div class="updates-date">
                    <?=$arItem['DATE']?>
                </div>
                <a class="update-title js-updates-show" title="<?=$arItem['TITLE']?>">
                    <?=$arItem['TITLE']?>
                </a>
            </div>
        </div>
        <?endforeach?>
    </div>

    <?foreach ($arResult['ITEMS'] as $i => $arItem):?>
    <div class="update-detail update-hidden" data-code="<?=$i?>">
        <div class="update-detail-header">
            <a class="update-detail-back js-updates-back">
                <?=Helper::svg('panel', 'back')?>
            </a>
            <div class="update-detail-title">
                <?=$arItem['TITLE']?>
            </div>
        </div>
        <img src="<?=$arItem['IMG_TO_BASE64']?>" alt="<?=$arItem['TITLE']?>">
        <div class="update-detail-content">
            <?=$arItem['CONTENT']?>
        </div>
    </div>
    <?endforeach?>
</div>
