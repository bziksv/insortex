<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("Как купить");

if (!\Bitrix\Main\Loader::includeModule('ranx.landing')) {
    die('ranx.landing module is not installed');
}

$APPLICATION->IncludeComponent(
	'ranx:pub.landing',
	'',
	array(
		'IBLOCK_ID' => '13',
		'SEF_FOLDER' => '/buy/',
		'SET_STATUS_404' => 'Y',
		'SHOW_404' => 'Y',
		'SEF_URL_TEMPLATES' => [
			'sections' => '',
			'section' => '',
			'detail' => '#ELEMENT_CODE#/',
		],
	),
	null,
	array(
		'HIDE_ICONS' => 'Y'
	)
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
