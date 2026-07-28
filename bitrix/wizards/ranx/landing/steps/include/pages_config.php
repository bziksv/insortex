<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

/**
 * @var string $siteType
 * @var array $pages
 * @var bool $existInaccessiblePage
 */

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<?if($siteType == 'multi'):?>
    <?if(!empty($pages)):?>

        <?= Loc::getMessage('RX_WIZ_STEP_PAGES_CONFIG_MULTI_DESC') ?>

        <div class="rx-pages">
            <?foreach($pages as $arPage):?>
                <?if (!$arPage['AVAILABLE']) {
                    $existInaccessiblePage = true;
                    continue;
                }?>

                <div class="rx-page-wrap">
                    <div class="rx-page">

                        <?= $this->ShowHiddenField('RX_PAGES['.$arPage['CODE'].']', 'N') ?>
                        <div class="rx-page-block">
                            <div class="rx-page-check"><?= rxWizardSvg('check') ?></div>

                            <?if(!empty($arPage['ICON'])):?>
                                <img class="rx-page-icon" src="<?= $arPage['ICON'] ?>" alt="<?= $arPage['TITLE'] ?>">
                            <?endif?>

                        </div>
                        <div class="rx-page-title"><?= $arPage['TITLE'] ?></div>
                    </div>
                </div>

            <?endforeach?>
        </div>

        <?if ($existInaccessiblePage): ?>
            <p class="rx-warning">
                <?= Loc::getMessage('RX_WIZ_STEP_PAGES_CONFIG_MULTI_PAGE_WARNING') ?>
            </p>
        <?endif?>

    <?else:?>
        <?= Loc::getMessage('RX_WIZ_STEP_PAGES_CONFIG_MULTI_NO_PAGES') ?>
    <?endif?>

<?endif?>

<script>
    $(document).ready(function(){
        $('.rx-page-block').on('click', function(e){
            const $block = $(this).parent();

            $block.toggleClass('active');
            $(this).siblings('input').val($block.hasClass('active') ? 'Y' : 'N');
        });
    });
</script>
