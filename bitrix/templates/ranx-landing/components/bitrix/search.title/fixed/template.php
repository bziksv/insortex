<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

if (empty($arParams['INPUT_ID']) || empty($arParams['CONTAINER_ID'])) {
    return;
}
?>

<div class="rx-fixed-search">
    <div class="maxwidth-theme">
        <form id="<?=$arParams['CONTAINER_ID']?>" action="<?=$arResult['FORM_ACTION']?>" class="search-form">
            <input class="search-input" id="<?=$arParams['INPUT_ID']?>" type="text" name="q" value="" maxlength="50"
                   autocomplete="off" placeholder="<?= Loc::getMessage('RX_SEARCH_TITLE_FIXED_PLACEHOLDER')?> "/>
            <button class="btn btn-primary btn-lg search-btn" type="submit" name="s">
                <?=Loc::getMessage('RX_SEARCH_TITLE_FIXED_BTN')?>
            </button>
            <div class="search-close theme-color-hover"><?= Helper::svg('header/close') ?></div>
            </div>
        </form>
    </div>
</div>

<script>
	let jsControl = new JCTitleSearch({
		'AJAX_PAGE' : window.location.pathname + window.location.search,
		'CONTAINER_ID': '<?= $arParams['CONTAINER_ID'] ?>',
		'INPUT_ID': '<?= $arParams['INPUT_ID'] ?>',
		'MIN_QUERY_LEN': 2
	});
</script>
