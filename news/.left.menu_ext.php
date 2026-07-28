<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader,
    Ranx\Landing\Cache,
    Ranx\Landing\Helpers\Menu;

if (!Loader::includeModule('ranx.landing')) {
    return false;
}

$aMenuLinksExt = [];

$arMenuParametrs = Menu::getDirMenuParametrs(__DIR__);
$landingIblockId = '19';

// set defaults
if (empty($arMenuParametrs['MENU_SHOW_SECTIONS'])) {
    $arMenuParametrs['MENU_SHOW_SECTIONS'] = 'Y';
}
if (empty($arMenuParametrs['MENU_SHOW_ELEMENTS'])) {
    $arMenuParametrs['MENU_SHOW_ELEMENTS'] = 'Y';
}

if($arMenuParametrs['MENU_SHOW_SECTIONS'] == 'Y' || $arMenuParametrs['MENU_SHOW_ELEMENTS'] == 'Y'){
    global $APPLICATION, $arRegion, $arTheme;
    $siteId = SITE_ID;
}

if($arMenuParametrs['MENU_SHOW_SECTIONS'] == 'Y'){
    $arSections = Cache::CIBlockSection_GetList(array('SORT' => 'ASC', 'ID' => 'ASC', 'CACHE' => array('TAG' => Cache::GetIBlockCacheTag($landingIblockId), 'MULTI' => 'Y')), array('IBLOCK_ID' => $landingIblockId, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, array('IBLOCK_ID', 'ID', 'IBLOCK_SECTION_ID', 'NAME', 'SECTION_PAGE_URL', 'PICTURE', 'DEPTH_LEVEL', 'UF_ICON', 'UF_BACKGROUND', 'UF_LINK'));
    $arSectionsByParentSectionID = Cache::GroupArrayBy($arSections, array('MULTI' => 'Y', 'GROUP' => array('IBLOCK_SECTION_ID')));
}

if($arMenuParametrs['MENU_SHOW_ELEMENTS'] == 'Y' || $arTheme['SHOW_SECTIONS_REGION']['VALUE'] == 'Y'){
    $arItems = Cache::CIBlockElement_GetList(array('SORT' => 'ASC', 'ID' => 'DESC', 'CACHE' => array('TAG' => Cache::GetIBlockCacheTag($landingIblockId), 'MULTI' => 'Y')), array('IBLOCK_ID' => $landingIblockId, 'ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'INCLUDE_SUBSECTIONS' => 'Y'), false, false, ['ID', 'NAME', 'CODE', 'PREVIEW_PICTURE', 'PROPERTY_ICON', 'DETAIL_PAGE_URL', 'CANONICAL_PAGE_URL', 'LINK_REGION', 'PROPERTY_MENU_LINK']);
    if($arMenuParametrs['MENU_SHOW_SECTIONS'] == 'Y'){
        $arItemsBySectionID = Cache::GroupArrayBy($arItems, array('MULTI' => 'Y', 'GROUP' => array('IBLOCK_SECTION_ID')));
    }
    else{
        $arItemsRoot = Cache::CIBlockElement_GetList(array('SORT' => 'ASC', 'ID' => 'DESC', 'CACHE' => array('TAG' => Cache::GetIBlockCacheTag($landingIblockId), 'MULTI' => 'Y')), array('IBLOCK_ID' => $landingIblockId, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'SECTION_ID' => 0), false, false, ['ID', 'NAME', 'CODE', 'PREVIEW_PICTURE', 'PROPERTY_ICON', 'DETAIL_PAGE_URL', 'CANONICAL_PAGE_URL', 'LINK_REGION', 'PROPERTY_MENU_LINK']);
        $arItems = array_merge((array)$arItems, (array)$arItemsRoot);
    }

    if($arItems && $arRegion)
    {
        foreach($arItems as $key => $arItem)
        {
            $arTmpProp = array();
            $rsPropRegion = CIBlockElement::GetProperty($arItem['IBLOCK_ID'], $arItem['ID'], array('sort' => 'asc'), Array('CODE'=>'LINK_REGION'));
            while($arPropRegion = $rsPropRegion->Fetch())
            {
                if($arPropRegion['VALUE'])
                    $arTmpProp[] = $arPropRegion['VALUE'];
            }
            $arItems[$key]['LINK_REGION'] = $arTmpProp;
        }
    }
}

if($arSections){
    Menu::getSectionChilds(false, $arSections, $arSectionsByParentSectionID, $arItemsBySectionID, $aMenuLinksExt, $landingIblockId);
}

if($arItems && $arMenuParametrs['MENU_SHOW_SECTIONS'] != 'Y'){
    foreach($arItems as $arItem){
        $arExtParam = array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => 1);
        if(isset($arItem['LINK_REGION'])){
            $arExtParam['LINK_REGION'] = $arItem['LINK_REGION'];
        }
        if (isset($arItem['PREVIEW_PICTURE'])) {
            $arExtParam['PICTURE'] = $arItem['PREVIEW_PICTURE'];
        }
        if (isset($arItem['PROPERTY_ICON_VALUE'])) {
            $arExtParam['UF_ICON'] = $arItem['PROPERTY_ICON_VALUE'];
        }

        $aMenuLinksExt[] = array($arItem['NAME'], $arItem['DETAIL_PAGE_URL'], array(), $arExtParam);
    }
}


$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
