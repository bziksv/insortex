<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arChainBody = '';
foreach($arCHAIN as $i => $item){
	if(strlen($item['LINK']) < strlen(SITE_DIR)){
		continue;
	}
	if(!empty($item['LINK'])){
		$arChainBody .= '<li><a href="'.$item['LINK'].'"><span>'.htmlspecialcharsex($item['TITLE']).'</span></a></li>';
	}
	else{
		$arChainBody .= '<li><span>'.htmlspecialcharsex($item['TITLE']).'</span></li>';
	}
}

return $arChainBody;
