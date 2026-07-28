<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $field
 */

use Ranx\Landing\Config;
use Bitrix\Main\Localization\Loc;

$aarrayFields = Config::getAarrayFields($field['NAME']);
if (!is_array($field['VALUE'])) {
    $field['VALUE'] = [];
}
$field['VALUE'][] = []; // add empty row
?>

<div class="form-group">
    <label>
        <?= $field['TITLE'] ?>
        <?if(!empty($field['DOC'])):?>(<a href="<?= $field['DOC'] ?>" target="_blank"
            title="<?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_DOC') ?>">?</a>)<?endif?>
    </label>

    <div class="aarray-fields <?if($field['AARRAY_EXPANDED']):?>aarray-expanded<?endif?>">
        <?foreach($field['VALUE'] as $j => $aarray):?>
            <div class="aarray-field">

                <?foreach($aarrayFields as $aarrayFieldCode => $aarrayField):?>

                    <?if($aarrayField['TYPE'] === 'checkbox'):?>
                        <div class="custom-control custom-checkbox">
                            <input 
                                type="checkbox" 
                                class="custom-control-input" 
                                id="panelCheck_<?=$field['NAME']?>_<?=$j?>_<?=$aarrayFieldCode?>"
                                name="<?=$field['NAME']?>[<?=$j?>][<?=$aarrayFieldCode?>]" <?if($aarray[$aarrayFieldCode]):?>checked<?endif?>
                            />
                            <label class="custom-control-label" for="panelCheck_<?=$field['NAME']?>_<?=$j?>_<?=$aarrayFieldCode?>">
                                <?=$aarrayField['TITLE']?>
                            </label>
                        </div>
                    <?elseif($aarrayField['TYPE'] === 'text'):?>
                        <textarea
                            name="<?=$field['NAME']?>[<?=$j?>][<?=$aarrayFieldCode?>]"
                            class="form-control <?=$aarrayField['CLASS']?>"
                            data-index="<?=$j?>"
                            placeholder="<?=$aarrayField['TITLE']?>"
                        ><?=(!empty($aarray) ? htmlspecialcharsbx($aarray[$aarrayFieldCode]) : '')?></textarea>
                    <?else:?>
                        <input 
                            class="form-control <?=$aarrayField['CLASS']?>"
                            type="text"
                            name="<?=$field['NAME']?>[<?=$j?>][<?=$aarrayFieldCode?>]"
                            data-index="<?=$j?>"
                            placeholder="<?=$aarrayField['TITLE']?>"
                            value="<?=(!empty($aarray) ? htmlspecialcharsbx($aarray[$aarrayFieldCode]) : '')?>"
                        />
                    <?endif?>
                <?endforeach?>

            </div>
        <?endforeach?>

        <a href="#" class="aarray-add js-add-aarray-field"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_AARRAY_ADD') ?></a>
    </div>
</div>
