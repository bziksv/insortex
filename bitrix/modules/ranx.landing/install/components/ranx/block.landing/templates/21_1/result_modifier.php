<?php
use Ranx\Landing\Api\Youtube;

$arResult['VIDEO_ID'] = Youtube::getVideoId($arResult['VIDEO_LINK']);

if (!empty($arResult['PREVIEW_PICTURE'])) {
    $resizedImg = CFile::ResizeImageGet($arResult['PREVIEW_PICTURE'], ['width' => 1200, 'height' => 720]);
    $arResult['PREVIEW_PICTURE'] = $resizedImg['src'] ?? '';
}
else {
 $arResult['PREVIEW_PICTURE'] = Youtube::getPreviewLinkByVideoId($arResult['VIDEO_ID']);
}
