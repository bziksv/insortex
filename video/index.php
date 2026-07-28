<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetPageProperty("description", "Лучше один раз увидеть: реальные примеры работы фотосепараторов с различными культурами. Видеодемонстрации сортировки зерна, инструкции и обзоры от компании Инсортекс.");
$APPLICATION->SetPageProperty("title", "Видео работы фотосепараторов | Инсортекс");
$APPLICATION->SetTitle("Видео");

if (!\Bitrix\Main\Loader::includeModule('ranx.landing')) {
    die('ranx.landing module is not installed');
}

$APPLICATION->IncludeComponent(
	'ranx:one.landing',
	'',
	array(
        'IBLOCK_ID' => '20',
		'LANDING_ID' => '192',
	),
	null,
	array(
		'HIDE_ICONS' => 'Y'
	)
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
