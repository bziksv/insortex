<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("#RX_TITLE#");

if (!\Bitrix\Main\Loader::includeModule('ranx.landing')) {
    die('ranx.landing module is not installed');
}

$APPLICATION->IncludeComponent(
	'ranx:pub.landing',
	'',
	array(
		'IBLOCK_ID' => '#RX_IBLOCK_ID#',
		'SEF_FOLDER' => '#RX_PATH#',
		'SET_STATUS_404' => 'Y',
		'SHOW_404' => 'Y',
		'SEF_URL_TEMPLATES' => [
			'sections' => '',
			'section' => '',
			'detail' => '#ELEMENT_CODE#/',
			'rss' => 'rss/',
        ],
	),
	null,
	array(
		'HIDE_ICONS' => 'Y'
	)
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
