<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $phones
 * @var array $firstPhone
 */

use Ranx\Landing\Page;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
?>

<div class="mobilemenu-list-item js-open-mobilemenu-dropdown with-arrow-right" data-class="open-phones">
    <div class="mobilemenu-list-icon mobilemenu-list-icon-phone"><?= Helper::svg('header/phone') ?></div>
    <a href="tel:<?= Helper::phone($firstPhone['NUMBER']) ?>"><?= $firstPhone['NUMBER'] ?></a>
</div>

<?if(!empty($phones)):?>
<div class="mobilemenu-dropdown mobilemenu-dropdown-phones">
    <div class="mobilemenu-block mobilemenu-block-header">
        <div class="mobilemenu-dropdown-back theme-color-hover js-close-mobilemenu-dropdown" data-class="open-phones"><?= Helper::svg('header/back') ?></div>
        <div class="mobilemenu-dropdown-close theme-color-hover js-mobilemenu-close"><?= Helper::svg('header/close') ?></div>
    </div>

    <?foreach($phones as $phone):?>
        <div class="mobilemenu-block"><a href="tel:<?= Helper::phone($phone['NUMBER']) ?>"><?= $phone['NUMBER']?><?= ($phone['DESC'] ? '<span>'.$phone['DESC'].'</span>' : '') ?></a></div>
    <?endforeach?>
    <?if(!empty($isShowPhoneBtn)):?>
    <div class="mobilemenu-block">
        <? Page::showPhoneBtn(); ?>
    </div>
    <?endif?>
</div>
<?endif?>
