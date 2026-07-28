<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
CHTTP::SetStatus('404 Not Found');
@define('ERROR_404', 'Y');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

\Bitrix\Main\Loader::includeModule('ranx.landing');

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/404.php');

$APPLICATION->SetTitle(Loc::getMessage('RX_LANDING_404_PAGE_NOT_FOUND'));
?>

<div class="page404">
    <div class="page404-image theme-color">
        <?= Helper::svg('404') ?>
    </div>
    <div class="page404-title"><?=Loc::getMessage('RX_LANDING_404_PAGE_NOT_FOUND')?></div>
    <div class="page404-text"><?=Loc::getMessage('RX_LANDING_404_PAGE_TEXT')?></div>
    <div class="page404-btn"><a href="<?=SITE_DIR?>" class="btn btn-primary btn-lg"><?=Loc::getMessage('RX_LANDING_404_PAGE_BTN')?></a></div>
</div>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>
