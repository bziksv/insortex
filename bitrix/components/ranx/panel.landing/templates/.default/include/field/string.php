<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $field
 */
use Bitrix\Main\Localization\Loc;
?>

<div class="form-group <?= $field['FORM_GROUP_CLASSES'] ?>">
    <label>
        <?= $field['TITLE'] ?>
        <?if(!empty($field['DOC'])):?>(<a href="<?= $field['DOC'] ?>" target="_blank"
            title="<?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_DOC') ?>">?</a>)<?endif?>
    </label>
    <input type="text" class="form-control" name="<?= $field['NAME'] ?>" value="<?= htmlspecialcharsbx($field['VALUE']) ?>"
        <?if(!empty($field['PLACEHOLDER'])):?>placeholder="<?= $field['PLACEHOLDER'] ?>"<?endif?>>
</div>
