<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $field
 * @var array $fieldCheckbox
 */
use Ranx\Landing\Config;
use Bitrix\Main\Web\Json;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Panel\Manager as PanelManager;

$rxDemoMode = Config::isDemoLanding();
?>

<div class="form-group <?= $field['FORM_GROUP_CLASSES'] ?>">
    <label>
        <?= $field['TITLE'] ?>
        <?if(!empty($field['DOC'])):?>(<a href="<?= $field['DOC'] ?>" target="_blank"
            title="<?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_DOC') ?>">?</a>)<?endif?>
        <?if(!empty($fieldCheckbox)):?>
            <?php
                $selectField = $field;
                $field = $fieldCheckbox;
                $field['FORM_GROUP_CLASSES'] = 'mt-3';
                include 'checkbox.php';
                $field = $selectField;
            ?>
        <?endif?>
    </label>

    <div class="panel-select <?if(!empty($fieldCheckbox) && !empty($field['SHOW_IF'])):?>js-show-if collapse<?endif?>"
         <?if(!empty($fieldCheckbox) && !empty($field['SHOW_IF'])):?>data-show-if='<?= Json::encode($field['SHOW_IF']) ?>'<?endif?>>
        <input
            type="hidden"
            name="<?= $field['NAME'] ?>" value="<?= $field['VALUE'] ?>"
            data-option="<?= $field['NAME'] ?>"
        >
        <div class="panel-select-options <?if($field['THEME_ICON_WIDE']):?>panel-select-options--wide<?endif?> row">

            <?foreach($field['LIST'] as $fieldItemId => $fieldItem):
                if ($rxDemoMode && $fieldItemId == 'custom') continue;
                ?>
                <div class="<?if($field['THEME_ICON_WIDE']):?>col-md-12<?else:?>col-md-4<?endif?>">
                    <div class="panel-select-option theme-border-class-active <?if($field['VALUE'] == $fieldItemId):?>active<?endif?>" data-value="<?= $fieldItemId ?>">

                        <?if(!empty($fieldItem['ICON'])):?>
                            <div class="panel-select-option-icon">
                                <img src="<?= PanelManager::getOptionIconPath($field['NAME'], $fieldItem['ICON']) ?>" alt="<?= $fieldItem['TITLE'] ?>">
                            </div>
                        <?endif?>

                        <div class="panel-select-option-title">
                            <?if(!empty($fieldItem['DESC'])):?>
                                <?= $fieldItem['DESC'] ?>
                            <?else:?>
                                <?= $fieldItem['TITLE'] ?>
                            <?endif?>
                        </div>
                    </div>
                </div>
            <?endforeach?>

        </div>
    </div>
</div>