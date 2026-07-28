<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

if($arResult['ITEMS']) {
	foreach($arResult['ITEMS'] as $key => $arItem) {
        if ($arItem['PROPERTY_TYPE'] == 'L' && count($arItem['VALUES']) == 1) {
            $arResult['ITEMS'][$key]['DISPLAY_TYPE'] = 'T';
        }

		if(in_array($arItem['PROPERTY_TYPE'], ['S', 'L', 'E'])) {
			foreach($arItem['VALUES'] as $arValue) {
				if(isset($arValue['CHECKED']) && $arValue['CHECKED']) {
					$arResult['ITEMS'][$key]['PROPERTY_SET'] = 'Y';
				}
			}
		}

		if($arItem['PROPERTY_TYPE'] == 'N') {
			foreach($arItem['VALUES'] as $arValue) {
				if(isset($arValue['HTML_VALUE'])) {
					$arResult['ITEMS'][$key]['PROPERTY_SET'] = 'Y';
				}
			}
		}
	}
}

if (!empty($arParams['BASE_URL'])) {
    $arResult['FORM_ACTION'] = $arParams['BASE_URL'];
}
