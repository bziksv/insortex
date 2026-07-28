<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$GLOBALS['APPLICATION']->IncludeComponent(
    'ranx:basket.landing',
    'header',
    [
        'CLASSES' => $classes,
    ]
);
