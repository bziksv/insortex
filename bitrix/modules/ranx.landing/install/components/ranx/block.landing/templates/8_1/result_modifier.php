<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['ITEMS'])) {
    foreach ($arResult['ITEMS'] as &$item) {
        $eventInfo = [
            $item['DISPLAY_ACTIVE_FROM'],
            $item['PROPS']['SCHEDULE'],
            $item['PROPS']['LOCATION'],
            $item['PROPS']['PERSON_NAME'],
        ];

        $item['EVENT_INFO'] = array_filter($eventInfo);
    }
}
