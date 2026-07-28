<?$GLOBALS['APPLICATION']->IncludeComponent(
    'bitrix:menu',
    'header',
    [
        'ROOT_MENU_TYPE' => $rootMenuType,
        'MAX_LEVEL' => 3,
        'CHILD_MENU_TYPE' => $childMenuType,
        'USE_EXT' => 'Y',
        'DELAY' => 'N',
        'ALLOW_MULTI_SELECT' => 'Y',
        'MENU_CACHE_TYPE' => 'A',
        'MENU_CACHE_TIME' => 3600,
        'MENU_CACHE_USE_GROUPS' => 'Y',
        'MENU_CACHE_GET_VARS' => '',
        'CUSTOM_NAV_CLASSES' => $navClasses,
        'CUSTOM_ROOT_ITEM_CLASSES' => $rootItemClasses,
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
?>
