<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;

if (!Loader::includeModule('main')) return;
if (!Loader::includeModule('form')) return;
if (!Loader::includeModule('ranx.landing')) return;

// activate web forms by default
\Ranx\Landing\Config::set('USE_FORM_MODULE', 1, WIZARD_SITE_ID);

\Bitrix\Main\Config\Option::set('form', 'SIMPLE', 'N');
