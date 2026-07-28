<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 */

if (!empty($arResult['ITEMS'])) {
    foreach ($arResult['ITEMS'] as $i => &$arItem) {
        if (!empty($arItem['DETAIL_PICTURE'])) {
            $resizedImg = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], ['width' => 700, 'height' => 500]);
            $arItem['IMG'] = $resizedImg['src'] ?? '';
        } elseif (!empty($arItem['PREVIEW_PICTURE'])) {
            $resizedImg = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 700, 'height' => 500]);
            $arItem['IMG'] = $resizedImg['src'] ?? '';
        } else {
            $arItem['IMG'] = $this->GetFolder() . '/demo/1.png';
        }
    }
}
