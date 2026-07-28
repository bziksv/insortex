<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Helpers\Helper;

if (!empty($arResult['IS_MODAL'])) {
    if (!empty($arResult['PREVIEW_PICTURE'])) {
        $resizedImg = CFile::ResizeImageGet($arResult['PREVIEW_PICTURE'], ['width' => 800, 'height' => 400]);
        $arResult['IMG'] = $resizedImg['src'] ?? '';
    }

    if (!empty($arResult['PROPERTIES']['MARK'])) {
        $arResult['MARK'] = intval($arResult['PROPERTIES']['MARK']['VALUE_XML_ID']);
    }

    $arResult['MORE_IMG'] = [];
    if (!empty($arResult['PROPS']['MORE_PHOTO'])) {
        foreach ($arResult['PROPS']['MORE_PHOTO'] as $fileId) {
            $resizedImg = CFile::ResizeImageGet($fileId, ['width' => 800, 'height' => 400]);
            if (empty($resizedImg['src'])) {
                continue;
            }
            $arResult['MORE_IMG'][] = $resizedImg['src'];
        }
    }
}

if (empty($arResult['GROUPS'])) {
    return;
}

foreach($arResult['GROUPS'] as &$arGroup)
{
    foreach ($arGroup['ITEMS'] as &$arItem) {
        if (!empty($arItem['PREVIEW_PICTURE'])) {
            $resizedImg = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 600, 'height' => 600]);
            $arItem['IMG'] = $resizedImg['src'] ?? '';
        }

        if (empty($arItem['IMG'])) {
            $arItem['IMG'] = $this->__folder.'/img/empty.png';
        }
    }
}
