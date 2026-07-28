<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);
/**
 * @var array $arResult
 */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<?if(empty($arResult)):?>
    <div class="alert alert-danger"><?= Loc::getMessage('RX_PANEL_LANDING_SETTINGS_EMPTY') ?></div>
<?else:?>

    <?foreach($arResult as $key => $item):?>

        <?if($item['TYPE'] === 'checkbox'):?>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="panelSettingsCheck_<?=$key?>"
                           name="<?=$key?>" <?if($item['VALUE']):?>checked<?endif?>>
                    <label class="custom-control-label" for="panelSettingsCheck_<?=$key?>"><?= $item['TITLE'] ?></label>
                </div>
            </div>

        <?elseif($item['TYPE'] == 'select'):?>

            <div class="form-group">
                <label><?= $item['TITLE'] ?></label>
                <select name="<?= $key ?>" class="form-control">
                    <?foreach($item['LIST'] as $listItemKey => $listItem):
                        $isSelected = $listItemKey == $item['VALUE'];
                        ?>
                        <option value="<?= $listItemKey ?>" <?if($isSelected):?>selected<?endif?>><?= $listItem['TITLE'] ?></option>
                    <?endforeach?>
                </select>
            </div>

        <?elseif($item['TYPE'] == 'text'):?>

            <div class="form-group">
                <label><?= $item['TITLE'] ?></label>
                <textarea name="<?= $key ?>" class="form-control"><?= $item['VALUE'] ?></textarea>
            </div>

        <?elseif($item['TYPE'] === 'string'):?>

            <div class="form-group">
                <label><?= $item['TITLE'] ?></label>
                <input type="text" class="form-control" name="<?= $key ?>" value="<?= htmlspecialcharsbx($item['VALUE']) ?>"
                       <?if(!empty($item['PLACEHOLDER'])):?>placeholder="<?= $item['PLACEHOLDER'] ?>"<?endif?>>
            </div>

        <?endif?>

    <?endforeach?>

<?endif?>
