<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetPageProperty("description", "Актуальные новости и статьи Инсортекс: обзоры фотосепараторов, зерноочистителей, анонсы поставок и сервиса. Узнайте первыми о новинках техники для очистки и сортировки зерна.");
$APPLICATION->SetPageProperty("title", "Новости и статьи от компании Инсортекс");
$APPLICATION->SetTitle("Новости");

if (!\Bitrix\Main\Loader::includeModule('ranx.landing')) {
    die('ranx.landing module is not installed');
}

$APPLICATION->IncludeComponent(
	'ranx:pub.landing',
	'',
	array(
		'IBLOCK_ID' => '19',
		'SEF_FOLDER' => '/news/',
		'SET_STATUS_404' => 'Y',
		'SHOW_404' => 'Y',
		'SEF_URL_TEMPLATES' => [
			'sections' => '',
			'section' => '#SECTION_CODE_PATH#/',
			'detail' => '#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
			'rss' => 'rss/',
        ],
	),
	null,
	array(
		'HIDE_ICONS' => 'Y'
	)
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
