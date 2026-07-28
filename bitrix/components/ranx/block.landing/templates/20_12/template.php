<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">
    <?= $arResult['BLOCK_TITLE'] ?>

    <div class="search-wrap">
        <?$APPLICATION->IncludeComponent(
            'bitrix:search.page',
            'rx_search',
            [
                'AJAX_MODE' => 'N',
                'AJAX_OPTION_ADDITIONAL' => '',
                'AJAX_OPTION_HISTORY' => 'N',
                'AJAX_OPTION_JUMP' => 'N',
                "AJAX_OPTION_STYLE" => 'N',
                'CACHE_TIME' => '3600',
                'CACHE_TYPE' => 'A',
                'CHECK_DATES' => 'Y',
                'DEFAULT_SORT' => 'rank',
                'DISPLAY_BOTTOM_PAGER' => 'Y',
                'DISPLAY_TOP_PAGER' => 'N',
                'FILTER_NAME' => '',
                'NO_WORD_LOGIC' => 'Y',
                'PAGER_SHOW_ALWAYS' => 'Y',
                'PAGER_TEMPLATE' => 'rx_simple',
                'PAGER_TITLE' => '',
                'PAGE_RESULT_COUNT' => Config::getSearchPageResultCount(),
                'PATH_TO_USER_PROFILE' => '',
                'RATING_TYPE' => '',
                'RESTART' => 'Y',
                'SHOW_RATING' => '',
                'SHOW_WHEN' => 'N',
                'SHOW_WHERE' => 'N',
                'USE_LANGUAGE_GUESS' => 'Y',
                'USE_SUGGEST' => 'N',
                'USE_TITLE_RANK' => 'Y',
                'arrFILTER' => ['no'],
                'arrWHERE' => [],
            ]
        );?>
    </div>
</div>

<?= $arResult['BLOCK_END'] ?>
