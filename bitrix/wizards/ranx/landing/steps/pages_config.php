<?php

use Bitrix\Main\Localization\Loc;

class PagesConfigStep extends CWizardStep
{
    function InitStep()
    {
        $this->SetStepID('pages_config');
        $this->SetTitle(Loc::getMessage('RX_WIZ_STEP_PAGES_CONFIG'));
        $this->SetSubTitle(Loc::getMessage('RX_WIZ_STEP_PAGES_CONFIG'));
        $this->SetPrevStep('main_config');
        $this->SetPrevCaption(Loc::getMessage('RX_WIZ_PREV_BTN'));
        $this->SetNextStep('prepare_install');
        $this->SetNextCaption(Loc::getMessage('RX_WIZ_NEXT_BTN'));
    }

    function ShowStep()
    {
        $wizard = $this->GetWizard();
        $siteType = $wizard->GetVar('RX_SITE_TYPE');
        $pages = self::getSelectablePage();
        $existInaccessiblePage = false;

        ob_start();
        include_once 'include/pages_config.php';
        $this->content = ob_get_clean();
    }

    static function getSelectablePage()
    {
        $pages = Ranx\Landing\Api\Repository::getPagesInfo();
        $selectablePages = self::filterSelectablePage(['CHILDS' => $pages]);
        $moduleVersion = Ranx\Landing\Config::getVersion();

        foreach ($selectablePages as &$page) {
            $page['AVAILABLE'] = empty($page['VERSION']) ||
                Ranx\Landing\Helpers\Helper::compareVersion($page['VERSION'], $moduleVersion) >= 0;
        }

        return $selectablePages;
    }

    static function filterSelectablePage($page)
    {
        $result = [];
        $childs = [];

        if (!empty($page['CHILDS'])) {
            $childs = $page['CHILDS'];
            unset($page['CHILDS']);
        }
        if ($page['CAN_SELECT']) {
            $result[] = $page;
        }

        foreach ($childs as $code => $child) {
            $child['CODE'] = $code;
            $result = array_merge($result, self::filterSelectablePage($child));
        }

        return $result;
    }
}
