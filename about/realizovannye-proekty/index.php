<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("Реализованные проекты");

if (!\Bitrix\Main\Loader::includeModule('ranx.landing')) {
    die('ranx.landing module is not installed');
}

$APPLICATION->IncludeComponent(
	'ranx:one.landing',
	'',
	array(
        'IBLOCK_ID' => '25',
		'LANDING_ID' => '1126',
	),
	null,
	array(
		'HIDE_ICONS' => 'Y'
	)
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
