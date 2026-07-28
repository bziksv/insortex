<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

class FinishStep extends CFinishWizardStep
{
    function InitStep()
    {
        $this->SetStepID('finish');
        $this->SetTitle(Loc::getMessage('FINISH_STEP_TITLE'));
        $this->SetNextStep('finish');
        $this->SetNextCaption(Loc::getMessage('WIZ_GO_TO_THE_SETTING'));
    }

    function ShowStep()
    {
        $wizard = $this->GetWizard();
        $type = $wizard->GetVar('RX_SITE_TYPE');
        $siteId = $wizard->GetVar('siteID');
        $redirectUrl = '/bitrix/admin/ranx.landing_section.php';

        if ($type != 'empty' && !empty($siteId)) {
            if ($arSite = \CSite::GetByID($siteId)->Fetch()) {
                $protocol = \CMain::IsHTTPS() ? 'https://' : 'http://';
                $host = !empty($arSite['SERVER_NAME']) ? $protocol.$arSite['SERVER_NAME'] : '';
                $dir = !empty($arSite['DIR']) ? $arSite['DIR'] : '/';
                $redirectUrl = $host.$dir;
                $this->SetNextCaption(Loc::getMessage('WIZ_GO_TO_THE_SITE'));
            }

            Option::set('main', 'wizard_solution', $wizard->solutionName, $siteId);
        }

        $wizard->SetFormActionScript($redirectUrl);
        $this->content .= Loc::getMessage('FINISH_STEP_CONTENT');
    }
}
