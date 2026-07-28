<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;

$btnType = Config::getUpButtonType();
$btnLocation = Config::getUpButtonLocation();
$left = Config::getUpButtonLeftIdent();
$right = Config::getUpButtonRightIdent();
$bottom = Config::getUpButtonBottomIdent();
$hideInMobile = Config::isMobileUpButtonHidden() ? 'hide-in-mobile' : '';
?>

<div class="up-button-wrap">
    <a href="#" class="up-button btn <?=$btnType?> <?=$btnLocation?> <?=$hideInMobile?> hide"
       style="left: <?=$left?>px; right: <?=$right?>px; bottom: <?=$bottom?>px;">
        <?=Helper::svg('upbutton')?>
    </a>
</div>
