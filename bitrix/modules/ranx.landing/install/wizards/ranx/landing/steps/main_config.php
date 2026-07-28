<?php

use Bitrix\Main\Localization\Loc;

class MainConfigStep extends CWizardStep
{
    function InitStep()
    {
        $wizard = $this->GetWizard();
        $type = $wizard->GetVar('RX_SITE_TYPE');

        $this->SetStepID('main_config');
        $this->SetTitle(Loc::getMessage('RX_WIZ_STEP_SELECT_SITE_PRESET'));
        $this->SetSubTitle(Loc::getMessage('RX_WIZ_STEP_SELECT_SITE_PRESET'));
        $this->SetPrevStep('select_site_type');
        $this->SetPrevCaption(Loc::getMessage('RX_WIZ_PREV_BTN'));
        $this->SetNextStep($type == 'multi' ? 'pages_config' : 'prepare_install');
        $this->SetNextCaption(Loc::getMessage('RX_WIZ_NEXT_BTN'));
    }

    function ShowStep()
    {
        $groups = self::getPresets();

        ob_start();
        include_once 'include/main_config.php';
        $this->content = ob_get_clean();
    }

    static function getPresets()
    {
        $groups = \Ranx\Landing\Config::getPresetGroups();
        foreach ($groups as $groupCode => $arGroup) {
            if (empty($arGroup['PRESETS']) || in_array($groupCode, ['CUSTOM', 'PAGES'])) {
                unset($groups[$groupCode]);
            }
        }

        return $groups;
    }
}
