<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
?>

<?=$arResult['BLOCK_START']?>

<div class="maxwidth-theme">
    <?=$arResult['BLOCK_TITLE']?>

    <?if (!empty($arResult['ITEMS'])):?>
        <div class="row block8-1-container">

            <?foreach ($arResult['ITEMS'] as $arItem):?>
                <div class="col-12">
                    <div class="card theme-bg-hover-parent theme-border-hover-parent collapsed" role="button"
                         data-toggle="collapse" data-target=".collapse-<?=$arItem['ID']?>"
                         aria-expanded="false" aria-controls="collapse-<?=$arItem['ID']?>">

                        <div class="card-header">
                            <div class="card-header-info">
                                <div class="event">
                                    <div class="event-title theme-before-bg">
                                        <span><?=$arItem['NAME']?></span>
                                    </div>
                                    <div class="event-info">
                                        <?foreach ($arItem['EVENT_INFO'] as $elementInfo): ?>
                                            <div class="element left-splitter">
                                                <?=$elementInfo?>
                                            </div>
                                        <?endforeach?>
                                    </div>
                                </div>
                                <div class="sale">
                                    <div class="prices">
                                        <?if (!empty($arItem['PRICE'])):?>
                                            <div class="cur-price">
                                                <?= Helper::money($arItem['PRICE']) ?>
                                            </div>
                                        <?endif?>

                                        <?if (!empty($arItem['OLD_PRICE'])):?>
                                            <div class="old-price">
                                                <?= Helper::money($arItem['OLD_PRICE']) ?>
                                            </div>
                                        <?endif?>
                                    </div>

                                    <?if (!empty($arItem['BTN'])):?>
                                        <div class="button">
                                            <?=$arItem['BTN']?>
                                        </div>
                                    <?endif?>
                                </div>
                            </div>
                            <?if (!empty($arItem['PREVIEW_TEXT'])): ?>
                                <div class="card-header-arrow">
                                    <a class="card-btn-arrow theme-bg-hover theme-border-hover collapsed" role="button"
                                       data-toggle="collapse" data-target=".collapse-<?=$arItem['ID']?>"
                                       aria-expanded="false" aria-controls="collapse-<?=$arItem['ID']?>"></a>
                                </div>
                            <?endif?>
                        </div>

                        <?if (!empty($arItem['PREVIEW_TEXT'])):?>
                            <div class="collapse collapse-<?=$arItem['ID']?>">
                                <div class="card-body">
                                    <?=$arItem['PREVIEW_TEXT']?>
                                </div>
                            </div>
                        <?endif?>
                    </div>
                </div>
            <?endforeach?>
        </div>
    <?endif?>

    <?=$arResult['BTN']?>
</div>

<?=$arResult['BLOCK_END']?>
