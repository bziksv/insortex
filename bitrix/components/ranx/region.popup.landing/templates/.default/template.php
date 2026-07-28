<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
?>
<div id="regionsModal" class="modal modal-<?= $arResult['MODAL_POSITION'] ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Loc::getMessage('RX_REGION_POPUP_LANDING_TITLE') ?></h5>
                <button type="button" class="close theme-stroke-hover" data-dismiss="modal">
                    <?= Helper::svg('form/close'); ?>
                </button>
            </div>
            <div class="modal-body">
                <form class="form">
                    <input class="modal-regions-search js-region-search<?if($arResult['ONLY_SEARCH']):?>-ajax<?endif?> form-control"
                        type="text" placeholder="<?= Loc::getMessage('RX_REGION_POPUP_LANDING_PLACEHOLDER') ?>">

                    <?if(!empty($arResult['FAVORITE'])):?>
                        <div class="modal-regions-hints">
                            <?= Loc::getMessage('RX_REGION_POPUP_LANDING_HINT') ?>

                            <?foreach($arResult['FAVORITE'] as $arRegion):?>
                                <a href="#" class="theme-border js-change-city <?if($arRegion['ID'] == $arResult['CURRENT']['ID']):?>active<?endif?>"
                                    data-id="<?= $arRegion['ID'] ?>" <?if(!empty($arRegion['URL'])):?>data-url="<?=$arRegion['URL']?>"<?endif?>>
                                    <?= $arRegion['NAME'] ?>
                                </a>
                            <?endforeach?>

                        </div>
                    <?endif?>

                    <?if(!empty($arResult['REGIONS'])):?>
                        <div class="modal-regions-block js-simplebar">
                            <div class="row">
                                <?if(!$arResult['ONLY_SEARCH']):?>
                                    <?foreach($arResult['REGIONS'] as $arRegion):?>
                                        <?if(!empty($arRegion['BRANCHES'])):?>
                                            <?foreach($arRegion['BRANCHES'] as $arBranch):?>
                                                <? include 'region.php'; ?>
                                            <?endforeach?>
                                            <?unset($arBranch);?>
                                        <?else:?>
                                            <? include 'region.php'; ?>
                                        <?endif?>
                                    <?endforeach?>
                                <?endif?>
                            </div>
                        </div>
                    <?endif?>
                </form>

            </div>
        </div>
    </div>
</div>
