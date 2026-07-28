<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Config;
use Bitrix\Main\Localization\Loc;
?>

<div id="cookies">
    <div class="maxwidth-theme">
        <div class="cookies-1">
            <div class="cookies-text">
                <?= Config::getCookiesText() ?>
            </div>
            <div class="cookies-button">
                <div class="btn btn-transparent"><?=Loc::getMessage('RX_PAGE_BLOCKS_COOKIES_1_BTN')?></div>
            </div>
        </div>
    </div>
</div>
