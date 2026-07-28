<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['ITEMS'])) {
    foreach ($arResult['ITEMS'] as &$arItem) {
        if (!empty($arItem['PREVIEW_PICTURE'])) {
			$sizes = $arItem['PROPS']['CHECK'] === 'Y' ? ['width' => 1400, 'height' => 600] : ['width' => 500, 'height' => 500];
            $img = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], $sizes);
            $arItem['IMG_SRC'] = $img['src'] ?? '';
        }
    }
}
