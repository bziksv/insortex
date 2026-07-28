<?php

use Bitrix\Main\Localization\Loc;

class SelectSiteTypeStep extends CWizardStep
{
    function InitStep()
    {
        $this->SetStepID('select_site_type');
        $this->SetTitle(Loc::getMessage('RX_WIZ_STEP_SELECT_SITE_TYPE'));
        $this->SetSubTitle(Loc::getMessage('RX_WIZ_STEP_SELECT_SITE_TYPE'));
        $this->SetPrevStep('select_site');
        $this->SetPrevCaption(Loc::getMessage('RX_WIZ_PREV_BTN'));
        $this->SetNextStep('prepare_install'); // by default
        $this->SetNextCaption(Loc::getMessage('RX_WIZ_NEXT_BTN'));
    }

    function ShowStep()
    {
        $types = self::getSiteTypes();

        ob_start();
        include_once 'include/select_site_type.php';
        $this->content = ob_get_clean();
    }

    function OnPostForm()
    {
        $wizard = $this->GetWizard();
        $type = $wizard->GetVar('RX_SITE_TYPE');
        if ($type != 'empty') {
            $wizard->SetCurrentStep('main_config');
        }
    }

    static function getSiteTypes()
    {
        return [
            'single' => Loc::getMessage('RX_WIZ_STEP_SELECT_SITE_TYPE_SINGLE'),
            'multi' => Loc::getMessage('RX_WIZ_STEP_SELECT_SITE_TYPE_MULTI'),
            'empty' => Loc::getMessage('RX_WIZ_STEP_SELECT_SITE_TYPE_EMPTY'),
        ];
    }
}
