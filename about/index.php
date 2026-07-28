<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetPageProperty("description", "Инсортекс – ваш партнер в оснащении сельхозпредприятий. Мы поставляем оборудование для очистки, сортировки, протравливания и упаковки зерна. Работаем по всей РФ. Наша цель – повышение эффективности вашего хозяйства.");
$APPLICATION->SetPageProperty("title", "О компании Инсортекс | Поставки зерноочистительного оборудования по России");
$APPLICATION->SetTitle("О компании");

if (!\Bitrix\Main\Loader::includeModule('ranx.landing')) {
    die('ranx.landing module is not installed');
}

$APPLICATION->IncludeComponent(
	'ranx:pub.landing',
	'',
	array(
		'IBLOCK_ID' => '14',
		'SEF_FOLDER' => '/about/',
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
