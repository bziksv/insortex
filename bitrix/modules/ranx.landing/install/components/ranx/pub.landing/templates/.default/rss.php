<?if (!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)die();
$this->setFrameMode(true);
?>
<?$APPLICATION->IncludeComponent(
    'bitrix:rss.out',
    '',
    [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'NUM_NEWS' => $arParams['RSS_NUM_NEWS'] ?? '20',
        'NUM_DAYS' => $arParams['RSS_NUM_DAYS'] ?? '',
        'RSS_TTL' => $arParams['RSS_TTL'] ?? '60',
        'YANDEX' => $arParams['RSS_YANDEX'] ?? 'N',
        'SORT_BY1' => $arParams['RSS_SORT_BY1'] ?? 'ACTIVE_FROM',
        'SORT_ORDER1' => $arParams['RSS_SORT_ORDER1'] ?? 'DESC',
        'SORT_BY2' => $arParams['RSS_SORT_BY2'] ?? 'SORT',
        'SORT_ORDER2' => $arParams['RSS_SORT_ORDER2'] ?? 'ASC',
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => $arParams['RSS_CACHE_TIME'] ?? '86400',
        'CACHE_GROUPS' => 'N',
    ]
);?>
