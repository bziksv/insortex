<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['PREVIEW_PICTURE'])) {
    $resizedImg = CFile::ResizeImageGet($arResult['PREVIEW_PICTURE'], ['width' => 1400, 'height' => 900]);
    $arResult['IMG'] = $resizedImg['src'] ?? '';
}
