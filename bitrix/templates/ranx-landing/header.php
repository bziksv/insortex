<?php
if (!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

if (!\Bitrix\Main\Loader::includeModule('ranx.landing')) {
    die('install ranx.landing module to use this template');
}

use Ranx\Landing\Page;
use Ranx\Landing\Config;

define('RX_LANDING_TEMPLATE', 1);
Page::includePartnerModules();
Config::defineSettingId();
?>
<!DOCTYPE html>
<html xml:lang="<?= LANGUAGE_ID;?>" lang="<?= LANGUAGE_ID;?>">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <? Page::showFavicon() ?>

	<title><? $APPLICATION->ShowTitle(); ?></title>
	<?php
	Page::addAssets();
	$APPLICATION->ShowHead();
    \CJSCore::Init();
	?>
</head>
<body class="site-<?= SITE_ID ?>  <?$APPLICATION->ShowProperty('body_classes')?>" <?$APPLICATION->ShowProperty('body_attributes')?>>
    <? Page::showGoogleTagManager(); ?>
    <? $APPLICATION->ShowPanel(); ?>
	<? Page::showHeader(); ?>
