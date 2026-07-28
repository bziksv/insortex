<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Config;
use Bitrix\Main\Localization\Loc;
?>

<?$GLOBALS['APPLICATION']->IncludeComponent(
    'bitrix:search.title',
    'fixed',
    [
        'NUM_CATEGORIES' => '1',
        'TOP_COUNT' => '10',
        'ORDER' => 'date',
        'USE_LANGUAGE_GUESS' => 'Y',
        'CHECK_DATES' => 'Y',
        'SHOW_OTHERS' => 'Y',
        'PAGE' => Config::getSearchPageLink(),
        'CATEGORY_0' => ['no'],
        'INPUT_ID' => 'rx-search-input',
        'CONTAINER_ID' => 'rx-search',
        'SHOW_PREVIEW' => 'Y',
    ],
    false);
?>
