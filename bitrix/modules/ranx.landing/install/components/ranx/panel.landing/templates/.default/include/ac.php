<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $acItems
 * @var string $acName
 * @var string $acAction
 * @var array $acAdditional
 */

use Bitrix\Main\Web\Json;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<div class="panel-ac-items" data-items='<?= Json::encode($acItems) ?>'>
    <div class="panel-ac-item panel-ac-item--example">
        <input type="hidden" name="<?= $acName ?>[]">
        <span></span>
        <div class="panel-ac-item-remove js-panel-ac-item-remove"><?= Helper::svg('panel', 'remove') ?></div>
    </div>
    <?if(!empty($acItems)):?>
        <?foreach($acItems as $item):?>
            <div class="panel-ac-item">
                <input type="hidden" name="<?= $acName ?>[]" value="<?=$item['id']?>">
                <span><?=$item['label']?></span>
                <div class="panel-ac-item-remove js-panel-ac-item-remove"><?= Helper::svg('panel', 'remove') ?></div>
            </div>
        <?endforeach?>
    <?endif?>
</div>
<input type="text" class="form-control js-panel-ac" data-action="<?= $acAction ?>" data-additional='<?= Json::encode($acAdditional) ?>' 
    data-name="<?= $acName ?>" placeholder="<?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_AC_PLACEHOLDER') ?>">
