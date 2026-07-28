<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
if(!defined('WIZARD_ABSOLUTE_PATH')) return;
if(!defined('WIZARD_SITE_DIR')) return;
if(!defined('WIZARD_SITE_PATH')) return;
if(!defined('LANGUAGE_ID')) return;

CopyDirFiles(
    str_replace('//', '/', WIZARD_ABSOLUTE_PATH.'/site/public/'.LANGUAGE_ID.'/'),
    WIZARD_SITE_PATH,
    $rewrite = false, 
    $recursive = true,
    $delete_after_copy = false,
    $exclude = 'bitrix'
);

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . '/.top.menu.php', ['SITE_DIR' => WIZARD_SITE_DIR]);
