<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetPageProperty("description", "Каталог оборудования Инсортекс: фотосепараторы, зерноочистительные машины, протравители семян и упаковочные линии. Широкий выбор техники для послеуборочной обработки зерна. Поставки по всей России. Выбирайте и заказывайте!");
$APPLICATION->SetPageProperty("title", "Каталог оборудования от Инсортекс – фотосепараторы, протравители, упаковка");
$APPLICATION->SetTitle("Каталог");

if (!\Bitrix\Main\Loader::includeModule('ranx.landing')) {
    die('ranx.landing module is not installed');
}

global $APPLICATION;
$dir = $APPLICATION->GetCurDir();

$arDir = explode('/',$dir);
if($arDir[3]!=''){
echo '<div class="catalog_d">';
}
$APPLICATION->IncludeComponent(
	'ranx:pub.landing',
	'',
	array(
		'IBLOCK_ID' => '18',
		'SEF_FOLDER' => '/catalog/',
		'SET_STATUS_404' => 'Y',
		'SHOW_404' => 'Y',
		'SEF_URL_TEMPLATES' => [
			'sections' => '',
			'root_smart_filter' => 'filter/#SMART_FILTER_PATH#/apply/',
			'section' => '#SECTION_CODE_PATH#/',
			'smart_filter' => '#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/',
			'detail' => '#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
        ],
        'URL_TEMPLATE_ALIASES' => [
			'smart_filter' => 'section',
			'root_smart_filter' => 'sections',
        ],
	),
	null,
	array(
		'HIDE_ICONS' => 'Y'
	)
);
if($arDir[3]!=''){
echo '</div>';
}
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
