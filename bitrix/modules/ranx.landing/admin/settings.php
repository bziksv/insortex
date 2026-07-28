<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

/**
 * RANX: Creator options page
 */

use Bitrix\Main\Loader,
    Ranx\Landing\Config,
    Ranx\Landing\Helpers,
    Bitrix\Main\Application,
    Bitrix\Main\Config\Option,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Web\Json;
    
defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'ranx.landing');

global $USER, $APPLICATION;
if (!$USER->CanDoOperation('rx_landing_settings_edit')) {
    $APPLICATION->authForm('Access denied');
}

Loader::includeModule(ADMIN_MODULE_NAME);
\Bitrix\Main\UI\Extension::load('ui.hint');
CJSCore::RegisterExt('rx_show_if', [
    'js' => '/bitrix/js/' . ADMIN_MODULE_NAME . '/rx_show_if.js'
]);
CJSCore::Init('jquery3');
CJSCore::Init('rx_show_if');

$context = Application::getInstance()->getContext();
$request = $context->getRequest();

Loc::loadMessages($context->getServer()->getDocumentRoot()."/bitrix/modules/main/options.php");
Loc::loadMessages(__FILE__);

$GLOBALS['APPLICATION']->SetTitle(Loc::getMessage('RX_LANDING_OPTIONS_TITLE'));

$sitesList = Helpers\Admin::getSites();
$tabControl = Helpers\Admin::initTabControl($sitesList);

Helpers\Admin::handlePostRequest($sitesList);

$tabControl->begin();
?>

<script>BX.ready(function() {BX.UI.Hint.init(BX('ranx-landing-options-form'))});</script>

<form id="ranx-landing-options-form" method="post" action="<?=sprintf('%s?mid=%s&lang=%s', $request->getRequestedPage(), urlencode($mid), LANGUAGE_ID)?>">
    <?php
    echo bitrix_sessid_post();
    ?>

    <?foreach($sitesList as $site):
        $tabControl->beginNextTab();    
    ?>

        <?foreach(Config::$params as $groupCode => $group):
            if (!empty($group['HIDDEN'])) continue;
        ?>
            <tr class="heading">
                <td colspan="2"><b><?= $group['TITLE'] ?></b></td>
            </tr>

            <?if(!empty($group['NOTE'])):?>
            <tr>
                <td colspan="2"><?= BeginNote('align="center"'); ?><?= $group['NOTE'] ?><?= EndNote(); ?></td>
            </tr>
            <?endif?>

            <?foreach($group['OPTIONS'] as $optionCode => $option):
                $trClasses = '';
                $trData    = '';
                if (!empty($option['SHOW_IF']))
                {
                    $trClasses .= 'js-show-if collapse ';
                    $trData    .= 'data-show-if=\'' . Json::encode($option['SHOW_IF']) . '\' ';
                }

                if (!empty($option['HIDDEN'])) {
                    $trClasses = 'collapse';
                }
            ?>
                <tr class="<?= $trClasses ?>" <?= $trData ?>>
                    <td class="adm-detail-content-cell-l" style="width: 50%;">
                        <label>
                            <?if(!empty($option['HINT'])):?><span data-hint="<?= $option['HINT'] ?>"></span><?endif?>
                            <?= $option['TITLE'] ?>:
                        </label>
                    </td>
                    <td class="adm-detail-content-cell-r" style="width: 50%;">

                        <?if($option['TYPE'] == 'select'):
                            $optionVal = Config::get($optionCode, null, $site['LID']);
                        ?>

                            <select name="<?= $optionCode ?>_<?= $site['LID'] ?>" <?if($option['DISABLED']):?>disabled<?endif?> data-option="<?= $optionCode ?>">

                                <?foreach($option['LIST'] as $listItemCode => $listItem):?>
                                <option value="<?= $listItemCode ?>" <?if($optionVal == $listItemCode):?>selected<?endif?>><?= $listItem['TITLE'] ?></option>
                                <?endforeach?>

                            </select>

                            <?if(!empty($option['AFTER'])):?>
                                <?= $option['AFTER'] ?>
                            <?endif?>

                        <?elseif($option['TYPE'] == 'multiselect'):
                            $optionVal = Config::get($optionCode, null, $site['LID']);
                        ?>

                            <select name="<?= $optionCode ?>_<?= $site['LID'] ?>[]" multiple <?if($option['DISABLED']):?>disabled<?endif?>>

                                <?foreach($option['LIST'] as $listItemCode => $listItem):?>
                                <option value="<?= $listItemCode ?>" <?if(in_array($listItemCode, $optionVal)):?>selected<?endif?>><?= $listItem['TITLE'] ?></option>
                                <?endforeach?>

                            </select>

                        <?elseif($option['TYPE'] == 'aarray'):
                            $optionVal = Config::get($optionCode, null, $site['LID']);
                            $optionFields = Config::getAarrayFields($optionCode);  

                            if (empty($optionVal)) {
                                $optionVal = [];
                            }
                            $optionVal[] = [];
                        ?>

                            <div class="aarray-fields <?if($option['AARRAY_EXPANDED']):?>aarray-expanded<?endif?>">
                                <?foreach($optionVal as $j => $aarray):?>
                                    <div class="aarray-field">

                                    <?foreach($optionFields as $optionFieldCode => $optionField):?>

                                        <?if($optionField['TYPE'] === 'checkbox'):?>
                                            <div class="custom-control custom-checkbox">
                                                <input 
                                                    type="checkbox" 
                                                    class="custom-control-input" 
                                                    id="panelCheck_<?=$optionCode?>_<?=$j?>_<?=$optionFieldCode?>"
                                                    name="<?=$optionCode?>_<?= $site['LID'] ?>[<?=$j?>][<?=$optionFieldCode?>]" <?if($aarray[$optionFieldCode]):?>checked<?endif?>
                                                />
                                                <label class="custom-control-label" for="panelCheck_<?=$optionCode?>_<?=$j?>_<?=$optionFieldCode?>">
                                                    <?=$optionField['TITLE']?>
                                                </label>
                                            </div>
                                        <?elseif($optionField['TYPE'] === 'text'):?>
                                            <textarea
                                            name="<?=$optionCode?>_<?= $site['LID'] ?>[<?=$j?>][<?=$optionFieldCode?>]"
                                                class="form-control"
                                                data-index="<?=$j?>"
                                                placeholder="<?=$optionField['TITLE']?>"
                                            ><?=(!empty($aarray) ? htmlspecialcharsbx($aarray[$optionFieldCode]) : '')?></textarea>
                                        <?else:?>
                                            <input 
                                                type="text"
                                                name="<?=$optionCode?>_<?= $site['LID'] ?>[<?=$j?>][<?=$optionFieldCode?>]"
                                                placeholder="<?=$optionField['TITLE']?>"
                                                value="<?=(!empty($aarray) ? htmlspecialcharsbx($aarray[$optionFieldCode]) : '')?>"
                                            />
                                        <?endif?>
                                    <?endforeach?>

                                    </div>
                                <?endforeach?>

                                <a href="#" class="js-add-aarray-field"><?= Loc::getMessage('RX_LANDING_OPTIONS_AARRAY_ADD') ?></a>
                            </div>

                        <?elseif($option['TYPE'] == 'file'):
                            $fileId = Config::get($optionCode, 0, $site['LID']);
                            $allowedExt = implode(', ', $option['EXTS'] ?? []);
                        ?>

                            <?=\Bitrix\Main\UI\FileInput::createInstance([
                                'name' => $optionCode . '_' . $site['LID'],
                                'description' => true,
                                'upload' => true,
                                'allowUpload' => 'A',
                                'allowUploadExt' => $allowedExt,
                                'medialib' => true,
                                'fileDialog' => true,
                                'cloud' => true,
                                'delete' => true,
                                'maxCount' => 1
                            ])->show($fileId);
                            ?>

                        <?elseif($option['TYPE'] == 'string'):
                            $optionValue = Config::get($optionCode, null, $site['LID']);
                        ?>
                            <input type="text" name="<?= $optionCode ?>_<?= $site['LID'] ?>" value="<?=htmlspecialcharsbx($optionValue)?>"
                                   <?if($option['PLACEHOLDER']):?>placeholder="<?=$option['PLACEHOLDER']?>"<?endif?> <?if($option['DISABLED']):?>disabled<?endif?>>
                        
                        <?elseif($option['TYPE'] == 'text'):
                            $optionValue = Config::get($optionCode, null, $site['LID']);
                        ?>
                            
                            <textarea name="<?= $optionCode ?>_<?= $site['LID'] ?>"
                                      cols="<?=($option['SIZES']['COLS'] ?? 30)?>" rows="<?=($option['SIZES']['ROWS'] ?? 5)?>"><?=htmlspecialcharsbx($optionValue)?></textarea>

                        <?elseif($option['TYPE'] == 'multitext'):
                            $optionValue = Config::get($optionCode, null, $site['LID']);
                        ?>

                            <?foreach($optionValue as $i => $val):?>
                                <textarea name="<?= $optionCode ?>_<?= $site['LID'] ?>[]" <?if(!empty($option['PLACEHOLDER'][$i])):?>placeholder="<?=htmlspecialcharsbx($option['PLACEHOLDER'][$i])?>"<?endif?>
                                          cols="<?=($option['SIZES']['COLS'] ?? 30)?>" rows="<?=($option['SIZES']['ROWS'] ?? 5)?>"><?=htmlspecialcharsbx($val)?></textarea><br>
                            <?endforeach?>

                        <?elseif($option['TYPE'] == 'checkbox'):
                            $optionValue = Config::get($optionCode, null, $site['LID']);
                        ?>
                            <input type="checkbox" name="<?= $optionCode ?>_<?= $site['LID'] ?>"
                                   <?if($optionValue):?>checked<?endif?> <?if($option['DISABLED']):?>disabled<?endif?>
                                   data-option="<?= $optionCode ?>">
                        <?endif?>
                    </td>
                </tr>
            <?endforeach?>
        <?endforeach?>
    
    <?endforeach?>

    <?php
    $tabControl->buttons();
    ?>
    <input type="submit"
           name="save"
           value="<?=Loc::getMessage("MAIN_SAVE") ?>"
           title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>"
           class="adm-btn-save"
           />
    <input type="submit"
           name="restore"
           title="<?=Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS") ?>"
           onclick="return confirm('<?= AddSlashes(Loc::getMessage("RX_LANDING_OPTIONS_RESTORE_WARNING")) ?>')"
           value="<?=Loc::getMessage("MAIN_RESTORE_DEFAULTS") ?>"
           />
    <?php
    $tabControl->end();
    ?>
</form>

<style>
    .collapse {
        display: none;
    }
    .collapse.show {
        display: table-row;
    }
    .adm-info-message {
        max-width: 750px;
    }
    .aarray-fields.aarray-expanded .aarray-field {
        display: flex;
        flex-direction: column;
        border-bottom: 1px solid #4b6267;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }
    .aarray-fields.aarray-expanded .aarray-field > * {
        margin-bottom: 10px;
    }
</style>

<script>
    $(document).ready(function(){

        $('.js-show-if').each(function(){
            let option   = $(this);
            let settings = option.closest('table');

            option.data('show-if-checker', new RX.ShowIf.ShowIfChecker(settings, option));
        });

        $('.js-add-aarray-field').on('click', function(e){
            e.preventDefault();

            let $parent = $(this).parent();
            let $last = $parent.find('.aarray-field').last();
            let $newEl = $last.clone();

            $newEl.find('input[type="text"]').each(function(){
                $(this).val('');

                let index = $(this).data('index');
                index++;
                $(this).data('index', index);
                $(this).attr('data-index', index);

                let name = $(this).attr('name');
                let firstBracket = name.indexOf('[');
                let secondBracket = name.indexOf(']');
                let firstPart = name.slice(0, firstBracket + 1);
                let secondPart = name.slice(secondBracket);

                name = firstPart + index + secondPart;

                $(this).attr('name', name);
            });

            $last.after($newEl);
        });
    });
</script>
