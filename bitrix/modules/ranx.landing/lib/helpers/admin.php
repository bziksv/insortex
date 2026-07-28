<?php

namespace Ranx\Landing\Helpers;

use Exception,
    CAdminMessage,
    CAdminTabControl,
    Ranx\Landing\Config,
    Bitrix\Main\Application,
    Bitrix\Main\Localization\Loc;

class Admin
{
    public static function getSites()
    {
        return \Bitrix\Main\SiteTable::getList()->fetchAll();
    }

    public static function initTabControl($sites)
    {
        $arTabs = [];
        foreach ($sites as $site) {
            $arTabs[] = [
                'DIV' => 'edit' . $site['LID'],
                'TAB' => Loc::getMessage('RX_LANDING_OPTIONS_TAB_SITE', ['#SITE_ID#' => $site['LID'], '#SITE_NAME#' => $site['NAME']]),
                'TITLE' => Loc::getMessage('RX_LANDING_OPTIONS_TAB_SITE', ['#SITE_ID#' => $site['LID'], '#SITE_NAME#' => $site['NAME']]),
            ];
        }

        return new CAdminTabControl('tabControl', $arTabs);
    }

    public static function handlePostRequest($sitesList)
    {
        $request = Application::getInstance()->getContext()->getRequest();

        if (($request->getPost('save')) && $request->isPost() && check_bitrix_sessid()) {

            try {
                $postList = $request->getPostList();
                //Color::generateThemeCss();

                foreach ($sitesList as $site) {
                    $params = [];

                    foreach (Config::$params as $group) {
                        foreach ($group['OPTIONS'] as $optionCode => $option) {

                            if (!empty($option['DISABLED'])) { // do not update disabled
                                continue;
                            }

                            $code = $optionCode . '_' . $site['LID'];
                            $delCode = $code . '_del';
                            $value = $postList[$code];

                            if ($option['TYPE'] == 'file' && !empty($postList[$delCode]) && $postList[$delCode] == 'Y') {
                                $value = ['del' => 'Y'];
                            }

                            $params[$optionCode] = $value;
                        }
                    }

                    Config::updateParams($params, Config::MODE_UPDATE_ALL, $site['LID']);
                }

                CAdminMessage::showMessage(array(
                    'MESSAGE' => Loc::getMessage('RX_LANDING_OPTIONS_SAVED'),
                    'TYPE'    => 'OK',
                ));
            } catch (Exception $e) {
                $msg = $e->getMessage();
                CAdminMessage::showMessage($msg ? $msg : Loc::getMessage('RX_LANDING_OPTIONS_INVALID'));
            }
        } elseif (($request->getPost('restore')) && $request->isPost() && check_bitrix_sessid()) {
            foreach ($sitesList as $site) {
                Config::restoreParams(Config::MODE_UPDATE_ALL, $site['LID']);
            }

            CAdminMessage::showMessage(array(
                'MESSAGE' => Loc::getMessage('RX_LANDING_OPTIONS_RESTORED'),
                'TYPE'    => 'OK',
            ));
        }
    }
}
