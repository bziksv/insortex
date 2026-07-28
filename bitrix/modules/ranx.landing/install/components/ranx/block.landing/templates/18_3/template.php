<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 * @var array $arParams
 */
$this->setFrameMode(true);

use Ranx\Landing\Config;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">
        <div class="row">

            <?if($arResult['IMG']):?>
                <div class="block18-3-bg lazy" <?if($useLazyLoad):?>data-bg="<?= $arResult['IMG'] ?>"<?else:?>style="background-image: url(<?= $arResult['IMG'] ?>)"<?endif?>></div>
            <?endif?>

            <div class="col-lg-5 <?if($arResult['PICTURE_ALIGN'] == 'left'):?>offset-lg-7<?endif?>">

                <?= $arResult['BLOCK_TITLE'] ?>

                <?if($arResult['FORM']):?>
                    <div class="block18-3-form form-wrap">
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
                <?endif?>

            </div>

        </div>
    </div>

<?= $arResult['BLOCK_END'] ?>

<?if (\Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAjaxRequest()):?>
    <script>
        initMasks();
        initForms();
    </script>
<?endif?>
