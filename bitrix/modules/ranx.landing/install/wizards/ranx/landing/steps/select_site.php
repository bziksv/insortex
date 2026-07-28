<?php

use Bitrix\Main\Localization\Loc;

class SelectSiteStep extends CSelectSiteWizardStep
{
    function InitStep()
    {
        parent::InitStep();
        $wizard = $this->GetWizard();
        $wizard->solutionName = 'landing';
        $this->SetNextStep('select_site_type');
        $this->SetNextCaption(Loc::getMessage('RX_WIZ_NEXT_BTN'));
    }
}
