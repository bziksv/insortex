<?php


namespace Ranx\Landing\ActionFilter;

use Bitrix\Main\Engine\ActionFilter\Base;
use Bitrix\Main\Event;

class ClearCompositeCache extends Base
{
    const EXCLUDE_ACTIONS = [
        'refreshBlock',
        'downloadPreset',
        'uploadPreset',
        'deleteCustomPreset',
        'searchElements',
        'searchRegions',
        'searchBranches',
        'getTotalPrice',
        'getIblockSectionsForSelect',
    ];

    public function onAfterAction(Event $event)
    {
        $actionName = $this->action->getName();

        if (!empty($actionName) && !in_array($actionName, self::EXCLUDE_ACTIONS)) {
            \Bitrix\Main\Composite\Page::getInstance()->deleteAll();
        }

        return null;
    }
}
