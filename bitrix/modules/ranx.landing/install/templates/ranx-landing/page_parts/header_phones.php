<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var string $classes
 * @var array $firstPhone
 * @var bool $showFirstPhoneDesc
 * @var array $phones
 * @var bool $showDropdown
 */

use Ranx\Landing\Page;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
?>

<div class="header-phone <?= $classes ?>">

    <div class="header-phone-wrap">
        <a class="header-phone-number theme-exclude-hover <?if(count($phones) > 1):?>with-arrow-down<?endif?>" href="tel:<?= Helper::phone($firstPhone['NUMBER']) ?>"><?= $firstPhone['NUMBER'] ?></a>

        <?if($showFirstPhoneDesc):?>
            <span><?=$firstPhone['DESC']?></span>
        <?endif?>

        <?if($showDropdown):?>
        <div class="header-phone-dropdown">

            <?foreach ($phones as $phone):?>
            <a class="header-phone-number theme-exclude-hover" href="tel:<?= Helper::phone($phone['NUMBER']) ?>">
                <?= $phone['NUMBER'] ?>
                <?= ($phone['DESC'] ? '<span>'.$phone['DESC'].'</span>' : '') ?>
            </a>
            <?endforeach?>

        </div>
        <?endif?>

    </div>
    <? Page::showPhoneBtn('header-phone-callback'); ?>
</div>
