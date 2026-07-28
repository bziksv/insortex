<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult)) {
    return;
}

use Ranx\Landing\Api\Youtube;

$height = $arResult['BLOCK_HEIGHT'] ?: 830;
foreach($arResult['ITEMS'] as &$arItem) {
    $imgSrc = '';
    if (!empty($arItem['PREVIEW_PICTURE'])) {
        $img = CFile::ResizeImageGet(
            $arItem['PREVIEW_PICTURE'],
            ['width' => ($height + 360), 'height' => ($height + 360)]
        );
        $imgSrc = $img['src'] ?? '';
    }
    $arItem['IMG'] = $imgSrc;

    $imgSrc = '';
    if (!empty($arItem['DETAIL_PICTURE'])) {
        $img = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], ['width' => 1920, 'height' => ($height + 20)],
            BX_RESIZE_IMAGE_PROPORTIONAL, true);
        $imgSrc = $img['src'] ?? '';
    }
    $arItem['BG_IMG'] = $imgSrc; 

    $arItem['VIDEO_ID'] = Youtube::getVideoId($arItem['PROPS']['LINK_TEXT']);
    if (!empty($arItem['VIDEO_ID'])) {
        $arItem['VIDEO_SRC'] = Youtube::generateVideoLink($arItem['VIDEO_ID'], [
            'controls' => '0',
            'iv_load_policy' => '3',
            'showinfo' => '0',
            'mute' => '1',
            'autoplay' => '1',
            'loop' => '1',
            'playlist' => $arItem['VIDEO_ID'],
        ]);

        $arItem['VIDEO_RATIO'] = Youtube::getRatioByVideoId($arItem['VIDEO_ID']);
        if (empty($arItem['VIDEO_RATIO'])) {
            $arItem['VIDEO_RATIO'] = 16 / 9;
        }

        if (empty($arItem['BG_IMG'])) {
            $arItem['BG_IMG'] = Youtube::getPreviewLinkByVideoId($arItem['VIDEO_ID']);
        }

        $arItem['VIDEO_HEIGHT'] = 830 + 100;
    }

    if (!empty($arItem['PROPS']['IMG'])) {
        $img = CFile::ResizeImageGet(
            $arItem['PROPS']['IMG'],
            ['width' => 800, 'height' => 800],
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
        $imgSrc = $img['src'] ?? '';
        if (!empty($imgSrc)) {
            $arItem['MOBILE_IMG'] = $imgSrc;
        }
    }

    $arItem['MOBILE_TYPE'] = $arItem['PROPERTIES']['MOBILE_TYPE_BANNER']['VALUE_XML_ID'] ?? 'with_text_block';
    if ($arItem['MOBILE_TYPE'] === 'only_image' && empty($arItem['MOBILE_IMG'])) {
        $arItem['MOBILE_TYPE'] = 'with_text_block';
    }
}
