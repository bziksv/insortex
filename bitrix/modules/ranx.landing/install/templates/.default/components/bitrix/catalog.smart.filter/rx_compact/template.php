<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);

use Ranx\Landing\Helpers;
use Bitrix\Main\Localization\Loc;

$isExistSetProp = false;
$templateData['INIT_JS'] = ''; // ajax crutch
?>
<? if($arResult['ITEMS']): ?>
	<div class="bx_filter rx_compact swipeignore">
        <form name="<?= $arResult['FILTER_NAME'].'_form'?>" action="<?= $arResult['FORM_ACTION']?>" method="get" class="smartfilter">
            <div class="bx_filter_parameters">
                <input type="hidden" name="del_url" id="del_url" value="<?= $arResult['SEF_DEL_FILTER_URL']?>" />
                <?foreach($arResult['HIDDEN'] as $arItem):?>
                    <input type="hidden" name="<?= $arItem['CONTROL_NAME']?>" id="<?= $arItem['CONTROL_ID']?>" value="<?= $arItem['HTML_VALUE']?>" />
                <?endforeach;?>

                <div class="bx_filter_icon"><?= Helpers\Helper::svg('block/filter'); ?></div>

                <? foreach($arResult['ITEMS'] as $key => $arItem):

                    if(empty($arItem['VALUES']) || !empty($arItem['PRICE']))
                        continue;

                    if ($arItem['PROPERTY_TYPE'] == 'N') {
                        $min = $arItem['VALUES']['MIN']['VALUE'];
                        $max = $arItem['VALUES']['MAX']['VALUE'];
                        if (!isset($min) || !isset($max))
                            continue;
                        if ($arItem['DISPLAY_TYPE'] == 'A' && ($max - $min <= 0))
                            continue;
                    }

                    $isSetProp = isset($arItem['PROPERTY_SET']) && $arItem['PROPERTY_SET'] == 'Y';
                    $isExistSetProp = $isExistSetProp || $isSetProp;
                    $isToggleType = $arItem['DISPLAY_TYPE'] == 'T';
                    ?>

                    <div class="bx_filter_parameters_box prop_type_<?=$arItem['PROPERTY_TYPE']?> <?=($isSetProp ? 'set' : '');?>"
                         data-prop_code="<?=strtolower($arItem['CODE']);?>" data-property_id="<?=$arItem['ID']?>">

                        <div class="bx_filter_parameters_box_title theme-bg title box-shadow-sm" >
                            <div class="text"><?= $arItem["NAME"] ?></div>
                            <span class="delete_filter theme-bg-hover"
                                  title="<?=Loc::getMessage('RX_SMART_FILTER_COMPACT_DEL')?>">
                                <?= Helpers\Helper::svg('block/filter_clear') ?>
                            </span>
                            <? if (!$isToggleType): ?>
                                <span class="arrow_down"><?= Helpers\Helper::svg('block/arrow_down'); ?></span>
                            <? endif ?>
                        </div>

                        <div class="bx_filter_block <?= ($isToggleType ? 'limited_block' : '') ?>">
                            <div class="bx_filter_parameters_box_container <?=(!in_array($arItem['DISPLAY_TYPE'], ['A', 'B', 'U', 'T']) ? 'js-simplebar' : '');?>">
                            <? switch ($arItem['DISPLAY_TYPE']) {
                                case 'A': //NUMBERS_WITH_SLIDER ?>
                                    <? $arMinValue = $arItem['VALUES']['MIN']; ?>
                                    <? $arMaxValue = $arItem['VALUES']['MAX']; ?>
                                    <div class="wrapp_all_inputs wrap_md">
                                        <div class="wrapp_change_inputs iblock">
                                            <div class="bx_filter_parameters_box_container_block">
                                                <div class="bx_filter_input_container">
                                                    <input
                                                        class="min-price"
                                                        type="text"
                                                        name="<?= $arMinValue['CONTROL_NAME']?>"
                                                        id="<?= $arMinValue['CONTROL_ID']?>"
                                                        value="<?= $arMinValue['HTML_VALUE']?>"
                                                        size="5"
                                                        placeholder="<?=$arMinValue['VALUE']?>"
                                                        onkeyup="smartFilter.keyup(this)"
                                                    />
                                                </div>
                                            </div>
                                            <div class="bx_filter_parameters_box_container_block">
                                                <div class="bx_filter_input_container">
                                                    <input
                                                        class="max-price"
                                                        type="text"
                                                        name="<?= $arMaxValue['CONTROL_NAME']?>"
                                                        id="<?= $arMaxValue['CONTROL_ID']?>"
                                                        value="<?= $arMaxValue['HTML_VALUE']?>"
                                                        size="5"
                                                        placeholder="<?= $arMaxValue['VALUE']?>"
                                                        onkeyup="smartFilter.keyup(this)"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wrapp_slider iblock">
                                            <div class="bx_ui_slider_track" id="drag_track_<?=$key?>">
                                                <div class="bx_ui_slider_pricebar_VD theme-after-bg" style="left: 0;right: 0;" id="colorUnavailableActive_<?=$key?>"></div>
                                                <div class="bx_ui_slider_pricebar_VN theme-after-bg" style="left: 0;right: 0;" id="colorAvailableInactive_<?=$key?>"></div>
                                                <div class="bx_ui_slider_pricebar_V theme-after-bg"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>
                                                <div class="bx_ui_slider_range" id="drag_tracker_<?=$key?>"  style="left: 0;right: 0;">
                                                    <a class="bx_ui_slider_handle theme-after-bg left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
                                                    <a class="bx_ui_slider_handle theme-after-bg right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>
                                                </div>
                                            </div>
                                            <div class="bx_ui_slider_label">
                                                <div class="min"><?= $arMinValue['VALUE'] ?></div>
                                                <div class="max"><?= $arMaxValue['VALUE'] ?></div>
                                            </div>
                                            <? $arJsParams = [
                                                'leftSlider' => 'left_slider_'.$key,
                                                'rightSlider' => 'right_slider_'.$key,
                                                'tracker' => 'drag_tracker_'.$key,
                                                'trackerWrap' => 'drag_track_'.$key,
                                                'minInputId' => $arMinValue['CONTROL_ID'],
                                                'maxInputId' => $arMaxValue['CONTROL_ID'],
                                                'minPrice' => $arMinValue['VALUE'],
                                                'maxPrice' => $arMaxValue['VALUE'],
                                                'curMinPrice' => $arMinValue['HTML_VALUE'],
                                                'curMaxPrice' => $arMaxValue['HTML_VALUE'],
                                                'fltMinPrice' => intval($arMinValue['FILTERED_VALUE']) ? $arMinValue['FILTERED_VALUE'] : $arMinValue['VALUE'],
                                                'fltMaxPrice' => intval($arMaxValue['FILTERED_VALUE']) ? $arMaxValue['FILTERED_VALUE'] : $arMaxValue['VALUE'],
                                                'precision' => $arItem['DECIMALS']? $arItem['DECIMALS']: 0,
                                                'colorUnavailableActive' => 'colorUnavailableActive_'.$key,
                                                'colorAvailableActive' => 'colorAvailableActive_'.$key,
                                                'colorAvailableInactive' => 'colorAvailableInactive_'.$key,
                                            ]; ?>
                                            <? ob_start() ?>
                                            <script>
                                                BX.ready(function(){
                                                    if(typeof window['trackBarOptions'] === 'undefined'){
                                                        window['trackBarOptions'] = {}
                                                    }
                                                    window['trackBarOptions']['<?=$key?>'] = <?=CUtil::PhpToJSObject($arJsParams)?>;
                                                    window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(window['trackBarOptions']['<?=$key?>']);
                                                });
                                            </script>
                                            <?$templateData['INIT_JS'] .= ob_get_clean();?>
                                        </div>
                                    </div>
                                    <? break;
                                case 'B': //NUMBERS ?>
                                    <? $arMinValue = $arItem['VALUES']['MIN']; ?>
                                    <? $arMaxValue = $arItem['VALUES']['MAX']; ?>
                                    <div class="wrapp_change_inputs">
                                        <div class="bx_filter_parameters_box_container_block">
                                        <div class="bx_filter_input_container">
                                            <input
                                                class="min-price"
                                                type="text"
                                                name="<?= $arMinValue['CONTROL_NAME']?>"
                                                id="<?= $arMinValue['CONTROL_ID']?>"
                                                value="<?= $arMinValue['HTML_VALUE']?>"
                                                size="5"
                                                placeholder="<?=$arMinValue['VALUE']?>"
                                                onkeyup="smartFilter.keyup(this)"
                                            />
                                        </div>
                                    </div>
                                        <div class="bx_filter_parameters_box_container_block">
                                        <div class="bx_filter_input_container">
                                            <input
                                                class="max-price"
                                                type="text"
                                                name="<?= $arMaxValue['CONTROL_NAME']?>"
                                                id="<?= $arMaxValue['CONTROL_ID']?>"
                                                value="<?= $arMaxValue['HTML_VALUE']?>"
                                                size="5"
                                                placeholder="<?= $arMaxValue['VALUE']?>"
                                                onkeyup="smartFilter.keyup(this)"
                                            />
                                        </div>
                                    </div>
                                    </div>
                                    <? break;
                                case 'G': //CHECKBOXES_WITH_PICTURES ?>
                                    <div class="wrapp_all_pictures">
                                    <?foreach ($arItem['VALUES'] as $value => $arValue):?>
                                        <?
                                        if(empty($arValue['VALUE']) || empty($arValue['FILE']))
                                            continue;

                                        $class = '';
                                        $class .= ($arValue['CHECKED'] ? 'active ' : '');
                                        $class .= ($arValue['DISABLED'] ? 'disabled ' : '');
                                        ?>
                                        <div class="pict">
                                            <input
                                                style="display: none"
                                                type="checkbox"
                                                name="<?=$arValue['CONTROL_NAME']?>"
                                                id="<?=$arValue['CONTROL_ID']?>"
                                                value="<?=$arValue['HTML_VALUE']?>"
                                                <?= $arValue['DISABLED'] ? 'disabled class="disabled"': '' ?>
                                                <?= $arValue['CHECKED']? 'checked="checked"': '' ?>
                                            />
                                            <label for="<?= $arValue['CONTROL_ID'] ?>"
                                                   data-role="label_<?= $arValue['CONTROL_ID'] ?>"
                                                   class="bx_filter_param_label bx_filter_pict_label <?=$class?>"
                                                   onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($arValue['CONTROL_ID'])?>')); smartFilter.toggleCheckbox(this, 'active');">
                                                <span class="bx_filter_btn_color_icon"
                                                      title="<?=$arValue['VALUE']?>"
                                                      style="background-image:url('<?=$arValue['FILE']['SRC']?>');">
                                                </span>
                                                <span class="bx_filter_pict_border theme-border"></span>
                                            </label>
                                        </div>
                                    <?endforeach?>
                                    </div>
                                    <? break;
                                case 'H': //CHECKBOXES_WITH_PICTURES_AND_LABELS ?>
                                    <div class="wrapp_all_pic_with_labels">
                                    <?foreach ($arItem['VALUES'] as $value => $arValue):?>
                                        <?
                                        if(empty($arValue['VALUE']) || empty($arValue['FILE']))
                                            continue;

                                        $class = '';
                                        $class .= ($arValue['CHECKED'] ? 'active ' : '');
                                        $class .= ($arValue['DISABLED'] ? 'disabled ' : '');
                                        ?>
                                        <input
                                            style="display: none"
                                            type="checkbox"
                                            name="<?=$arValue['CONTROL_NAME']?>"
                                            id="<?=$arValue['CONTROL_ID']?>"
                                            value="<?=$arValue['HTML_VALUE']?>"
                                            <?= $arValue['DISABLED'] ? 'disabled class="disabled"': '' ?>
                                            <?= $arValue['CHECKED']? 'checked="checked"': '' ?>
                                        />
                                        <label for="<?=$arValue['CONTROL_ID']?>"
                                               data-role="label_<?=$arValue['CONTROL_ID']?>"
                                               class="bx_filter_param_label bx_filter_pict_label with_text <?=$class?>"
                                               onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($arValue['CONTROL_ID'])?>')); smartFilter.toggleCheckbox(this, 'active');">
                                            <span class="bx_filter_btn_color_icon"
                                                  style="background-image:url('<?=$arValue['FILE']['SRC']?>');">
                                            </span>
                                            <span class="bx_filter_pict_border theme-border"></span>
                                            <span class="bx_filter_param_text" title="<?=$arValue['VALUE'];?>">
                                                <?=$arValue['VALUE'];?>
                                            </span>
                                        </label>
                                    <?endforeach?>
                                    </div>
                                    <? break;
                                case 'P': //DROPDOWN ?>
                                    <div class="bx_filter_select_container">
                                        <div class="bx_filter_select_block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
                                            <div class="bx_filter_select_wrap">
                                                <div class="bx_filter_select_text" data-role="currentOption">
                                                    <? $checkedItemExist = false;
                                                    foreach ($arItem['VALUES'] as $value => $arValue) {
                                                        if ($arValue['CHECKED']) {
                                                            echo $arValue['VALUE'];
                                                            $checkedItemExist = true;
                                                        }
                                                    }
                                                    if (!$checkedItemExist) {
                                                        echo Loc::getMessage('RX_SMART_FILTER_COMPACT_ALL');
                                                    }
                                                    ?>
                                                </div>
                                                <div class="bx_filter_select_arrow"><?= Helpers\Helper::svg('block/arrow_down'); ?></div>
                                            </div>
                                            <?foreach ($arItem['VALUES'] as $value => $arValue):?>
                                                <input
                                                    style="display: none"
                                                    type="radio"
                                                    name="<?=$arValue['CONTROL_NAME_ALT']?>"
                                                    id="<?=$arValue['CONTROL_ID']?>"
                                                    value="<?=$arValue['HTML_VALUE_ALT'] ?>"
                                                    <?= $arValue['DISABLED'] ? 'disabled class="disabled"': '' ?>
                                                    <?= $arValue['CHECKED']? 'checked="checked"': '' ?>
                                                />
                                            <?endforeach?>
                                            <div class="bx_filter_select_popup" data-role="dropdownContent" style="display: none;">
                                                <ul>
                                                    <? foreach ($arItem['VALUES'] as $value => $arValue):
                                                        $class = '';
                                                        $class .= ($arValue['CHECKED'] ? 'selected ' : '');
                                                        $class .= ($arValue['DISABLED'] ? 'disabled ' : '');
                                                    ?>
                                                    <li>
                                                        <label for="<?=$arValue['CONTROL_ID']?>"
                                                               class="bx_filter_param_label bx_filter_select_label theme-bg <?=$class?>"
                                                               data-role="label_<?=$arValue['CONTROL_ID']?>"
                                                               onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($arValue['CONTROL_ID'])?>')">
                                                            <?=$arValue['VALUE']?>
                                                        </label>
                                                    </li>
                                                    <?endforeach?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                    break;
                                case 'R': //DROPDOWN_WITH_PICTURES_AND_LABELS ?>
                                    <div class="bx_filter_select_container">
                                        <div class="bx_filter_select_block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
                                            <div class="bx_filter_select_wrap">
                                                <div class="bx_filter_select_text bx_filter_select_pict" data-role="currentOption">
                                                    <? $checkedItemExist = false;
                                                    foreach ($arItem['VALUES'] as $value => $arValue) {
                                                        if ($arValue['CHECKED'] && !empty($arValue['FILE'])) { ?>
                                                            <span class="bx_filter_btn_color_icon"
                                                                  style="background-image:url('<?=$arValue['FILE']['SRC']?>');">
                                                            </span>
                                                            <span class="bx_filter_param_text radio" title="<?=$arValue['VALUE'];?>">
                                                                <?=$arValue['VALUE'];?>
                                                            </span>
                                                        <? $checkedItemExist = true;
                                                        }
                                                    }
                                                    if (!$checkedItemExist){
                                                        echo Loc::getMessage('RX_SMART_FILTER_COMPACT_ALL');
                                                    }
                                                    ?>
                                                </div>
                                                <div class="bx_filter_select_arrow"><?= Helpers\Helper::svg('block/arrow_down'); ?></div>
                                            </div>
                                            <?foreach ($arItem['VALUES'] as $value => $arValue):?>
                                                <input
                                                    style="display: none"
                                                    type="radio"
                                                    name="<?=$arValue['CONTROL_NAME_ALT']?>"
                                                    id="<?=$arValue['CONTROL_ID']?>"
                                                    value="<?=$arValue['HTML_VALUE_ALT']?>"
                                                    <?= $arValue['DISABLED'] ? 'disabled class="disabled"': '' ?>
                                                    <?= $arValue['CHECKED']? 'checked="checked"': '' ?>
                                                />
                                            <?endforeach?>
                                            <div class="bx_filter_select_popup" data-role="dropdownContent" style="display: none">
                                                <ul>
                                                    <?
                                                    foreach ($arItem['VALUES'] as $value => $arValue):
                                                        if (empty($arValue['FILE'])) {
                                                            continue;
                                                        }

                                                        $class = '';
                                                        $class .= ($arValue['CHECKED'] ? 'selected ' : '');
                                                        $class .= ($arValue['DISABLED'] ? 'disabled ' : '');
                                                    ?>
                                                    <li>
                                                        <label for="<?=$arValue['CONTROL_ID']?>"
                                                               data-role="label_<?=$arValue["CONTROL_ID"]?>"
                                                               class="bx_filter_param_label bx_filter_select_label bx_filter_select_pict <?=$class?>"
                                                               onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($arValue['CONTROL_ID'])?>')">
                                                            <span class="bx_filter_btn_color_icon"
                                                                  title="<?=$arValue['VALUE']?>"
                                                                  style="background-image:url('<?=$arValue['FILE']['SRC']?>');">
                                                            </span>
                                                            <span class="bx_filter_pict_border theme-border"></span>
                                                            <span class="bx_filter_param_text" title="<?=$arValue['VALUE'];?>">
                                                                <?=$arValue["VALUE"]?>
                                                            </span>
                                                        </label>
                                                    </li>
                                                    <?endforeach?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <? break;
                                case 'K': //RADIO_BUTTONS ?>
                                    <?foreach($arItem['VALUES'] as $value => $arValue):?>
                                        <div class="bx_filter_radio_wrap">
                                            <input
                                                type="radio"
                                                value="<?= $arValue['HTML_VALUE_ALT'] ?>"
                                                name="<?= $arValue['CONTROL_NAME_ALT'] ?>"
                                                id="<?= $arValue['CONTROL_ID'] ?>"
                                                <?= $arValue['DISABLED'] ? 'disabled class="disabled"': '' ?>
                                                <?= $arValue['CHECKED'] ? 'checked="checked"': '' ?>
                                                onclick="smartFilter.click(this)"
                                            />
                                            <label data-role="label_<?=$arValue['CONTROL_ID']?>"
                                                   class="bx_filter_param_label bx_filter_radio_label theme-before-bg <?= $arValue['DISABLED'] ? 'disabled': '' ?>"
                                                   for="<?= $arValue['CONTROL_ID'] ?>">
                                                <span class="bx_filter_param_text"
                                                      title="<?=$arValue['VALUE'];?>">
                                                    <?=$arValue['VALUE'];?>
                                                </span>
                                            </label>
                                        </div>
                                    <?endforeach;?>
                                    <? break;
                                case 'U': //CALENDAR ?>
                                    <div class="wrapp_change_inputs">
                                        <div class="bx_filter_parameters_box_container_block calendar">
                                            <div class="bx_filter_input_container bx_filter_calendar_container">
                                                <?$APPLICATION->IncludeComponent(
                                                    'bitrix:main.calendar',
                                                    '',
                                                    array(
                                                        'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
                                                        'SHOW_INPUT' => 'Y',
                                                        'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                                        'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
                                                        'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                                                        'SHOW_TIME' => 'N',
                                                        'HIDE_TIMEBAR' => 'Y',
                                                    ),
                                                    null,
                                                    array('HIDE_ICONS' => 'Y')
                                                );?>
                                            </div>
                                        </div>
                                        <div class="bx_filter_parameters_box_container_block calendar">
                                            <div class="bx_filter_input_container bx_filter_calendar_container">
                                                <?$APPLICATION->IncludeComponent(
                                                    'bitrix:main.calendar',
                                                    '',
                                                    array(
                                                        'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
                                                        'SHOW_INPUT' => 'Y',
                                                        'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                                        'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
                                                        'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                                                        'SHOW_TIME' => 'N',
                                                        'HIDE_TIMEBAR' => 'Y',
                                                    ),
                                                    null,
                                                    array('HIDE_ICONS' => 'Y')
                                                );?>
                                            </div>
                                        </div>
                                    </div>
                                    <? break;
                                default: //CHECKBOXES (F) ?>
                                    <?foreach($arItem['VALUES'] as $value => $arValue):?>
                                        <div class="bx_filter_checkbox_wrap custom-checkbox">
                                        <input
                                            class="custom-control-input"
                                            type="checkbox"
                                            value="<?= $arValue['HTML_VALUE'] ?>"
                                            name="<?= $arValue['CONTROL_NAME'] ?>"
                                            id="<?= $arValue['CONTROL_ID'] ?>"
                                            <?= $arValue['DISABLED'] ? 'disabled class="disabled"': '' ?>
                                            <?= $arValue['CHECKED']? 'checked="checked"': '' ?>
                                            onclick="smartFilter.click(this)"
                                        />
                                        <label data-role="label_<?=$arValue['CONTROL_ID']?>"
                                               class="bx_filter_param_label bx_filter_checkbox_label custom-control-label <?= $arValue['DISABLED'] ? 'disabled': '' ?>"
                                               for="<?= $arValue['CONTROL_ID'] ?>">
                                            <span class="bx_filter_param_text" title="<?=$arValue['VALUE'];?>">
                                                <?=$arValue['VALUE'];?>
                                            </span>
                                        </label>
                                        </div>
                                    <?endforeach;?>
                            <?}?>
                            </div>

                            <div class="bx_filter_button_box">
                                <span class="bx_filter_container_modef" data-role="count_<?=$key?>"></span>
                            </div>

                        </div>
                    </div>
                <?endforeach?>

                <button class="bx_filter_search_reset theme-color <?=(!$isExistSetProp ? 'hidden' : '');?>"
                        type="reset" id="del_filter" name="del_filter" data-href="">
                    <span><?=Loc::getMessage('RX_SMART_FILTER_COMPACT_DEL')?></span>
                </button>
            </div>
        </form>
	</div>

    <? ob_start() ?>
	<script>
		let smartFilter = new JCSmartFilter(
		    '<?= CUtil::JSEscape($arResult['FORM_ACTION']) ?>',
            '<?= $arParams['BLOCK_ID'] ?>',
            <?= CUtil::PhpToJSObject($arResult['JS_FILTER_PARAMS']) ?>
        );

		BX.message({
            RX_SMART_FILTER_COMPACT_SELECTED: '<?= Loc::getMessage('RX_SMART_FILTER_COMPACT_SELECTED') ?>',
            RX_SMART_FILTER_COMPACT_ALL: '<?= Loc::getMessage('RX_SMART_FILTER_COMPACT_ALL') ?>',
		});
	</script>
    <?$templateData['INIT_JS'] .= ob_get_clean();?>
<? endif ?>
