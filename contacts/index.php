<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetPageProperty("description", "Свяжитесь с нами: ☎ +7 (800) 350-70-12. Адрес офисов, схема проезда, реквизиты. Получите консультацию по фотосепараторам и зерноочистительному оборудованию.");
$APPLICATION->SetPageProperty("title", "Контакты компании Инсортекс | Телефон, адрес, обратная связь");
$APPLICATION->SetTitle("Контакты");

if (!\Bitrix\Main\Loader::includeModule('ranx.landing')) {
    die('ranx.landing module is not installed');
}

$APPLICATION->IncludeComponent(
	'ranx:pub.landing',
	'',
	array(
		'IBLOCK_ID' => '28',
		'SEF_FOLDER' => '/contacts/',
		'SET_STATUS_404' => 'Y',
		'SHOW_404' => 'Y',
		'SEF_URL_TEMPLATES' => [
			'sections' => '',
			'section' => '#SECTION_CODE_PATH#/',
			'detail' => '#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
		],
	),
	null,
	array(
		'HIDE_ICONS' => 'Y'
	)
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
