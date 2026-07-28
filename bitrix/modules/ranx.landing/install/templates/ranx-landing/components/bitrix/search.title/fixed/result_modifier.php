<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$elementsIds = [];
$sectionIds = [];
$arResult['IMAGES'] = [];

foreach($arResult['CATEGORIES'] as $categoryId => $arCategory)
{
	foreach($arCategory['ITEMS'] as $i => $arItem)
	{
	    $itemId = $arItem['ITEM_ID'];
		if(!isset($itemId) || $arItem['MODULE_ID'] != 'iblock') {
		    continue;
        }

		if (strpos($itemId, 'S') === 0) {
		    $sectionIds[] = substr($itemId, 1);
        }
		if (strpos($itemId, 'S') === false) {
		    $elementsIds[] = $itemId;
        }
	}
}

if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    return;
}

$rxGetImgSrc = function ($id, $imgSrc) {
    if (empty($id) || !empty($imgSrc)) {
        return $imgSrc;
    }

    $arImg = CFile::ResizeImageGet($id, ['width' => 80, 'height' => 80], BX_RESIZE_IMAGE_PROPORTIONAL);
    return $arImg['src'];
};


if (!empty($elementsIds) && $arParams['SHOW_PREVIEW'] == 'Y') {
    $arSelect = [
        'ID',
        'PREVIEW_PICTURE',
        'PROPERTY_ICON',
    ];
    $arFilter = [
        'IBLOCK_SITE_ID' => SITE_ID,
        'IBLOCK_ACTIVE' => 'Y',
        'ACTIVE_DATE' => 'Y',
        'ACTIVE' => 'Y',
        'ID' => $elementsIds,
    ];

    $rsElements = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
    while ($arElement = $rsElements->Fetch()) {
        $imgSrc = $rxGetImgSrc($arElement['PREVIEW_PICTURE'], '');
        $imgSrc = $rxGetImgSrc($arElement['PROPERTY_ICON_VALUE'], $imgSrc);

        if (!empty($imgSrc)) {
            $arResult['IMAGES'][$arElement['ID']] = $imgSrc;
        }
    }
}

if (!empty($sectionIds) && $arParams['SHOW_PREVIEW'] == 'Y') {
    $arSelect = [
        'ID',
        'PICTURE',
        'UF_ICON',
    ];
    $arFilter = [
        'IBLOCK_ACTIVE' => 'Y',
        'ACTIVE' => 'Y',
        'ID' => $sectionIds,
    ];

    $rsSections = \CIBlockSection::GetList([], $arFilter, false, false, $arSelect);
    while ($arSection = $rsSections->Fetch()) {
        $imgSrc = $rxGetImgSrc($arSection['PICTURE'], '');
        $imgSrc = $rxGetImgSrc($arSection['UF_ICON'], $imgSrc);

        if (!empty($imgSrc)) {
            $arResult['IMAGES']['S'.$arSection['ID']] = $imgSrc;
        }
    }
}
