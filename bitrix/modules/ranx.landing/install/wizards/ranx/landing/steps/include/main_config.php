<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

/**
 * @var CWizardStep $this
 * @var array $presets
 */

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<?if(!empty($groups)):
    $firstGroup = reset($groups);
    $firstGroupCode = key($groups);

    $firstPreset = reset($firstGroup['PRESETS']);
    $firstPresetCode = key($firstGroup['PRESETS']);
?>

    <?= $this->ShowHiddenField('RX_MAIN_PRESET', $firstPresetCode) ?>
    <p class="rx-warning hidden">
        <?= Loc::getMessage('RX_WIZ_STEP_MAIN_CONFIG_VERSION_WARNING', ['#VERSION#' => '<span class="version"></span>']) ?>
    </p>

    <div class="rx-main-config">
        <div class="rx-main-config-list">
            <div class="rx-group-title"><?= Loc::getMessage('RX_WIZ_STEP_MAIN_CONFIG_CATEGORY_TITLE') ?></div>
            <div class="rx-groups">
                <?foreach ($groups as $groupCode => $arGroup):?>
                    <div class="rx-group <?=($groupCode == $firstGroupCode ? 'active' : '')?>" data-target="<?=$groupCode?>">
                        <?=$arGroup['TITLE'];?>
                    </div>
                <?endforeach?>
            </div>

            <div class="rx-group-title"><?= Loc::getMessage('RX_WIZ_STEP_MAIN_CONFIG_THEME_TITLE') ?></div>
            <?foreach ($groups as $groupCode => $arGroup):?>
                <div class="rx-presets <?=($groupCode == $firstGroupCode ? '' : 'hidden')?>" data-group="<?=$groupCode?>">
                    <?foreach($arGroup['PRESETS'] as $presetCode => $arPreset):?>
                        <? $isFirst = $groupCode == $firstGroupCode && $presetCode == $firstPresetCode; ?>
                        <div class="rx-preset <?=($isFirst ? 'active' : '')?> <?=$arPreset['AVAILABLE'] ? '' : 'disabled'?>"
                             data-code="<?=$presetCode?>" data-preview="<?=$arPreset['DETAIL']?>" data-version="<?=$arPreset['VERSION']?>">
                            <?= $arPreset['TITLE'] ?>
                        </div>
                    <?endforeach?>
                </div>
            <?endforeach?>

            <div class="rx-line"></div>
            <div class="rx-preset-empty">
                <?= Loc::getMessage('RX_WIZ_STEP_MAIN_CONFIG_PRESET_EMPTY') ?>
            </div>
        </div>

        <div class="rx-preset-preview-wrap">
            <div data-simplebar data-simplebar-auto-hide="false" class="rx-preset-preview">
                <img src="<?= $firstPreset['DETAIL'] ?>" alt="">
            </div>
            <div class="rx-preset-preview-preload">
                <?foreach ($groups as $groupCode => $arGroup):?>
                    <?foreach($arGroup['PRESETS'] as $presetCode => $arPreset):?>
                        <img src="<?=$arPreset['DETAIL']?>" alt="">
                    <?endforeach?>
                <?endforeach?>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function(){
            $('.rx-group').on('click', function (e){
                e.preventDefault();
                refresh();

                const $this = $(this);
                const target = $(this).data('target');
                const $presets = $('.rx-presets[data-group="'+target+'"]');
                const $firstPreset = $presets.find('.rx-preset').first();

                $this.addClass('active');
                $presets.removeClass('hidden');
                $firstPreset.trigger('click');
            })
            $('.rx-preset').on('click', function(e){
                e.preventDefault();

                let code = $(this).data('code');
                let preview = $(this).data('preview');

                $('[name$="RX_MAIN_PRESET"]').val(code);
                $('.rx-preset-preview img').attr('src', preview);
                $('.rx-preset-preview .simplebar-content-wrapper').scrollTop(0);

                $('.rx-preset-empty').removeClass('active');
                $('.rx-preset').removeClass('active');
                $(this).addClass('active');

                const $warning = $('.rx-warning');
                const $nextBtn = $('.wizard-next-button');
                const version = $(this).data('version');

                if ($(this).hasClass('disabled')) {
                    $warning.find('.version').text(version);
                    $warning.removeClass('hidden');
                    $nextBtn.prop('disabled', true);
                }
                else {
                    $nextBtn.prop('disabled', false);
                    $warning.addClass('hidden');
                }
            });
            $('.rx-preset-empty').on('click', function(e){
                e.preventDefault();
                refresh();

                $('[name$="RX_MAIN_PRESET"]').val('empty');
                $('.rx-preset-preview img').attr('src', '');
                $('.wizard-next-button').prop('disabled', false);
                $('.rx-warning').addClass('hidden');
            });

            function refresh() {
                $('.rx-group').removeClass('active');
                $('.rx-presets').addClass('hidden');
                $('.rx-preset-empty').removeClass('active');
            }
        });
    </script>

<?else:?>
    <p><?= Loc::getMessage('RX_WIZ_STEP_MAIN_CONFIG_ERROR') ?></p>
<?endif?>
