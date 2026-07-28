<?php

namespace Ranx\Landing;

use Ranx\Landing\Region;
use Ranx\Landing\Cache;
use Bitrix\Main\EventManager;

class Event
{
    public static function removeOtherEvents()
    {
        $events = [
            ['MODULE_ID' => 'main', 'TYPE' => 'OnEndBufferContent'],
        ];

        self::removeAsproEvents($events);
    }

    public static function removeOtherResultAddEvents()
    {
        $events = [
            ['MODULE_ID' => 'form', 'TYPE' => 'onAfterResultAdd'],
            ['MODULE_ID' => 'form', 'TYPE' => 'onBeforeResultAdd'],
        ];

        self::removeAsproEvents($events);
    }

    private static function removeAsproEvents($events)
    {
        $eventManager = EventManager::getInstance();

        foreach ($events as $event) {
            $handlers = $eventManager->findEventHandlers($event['MODULE_ID'], $event['TYPE']);

            foreach ($handlers as $key => $handler) {
                if (strpos($handler['TO_MODULE_ID'], 'aspro.') !== 0)
                    continue;

                $eventManager->removeEventHandler($event['MODULE_ID'], $event['TYPE'], $key);
            }
        }
    }

    public static function onEndBufferContent(&$content)
    {
        if (defined('RX_LANDING_TEMPLATE')) { // make sure we can change the content
            $content = Region::replaceMacros($content);
        }
    }
}
