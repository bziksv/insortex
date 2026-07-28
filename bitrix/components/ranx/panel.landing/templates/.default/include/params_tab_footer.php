<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
?>
<div class="panel-tab-footer">
    <div class="row">
        <div class="col-6">
            <button class="btn btn-block btn-transparent js-settings-restore">
                <i class="btn-icon"><?= Helper::svg('panel', 'settings_default_icon') ?></i>
                <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SETTINGS_BTN_DEFAULT') ?>
            </button>
        </div>
        <div class="col-6">
            <button class="btn btn-block btn-primary" type="submit">
                <i class="btn-icon"><?= Helper::svg('panel', 'settings_apply_icon') ?></i>
                <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SETTINGS_BTN_APPLY') ?>
            </button>
        </div>
    </div>
</div>
