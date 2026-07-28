<?php

namespace Ranx\Landing\ActionFilter;

use Bitrix\Main\Engine\ActionFilter\Base;
use Bitrix\Main\Error;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Config;

class CheckAccess extends Base
{
    const ERROR_ACCESS_DENIED = 'rx_access_denied';
    const ACTION_OPERATION = [
        'editParams' => 'rx_landing_settings_edit',
        'restoreParams' => 'rx_landing_settings_edit',
        'uploadPreset' => 'rx_landing_preset_upload',
        'downloadPreset' => 'rx_landing_preset_download',
        'applyPreset' => 'rx_landing_block_edit',
        'deleteCustomPreset' => 'rx_landing_preset_delete',
        'showBlock' => 'rx_landing_block_edit',
        'hideBlock' => 'rx_landing_block_edit',
        'downBlock' => 'rx_landing_block_edit',
        'upBlock' => 'rx_landing_block_edit',
        'sortBlock' => 'rx_landing_block_edit',
        'addBlock' => 'rx_landing_block_edit',
        'removeBlock' => 'rx_landing_block_edit',
        'replaceBlock' => 'rx_landing_block_edit',
        'moveBlock' => 'rx_landing_block_edit',
        'copyBlock' => 'rx_landing_block_edit',
        'refreshBlock' => 'rx_landing_block_edit',
        'editDesign' => 'rx_landing_block_edit',
        'editContent' => 'rx_landing_block_edit',
        'editCard' => 'rx_landing_block_edit',
        'editVariant' => 'rx_landing_block_edit',
        'addVariant' => 'rx_landing_block_edit',
        'editMenu' => 'rx_landing_section_edit',
        'addSection' => 'rx_landing_section_edit',
        'removeSection' => 'rx_landing_section_edit',
        'addElement' => 'rx_landing_section_edit',
        'removeElement' => 'rx_landing_section_edit',
        'searchElements' => 'rx_landing_block_edit',
        'searchRegions' => 'rx_landing_block_edit',
        'searchBranches' => 'rx_landing_block_edit',
        'getTotalPrice' => 'rx_landing_block_edit',
        'getIblockSectionsForSelect' => 'rx_landing_block_edit',
        'editTabs' => 'rx_landing_block_edit',
        'editSettings' => 'rx_landing_block_edit',

        'getDesignTemplate' => 'rx_landing_block_edit',
        'getContentTemplate' => 'rx_landing_block_edit',
        'getCopyTemplate' => 'rx_landing_block_edit',
        'getCardTemplate' => 'rx_landing_block_edit',
        'getGroupTemplate' => 'rx_landing_block_edit',
        'getVariantTemplate' => 'rx_landing_block_edit',
        'getTabsTemplate' => 'rx_landing_block_edit',
        'getSettingsTemplate' => 'rx_landing_block_edit',
        'getMenuTemplate' => 'rx_landing_section_edit',
    ];

    public function onBeforeAction(Event $event)
    {
        global $USER;
        $actionName = $this->action->getName();

        if (!($USER instanceof \CAllUser) || !$USER->IsAdmin()) {
            if (!Config::isDemoMode()
                && (!empty(self::ACTION_OPERATION[$actionName]) && !$USER->CanDoOperation(self::ACTION_OPERATION[$actionName]))) {
            
                $this->addError(new Error(
                    Loc::getMessage('RX_LANDING_LIB_ACTIONFILTER_CHECKACCESS_ERROR'), self::ERROR_ACCESS_DENIED)
                );

                return new EventResult(EventResult::ERROR, null, null, $this);
            }
        }

        return null;
    }
}
