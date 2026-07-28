<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="row">
            <div class="col-md-12">

                <? if (!empty($arResult['ITEMS'])) : ?>
                    <div class="block5-1-container">
                        <? foreach ($arResult['ITEMS'] as $i => $arItem) : ?>
                            <div class="block5-1-card theme-bg-hover-parent theme-border-hover-parent collapsed" data-toggle="collapse"
                                 data-target=".collapse-sort-<?= $arResult['SORT'] ?>-<?= $i ?>" aria-expanded="false"
                                 aria-controls="collapse-sort-<?= $arResult['SORT'] ?>-<?= $i ?>">
                                <div class="block5-indicator theme-bg"></div>
                                <a class="theme-bg-hover theme-border-hover btn-arrow collapsed" data-toggle="collapse"
                                   data-target=".collapse-sort-<?= $arResult['SORT'] ?>-<?= $i ?>" aria-expanded="false"
                                   aria-controls="collapse-sort-<?= $arResult['SORT'] ?>-<?= $i ?>"></a>
                                <div class="block5-1-card-header">
                                    <div class="block5-1-question block-el-title"><?= $arItem['~PREVIEW_TEXT'] ?></div>
                                </div>
                                <div class="collapse collapse-sort-<?= $arResult['SORT'] ?>-<?= $i ?>">
                                    <div class="block5-1-card-body">
                                        <? if (!empty($arItem['DETAIL_TEXT'])) : ?>
                                            <?= $arItem['~DETAIL_TEXT'] ?>
                                        <? endif ?>
                                    </div>
                                </div>
                            </div>
                        <? endforeach ?>
                    </div>
                <? endif ?>

            </div>
        </div>

        <?= $arResult['BTN'] ?>

    </div>

<?= $arResult['BLOCK_END'] ?>
