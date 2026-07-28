<?php

use Bitrix\Main\Localization\Loc;

class DataInstallStep extends CDataInstallWizardStep
{
    function InitStep()
    {
        $this->SetStepID('data_install');
        $this->SetTitle(Loc::getMessage('WIZ_STEP_INSTALL_DATA'));
        $this->SetSubTitle(Loc::getMessage('WIZ_STEP_INSTALL_DATA'));
    }
}
