<?php
/**
 * If preset is very heavy (>100Mb) and you can't apply it.
 * In this situation, use this cli script.
 * Script will apply a preset to chosen landing.
 */

if (php_sapi_name() !== 'cli') die();

if (empty($argv[1]) || empty($argv[2]) || empty($argv[3])|| intval($argv[2]) <= 0) {
    echo 'Usage: <preset-code> <landing-id> <mode>' . PHP_EOL;
    die();
}

$_SERVER['DOCUMENT_ROOT'] = realpath(dirname(__FILE__) . '/../../../..');
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS',true);
define('BX_CRONTAB', true);
define('BX_NO_ACCELERATOR_RESET', true);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

@set_time_limit(0);
@ignore_user_abort(true);

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('ranx.landing');

if (!in_array($argv[3], \Ranx\Landing\Landing::MODE_ALL)) {
    echo 'Error: no such mode' . PHP_EOL;
    die();
}

\Ranx\Landing\Preset::apply($argv[1], $argv[2], $argv[3]);

echo 'Preset is successfully applied!' . PHP_EOL;
