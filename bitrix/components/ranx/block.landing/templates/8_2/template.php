<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">
    <?= $arResult['BLOCK_TITLE'] ?>
    <?= $arResult['BLOCK_TABS'] ?>

    <?foreach ($arResult['GROUPS'] as $arGroup):?>
    <div class="schedule-group <?=$arGroup['CLASS']?>" <?=$arGroup['ATTR']?>>
        <?foreach ($arGroup['ITEMS'] as $arItem):?>
        <div class="schedule-card">

            <div class="schedule-wrap">
                <?if (!empty($arItem['PROPS']['SCHEDULE'])):?>
                <div class="schedule-time">
                    <div class="schedule-indicator theme-bg"></div>
                    <?=$arItem['PROPS']['SCHEDULE']?>
                </div>
                <?endif?>

                <?if (!empty($arItem['IMG'])):?>
                <div class="schedule-avatar">
                    <img class="lazy" alt="<?=$arItem['NAME'];?>" title="<?=$arItem['NAME'];?>"
                         <?if($useLazyLoad):?> data-src="<?=$arItem['IMG']?>"
                         <?else:?>src="<?=$arItem['IMG']?>"
                         <?endif?>>
                </div>
                <?endif?>
            </div>

            <div class="schedule-desc">
                <div class="schedule-name">
                    <?=$arItem['NAME']?>
                </div>
                <div class="schedule-info">
                    <?foreach ($arItem['EVENT_INFO'] as $elementInfo): ?>
                        <div class="schedule-element-info left-splitter">
                            <?=$elementInfo?>
                        </div>
                    <?endforeach?>
                </div>
                <?if (!empty($arItem['PREVIEW_TEXT'])):?>
                    <div class="schedule-text">
                        <?=$arItem['PREVIEW_TEXT']?>
                    </div>
                <?endif?>
            </div>
        </div>
        <?endforeach?>
    </div>
    <?endforeach?>

    <?= $arResult['BTN'] ?>
</div>

<?= $arResult['BLOCK_END'] ?>
