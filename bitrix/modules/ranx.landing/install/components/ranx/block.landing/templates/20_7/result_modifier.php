<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult['ITEMS'])) {
    return;
}

$arResult['CHARS'] = is_array($arResult['CHARS']) ? $arResult['CHARS'] : [];
$charsDisplayCount = \Ranx\Landing\Config::getBlockInfo($arResult['CODE'])['DISPLAY_CHARS_COUNT'] ?? 0;
foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['PROPERTIES']['MARK'])) {
        $arItem['MARK'] = intval($arItem['PROPERTIES']['MARK']['VALUE_XML_ID']);
    }

    $arItem['BTN'] = \Ranx\Landing\Page::getBtn([
        'BTN_SHOW' => 'Y',
        'BTN_SIZE' => 'btn-lg',
        'BTN_TEXT' => \Bitrix\Main\Localization\Loc::getMessage('RX_BLOCK_20_7_BTN_TEXT'),
        'BTN_LINK_TYPE' => 'form',
        'BTN_LINK' => 'ranx_landing_form_order',
        'SUBJECT' => $arItem['ID'].': '.$arItem['NAME'],
    ]);

    if (!empty($arItem['DETAIL_PICTURE'])) {
        $resizedImg = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], ['width' => 600, 'height' => 600]);
        $arItem['IMG'] = $resizedImg['src'] ?? '';
    }
    if (empty($arItem['IMG'])) {
        $arItem['IMG'] = $this->__folder.'/img/empty.png';
    }

    $arCharProps = [];
    $i = 0;
    foreach ($arResult['CHARS'] as $propCode) {
        if (empty($arItem['PROPS'][$propCode])) {
            continue;
        }

        $value = $arItem['PROPS'][$propCode];
        if (is_array($value)) {
            $value = implode(', ', $value);
        }

        $arCharProps[] = [
            'NAME' => $arItem['PROPERTIES'][$propCode]['NAME'],
            'VALUE' => $value,
        ];

        $i++;
        if ($i == $charsDisplayCount) {
            break;
        }
    }
    $arItem['CHARS'] = $arCharProps;
}
