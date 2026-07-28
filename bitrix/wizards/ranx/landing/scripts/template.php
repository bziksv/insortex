<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

class RanxTemplate extends CWizardTemplate
{
    function GetLayout()
    {
        $wizard =& $this->GetWizard();

        $rxPartnerName = RX_PARTNER_NAME;
        $rxModuleNameShort = RX_MODULE_NAME_SHORT;

        $formName = htmlspecialcharsbx($wizard->GetFormName());

        $nextButtonID = htmlspecialcharsbx($wizard->GetNextButtonID());
        $prevButtonID = htmlspecialcharsbx($wizard->GetPrevButtonID());
        $cancelButtonID = htmlspecialcharsbx($wizard->GetCancelButtonID());
        $finishButtonID = htmlspecialcharsbx($wizard->GetFinishButtonID());

        $wizardPath = $wizard->GetPath();
        $obStep =& $wizard->GetCurrentStep();
        $stepTitle = $obStep->GetTitle();
        $wizardName = $wizard->GetWizardName();

        $strError = $this->getErrorsTemplate();

        $logoImage = '<img src="'.$wizardPath.'/images/'.LANGUAGE_ID.'/logo.gif" alt="" />';
        $boxImage = '<img src="'.$wizardPath.'/images/'.LANGUAGE_ID.'/box.jpg" alt="" />';

        // Helps to avoid the error of rights
        $jsCode = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/install/wizard_sol/script.js");

        $strNavigation = $this->getNavigationTemplate();

        ob_start();
        include_once $_SERVER['DOCUMENT_ROOT'].$wizardPath.'/include/template.php';
        return ob_get_clean();
    }

    function getNavigationTemplate()
    {
        $wizard =& $this->GetWizard();

        $arSteps = $wizard->GetWizardSteps();
        $currentStepID = $wizard->GetCurrentStepID();
        if ($currentStepID == "ldap_settings" || $currentStepID == "ldap_groups")
            $currentStepID = "site_settings";

        $currentSuccess = false;
        $stepNumber = 1;

        $strNavigation = "";
        foreach ($arSteps as $stepID => $stepObject)
        {
            if ($stepID == "ldap_settings" || $stepID == "ldap_groups")
                continue;

            if ($stepID == $currentStepID)
            {
                $class = ' inst-active-step';
                $currentSuccess = true;
            }
            elseif ($currentSuccess)
                $class = '';
            else
                $class = ' inst-past-stage';

            $strNavigation .= '
			<div class="inst-sequence-step-item'.$class.'"><span class="inst-sequence-step-num">'.$stepNumber.'</span><span class="inst-sequence-step-text">'.$stepObject->GetTitle().'</span></div>';

            $stepNumber++;
        }

        if (strlen($strNavigation) > 0) {
            $strNavigation = '<div class="inst-sequence-steps">' . $strNavigation . '</div>';
        }

        return $strNavigation;
    }

    function getErrorsTemplate()
    {
        $wizard =& $this->GetWizard();
        $obStep =& $wizard->GetCurrentStep();
        $arErrors = $obStep->GetErrors();

        $strError = "";
        if (count($arErrors) > 0)
        {
            foreach ($arErrors as $arError)
                $strError .= $arError[0]."<br />";

            if (strlen($strError) > 0)
                $strError = '<div class="inst-note-block inst-note-block-red"><div class="inst-note-block-icon"></div><div class="inst-note-block-text">'.$strError."</div></div>";
        }

        return $strError;
    }
}
