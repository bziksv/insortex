<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme block-loading-content">
    <?= $arResult['BLOCK_TITLE'] ?>
    <?= $arResult['BLOCK_TABS'] ?>
    <?= $arResult['BLOCK_FILTER']?>

    <div class="calendar__groups">
        <?foreach ($arResult['GROUPS'] as $arGroup):?>
            <div class="calendar__group swipe-ignore <?=$arGroup['CLASS']?>" <?=$arGroup['ATTR']?>>
                <div class="calendar__days">
                    <?foreach ($arGroup['DAYS'] as $dayKey => $arDay):?>
                        <? $additionalClasses = empty($arDay['ITEM_KEYS']) ? 'disabled' : ''; ?>
                        <div class="calendar__day-wrapper">
                            <div class="calendar__day theme-bg <?=$additionalClasses?>" data-target="<?=$dayKey?>">
                                <?= Loc::getMessage('RX_BLOCK_8_3_TEMPLATE_DAY_'.$dayKey.'_NAME')?>
                            </div>
                        </div>
                    <?endforeach;?>
                </div>
                <div class="calendar__items">
                    <?foreach ($arGroup['DAYS'] as $dayKey => $arDay):?>
                        <div class="calendar__items-day" data-day="<?=$dayKey?>">
                            <?foreach ($arDay['ITEM_KEYS'] as $itemKey):?>
                                <? $arItem = $arGroup['ITEMS'][$itemKey]; ?>
                                <div class="calendar__item shadow-hover shadow-parent-hover">
                                    <div class="calendar__item-column column--service">
                                        <?if (!empty($arItem['NAME'])):?>
                                            <div class="calendar__item-service"><?=$arItem['NAME']?></div>
                                        <?endif;?>
                                        <?if (!empty($arItem['PROPS']['PERSON_NAME'])):?>
                                            <div class="calendar__item-person"><?=$arItem['PROPS']['PERSON_NAME']?></div>
                                        <?endif;?>
                                    </div>
                                    <div class="calendar__item-column column--time">
                                        <?if (!empty($arItem['INTERVAL_TIME'])):?>
                                            <div class="calendar__item-time">
                                                <div class="time__icon theme-color"><?= Helper::svg('block/clock'); ?></div>
                                                <div class="time__text"><?=$arItem['INTERVAL_TIME']?></div>
                                            </div>
                                        <?endif;?>
                                        <?if (!empty($arItem['PROPS']['CAT'])):?>
                                            <div class="calendar__item-car"><?=$arItem['PROPS']['CAT']?></div>
                                        <?endif;?>
                                        <?if (!empty($arItem['PROPS']['YEARS'])):?>
                                            <div class="calendar__item-years"><?=$arItem['PROPS']['YEARS']?></div>
                                        <?endif;?>
                                        <?if (!empty($arItem['PREVIEW_TEXT'])):?>
                                            <div class="calendar__item-comment"><?=$arItem['~PREVIEW_TEXT']?></div>
                                        <?endif;?>
                                    </div>
                                    <div class="calendar__item-column column--button">
                                        <?if (!empty($arItem['LINK'])):?>
                                            <a class="calendar__item-btn btn btn-primary shadow-hover <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
                                                <?= $arItem['PROPS']['LINK_TEXT'] ?: Loc::getMessage('RX_BLOCK_8_3_TEMPLATE_DEFAULT_BTN_TEXT') ?>
                                            </a>
                                        <?endif;?>
                                    </div>
                                </div>
                            <?endforeach;?>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
        <?endforeach?>
    </div>

    <?= $arResult['BTN'] ?>
</div>

<script>
    $(document).ready(function() {
        initDaySelection();

        $('#block_<?= $arResult['ID'] ?>').on('rxFilterItems', function (e, data) {
            let $this = $(this);
            let scrollPosition = $(document).scrollTop();

            $(this).find('.calendar__groups').html($(data.html).find('.calendar__groups').html());

            initTabs();
            initDaySelection();
            $(document).scrollTop(scrollPosition);
            endBlockLoad($this.data('id'));
        });
        $('#block_<?= $arResult['ID'] ?>').on('click', '.calendar__day:not(.disabled)', function () {
            const $this = $(this);
            if ($this.hasClass('active')) {
                return;
            }

            const $group = $this.closest('.calendar__group');
            const target = $this.data('target');

            $group.find('.calendar__day.active').removeClass('active');
            $this.addClass('active');

            $group.find('.calendar__items-day.day--select').removeClass('day--select');
            $group.find('.calendar__items-day[data-day="'+target+'"]').addClass('day--select');
        });

        function initDaySelection()
        {
            $('#block_<?=$arResult['ID']?> .calendar__group').each(function () {
                const $group = $(this);
                const $days = $group.find('.calendar__day:not(.disabled)');
                if (!$days.length) {
                    return;
                }

                const $activeDays = $days.filter('.active');
                const $dayItems = $group.find('.calendar__items-day');

                let activeDay = ($activeDays.length ? $activeDays : $days).first().data('target');
                if (!activeDay) {
                    return;
                }

                $days.removeClass('active');
                $days.filter('[data-target="'+activeDay+'"]').addClass('active');

                $dayItems.removeClass('day--select');
                $dayItems.filter('[data-day="'+activeDay+'"]').addClass('day--select');
            });
        }
    });
</script>

<?= $arResult['BLOCK_END'] ?>
