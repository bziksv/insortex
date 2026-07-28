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
	<!-- rx-mobile-fix: inline so phones ignore stale Bitrix/nginx CSS bundles -->
	<style id="rx-mobile-fix">
	html{overflow-x:clip;max-width:100%;}
	body{overflow-x:clip;max-width:100%;width:100%;position:relative;}
	@supports not (overflow-x:clip){
		html,body{overflow-x:hidden;}
	}
	@media (max-width:991px){
		body.headermobile-is-sticky{#headermobile{
			position:fixed!important;top:0!important;left:0!important;right:0!important;
			width:100%!important;max-width:100%!important;z-index:10000!important;
			transform:none!important;-webkit-transform:none!important;
		}
		body.headermobile-is-sticky{padding-top:62px!important;}
		#blocks_wrapper,.block-wrap,.block,.maxwidth-theme{max-width:100%;min-width:0;box-sizing:border-box;}
		#blocks_wrapper{overflow-x:hidden;}
		.block1-1 .col-flex-rw,.block1-3 .col-flex-rw,.block16-1 .col-flex-rw,.block16-2 .col-flex-rw{
			width:100%!important;max-width:100%!important;
		}
		.block .row{margin-left:0!important;margin-right:0!important;max-width:100%;}
		.slick-list{max-width:100%;overflow:hidden;}
		/* Full-screen mobile menu above sticky header */
		#mobilemenu{
			max-width:none!important;width:100%!important;right:0!important;
			z-index:10050!important;height:100%!important;height:100dvh!important;
		}
		#mobilemenu-overlay{z-index:10040!important;height:100%!important;height:100dvh!important;}
		body.mobilemenu-open #headermobile{visibility:hidden!important;}
	}
	</style>
</head>
<body class="site-<?= SITE_ID ?>  <?$APPLICATION->ShowProperty('body_classes')?>" <?$APPLICATION->ShowProperty('body_attributes')?>>
    <? Page::showGoogleTagManager(); ?>
    <? $APPLICATION->ShowPanel(); ?>
	<? Page::showHeader(); ?>
