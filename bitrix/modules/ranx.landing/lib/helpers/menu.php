<?php

namespace Ranx\Landing\Helpers;

use Ranx\Landing\Cache;
use Bitrix\Main\Localization\Loc;

class Menu
{
    public static function getDirMenuParametrs($dir){
		if(strlen($dir)){
			$file = str_replace('//', '/', $dir.'/.section.php');
			if(file_exists($file)){
				@include($file);
				return $arDirProperties;
			}
		}

		return false;
    }
    
    public static function getSectionChilds($PSID, &$arSections, &$arSectionsByParentSectionID, &$arItemsBySectionID, &$aMenuLinksExt, $IBlockID = false){
		if($arSections && is_array($arSections)){
			$arElements = array();

			$arSectionsID = array_column($arSections, 'ID');
			if($arSectionsID){
				if($arItemsBySectionID){
					$arElements = Cache::CIblockElement_GetList(array("CACHE" => array("TAG" => Cache::GetIBlockCacheTag($IBlockID), "MULTI" => "N", 'GROUP' => 'ID')), array('IBLOCK_ID' => $IBlockID, 'IBLOCK_SECTION_ID' => $arSectionsID, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y'), false, false, array('IBLOCK_ID', 'IBLOCK_SECTION_ID', 'PROPERTY_LINK_REGION'));
				}
			}

			foreach($arSections as $key => $arSection){
				if($arSection['IBLOCK_SECTION_ID'] == $PSID){
					$arItem = array($arSection['NAME'], $arSection['SECTION_PAGE_URL'], array(), array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => $arSection['DEPTH_LEVEL'], 'ICON' => $arSection['UF_ICON'], 'ICON_BACKGROUND' => $arSection['UF_BACKGROUND']));
					$arItem[3]['IS_PARENT'] = (isset($arItemsBySectionID[$arSection['ID']]) || isset($arSectionsByParentSectionID[$arSection['ID']]) ? 1 : 0);
					if($arSection["PICTURE"])
						$arItem[3]["PICTURE"]=$arSection["PICTURE"];

					$aMenuLinksExt[] = $arItem;
					if($arItem[3]['IS_PARENT']){
						$arItem[3]['ID'] = $arSection['ID'];
						// subsections
						self::getSectionChilds($arSection['ID'], $arSections, $arSectionsByParentSectionID, $arItemsBySectionID, $aMenuLinksExt);
						// section elements
						if($arItemsBySectionID[$arSection['ID']] && is_array($arItemsBySectionID[$arSection['ID']])){
							foreach($arItemsBySectionID[$arSection['ID']] as $arItem){
								if(is_array($arItem['DETAIL_PAGE_URL'])){
									if(isset($arItem['CANONICAL_PAGE_URL'])){
										$arItem['DETAIL_PAGE_URL'] = $arItem['CANONICAL_PAGE_URL'];
									}
									else{
										$arItem['DETAIL_PAGE_URL'] = $arItem['DETAIL_PAGE_URL'][key($arItem['DETAIL_PAGE_URL'])];
									}
								}
								$arTmpLink = array();
								if($arItem['LINK_REGION']){
									$arTmpLink['LINK_REGION'] = $arItem['LINK_REGION'];
								}
								if($arElements[$arItem['ID']]['PROPERTY_LINK_REGION_VALUE']){
									$arTmpLink['LINK_REGION'] = $arElements[$arItem['ID']]['PROPERTY_LINK_REGION_VALUE'];
								}
								else{
									$arTmpLink['LINK_REGION'] = '';
								}

								$aMenuLinksExt[] = array($arItem['NAME'], $arItem['DETAIL_PAGE_URL'], array(), array_merge(array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => ($arSection['DEPTH_LEVEL'] + 1), 'IS_ITEM' => 1), $arTmpLink));
							}
						}
					}
				}
			}
		}
	}

	public static function getChilds($input, &$start = 0, $level = 0){
		$arIblockItemsMD5 = array();

		if(!$level){
			$lastDepthLevel = 1;
			if($input && is_array($input)){
				foreach($input as $i => $arItem){
					if($arItem['DEPTH_LEVEL'] > $lastDepthLevel){
						if($i > 0){
							if(!$input[$i - 1]['IS_PARENT'])
								$input[$i - 1]['NO_PARENT'] = false;
							$input[$i - 1]['IS_PARENT'] = 1;
						}
					}
					$lastDepthLevel = $arItem['DEPTH_LEVEL'];
				}
			}
		}

		$childs = array();
		$count = count($input);
		for($i = $start; $i < $count; ++$i){
			$item = $input[$i];
			if(!isset($item)){
				continue;
			}
			if($level > $item['DEPTH_LEVEL'] - 1){
				break;
			}
			else{
				if(!empty($item['IS_PARENT'])){
					$i++;
					$item['CHILD'] = self::getChilds($input, $i, $level + 1);
					$i--;
				}

				$childs[] = $item;
			}
		}
		$start = $i;
		if(is_array($childs)){
			foreach($childs as $j => $item){
				if($item['PARAMS']){
					$md5 = md5($item['TEXT'].$item['LINK'].$item['SELECTED'].$item['PERMISSION'].$item['ITEM_TYPE'].$item['IS_PARENT'].serialize($item['ADDITIONAL_LINKS']).serialize($item['PARAMS']));

 				// check if repeat in one section chids list
					if(isset($arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']])){
						if(isset($arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']][$level]) || ($item['DEPTH_LEVEL'] === 1 && !$level)){
							unset($childs[$j]);
							continue;
						}
					}
					if(!isset($arIblockItemsMD5[$md5])){
						$arIblockItemsMD5[$md5] = array($item['PARAMS']['DEPTH_LEVEL'] => array($level => true));
					}
					else{
						$arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']][$level] = true;

					}
				}
			}
		}

		if(!$level){
			$arIblockItemsMD5 = array();
		}

		return $childs;
	}

	public static function uniqueMultidimArray($array, $key) {
	    $temp_array = array();
	    $i = 0;
	    $key_array = array();

	    foreach($array as $val) {
	        if (!in_array($val[$key], $key_array)) {
	            $key_array[$i] = $val[$key];
	            $temp_array[$i] = $val;
	        }
	        $i++;
	    }
	    return $temp_array;
	}

	public static function isChildsSelected($arChilds){
		if($arChilds && is_array($arChilds)){
			foreach($arChilds as $arChild){
				if($arChild['SELECTED']){
					return $arChild;
				}
			}
		}
		return false;
	}

	public static function prepareSections($menu, $id = '', $level = 0){
        if (empty($menu)) {
            return [];
        }

        $sections = [];

        $curSection = [];
        $curSection['SECTION_ID'] = $id;
        $curSection['ITEMS'] = [];

        foreach ($menu as $key => $item) {
            $tempItem = [];
            $tempItem['TEXT'] = $item['TEXT'] ?? '';
            $tempItem['LINK'] = $item['LINK'] ?? '#';
            $tempItem['SELECTED'] = $item['SELECTED'] ?? false;
            $tempItem['IS_PARENT'] = $item['IS_PARENT'] ?? false;
            $tempItem['PARAMS'] = $item['PARAMS'] ?? [];

            if (!empty($item['IS_PARENT'])) {
                $subsectionId = $id.'-'.$key;
                $tempItem['SUBSECTION_ID'] = $subsectionId;
                $tempItem['CLASS'] = 'level-'.strval($level + 1);

                $subsections = self::prepareSections($item['CHILD'], $subsectionId, $level + 1);
                $sections = array_merge($sections, $subsections);

                $sections[$subsectionId]['PARENT'] = [
                    'TEXT' => $tempItem['TEXT'],
                    'LINK' => $tempItem['LINK'],
                ];
            }

            $curSection['ITEMS'][] = $tempItem;
        }

        $sections[$id] = $curSection;
        return $sections;
    }

    public static function generateDefaultMenu($path, $name) {
        $fullName = '.'.$name.'.menu.php';
        $fullPath = $path.$fullName;
        if (!file_exists($path) || file_exists($fullPath)) {
            return;
        }

        $defaultTitle = Loc::getMessage('RX_LANDING_LIB_HELPERS_MENU_DEFAULT_TITLE');
        file_put_contents($fullPath, '<?$aMenuLinks = Array(Array("'.$defaultTitle.'", "/", array(), array(), ""));?>');
    }

    public static function formatLink($link)
    {
        $result = '';
        if (empty($link)) {
            $link = '#';
        }

        // external
        if (mb_strpos($link, '/') !== 0) {
            $result = 'target="_blank" ';
        }
        $result .= 'href="'.$link.'"';

        return $result;
    }

    public static function append($sectionId, $type = false)
    {
        if (!\Bitrix\Main\Loader::includeModule('fileman')) {
            return false;
        }

        if ($type === false) {
            $type = \Ranx\Landing\Config::getRootMenuType();
        }
        if (empty($type)) {
            return false;
        }

        $arSection = \Ranx\Landing\SectionTable::getByPrimary($sectionId)->fetchObject();
        if (empty($arSection)) {
            return false;
        }

        $siteId = $arSection['SITE_ID'];
        $arSite = \Bitrix\Main\SiteTable::getByPrimary($siteId)->fetch();
        if (empty($arSite)) {
            return false;
        }
        $siteDir = $arSite['DIR'];
        $docRoot = $arSite['DOC_ROOT'];
        if (empty($docRoot)) {
            $docRoot = $_SERVER['DOCUMENT_ROOT'];
        }
        $path = $siteDir.'.'.$type.'.menu.php';
        $fullPath = $docRoot.$path;

        $aMenuLinks = \CFileMan::GetMenuArray($fullPath)['aMenuLinks'];
        if (!is_array($aMenuLinks)) {
            $aMenuLinks = [];
        }

        $aMenuLinks[] = [
            $arSection['TITLE'],
            $arSection['PATH'],
            [],
            [
                'HIDDEN' => 'N',
                'FULL_DROPDOWN' => 'N',
            ],
            '',
        ];

        \CFileMan::SaveMenu([$siteId, $path], $aMenuLinks);
        return true;
    }
}
