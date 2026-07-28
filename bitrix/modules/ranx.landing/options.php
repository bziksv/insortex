<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

$module_id = 'ranx.landing';

if (!Loader::includeModule($module_id)) {
    return;
}

$postRight = $APPLICATION->GetGroupRight($module_id);

// lang
IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/options.php');
Loc::loadMessages(__FILE__);

// tabs
$tabControl = new \CAdmintabControl('tabControl', [
    ['DIV' => 'editRights', 'TAB' => Loc::getMessage('MAIN_TAB_RIGHTS'), 'ICON' => ''],
]);

?>

<form method="post" action="<?= $APPLICATION->GetCurPage()?>?mid=<?= urlencode($mid)?>&amp;lang=<?= LANGUAGE_ID?>">
<?php
    $tabControl->Begin();
    $tabControl->BeginNextTab();
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/admin/group_rights2.php');
    $tabControl->Buttons();
?>
    <input <?if ($postRight < 'W') echo 'disabled="disabled"' ?> type="submit" name="Update" value="<?= Loc::getMessage('MAIN_SAVE')?>" title="<?= Loc::getMessage('MAIN_OPT_SAVE_TITLE')?>" />
    <?if (strlen($backUrl) > 0):?>
        <input <?if ($postRight < 'W') echo 'disabled="disabled"' ?> type="button" name="Cancel" value="<?= Loc::getMessage('MAIN_OPT_CANCEL')?>" title="<?= Loc::getMessage('MAIN_OPT_CANCEL_TITLE')?>" onclick="window.location='<?echo \htmlspecialcharsbx(CUtil::addslashes($backUrl))?>'" />
        <input type="hidden" name="back_url_settings" value="<?=\htmlspecialcharsbx($backUrl)?>" />
    <?endif?>
<?php
    echo bitrix_sessid_post();
    $tabControl->End();
?>
</form>
