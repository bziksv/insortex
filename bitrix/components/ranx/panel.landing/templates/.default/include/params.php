<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var bool $rxDemoMode
 * @var string $rxLandingPanel
 */

use Ranx\Landing\Config;
use Bitrix\Main\Web\Json;
use Bitrix\Main\Localization\Loc;
?>

<?if(!empty($arResult['PARAMS'])):?>
    <form id="panelParamsForm">

        <?foreach($arResult['PARAMS'] as $groupId => $group):
            if (empty($group) || $group['THEME'] === 'N' || ($rxDemoMode && $group['DEMO'] != 'Y')) continue;

            // groups that have own template
            if (!empty($group['TEMPLATE'])) {
                include 'params_' . $group['TEMPLATE'] . '.php';
                continue;
            }
            ?>
            <div class="panel-tab panel-settings panel-tab-with-footer <?if($rxLandingPanel == '#panelParams'.$groupId):?>active<?endif?>" id="panelParams<?=$groupId?>">

                <?if(!empty($group['NOTE'])):?>
                    <div class="alert alert-warning"><?= $group['NOTE'] ?></div>
                <?endif?>

                <?foreach($group['OPTIONS'] as $optionCode => $option):
                    if ($option['THEME'] === 'N' || $option['DISABLED'] || in_array($optionCode, ['COLOR_THEME_CUSTOM'])
                        || ($rxDemoMode && $option['DEMO'] != 'Y')) continue;

                    // skip all button params
                    if (strpos($optionCode, '_BTN_') !== false && strpos($optionCode, '_BTN_SHOW') === false) {
                        continue;
                    }

                    $optionVal = Config::get($optionCode);
                    $option['NAME'] = $optionCode;
                    $option['VALUE'] = $optionVal;

                    $panelRowClasses = '';
                    $panelRowData    = '';
                    if (!empty($option['SHOW_IF']))
                    {
                        $panelRowClasses .= 'js-show-if collapse ';
                        $panelRowData    .= 'data-show-if=\'' . Json::encode($option['SHOW_IF']) . '\' ';
                    }

                    if (!empty($option['HIDDEN'])) {
                        $panelRowClasses = 'collapse';
                    }
                    if (!empty($option['CLASSES'])) {
                        $panelRowClasses .= ' ' . $option['CLASSES'];
                    }
                ?>
                    <div class="panel-row flex-wrap <?=$panelRowClasses?>" <?= $panelRowData ?>>

                        <?if($optionCode == 'COLOR_THEME'):
                            $optionCustom = $group['OPTIONS']['COLOR_THEME_CUSTOM'];
                            $customColorVal = Config::get('COLOR_THEME_CUSTOM');

                            $isCustomColor = !empty($customColorVal);
                            $radioColorGroup = 'color_theme';
                            $customColorName = 'COLOR_THEME_CUSTOM';
                            ?>

                            <div class="form-group p-0">
                                <label><?= $option['TITLE'] ?></label>
                                <div class="radiocolor theme-border-class-active" data-group="<?= $radioColorGroup ?>">
                                    <input type="hidden" name="COLOR_THEME" value="<?= ($optionVal ? $optionVal : $option['DEFAULT']) ?>">

                                    <?foreach($option['LIST'] as $optionItemId => $optionItem):?>
                                        <div class="radiocolor-item <?if(!$isCustomColor && $optionItemId == $optionVal):?>active<?endif?>"
                                             data-value="<?= $optionItemId ?>" title="<?= $optionItem['TITLE'] ?>">
                                            <div class="radiocolor-item-color" style="background-color: <?= $optionItem['COLOR'] ?>;"></div>
                                        </div>
                                    <?endforeach?>

                                </div>
                            </div>

                            <div class="form-group p-0">
                                <label class="sublabel"><?= $optionCustom['TITLE'] ?></label>
                                <?php
                                    include 'radiocolor_big.php';
                                ?>
                            </div>

                        <?elseif(strpos($optionCode, '_BTN_SHOW') !== false):
                            $preFix = substr($optionCode, 0, strpos($optionCode, 'BTN_SHOW'));
                            $btnId = $optionCode;
                            $btnShow = $optionVal ? 'Y' : 'N';

                            $hideBtnType = !empty($option['HIDE_TYPE']);
                            if (!$hideBtnType) {
                                $btnType = Config::get($preFix . 'BTN_TYPE');
                                $btnTypeValuesRaw = Config::getParamList($preFix . 'BTN_TYPE');
                                $btnTypeValues = [];
                                foreach ($btnTypeValuesRaw as $btnTypeKey => $btnTypeVal) {
                                    $btnTypeValues[$btnTypeVal['TITLE']] = $btnTypeKey;
                                }
                            }

                            $hideBtnSize = !empty($option['HIDE_SIZE']);
                            if (!$hideBtnSize) {
                                $btnSize = Config::get($preFix . 'BTN_SIZE');
                                $btnSizeValuesRaw = Config::getParamList($preFix . 'BTN_SIZE');
                                $btnSizeValues = [];
                                foreach ($btnSizeValuesRaw as $btnSizeKey => $btnSizeVal) {
                                    $btnSizeValues[$btnSizeVal['TITLE']] = $btnSizeKey;
                                }
                            }

                            $btnText = Config::get($preFix . 'BTN_TEXT');
                            $btnLinkType = Config::get($preFix . 'BTN_LINK_TYPE');
                            $btnLink = Config::get($preFix . 'BTN_LINK');
                            $btnGoal = Config::get($preFix . 'BTN_GOAL');
                            $btnClass = Config::get($preFix . 'BTN_CLASS');

                            $btnForms = $arResult['FORMS'];

                            $hideBtnLinkTypeAnchor = true;

                            include 'btn.php';
                        ?>

                        <?elseif($option['TYPE'] == 'aarray'):
                            $field = $option;
                            include __DIR__ . '/field/aarray.php';
                        ?>    

                        <?elseif($option['TYPE'] == 'file'):
                            $field = [
                                'TITLE' => $option['TITLE'],
                                'NAME' => $optionCode,
                                'ID' => 'panelParamsFile'.$optionCode,
                                'VALUE' => $optionVal,
                                'MIME_TYPE' => $option['MIME_TYPE'] ?? '',
                                'FILE_TYPE' => implode(', ', $option['EXTS'] ?? ''),
                                'BTN_TEXT' => Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PICTURE_UPLOAD'),
                            ];

                            include __DIR__.'/field/file.php';
                        ?>

                        <?elseif($option['TYPE'] == 'select'):
                            $field = $option;
                            include __DIR__ . '/field/selectbox.php';
                        ?>

                        <?elseif($option['TYPE'] == 'multiselect'):?>

                            <div class="form-group">
                                <label>
                                    <?= $option['TITLE'] ?>
                                    <?if(!empty($option['DOC'])):?>(<a href="<?= $option['DOC'] ?>" target="_blank"
                                        title="<?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_DOC') ?>">?</a>)<?endif?>
                                </label>

                                <select name="<?= $optionCode ?>" data-option="<?= $optionCode ?>" multiple>

                                    <?foreach($option['LIST'] as $optionItemId => $optionItem):
                                        $isSelected = in_array($optionItemId, $optionVal);
                                        ?>
                                        <option value="<?= $optionItemId ?>" <?if($isSelected):?>selected<?endif?>>
                                            <?= $optionItem['TITLE'] ?>
                                        </option>
                                    <?endforeach;?>

                                </select>
                            </div>

                        <?elseif($option['TYPE'] == 'checkbox'):
                            $field = $option;
                            include __DIR__ . '/field/checkbox.php';
                        ?>

                        <?elseif($option['TYPE'] == 'text'):?>

                            <div class="form-group">
                                <label>
                                    <?= $option['TITLE'] ?>
                                    <?if(!empty($option['DOC'])):?>(<a href="<?= $option['DOC'] ?>" target="_blank"
                                        title="<?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_DOC') ?>">?</a>)<?endif?>
                                </label>
                                <textarea name="<?= $optionCode ?>" class="form-control"><?= $optionVal ?></textarea>
                            </div>

                        <?elseif($option['TYPE'] == 'string'):
                            $field = $option;
                            include __DIR__ . '/field/string.php';
                        ?>

                        <?endif?>

                    </div>

                <?endforeach?>

                <?php
                include 'params_tab_footer.php'
                ?>

            </div>
        <?endforeach?>

    </form>
<?endif?>