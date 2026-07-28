<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $field
 */
use Bitrix\Main\Localization\Loc;
?>

<div class="form-group <?= $field['FORM_GROUP_CLASSES'] ?>">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="panelCheck_<?=$field['NAME']?>"
               name="<?=$field['NAME']?>" <?if($field['VALUE']):?>checked<?endif?>
               data-option="<?= $field['NAME'] ?>">
        <label class="custom-control-label" for="panelCheck_<?=$field['NAME']?>">
            <?= $field['TITLE'] ?>
            <?if(!empty($field['DOC'])):?>(<a href="<?= $field['DOC'] ?>" target="_blank"
                title="<?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_DOC') ?>">?</a>)<?endif?>
        </label>
    </div>
</div>
