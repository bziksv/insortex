<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$asset = \Bitrix\Main\Page\Asset::getInstance();
$templatePath = $this->__template->__folder;

$asset->addCss($templatePath . '/assets/css/main.css');
$asset->addCss($templatePath . '/assets/css/block.css');
$asset->addCss($templatePath . '/assets/css/aarray.css');
$asset->addCss($templatePath . '/assets/css/radiocolor.css');
$asset->addCss($templatePath . '/assets/css/select.css');
$asset->addCss($templatePath . '/assets/css/selectric.css');
$asset->addCss($templatePath . '/assets/css/spectrum.css');
$asset->addCss($templatePath . '/assets/css/tooltip.css');
$asset->addCss($templatePath . '/assets/css/ac.css');
$asset->addCss($templatePath . '/assets/css/group.css');
$asset->addCss($templatePath . '/assets/css/menu.css');
$asset->addCss($templatePath . '/assets/css/gallery.css');
$asset->addCss($templatePath . '/assets/css/tabs.css');

$asset->addJs($templatePath . '/assets/js/functions.js');
$asset->addJs($templatePath . '/assets/js/main.js');
$asset->addJs($templatePath . '/assets/js/block.js');
$asset->addJs($templatePath . '/assets/js/aarray.js');
$asset->addJs($templatePath . '/assets/js/preset.js');
$asset->addJs($templatePath . '/assets/js/radiocolor.js');
$asset->addJs($templatePath . '/assets/js/section_element.js');
$asset->addJs($templatePath . '/assets/js/select.js');
$asset->addJs($templatePath . '/assets/js/updates.js');
$asset->addJs($templatePath . '/assets/js/ac.js');
$asset->addJs($templatePath . '/assets/js/group.js');
$asset->addJs($templatePath . '/assets/js/menu.js');
$asset->addJs($templatePath . '/assets/js/gallery.js');
$asset->addJs($templatePath . '/assets/js/tabs.js');

$asset->addJs('/bitrix/js/ranx.landing/rx_show_if.js');
