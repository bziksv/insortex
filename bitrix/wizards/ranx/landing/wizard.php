<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/install/wizard_sol/wizard.php');

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if (!function_exists('rxWizardSvg')) {
    function rxWizardSvg($id)
    {
        return '<svg class="svg"><use xlink:href="/bitrix/wizards/' . RX_PARTNER_NAME . '/' . RX_MODULE_NAME_SHORT . '/images/sprite.svg#' . $id . '"></use></svg>';
    }
}

include_once 'steps/select_site.php';
include_once 'steps/select_site_type.php';
include_once 'steps/main_config.php';
include_once 'steps/pages_config.php';
include_once 'steps/prepare_install.php';
include_once 'steps/data_install.php';
include_once 'steps/finish.php';
