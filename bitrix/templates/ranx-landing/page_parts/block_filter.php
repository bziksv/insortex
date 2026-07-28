<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arParams
 */
?>
<?$GLOBALS['APPLICATION']->IncludeComponent(
    'ranx:block.filter.landing',
    '.default',
    $arParams,
    false);
?>
