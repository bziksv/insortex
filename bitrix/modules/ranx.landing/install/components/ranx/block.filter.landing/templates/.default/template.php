<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 * @var array $arParams
 */
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;

$templateData['INIT_JS'] = '';
?>
<?if (!empty($arResult['ITEMS'])):?>
    <div class="rx-block-filter">
        <div class="filter__toggle theme-before-bg collapsed" role="button" data-toggle="collapse" aria-expanded="false"
             data-target=".collapse-<?=$arParams['BLOCK_ID']?>" aria-controls="collapse-<?=$arParams['BLOCK_ID']?>">
            <?=Loc::getMessage('RX_LANDING_BLOCK_FILTER_TITLE')?>
        </div>
        <form id="blockFilterForm_<?=$arParams['BLOCK_ID']?>" class="filter__controls collapse collapse-<?=$arParams['BLOCK_ID']?>"
              data-block-id="<?=$arParams['BLOCK_ID']?>">
            <div class="filter__control-wrap row">
                <?foreach ($arResult['ITEMS'] as $code => $arItem):?>
                    <? if ($arItem['TYPE'] == 'LIST' && empty($arItem['VALUES'])) continue; ?>
                    <div class="col-lg-3 col-sm-6 filter__control control--input control--<?=strtolower($arItem['TYPE'])?> is-empty">
                        <label><?=$arItem['TITLE']?></label>
                        <?if ($arItem['TYPE'] == 'TIME'):?>
                            <input name="<?=$code?>" class="form-control js-mask-interval-time" value="" />
                        <?endif;?>
                        <?if ($arItem['TYPE'] == 'LIST'):?>
                            <select name="<?=$code?>[]" class="form-control" multiple>
                                <option value="" disabled><?=Loc::getMessage('RX_LANDING_BLOCK_FILTER_SELECT_NOT_VALUE')?></option>
                                <?foreach($arItem['VALUES'] as $value):?>
                                    <option value="<?=$value?>"><?=$value?></option>
                                <?endforeach?>
                            </select>
                        <?endif;?>
                        <?if ($arItem['TYPE'] == 'NUMBER'):?>
                            <input name="<?=$code?>" class="form-control js-mask-integer" value="" />
                        <?endif;?>
                    </div>
                <?endforeach;?>
            </div>
            <div class="filter__control-wrap row">
                <div class="col-sm-3 filter__control control--button">
                    <button class="btn btn-primary btn-filter">
                        <?=Loc::getMessage('RX_LANDING_BLOCK_FILTER_SUBMIT_BTN')?>
                    </button>
                </div>
                <div class="col-sm-3 filter__control control--button button--reset">
                    <button class="btn btn-primary btn-filter btn-reset">
                        <?=Loc::getMessage('RX_LANDING_BLOCK_FILTER_RESET_BTN')?>
                    </button>
                </div>
            </div>
        </form>
    </div>
<?endif;?>

<? ob_start() ?>
    <script>
        $(document).ready(function(){
            const formIdSelector = '#blockFilterForm_<?=$arParams['BLOCK_ID']?>';

            $('.rx-block-filter .control--list select').selectric({
                maxHeight: 200
            });
            $(document).on('submit', formIdSelector, function(e){
                e.preventDefault();

                let $form = $(this);
                let blockId = $form.data('block-id');
                let dataArr = $form.serializeArray();
                let data = convertFormArrToObj(dataArr);

                startBlockLoad(blockId);
                rxRunComponentAction(
                    'block',
                    'blockFilter',
                    {data: {post:data, blockId: blockId}}
                ).then(function(result){
                    $('#block_' + blockId).trigger('rxFilterItems', { html: result.data.html });
                }, function (result) {
                    console.log(result);
                });
            });
            $(formIdSelector).on('change', 'input, select', function () {
                const $form = $(formIdSelector);
                const $this = $(this);
                const value = $this.val();

                $this.closest('.filter__control').toggleClass('is-empty', !value.length);
                $form.toggleClass('set', !!$form.find('.control--input:not(.is-empty)').length);
            });
            $(document).on('click', formIdSelector + ' .btn-reset', function (e) {
                const $form = $(this).closest(formIdSelector);
                $form.find('input').val('').trigger('change');
                $form.find('.selectric-form-control select').val('').selectric('refresh');
            });
        });
    </script>
<?$templateData['INIT_JS'] .= ob_get_clean();?>
