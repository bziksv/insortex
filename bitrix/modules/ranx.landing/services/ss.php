<?php
define('STATISTIC_SKIP_ACTIVITY_CHECK', true);
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if (!\Bitrix\Main\Loader::includeModule('ranx.landing')) {
    die('DEL');
}

$serverIp = \Ranx\Landing\Helpers\Helper::getDataByUrl('https://soft.landing-demo.ru/ss/ip.php');
if ($_SERVER['REMOTE_ADDR'] !== $serverIp) {
    CHTTP::SetStatus('404 Not Found');
    @define('ERROR_404', 'Y');
    die();
}

if (!\Ranx\Landing\Config::get('SEND_STATS')) {
    die('OFF');
}
\Bitrix\Main\Loader::includeModule('iblock');

$result = [];

$sites = \Bitrix\Main\SiteTable::getList(['select' => ['LID', 'ACTIVE', 'DEF', 'SERVER_NAME', 'DIR']])->fetchAll();
$result['sites'] = $sites;

$modules = array_keys(\Bitrix\Main\ModuleManager::getInstalledModules());
foreach ($modules as $module) {
    $moduleVersion = \Bitrix\Main\ModuleManager::getVersion($module);
    $result['modules'][] = [
        'ID' => $module,
        'VERSION' => $moduleVersion,
    ];
}

$sections = \Ranx\Landing\SectionTable::getList(['select' => ['SITE_ID', 'TITLE', 'PATH', 'TYPE', 'DOMAIN', 'OWN_SETTINGS', 'ROOT_MODE']])->fetchAll();
foreach ($sections as $section) {
    $result['sections'][] = $section;
}

foreach ($sites as $site) {
    $options = \Bitrix\Main\Config\Option::getForModule('ranx.landing', $site['LID']);
    $options['INSTAGRAM_TOKEN'] = !empty($options['INSTAGRAM_TOKEN']) ? 'Y' : 'N';
    $result['options'][$site['LID']] = $options;
}

$blocksRes = \CIBlockElement::GetList([], ['IBLOCK_ID' => \Ranx\Landing\Block::getIblockId()], false, false, ['CODE']);
while ($block = $blocksRes->Fetch()) {
    if (!isset($result['blocks_count'][$block['CODE']])) {
        $result['blocks_count'][$block['CODE']] = 0;
    }
    $result['blocks_count'][$block['CODE']]++;
}

$key = \Ranx\Landing\Helpers\Helper::getDataByUrl('https://soft.landing-demo.ru/ss/public.pem');
if (empty($key)) {
    die('KEY');
}

$cipher = 'aes-128-gcm';
if (in_array($cipher, openssl_get_cipher_methods())) {

    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $password = random_bytes($ivlen);
    $encrypted = openssl_encrypt(json_encode($result), $cipher, $password, $options=0, $iv, $tag);
	openssl_public_encrypt($password, $encryptedPassword, $key);

    $fileId = \CFile::SaveFile(['name' => date('YmdHis'), 'MODULE_ID' => 'ranx.landing', 'content' => $encrypted], 'tmp/ranx.landing/ss');
    if (!$fileId) {
        die('ERR');
    }

	echo \CFile::GetPath($fileId) . "\n";
	echo bin2hex($encryptedPassword) . "\n";
	echo bin2hex($iv) . "\n";
	echo bin2hex($tag);
}
