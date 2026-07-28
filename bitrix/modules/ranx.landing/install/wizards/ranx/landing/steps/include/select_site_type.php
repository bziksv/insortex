<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

/**
 * @var CWizardStep $this
 * @var array $types
 */

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<?= Loc::getMessage('RX_WIZ_STEP_SELECT_SITE_DESC') ?>

<div class="rx-types">
    <?= $this->ShowHiddenField('RX_SITE_TYPE', 'empty') ?>

    <?foreach($types as $type => $typeName):?>
        <div class="rx-type-wrap">
            <div class="rx-type <?= ($type == 'empty' ? 'active' : '') ?>" data-type="<?= $type ?>">
                <div class="rx-type-check"><?= rxWizardSvg('check') ?></div>

                <div class="rx-type-image">
                    <?= rxWizardSvg('type_' . $type) ?>
                </div>
                <div class="rx-type-name"><?= $typeName ?></div>
            </div>
        </div>
    <?endforeach?>
</div>

<script>
    $(document).ready(function(){
        $(document).on('click', '.rx-type', function(e){
            e.preventDefault();

            let type = $(this).data('type');
            let $siteType = $('[name$="RX_SITE_TYPE"]');

            $('.rx-type').removeClass('active');
            $(this).addClass('active');
            $siteType.val(type);
            $siteType.trigger('change');
        });
    });
</script>
