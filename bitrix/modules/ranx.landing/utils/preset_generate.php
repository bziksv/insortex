<?php
/**
 * If preset is very heavy (>100Mb) and you can't download it.
 * In this situation, use this cli script.
 * Script will generate a preset and save one to /upload/ranx.landing/presets/ dir.
 */

if (php_sapi_name() !== 'cli') die();

if (empty($argv[1]) || empty($argv[2]) || intval($argv[1]) <= 0) {
    echo 'Usage: <landing-id> <mode>' . PHP_EOL;
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

if (!in_array($argv[2], \Ranx\Landing\Landing::MODE_ALL)) {
    echo 'Error: no such mode' . PHP_EOL;
    die();
}

$data = \Ranx\Landing\Preset::generateFromLanding($argv[1], $argv[2]);
$base64 = base64_encode($data);
$uniqueId = md5($base64);

$presetDir = $_SERVER['DOCUMENT_ROOT'] . \Ranx\Landing\Preset::CUSTOM_PATH;
if (file_exists($presetDir . $uniqueId . \Ranx\Landing\Preset::FILE_EXT)) {
    echo 'Error: this preset is already exists' . PHP_EOL;
    die();
}

file_put_contents($presetDir . $uniqueId . \Ranx\Landing\Preset::FILE_EXT, $data);

echo 'Preset is successfully created with code ' . $uniqueId . PHP_EOL;
