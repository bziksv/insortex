<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 * @var array $arParams
 */
$this->setFrameMode(true);

use \Ranx\Landing\Helpers\FormHelper;
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

    </div>

    <?if($arResult['FORM']):?>
    <div class="maxwidth-theme">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                
                <div class="form-wrap form-btn-center" <?if(FormHelper::isB24Form($arResult['FORM'])):?>style="padding: 0;"<?endif?>>
                    <?$GLOBALS['APPLICATION']->IncludeComponent(
                        'ranx:form.landing',
                        '',
                        [
                            'FORM_CODE' => $arResult['FORM'],
                            'BTN_TEXT'  => $arResult['FORM_BTN_TEXT'],
                            'BLOCK_ID' => $arResult['ID'], // fixed a bug with agreement checkbox
                        ],
                        false,
                        [
                            'HIDE_ICONS' => 'Y',
                        ]
                    );?>
                </div>

            </div>
        </div>
    </div>
    <?endif?>

<?= $arResult['BLOCK_END'] ?>

<?if (\Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAjaxRequest()):?>
    <script>
        initMasks();
        initForms();
    </script>
<?endif?>
