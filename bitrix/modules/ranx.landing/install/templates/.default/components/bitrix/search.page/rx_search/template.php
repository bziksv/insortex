<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
?>
<div class="rx-search-page">
    <form action="" method="get" class="search-form">
        <input class="search-input" type="text" name="q" value="<?=$arResult['REQUEST']['QUERY']?>"
               placeholder="<?=Loc::getMessage('RX_SEARCH_PAGE_PLACEHOLDER');?>" />
        <input type="hidden" name="how" value="<?=$arResult['REQUEST']['HOW']=='d' ? 'd': 'r'?>" />
        <button class="btn search-btn theme-color-hover" type="submit" name="s" value="<?=Loc::getMessage('RX_SEARCH_PAGE_GO')?>">
            <?= Helper::svg('block/search') ?>
        </button>
    </form>

	<?if(!empty($arResult['REQUEST']['ORIGINAL_QUERY'])):?>
		<div class="alert alert-info search-alert">
			<?=Loc::getMessage('RX_SEARCH_PAGE_KEYBOARD_WARNING',
                ['#query#' => '<a href="'.$arResult["ORIGINAL_QUERY_URL"].'">'.$arResult['REQUEST']['ORIGINAL_QUERY'].'</a>'])?>
		</div>
    <?endif?>

    <?if($arResult['REQUEST']['QUERY'] === false && $arResult['REQUEST']['TAGS'] === false):?>
	<?elseif($arResult['ERROR_CODE'] != 0):?>

		<div class="alert alert-danger search-danger"><?=Loc::getMessage('RX_SEARCH_PAGE_CORRECT_AND_CONTINUE')?></div>

	<?elseif(count($arResult['SEARCH']) > 0):?>

		<div class="search-items">
			<?foreach($arResult['SEARCH'] as $arItem):?>
				<div class="search-item">
					<a class="search-name" href="<?=$arItem['URL']?>"><?=$arItem['TITLE_FORMATED']?></a>
					<?if($arItem['CHAIN_PATH']):?>
						<ul class="breadcrumb search-breadcrumb"><?=$arItem["CHAIN_PATH"]?></ul>
					<?endif;?>
					<?if(!empty($arItem['BODY_FORMATED'])):?>
						<div class="search-text"><?=$arItem['BODY_FORMATED']?></div>
					<?endif;?>
				</div>
			<?endforeach;?>
		</div>

		<?if($arParams['DISPLAY_BOTTOM_PAGER'] != 'N' && $arResult['NAV_STRING']):?>
			<div class="search-navigation">
				<?=$arResult['NAV_STRING']?>
			</div>
		<?endif?>

		<div class="search-sort">
            <? $from = $arResult['REQUEST']['FROM'] ? '&amp;from='.$arResult['REQUEST']['FROM'] : ''; ?>
            <? $to = $arResult['REQUEST']['TO']? '&amp;to='.$arResult['REQUEST']['TO'] : ''; ?>
			<?if($arResult['REQUEST']['HOW'] == 'd'):?>
				<a href="<?=$arResult['URL']?>&amp;how=r<?=$from?><?=$to?>">
                    <?=Loc::getMessage('RX_SEARCH_PAGE_SORT_BY_RANK')?>
                </a>
                <span class="separator">&nbsp;|&nbsp;</span>
                <span><?=Loc::getMessage('RX_SEARCH_PAGE_SORTED_BY_DATE')?></span>
			<?else:?>
				<span><?=Loc::getMessage('RX_SEARCH_PAGE_SORTED_BY_RANK')?></span>
                <span class="separator">&nbsp;|&nbsp;</span>
                <a href="<?=$arResult['URL']?>&amp;how=d<?=$form?><?=$to?>">
                    <?=Loc::getMessage('RX_SEARCH_PAGE_SORT_BY_DATE')?>
                </a>
			<?endif;?>
		</div>

	<?else:?>
		<div class="alert alert-danger search-danger"><?=Loc::getMessage('RX_SEARCH_PAGE_NOTHING_TO_FOUND')?></div>
	<?endif;?>
</div>
