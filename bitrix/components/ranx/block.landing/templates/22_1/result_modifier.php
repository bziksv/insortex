<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Api\Instagram;

$instagram = new Instagram();
$arResult['ITEMS'] = $instagram->getPosts();
