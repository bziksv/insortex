<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("Проекты");

if (!\Bitrix\Main\Loader::includeModule('ranx.landing')) {
    die('ranx.landing module is not installed');
}

$APPLICATION->IncludeComponent(
	'ranx:one.landing',
	'',
	array(
        'IBLOCK_ID' => '16',
		'LANDING_ID' => '90',
	),
	null,
	array(
		'HIDE_ICONS' => 'Y'
	)
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
