<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

use Ranx\Landing\Block;
use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<?if(in_array('COLS', $arResult['SECTIONS']) && !empty($arResult['INFO']['COLS'])):
    $selectedVal = Block::checkCols($arResult['CODE'], $arResult['PROPS']['COLS']);
?>
<div class="panel-row">
    <div class="form-group">
        <label><?= $arResult['DESIGN_FIELDS_MESS']['COLS'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_COLS') ?></label>
        <select name="cols" class="form-control">
            <?foreach($arResult['INFO']['COLS'] as $col):?>
            <option value="<?=$col?>" <?if($col == $selectedVal):?>selected<?endif?>><?=$col?></option>
            <?endforeach?>
        </select>
    </div>
</div>
<?endif?>

<?if(in_array('ALIGN', $arResult['SECTIONS'])):
    $selectedVal = $arResult['PROPS']['ALIGN'];
    if (empty($selectedVal)) {
        $selectedVal = Config::getBlockDefaultAlign($arResult['CODE']);
    }
?>
<div class="panel-row">
    <div class="form-group">
        <label><?= $arResult['DESIGN_FIELDS_MESS']['ALIGN'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_ALIGN') ?></label>
        <select name="align" class="form-control">
            <option value="center" <?if($selectedVal == 'center'):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_DESIGN_ALIGN_CENTER') ?></option>
            <option value="left" <?if($selectedVal == 'left'):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_DESIGN_ALIGN_LEFT') ?></option>
            <option value="wide" <?if($selectedVal == 'wide'):?>selected<?endif?>><?= Loc::getMessage('RX_PANEL_LANDING_DESIGN_ALIGN_WIDE') ?></option>
        </select>
    </div>
</div>
<?endif?>

<?if(in_array('PICTURE_ALIGN', $arResult['SECTIONS'])):
    $selectedVal = $arResult['PROPS']['PICTURE_ALIGN'];
    if (empty($arResult['PROPS']['PICTURE_ALIGN'])) {
        $selectedVal = Config::getBlockDefaultPictureAlign($arResult['CODE']);
    }
?>
<div class="panel-row">
    <div class="form-group">
        <label><?= $arResult['DESIGN_FIELDS_MESS']['PICTURE_ALIGN'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_PICTURE_ALIGN') ?></label>
        <select name="picture_align" class="form-control">
            <?foreach ($arResult['PICTURE_ALIGN_OPTIONS'] as $i => $option):?>
                <option value="<?=$option?>" <?if(!$selectedVal && $i === 0 || $selectedVal == $option):?>selected<?endif?>>
                    <?= Loc::getMessage('RX_PANEL_LANDING_DESIGN_PICTURE_ALIGN_'.strtoupper($option)) ?>
                </option>
            <?endforeach?>
        </select>
    </div>
</div>
<?endif?>

<?if(in_array('INDENTS', $arResult['SECTIONS'])):?>
<div class="panel-row panel-row-two">
    <div class="form-group">
        <label><?= $arResult['DESIGN_FIELDS_MESS']['INDENT_TOP'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_INDENT_TOP') ?></label>
        <? $selectedVal = Block::checkIndent($arResult['PROPS']['INDENT_TOP']); ?>
        <select name="indent_top" class="form-control">
            <?foreach($arResult['CONFIG']['INDENTS'] as $indent):?>
            <option value="<?=$indent?>" <?if($indent == $selectedVal):?>selected<?endif?>><?=$indent?>px</option>
            <?endforeach?>
        </select>
    </div>
    <div class="form-group">
        <label><?= $arResult['DESIGN_FIELDS_MESS']['INDENT_BOT'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_INDENT_BOT') ?></label>
        <? $selectedVal = Block::checkIndent($arResult['PROPS']['INDENT_BOT']); ?>
        <select name="indent_bot" class="form-control">
            <?foreach($arResult['CONFIG']['INDENTS'] as $indent):?>
            <option value="<?=$indent?>" <?if($indent == $selectedVal):?>selected<?endif?>><?=$indent?>px</option>
            <?endforeach?>
        </select>
    </div>
</div>
<?endif?>

<?if(in_array('BLOCK_HEIGHT', $arResult['SECTIONS'])):?>
    <div class="panel-row">
        <div class="form-group">
            <label for="panelBlockHeight"><?= $arResult['DESIGN_FIELDS_MESS']['HEIGHT'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_BLOCK_HEIGHT') ?></label>
            <input type="text" class="js-mask-integer form-control" id="panelBlockHeight" name="block_height" value="<?= $arResult['PROPS']['HEIGHT'] ?>">
        </div>
    </div>
<?endif;?>

<?if(in_array('LINE_BOT', $arResult['SECTIONS'])):?>
<? $isLineBotChecked = $arResult['PROPS']['LINE_BOT'] == 'Y'; ?>
<div class="panel-row">
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="panelLineBotCheck" name="line_bot" <?if($isLineBotChecked):?>checked<?endif?>>
            <label class="custom-control-label" for="panelLineBotCheck">
                <?= $arResult['DESIGN_FIELDS_MESS']['LINE_BOT'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_LINE_BOT') ?>
            </label>
        </div>
    </div>
</div>
<?endif?>

<?if(in_array('SLIDER', $arResult['SECTIONS'])):?>
    <? $isSliderChecked = $arResult['PROPS']['SLIDER'] == 'Y'; ?>
    <div class="panel-row">
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="panelSliderCheck" name="slider" <?if($isSliderChecked):?>checked<?endif?>>
                <label class="custom-control-label" for="panelSliderCheck">
                    <?= $arResult['DESIGN_FIELDS_MESS']['SLIDER'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_SLIDER') ?>
                </label>
            </div>
        </div>
    </div>
<?endif?>

<?if(in_array('WIDE', $arResult['SECTIONS'])):?>
    <? $isWide = $arResult['PROPS']['WIDE'] == 'Y'; ?>
    <div class="panel-row">
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="panelWideBlockCheck" name="wide" <?if($isWide):?>checked<?endif?>>
                <label class="custom-control-label" for="panelWideBlockCheck">
                    <?= $arResult['DESIGN_FIELDS_MESS']['WIDE'] ?? $arResult['PROPERTIES']['WIDE']['NAME'] ?>
                </label>
            </div>
        </div>
    </div>
<?endif?>

<?if(in_array('HOVER_EFFECT', $arResult['SECTIONS'])):?>
    <? $isEffectBotChecked = $arResult['PROPERTIES']['HOVER_EFFECT']['VALUE_XML_ID'] == 'LIFT_UP'; ?>
    <div class="panel-row">
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="panelHoverEffectBotCheck"
                       name="hover_effect" <?if($isEffectBotChecked):?>checked<?endif?>>
                <label class="custom-control-label" for="panelHoverEffectBotCheck">
                    <?= $arResult['DESIGN_FIELDS_MESS']['HOVER_EFFECT'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_HOVER_EFFECT') ?>
                </label>
            </div>
        </div>
    </div>
<?endif?>

<?if(in_array('INDENT_ELEMENTS', $arResult['SECTIONS'])):?>
<? $isIndentElementsChecked = $arResult['PROPS']['INDENT_ELEMENTS'] == 'Y'; ?>
<div class="panel-row">
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="panelIndentElementsCheck" name="indent_elements" <?if($isIndentElementsChecked):?>checked<?endif?>>
            <label class="custom-control-label" for="panelIndentElementsCheck">
                <?= $arResult['DESIGN_FIELDS_MESS']['INDENT_ELEMENTS'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_INDENT_ELEMENTS') ?>
            </label>
        </div>
    </div>
</div>
<?endif?>

<?if(in_array('HIDE_TABS', $arResult['SECTIONS'])):?>
<? $isHideTabsChecked = $arResult['PROPS']['HIDE_TABS'] == 'Y'; ?>
<div class="panel-row">
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="panelHideTabsCheck" name="hide_tabs" <?if($isHideTabsChecked):?>checked<?endif?>>
            <label class="custom-control-label" for="panelHideTabsCheck">
                <?= $arResult['DESIGN_FIELDS_MESS']['HIDE_TABS'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_HIDE_TABS') ?>
            </label>
        </div>
    </div>
</div>
<?endif?>

<?if(in_array('BG_COLOR', $arResult['SECTIONS'])):?>
<?php
    $curBgColor = $arResult['PROPS']['BG_COLOR'] ? $arResult['PROPS']['BG_COLOR'] : $arResult['CONFIG']['BG_COLOR_DEFAULT'];
    $bgColors = $arResult['CONFIG']['BG_COLORS'];
    $bgColorValues = array_column($bgColors, 'VALUE');
    $isCustomColor = !in_array($curBgColor, $bgColorValues);
    $themeColor = Config::getThemeColor();

    $radioColorGroup = 'bg_color';
?>
<div class="panel-row panel-row-two">
    <div class="form-group">
        <label><?= $arResult['DESIGN_FIELDS_MESS']['BG_COLOR'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_BG_COLOR') ?></label>
        <div class="radiocolor theme-border-class-active" data-group="<?= $radioColorGroup ?>">
            <input type="hidden" name="bg_color" value="<?= (!$isCustomColor ? $curBgColor : '') ?>">

            <?foreach($bgColors as $color):?>
            <div class="radiocolor-item <?= ((!$isCustomColor && $color['VALUE'] == $curBgColor) ? 'active' : '') ?>"
                data-value="<?=$color['VALUE']?>" title="<?=$color['NAME']?>">
                <div class="radiocolor-item-color" style="background-color: <?=($color['VALUE'] == 'theme' ? $themeColor : $color['VALUE'])?>;"></div>
            </div>
            <?endforeach?>

        </div>
    </div>
    <div class="form-group">
        <label><?= $arResult['DESIGN_FIELDS_MESS']['BG_COLOR_CUSTOM'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_BG_COLOR_CUSTOM') ?></label>
        <?php
            $customColorVal = $isCustomColor ? $curBgColor : '';
            $customColorName = 'bg_color_custom';
            include __DIR__ . '/../include/radiocolor_big.php';
        ?>
    </div>
</div>
<?endif?>

<?if(in_array('TEXT_COLOR', $arResult['SECTIONS'])):?>
<? $isTextColorLight = $arResult['PROPS']['TEXT_LIGHT'] == 'Y'; ?>
<div class="panel-row">
    <div class="form-group">
        <label><?= $arResult['DESIGN_FIELDS_MESS']['TEXT_COLOR'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_TEXT_COLOR') ?></label>
        <div class="radiocolor theme-border-class-active">
            <input type="hidden" name="text_color" value="<?if($isTextColorLight):?>light<?else:?>dark<?endif?>">
            <div class="radiocolor-item <?if($isTextColorLight):?>active<?endif?>" data-value="light" title="<?= Loc::getMessage('RX_PANEL_LANDING_DESIGN_TEXT_COLOR_LIGHT') ?>">
                <div class="radiocolor-item-color" style="background-color: #ffffff;"></div>
            </div>
            <div class="radiocolor-item <?if(!$isTextColorLight):?>active<?endif?>" data-value="dark" title="<?= Loc::getMessage('RX_PANEL_LANDING_DESIGN_TEXT_COLOR_DARK') ?>">
                <div class="radiocolor-item-color" style="background-color: #333333;"></div>
            </div>
        </div>
    </div>
</div>
<?endif?>

<?if(in_array('CARDS_BG_COLOR', $arResult['SECTIONS'])):?>
    <?php
    $curBgColor = $arResult['PROPS']['CARDS_BG_COLOR'] ?: 'transparent';
    $bgColors = $arResult['CONFIG']['CARDS_BG_COLORS'];
    $bgColorValues = array_column($bgColors, 'VALUE');
    $isCustomColor = !in_array($curBgColor, $bgColorValues);
    $themeColor = Config::getThemeColor();

    $radioColorGroup = 'cards_bg_color';
    ?>
    <div class="panel-row panel-row-two">
        <div class="form-group">
            <label><?= $arResult['DESIGN_FIELDS_MESS']['CARDS_BG_COLOR'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_CARDS_BG_COLOR') ?></label>
            <div class="radiocolor theme-border-class-active" data-group="<?= $radioColorGroup ?>">
                <input type="hidden" name="cards_bg_color" value="<?= (!$isCustomColor ? $curBgColor : '') ?>">

                <?foreach($bgColors as $color):?>
                    <div class="radiocolor-item <?= ((!$isCustomColor && $color['VALUE'] == $curBgColor) ? 'active' : '') ?>"
                         data-value="<?=$color['VALUE']?>" title="<?=$color['NAME']?>">

                        <?switch ($color['VALUE']) {
                            case 'theme': $styleValue = $themeColor; break;
                            case 'transparent': $styleValue = 'url(\''.Config::getTemplatePath().'/assets/img/header/transparent.png\') repeat'; break;
                            default: $styleValue = $color['VALUE'];
                        }?>

                        <div class="radiocolor-item-color" style="background: <?=$styleValue?>;"></div>
                    </div>
                <?endforeach?>

            </div>
        </div>
        <div class="form-group">
            <label><?= $arResult['DESIGN_FIELDS_MESS']['CARDS_BG_COLOR_CUSTOM'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_BG_COLOR_CUSTOM') ?></label>
            <?php
            $customColorVal = $isCustomColor ? $curBgColor : '';
            $customColorName = 'cards_bg_color_custom';
            include __DIR__ . '/../include/radiocolor_big.php';
            ?>
        </div>
    </div>
<?endif?>

<?if(in_array('TINT_COLOR', $arResult['SECTIONS'])):?>
<?php
    $curTintColor = $arResult['PROPS']['TINT_COLOR'];
    $tintColors = $arResult['CONFIG']['TINT_COLORS'];
    $tintColorValues = array_column($tintColors, 'VALUE');
?>
<div class="panel-row">
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="panelTintColorCheck" data-toggle="#panelTintColorToggle" 
                name="tint_color_active" <?if($curTintColor):?>checked<?endif?>>
            <label class="custom-control-label" for="panelTintColorCheck">
                <?= $arResult['DESIGN_FIELDS_MESS']['TINT_COLOR_ACTIVE'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_TINT_COLOR_ACTIVE') ?>
            </label>
        </div>
    </div>
</div>
<div id="panelTintColorToggle" class="panel-row panel-row-two" <?if(!$curTintColor):?>style="display: none;"<?endif?>>
    <div class="form-group">
        <label><?= $arResult['DESIGN_FIELDS_MESS']['TINT_COLOR'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_TINT_COLOR') ?></label>
        <div class="radiocolor theme-border-class-active" data-group="tint_color">
            <input type="hidden" name="tint_color" value="<?= $curTintColor ?>">

            <?foreach($tintColors as $color):?>
            <div class="radiocolor-item <?= (($color['VALUE'] == $curTintColor) ? 'active' : '') ?>"
                data-value="<?=$color['VALUE']?>" title="<?=$color['NAME']?>">
                <div class="radiocolor-item-color" style="background-color: <?=$color['VALUE']?>;"></div>
            </div>
            <?endforeach?>

        </div>
    </div>
</div>
<?endif?>

<?if(in_array('BG_PICTURE', $arResult['SECTIONS'])):?>
    <div class="panel-row panel-row--column">
        <?
        $field = [
            'ID' => 'panelBgPicture',
            'NAME' => 'bg_picture',
            'VALUE' => $arResult['PROPS']['BG_PICTURE'],
            'MIME_TYPE' => 'image',
            'TITLE' => Loc::getMessage('RX_PANEL_LANDING_DESIGN_BG_PICTURE'),
            'BTN_TEXT' => Loc::getMessage('RX_PANEL_LANDING_DESIGN_BG_PICTURE_UPLOAD'),
        ];

        include __DIR__.'/../include/field/file.php';
        ?>

        <?if(in_array('PARALLAX_EFFECT', $arResult['SECTIONS'])):?>
            <? $isParallaxEffectChecked = in_array('parallax', $arResult['PROPERTIES']['EFFECTS']['VALUE_XML_ID'] ?: []);?>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="panelParallaxEffectCheck" data-toggle="#panelParallaxEffectToggle"
                           name="parallax_effect" <?if($isParallaxEffectChecked):?>checked<?endif?>>
                    <label class="custom-control-label" for="panelParallaxEffectCheck">
                        <?= $arResult['DESIGN_FIELDS_MESS']['PARALLAX_EFFECT'] ?? Loc::getMessage('RX_PANEL_LANDING_DESIGN_PARALLAX_EFFECT') ?>
                    </label>
                </div>
            </div>
        <?endif?>
    </div>

<?endif?>
