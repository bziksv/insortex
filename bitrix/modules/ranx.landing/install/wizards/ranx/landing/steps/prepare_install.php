<?php

use Bitrix\Main\Localization\Loc;

class PrepareInstallStep extends CWizardStep
{
    function InitStep()
    {
        $this->SetStepID('prepare_install');
        $this->SetTitle(Loc::getMessage('RX_WIZ_STEP_PREPARE_INSTALL'));
        $this->SetSubTitle(Loc::getMessage('RX_WIZ_STEP_PREPARE_INSTALL'));
        $this->SetPrevStep('select_site_type');
        $this->SetPrevCaption(Loc::getMessage('RX_WIZ_PREV_BTN'));
        $this->SetNextStep('data_install');
        $this->SetNextCaption(Loc::getMessage('RX_WIZ_NEXT_BTN'));
    }

    function ShowStep()
    {
        $wizard = $this->GetWizard();
        $type = $wizard->GetVar('RX_SITE_TYPE');
        $changes = [];
        $replace = [
            'GROUP' => [],
            'ACTION' => [],
        ];
        $structures = [];

        $changes['DEFAULT'] = ['IBLOCK', 'FORM', 'PUBLIC'];

        // main
        if (in_array($type, ['single', 'multi'])) {
            $mainPresetCode = $wizard->GetVar('RX_MAIN_PRESET');
            $mainPresetInfo = \Ranx\Landing\Preset::getInfo($mainPresetCode);
            $actionCode = 'REPLACE_MAIN';

            if ($mainPresetCode === 'empty') {
                $actionCode = 'REPLACE_EMPTY_MAIN';
            }
            elseif (!empty($mainPresetInfo)) {
                $actionCode = 'REPLACE_TEMPLATE_MAIN';
                $replace['ACTION'][$actionCode] = ['#PRESET_TITLE#' => $mainPresetInfo['TITLE']];
            }

            $changes['MAIN'] = [$actionCode];
        }

        // pages
        if ($type == 'multi') {
            $changes['PAGES'] = ['PAGES'];
            $structure = $this->getPagesStructure();
            $structures['PAGES'] = $this->printStructure($structure);
        }

        ob_start();
        include_once 'include/prepare_install.php';
        $this->content = ob_get_clean();
    }

    function getPagesStructure()
    {
        $wizard = $this->GetWizard();
        $selectedPages = $wizard->GetVar('RX_PAGES');
        $siteId = $wizard->GetVar('siteID');
        $arPages = \Ranx\Landing\Api\Repository::getPagesInfo();
        $siteDir = '/';
        if (!empty($siteId)) {
            $arSite = \CSite::GetByID($siteId)->Fetch();
            if (!empty($arSite['DIR'])) {
                $siteDir = $arSite['DIR'];
            }
        }

        foreach ($arPages as $code => &$arPage) {
            $arPage['CODE'] = $code;
            $arPage['PATH'] = $siteDir.str_replace('_', '-', mb_strtolower($code)).'/';
            $this->setCreationMark($arPage, $selectedPages);
            $arPages[$code] = $arPage;
        }

        return $this->walkPagesTree(['CHILDS' => $arPages]);
    }

    function setCreationMark(&$page, $selectedPages, $isParentSelected = false)
    {
        $isSelected = $isParentSelected || $selectedPages[$page['CODE']] == 'Y';
        $childs = $page['CHILDS'] ?? [];

        $isSelectedChild = false;
        foreach ($childs as $code => &$child) {
            $child['CODE'] = $code;
            $child['PATH'] = $page['PATH'].str_replace('_', '-', mb_strtolower($code)).'/';
            $isSelectedChild = $this->setCreationMark($child, $selectedPages, $isSelected) || $isSelectedChild;
            $page['CHILDS'][$code] = $child;
        }

        $page['CREATE'] = $isSelected || $isSelectedChild;
        return $page['CREATE'];
    }

    function walkPagesTree($page, $level = 0)
    {
        $result = [];
        $childs = $page['CHILDS'] ?? [];

        foreach ($childs as $child) {
            if (empty($child['CREATE'])) {
                continue;
            }

            $child['LEVEL'] = $level + 1;
            $childPages = $this->walkPagesTree($child, $level + 1);
            if (!empty($childPages)) {
                $child['IS_PARENT'] = true;
            }

            $result = array_merge($result, [$child], $childPages);
        }

        return $result;
    }

    function printStructure($structure)
    {
        $str = '';
        if (empty($structure)) {
            return $str;
        }

        $lastLevel = 0;
        $str .= '<ul class="rx-structure-ul">';
        foreach ($structure as $page) {
            if ($lastLevel && $page['LEVEL'] < $lastLevel) {
                $str .= str_repeat('</ul></li>', ($lastLevel - $page['LEVEL']));
            }

            $title = $page['TITLE'];
            $path = ' ( '.$page['PATH'].' )';

            if ($page['IS_PARENT'])
                $str .= '<li class="rx-structure-li"><span>'.$title.$path.':'.'</span><ul class="rx-structure-ul">';
            else
                $str .= '<li class="rx-structure-li"><span>'.$title.$path.'</span></li>';

            $lastLevel = $page['LEVEL'];
        }
        $str .= str_repeat('</ul></li>', ($lastLevel - 1));
        $str .= '</ul>';

        return $str;
    }
}
