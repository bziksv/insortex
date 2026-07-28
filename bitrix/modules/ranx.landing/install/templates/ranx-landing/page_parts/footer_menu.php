<?$GLOBALS['APPLICATION']->IncludeComponent(
    'bitrix:menu',
    'footer',
    [
        'ROOT_MENU_TYPE' => $rootMenuType,
        'MAX_LEVEL' => 1,
        'USE_EXT' => 'Y',
        'DELAY' => 'N',
        'ALLOW_MULTI_SELECT' => 'Y',
        'MENU_CACHE_TYPE' => 'A',
        'MENU_CACHE_TIME' => 3600,
        'MENU_CACHE_USE_GROUPS' => 'Y',
        'MENU_CACHE_GET_VARS' => '',
    ]
);
?>
