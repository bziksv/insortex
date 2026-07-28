<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$field['MULTI'] = $field['MULTI'] ?? false;

if ($field['MULTI'] && !is_array($field['VALUE'])) {
    $field['VALUE'] = array_filter((array)$field['VALUE']);
}
?>

<div class="form-group <?=$field['CLASSES']?>">
    <label><?=$field['TITLE']?></label>
    <select name="<?=$field['NAME']?>" class="form-control" <?if($field['MULTI']):?>multiple<?endif?>>

        <?foreach($field['LIST_VALUES'] as $value => $id):?>
            <? $isSelected = $field['MULTI'] ? in_array($id, $field['VALUE']) : ($id == $field['VALUE']); ?>
            <option value="<?=$id?>" <?if($isSelected):?>selected<?endif?>><?=$value?></option>
        <?endforeach?>

    </select>
</div>
