<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<p class="panel-copy--moved"><?=Loc::getMessage('RX_PANEL_LANDING_COPY_TAB_MOVED_SUCCESS')?><br><span></span></p>
<p class="panel-copy--copied"><?=Loc::getMessage('RX_PANEL_LANDING_COPY_TAB_COPIED_SUCCESS')?><br><span></span></p>

<?if(!empty($arResult['IBLOCKS'])):?>
    <div class="form-group">
        <label><?= Loc::getMessage('RX_PANEL_LANDING_COPY_TAB_IBLOCK_TITLE') ?></label>
        <select name="iblock" class="form-control">
            <option value="0"><?= Loc::getMessage('RX_PANEL_LANDING_COPY_TAB_IBLOCK_DEFAULT') ?></option>

            <?foreach($arResult['IBLOCKS'] as $iblock):?>
                <option value="<?= $iblock['ID'] ?>">[<?= $iblock['ID'] ?>] <?= $iblock['NAME'] ?></option>
            <?endforeach?>
        </select>
    </div>

    <?foreach($arResult['IBLOCKS'] as $iblock):?>
        <div class="form-group" data-iblock="<?=$iblock['ID']?>" style="display: none">
            <label><?= Loc::getMessage('RX_PANEL_LANDING_COPY_TAB_LANDING_TITLE') ?></label>
            <select name="landing_<?=$iblock['ID']?>" class="form-control" disabled>

                <?if(!empty($iblock['TYPE']) && (!in_array($iblock['TYPE'], [SectionTable::TYPE_MAIN, SectionTable::TYPE_LANDING]))):?>
                <option value="0" data-mode=""><?= Loc::getMessage('RX_PANEL_LANDING_COPY_TAB_LANDING_DEFAULT') ?></option>
                <?endif?>

                <?if(!empty($iblock['SECTIONS'])):?>
                    <?foreach($iblock['SECTIONS'] as $section):?>
                        <option value="<?=$section['ID']?>" data-mode="<?=Landing::MODE_SECTION?>"><?=$section['NAME']?></option>

                        <?if(!empty($section['ELEMENTS'])):?>
                            <?foreach($section['ELEMENTS'] as $element):?>
                                <option value="<?=$element['ID']?>" data-mode="<?=Landing::MODE_ELEMENT?>">&nbsp;&nbsp;&nbsp;&nbsp;<?=$element['NAME']?></option>
                            <?endforeach?>
                        <?endif?>

                    <?endforeach?>
                <?endif?>

                <?if(!empty($iblock['ELEMENTS'])):?>
                    <?foreach($iblock['ELEMENTS'] as $element):?>
                        <option value="<?=$element['ID']?>" data-mode="<?=Landing::MODE_ELEMENT?>"><?=$element['NAME']?></option>
                    <?endforeach?>
                <?endif?>

            </select>
        </div>
    <?endforeach?>

    <div class="form-group form-group--last">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="panelBlockCopyThrough" name="through">
            <label class="custom-control-label" for="panelBlockCopyThrough"><?= Loc::getMessage('RX_PANEL_LANDING_COPY_TAB_THROUGH_TITLE') ?></label>
        </div>
    </div>

    <button class="btn btn-primary" data-action="copy" disabled><?= Loc::getMessage('RX_PANEL_LANDING_COPY_TAB_ACTION_COPY') ?></button>
    <button class="btn btn-transparent" data-action="move" disabled><?= Loc::getMessage('RX_PANEL_LANDING_COPY_TAB_ACTION_MOVE') ?></button>

    <div class="panel-body-desc">
        <p><?= Loc::getMessage('RX_PANEL_LANDING_COPY_TAB_DESC') ?></p>
        <p><?= Loc::getMessage('RX_PANEL_LANDING_COPY_TAB_DESC_2') ?></p>
    </div>
<?endif?>
