<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arResult['IMG'] = '';
if (!empty($arResult['PREVIEW_PICTURE'])) {
    $resizedImg = \CFile::ResizeImageGet($arResult['PREVIEW_PICTURE'], ['width' => 1000, 'height' => 1000]);
    $arResult['IMG'] = $resizedImg['src'] ?? '';
}
