<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\AssetLocation;

$asset = \Bitrix\Main\Page\Asset::getInstance();
$templatePath = $this->__template->__folder;
$docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');

// Bust nginx/browser static cache; MUST load after jQuery (AssetLocation::AFTER_JS).
// addString(..., true) defaults to AFTER_JS_KERNEL and runs before template jQuery → "$ is not defined".
$assetVer = static function ($relPath) use ($docRoot) {
    $full = $docRoot . $relPath;
    $mtime = is_file($full) ? filemtime($full) : time();
    return $relPath . '?v=' . $mtime . '-move9';
};

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

$panelJs = [
    'functions.js',
    'main.js',
    'block.js',
    'aarray.js',
    'preset.js',
    'radiocolor.js',
    'section_element.js',
    'select.js',
    'updates.js',
    'ac.js',
    'group.js',
    'menu.js',
    'gallery.js',
    'tabs.js',
];

foreach ($panelJs as $file) {
    $src = htmlspecialcharsbx($assetVer($templatePath . '/assets/js/' . $file));
    $asset->addString(
        '<script src="' . $src . '"></script>',
        false,
        AssetLocation::AFTER_JS
    );
}

$asset->addJs('/bitrix/js/ranx.landing/rx_show_if.js');
