<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var string $groupId
 * @var array $group
 * @var string $rxLandingPanel
 */

use Ranx\Landing\Config;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Panel\Manager as PanelManager;
?>

<div class="panel-tab panel-settings panel-tab-with-footer <?if($rxLandingPanel == '#panelParams'.$groupId):?>active<?endif?>" id="panelParams<?=$groupId?>">

    <div class="panel-tab-desc">
        <div class="panel-tab-title"><?= $group['TITLE'] ?></div>

        <?if(!empty($group['NOTE'])):?>
            <div class="panel-tab-text"><?= $group['NOTE'] ?></div>
        <?endif?>
    </div>

    <?foreach($group['OPTIONS'] as $optionCode => $option):
        if ($option['THEME'] === 'N' || !in_array($option['TYPE'], ['text', 'multitext'])) continue;

        $optionVal = Config::get($optionCode);
        ?>

        <div class="panel-chat">

            <?if($option['ICON']):?>
                <div class="panel-chat-icon theme-color">
                    <?if(strpos($option['ICON'], '.svg') !== false):?>
                        <?= file_get_contents($_SERVER['DOCUMENT_ROOT'] . PanelManager::getOptionIconPath($optionCode, $option['ICON'])) ?>
                    <?else:?>
                        <img src="<?= PanelManager::getOptionIconPath($optionCode, $option['ICON']) ?>" alt="<?= $option['TITLE'] ?>">
                    <?endif?>
                </div>
            <?endif?>
            <div class="panel-chat-title"><?= $option['TITLE'] ?></div>

            <?if($option['LINK']):?>
                <a href="<?= $option['LINK'] ?>" target="_blank" rel="nofollow" class="panel-chat-btn btn btn-transparent"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_CHAT_REGISTER') ?></a>
            <?endif?>

            <a href="#" class="panel-chat-toggle theme-color-hover">
                <?if(!is_array($optionVal) && !empty($optionVal) ||
                    is_array($optionVal) && count(array_filter($optionVal))):?>
                    <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_CHAT_TOGGLE_NOT_EMPTY') ?>
                <?else:?>
                    <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_CHAT_TOGGLE') ?>
                <?endif?>
            </a>
            <div class="form-group" style="display: none;">
                <?if (is_array($optionVal)):?>
                    <?foreach ($optionVal as $i => $val):?>
                        <div class="form-group">
                            <textarea name="<?=$optionCode?>[]" class="form-control"
                                <?if(!empty($option['PLACEHOLDER'][$i])):?>placeholder="<?=htmlspecialcharsbx($option['PLACEHOLDER'][$i])?>"<?endif?>><?=htmlspecialcharsbx($val)?></textarea>
                        </div>
                    <?endforeach?>
                <?else:?>
                    <textarea name="<?= $optionCode ?>" class="form-control"
                        <?if(!empty($option['PLACEHOLDER'])):?>placeholder="<?=htmlspecialcharsbx($option['PLACEHOLDER'])?>"<?endif?>><?= htmlspecialcharsbx($optionVal) ?></textarea>
                <?endif?>
            </div>

            <?if($optionCode == 'YAMETRIKA'):?>

                <div class="panel-settings-metrics">
                    <?php
                        $useGoals = Config::get('YAMETRIKA_USE_GOALS');
                        $curOption = Config::getParamInfo('YAMETRIKA_USE_GOALS');
                    ?>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="panelCheck_YAMETRIKA_USE_GOALS"
                                   name="YAMETRIKA_USE_GOALS" <?if($useGoals):?>checked<?endif?>
                                   data-option="YAMETRIKA_USE_GOALS">
                            <label class="custom-control-label" for="panelCheck_YAMETRIKA_USE_GOALS">
                                <?= $curOption['TITLE'] ?> (<a target="_blank" href="<?=$curOption['DOC']?>">?</a>)
                            </label>
                        </div>
                    </div>

                    <?php
                        $curOption = Config::getParamInfo('YAMETRIKA_COUNTER');
                    ?>
                    <div class="form-group collapse js-show-if" data-show-if='{"YAMETRIKA_USE_GOALS": true}'>
                        <label><?= $curOption['TITLE'] ?></label>
                        <input type="text" class="form-control" name="YAMETRIKA_COUNTER" value="<?= htmlspecialcharsbx(Config::get('YAMETRIKA_COUNTER')) ?>"
                               <?if(!empty($curOption['PLACEHOLDER'])):?>placeholder="<?= $curOption['PLACEHOLDER'] ?>"<?endif?>>
                    </div>

                    <?php
                        $curOption = Config::getParamInfo('YAMETRIKA_USE_DEBUG');
                    ?>
                    <div class="form-group collapse js-show-if" data-show-if='{"YAMETRIKA_USE_GOALS": true}'>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="panelCheck_YAMETRIKA_USE_DEBUG"
                                   name="YAMETRIKA_USE_DEBUG" <?if(Config::get('YAMETRIKA_USE_DEBUG')):?>checked<?endif?>>
                            <label class="custom-control-label" for="panelCheck_YAMETRIKA_USE_DEBUG"><?= $curOption['TITLE'] ?></label>
                        </div>
                    </div>
                </div>

            <?elseif ($optionCode == 'GANALYTICS'):?>

                <div class="panel-settings-metrics">
                    <?
                    $gaSendEvent = Config::get('GANALYTICS_USE_EVENTS');
                    ['TITLE' => $title, 'DOC' => $doc] = Config::getParamInfo('GANALYTICS_USE_EVENTS');
                    ?>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="panelCheck_GANALYTICS_USE_EVENTS"
                                   name="GANALYTICS_USE_EVENTS" <?if($gaSendEvent):?>checked<?endif?>
                                   data-option="GANALYTICS_USE_EVENTS">
                            <label class="custom-control-label" for="panelCheck_GANALYTICS_USE_EVENTS">
                                <?= $title ?> <?if(!empty($doc)):?>(<a target="_blank" href="<?=$doc?>">?</a>)<?endif?>
                            </label>
                        </div>
                    </div>

                    <?
                    $gaResource = Config::get('GANALYTICS_RESOURCE');
                    ['TITLE' => $title, 'PLACEHOLDER' => $placeholder, 'SHOW_IF' => $showIf] = Config::getParamInfo('GANALYTICS_RESOURCE');
                    ?>
                    <div class="form-group collapse js-show-if" data-show-if='<?= \Bitrix\Main\Web\Json::encode($showIf) ?>'>
                        <label><?= $title ?></label>
                        <input type="text" class="form-control" name="GANALYTICS_RESOURCE" value="<?= htmlspecialcharsbx($gaResource) ?>"
                               <?if(!empty($placeholder)):?>placeholder="<?= $placeholder ?>"<?endif?>>
                    </div>
                </div>

            <?endif?>

        </div>

    <?endforeach?>

    <?php
        include 'params_tab_footer.php'
    ?>

</div>
