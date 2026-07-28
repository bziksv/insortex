<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult['ITEMS'])) {
    return;
}

foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['PROPERTIES']['MARK'])) {
        $arItem['MARK'] = intval($arItem['PROPERTIES']['MARK']['VALUE_XML_ID']);
    }

    $arItem['BTN'] = \Ranx\Landing\Page::getBtn([
        'BTN_SHOW' => 'Y',
        'BTN_TEXT' => \Bitrix\Main\Localization\Loc::getMessage('RX_BLOCK_20_5_BTN_TEXT'),
        'BTN_LINK_TYPE' => 'form',
        'BTN_LINK' => 'ranx_landing_form_order',
        'SUBJECT' => $arItem['ID'].': '.$arItem['NAME'],
    ]);

    if (!empty($arItem['PREVIEW_PICTURE'])) {
        $resizedImg = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 600, 'height' => 600]);
        $arItem['IMG'] = $resizedImg['src'] ?? '';
    }
    if (empty($arItem['IMG'])) {
        $arItem['IMG'] = $this->__folder.'/img/empty.png';
    }
}
