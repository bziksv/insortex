<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 * @var array $arParams
 */
$this->setFrameMode(true);

use Ranx\Landing\Config;
use Ranx\Landing\Captcha\CaptchaManager;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
?>

<?if(!empty($arResult['B24'])):?>

    <?= $arResult['B24']['CODE'] ?>

<?elseif(!empty($arResult['FIELDS'])):?>
    <?
    $extraFormClasses = '';
    $currentRegion = \Ranx\Landing\Region::getCurrent();
    if ($currentRegion) {
        $currentRegionName = $currentRegion['NAME'];
    }
    if($arResult['USE_CAPTCHA'])
        $extraFormClasses .= 'has-captcha ';
    ?>
    <form class="form js-form <?= $extraFormClasses ?>" method="POST" novalidate>
        <?=bitrix_sessid_post()?>
        <input type="hidden" name="FORM_CODE" value="<?=$arParams['FORM_CODE']?>">
        <?if($arParams['IS_ONECLICK']):?>
            <input type="hidden" name="IS_ONECLICK" value="1">
            <input type="hidden" name="PRODUCT_ID" value="<?= $arParams['PRODUCT_ID'] ?>">
        <?endif?>

        <div class="form-block">

            <?foreach($arResult['FIELDS'] as $field):
                $isRequired = $field['IS_REQUIRED'] == 'Y';
            ?>

                <?if($field['IS_SOURCE']):?>
                    <input type="hidden" name="<?= $field['CODE'] ?>" data-code="SOURCE" value="">
                <?continue;endif?>

                <div class="form-group">

                    <label><?= $field['NAME'] ?> <?if($isRequired):?><span>*</span><?endif?></label>

                    <?if($field['TYPE'] == 'textarea'):?>
                        <textarea name="<?=$field['CODE']?>" class="form-control empty" <?if($isRequired):?>required<?endif?>></textarea>
                    <?elseif(in_array($field['TYPE'], ['dropdown', 'multiselect'])):
                        $isMultiple = $field['TYPE'] == 'multiselect';
                    ?>
                        <select name="<?=$field['CODE']?><?if($isMultiple):?>[]<?endif?>" class="form-control" <?if($isRequired):?>required<?endif?> <?if($isMultiple):?>multiple<?endif?>>

                            <?foreach($field['VALUES'] as $fieldValue):?>
                                <? $isSelected = ($field['IS_CITY'] && $fieldValue['VALUE'] == $currentRegionName); ?>
                                <option value="<?=$fieldValue['ID']?>" <?if($isSelected):?>selected<?endif;?>><?=$fieldValue['VALUE']?></option>
                            <?endforeach?>

                        </select>
                    <?elseif($field['TYPE'] == 'checkbox'):
                        $lastKey = array_key_last($field['VALUES']); ?>
                        <div class="checkbox-group <?if($isRequired):?>required<?endif?>" <?if($field['NUMBER_FROM']):?>data-min-values="<?=$field['NUMBER_FROM']?>"<?endif?> <?if($field['NUMBER_TO']):?>data-max-values="<?=$field['NUMBER_TO']?>"<?endif?>>
                            <?foreach($field['VALUES'] as $key => $fieldValue):?>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="<?=$field['CODE']?>[]" value="<?=$fieldValue['ID']?>" id="<?=$fieldValue['ID']?>" aria-required="true">
                                    <label class="custom-control-label" for="<?=$fieldValue['ID']?>"><?=$fieldValue['VALUE']?></label>
                                    <? if ($key == $lastKey): ?>
                                        <div class="invalid-feedback">
                                            <?= Loc::getMessage('RX_FORM_LANDING_REQUIRED_FIELD'); ?>
                                        </div>
                                        <? if ($field['NUMBER_FROM'] || $field['NUMBER_TO']): ?>
                                            <div class="invalid-feedback invalid-num-selected">
                                                <?= Loc::getMessage(
                                                    'RX_FORM_LANDING_INVALID_SELECTED_COUNT',
                                                    [
                                                        '#FROM#' => $field['NUMBER_FROM'] ? Loc::getMessage('RX_FORM_LANDING_NUMBER_FROM') . $field['NUMBER_FROM'] : '',
                                                        '#TO#' => $field['NUMBER_TO'] ? Loc::getMessage('RX_FORM_LANDING_NUMBER_TO') . $field['NUMBER_TO'] : ''
                                                    ]
                                                ); ?>
                                            </div>
                                        <? endif; ?>
                                    <? endif; ?>
                                </div>
                            <?endforeach?>
                        </div>
                    <?elseif($field['TYPE'] == 'radio'):
                        $lastKey = array_key_last($field['VALUES']);?>
                        <?foreach($field['VALUES'] as $key => $fieldValue):?>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" name="<?=$field['CODE']?>" value="<?=$fieldValue['ID']?>" id="<?=$fieldValue['ID']?>" <?if($isRequired && $key == 0):?>required<?endif?>>
                                <label class="custom-control-label" for="<?=$fieldValue['ID']?>"><?=$fieldValue['VALUE']?></label>
                                <? if ($key == $lastKey): ?>
                                    <div class="invalid-feedback">
                                        <?= Loc::getMessage('RX_FORM_LANDING_REQUIRED_FIELD'); ?>
                                    </div>
                                <? endif; ?>
                            </div>
                        <?endforeach?>
                    <?elseif($field['IS_EMAIL']):?>
                        <input name="<?=$field['CODE']?>" class="form-control empty" type="email" <?if($isRequired):?>required<?endif?> />
                    <?elseif($field['TYPE'] == 'file'):?>
                        <div class="form-custom-file-wrapper">
                            <label class="form-custom-file-label theme-bg-hover theme-border-hover">
                                <span class="form-custom-file-clip"><?=Helper::svg('form/clip')?></span>
                                <span class="form-custom-file-default-text">
                                    <?= Loc::getMessage('RX_FORM_LANDING_CUSTOM_FILE_INPUT_TEXT')?>
                                </span>
                                <span class="form-custom-file-name"></span>
                                <input type="file" name="<?=$field['CODE']?>" class="form-control-file form-custom-file-input"
                                       <?if(!empty($field['FILE_TYPE'])):?>accept="<?=$field['FILE_TYPE']?>"<?endif?>
                                       size="<?=$field['MAX_SIZE']?>" <?if($isRequired):?>required<?endif?>/>
                            </label>
                            <div class="form-custom-file-close theme-bg-hover theme-border-hover">
                                <?=Helper::svg('form/close')?>
                            </div>
                        </div>
                        <div class="invalid-feedback invalid-required">
                            <?= Loc::getMessage('RX_FORM_LANDING_REQUIRED_FIELD'); ?>
                        </div>
                        <div class="invalid-feedback invalid-maxsize">
                            <?= Loc::getMessage('RX_FORM_LANDING_INVALID_MAXSIZE'); ?>
                        </div>
                        <div class="invalid-feedback invalid-ext">
                            <?= Loc::getMessage('RX_FORM_LANDING_INVALID_EXT'); ?>
                        </div>
                    <?elseif($field['IS_PHONE']):?>
                        <input name="<?=$field['CODE']?>" class="form-control empty phone" type="tel"
                               <?if($isRequired):?>required<?endif?> <?if($field['IS_DISABLED']):?>disabled <?endif?>
                               <?if($field['VALUE']):?>value="<?= $field['VALUE'] ?>"<?endif?>/>
                    <?elseif($field['TYPE'] == 'text'):?>
                        <input name="<?=$field['CODE']?>" class="form-control empty" type="text"
                               <? $value = $field['VALUE'] ?: ($field['IS_CITY'] ? ($currentRegionName ?? '') : ''); ?>
                               <?if($isRequired):?>required<?endif?> <?if($field['IS_DISABLED']):?>disabled <?endif?>
                               <?if($value):?>value="<?= $value ?>"<?endif?>/>
                    <?endif?>

                    <div class="invalid-feedback">
                        <?= Loc::getMessage('RX_FORM_LANDING_REQUIRED_FIELD'); ?>
                    </div>
                </div>
            <?endforeach?>

            <?if($arResult['USE_CAPTCHA']):?>
                <div class="form-group form-group-captcha">
                    <? $captchaClass = CaptchaManager::getCaptchaClassByCode($arResult['CAPTCHA_TYPE']) ?>
                    <? $captchaClass::showFormField() ?>
                </div>
            <?endif?>

            <?if($arResult['USE_AGREEMENT']):?>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="AGREEMENT"
                            id="agreement_<?=$arParams['BLOCK_ID']?>_<?=$arResult['RAND']?>" <?if($arResult['AGREEMENT_ACTIVE']):?>checked<?endif?> required />
                        <label class="custom-control-label" for="agreement_<?=$arParams['BLOCK_ID']?>_<?=$arResult['RAND']?>">
                            <?if(Config::getAgreementId()):?>
                                <?= Loc::getMessage('RX_FORM_LANDING_AGREEMENT_ID_FIELD_LABEL', ['#CODE#' => $arParams['FORM_CODE']]) ?>
                            <?else:?>
                                <?= Loc::getMessage('RX_FORM_LANDING_AGREEMENT_FIELD_LABEL', ['#LINK#' => $arResult['AGREEMENT_LINK']]) ?>
                            <?endif?>
                        </label>
                        <div class="invalid-feedback">
                            <?= Loc::getMessage('RX_FORM_LANDING_AGREEMENT_FIELD_INVALID'); ?>
                        </div>
                    </div>
                </div>
            <?endif?>

            <div class="form-btn-wrap">
                <div class="spinner-grow theme-color"></div>
                <button type="submit" class="d-none btn btn-lg btn-primary"><?=$arResult['BTN_TEXT']?></button>
            </div>
        </div>

        <div class="form-block form-block-success">
            <div>
                <div class="theme-stroke">
                    <?= Helper::svg('form/success'); ?>
                </div>
                <div class="form-block-title"><?= Loc::getMessage('RX_FORM_LANDING_BLOCK_SUCCESS_TITLE') ?></div>
                <div class="form-block-text">
                    <?if($arParams['IS_ONECLICK']):?>
                        <?= Loc::getMessage('RX_FORM_LANDING_BLOCK_SUCCESS_TEXT_ONECLICK') ?>
                    <?else:?>
                        <?= Loc::getMessage('RX_FORM_LANDING_BLOCK_SUCCESS_TEXT') ?>
                    <?endif?>
                </div>
                <?if(!$arParams['IS_ONECLICK']):?>
                    <div><button class="btn btn-transparent js-form-back"><?= Loc::getMessage('RX_FORM_LANDING_BLOCK_SUCCESS_BTN') ?></button></div>
                <?endif?>
            </div>
        </div>
        <div class="form-block form-block-error">
            <div>
                <div class="theme-stroke">
                    <?= Helper::svg('form/error'); ?>
                </div>
                <div class="form-block-title"><?= Loc::getMessage('RX_FORM_LANDING_BLOCK_ERROR_TITLE') ?></div>
                <div class="form-block-text"><?= Loc::getMessage('RX_FORM_LANDING_BLOCK_ERROR_TEXT') ?></div>
                <div><button class="btn btn-transparent js-form-back"><?= Loc::getMessage('RX_FORM_LANDING_BLOCK_ERROR_BTN') ?></button></div>
            </div>
        </div>
    </form>
<?endif?>
