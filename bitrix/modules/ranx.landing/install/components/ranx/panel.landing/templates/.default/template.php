<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

use Ranx\Landing\Block;
use Ranx\Landing\Config;
use Ranx\Landing\Landing;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$rxEditMode = Config::isEditMode();
$rxDemoMode = Config::isDemoLanding();
$mode = $arParams['MODE'];
$elType = \Ranx\Landing\Section\Manager::getElementType($arParams['SECTION_TYPE']);

$canEditParams = $GLOBALS['USER']->CanDoOperation('rx_landing_settings_edit') || $rxDemoMode;
$canEditSection = $GLOBALS['USER']->CanDoOperation('rx_landing_section_edit');
$canEditPresets = $GLOBALS['USER']->CanDoOperation('rx_landing_block_edit')
    || $GLOBALS['USER']->CanDoOperation('rx_landing_preset_upload')
    || $GLOBALS['USER']->CanDoOperation('rx_landing_preset_download')
    || $GLOBALS['USER']->CanDoOperation('rx_landing_preset_delete')
    || $rxDemoMode;
$canEditBlocks = $GLOBALS['USER']->CanDoOperation('rx_landing_block_edit') || $rxDemoMode;

$rxLandingPanel = trim(htmlspecialchars($_COOKIE['RX_LANDING_PANEL']));
unset($_COOKIE['RX_LANDING_PANEL']);
setcookie('RX_LANDING_PANEL', null, -1, '/');
?>

<div class="panel-btns-wrap">

    <?if(!$rxDemoMode && $canEditSection):?>
        <?if($mode !== Landing::MODE_ELEMENT):?>
            <div class="panel-btns theme-bg">
                <?if($mode !== Landing::MODE_ROOT_SECTION):?>
                    <a href="#" class="panel-btn panel-btn-one theme-bg theme-bg-hover" data-open-panel="#panelSectionAdd">
                        <div class="panel-btn-icon">
                            <?=Helper::svg('panel', 'panel_section_add')?>
                        </div>
                        <div class="panel-tooltip">
                            <div class="panel-tooltip-title"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SECTION_ADD_TITLE') ?></div>
                            <div class="panel-tooltip-text">
                                <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SECTION_ADD_TEXT') ?>
                            </div>
                        </div>
                    </a>
                <?endif?>
                <?if($mode === Landing::MODE_SECTION || $mode === Landing::MODE_ROOT_SECTION):?>
                    <a href="#" class="panel-btn panel-btn-one theme-bg theme-bg-hover" data-open-panel="#panelElementAdd">
                        <div class="panel-btn-icon">
                            <?=Helper::svg('panel', 'panel_element_add')?>
                        </div>
                        <div class="panel-tooltip">
                            <div class="panel-tooltip-title"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_'.$elType.'_ADD_TITLE') ?></div>
                            <div class="panel-tooltip-text">
                                <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_'.$elType.'_ADD_TEXT') ?>
                            </div>
                        </div>
                    </a>
                <?endif?>
            </div>
        <?endif?>
    <?endif?>

    <div class="panel-btns theme-bg">

        <?if($canEditParams):?>
        <a href="#" class="panel-btn theme-bg theme-bg-hover" data-open-panel="#panelParams">
            <div class="panel-btn-icon">
                <?=Helper::svg('panel', 'panel_settings')?>
            </div>
            <div class="panel-tooltip">
                <div class="panel-tooltip-title"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SETTINGS_TITLE') ?></div>
                <div class="panel-tooltip-text">
                    <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SETTINGS_TEXT') ?>
                </div>
            </div>
        </a>
        <?endif?>

        <?if($rxEditMode):?>
        <a href="#" class="panel-btn theme-bg theme-bg-hover" data-open-panel="#panelLib">
            <div class="panel-btn-icon">
                <?=Helper::svg('panel', 'panel_lib')?>
            </div>
            <div class="panel-tooltip">
                <div class="panel-tooltip-title"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_LIB_TITLE') ?></div>
                <div class="panel-tooltip-text">
                    <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_LIB_TEXT') ?>
                </div>
            </div>
        </a>
        <?endif?>

        <?if($canEditPresets):?>
        <a href="#" class="panel-btn theme-bg theme-bg-hover" data-open-panel="#panelPresets">
            <div class="panel-btn-icon">
                <?=Helper::svg('panel', 'panel_presets')?>
            </div>
            <div class="panel-tooltip">
                <div class="panel-tooltip-title"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESETS_TITLE') ?></div>
                <div class="panel-tooltip-text">
                    <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESETS_TEXT') ?>
                </div>
            </div>
        </a>
        <?endif?>

        <a href="#" class="panel-btn theme-bg theme-bg-hover" data-open-panel="#panelSupport">
            <div class="panel-btn-icon">
                <?=Helper::svg('panel', 'panel_support')?>
            </div>
            <div class="panel-tooltip">
                <div class="panel-tooltip-title"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SUPPORT_TITLE') ?></div>
                <div class="panel-tooltip-text">
                    <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SUPPORT_TEXT') ?>
                </div>
            </div>
        </a>
    </div>

    <?if($canEditBlocks):?>
        <?if($rxEditMode):?>
            <div class="panel-btns panel-btns-exit">
                <a href="#" class="panel-btn js-exit-edit-mode">
                    <div class="panel-btn-icon">
                        <?=Helper::svg('panel', 'panel_exit')?>
                    </div>
                    <div class="panel-tooltip">
                        <div class="panel-tooltip-title"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_EXIT_TITLE') ?></div>
                        <div class="panel-tooltip-text">
                            <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_EXIT_TEXT') ?>
                        </div>
                    </div>
                </a>
            </div>
        <?else:?>
            <div class="panel-btns theme-bg">
                <a href="#" class="panel-btn theme-bg theme-bg-hover panel-btn-one js-enter-edit-mode">
                    <div class="panel-btn-icon">
                        <?=Helper::svg('panel', 'panel_enter')?>
                    </div>
                    <div class="panel-tooltip">
                        <div class="panel-tooltip-title"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_ENTER_TITLE') ?></div>
                        <div class="panel-tooltip-text">
                            <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_ENTER_TEXT') ?>
                        </div>
                    </div>
                </a>
            </div>
        <?endif?>
    <?endif?>
</div>

<div class="panel <?if($rxLandingPanel):?>open active<?endif?>" id="panel" data-lpanel="<?=$rxLandingPanel?>">
    <div class="panel-menu js-simplebar">
        <ul>
            <?if(!$rxDemoMode && $mode !== Landing::MODE_ELEMENT && $canEditSection):?>
                <?if($mode !== Landing::MODE_ROOT_SECTION):?>
                    <li>
                        <a href="#panelSectionAdd" class="theme-before-bg">
                            <div class="panel-menu-icon"><?=Helper::svg('panel', 'panel_section_add')?></div>
                            <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SECTION_ADD_TITLE') ?>
                        </a>
                    </li>
                <?endif?>
                <?if($mode == Landing::MODE_SECTION || $mode == Landing::MODE_ROOT_SECTION):?>
                    <li>
                        <a href="#panelElementAdd" class="theme-before-bg">
                            <div class="panel-menu-icon"><?=Helper::svg('panel', 'panel_element_add')?></div>
                            <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_'.$elType.'_ADD_TITLE') ?>
                        </a>
                    </li>
                <?endif?>
            <?endif?>

            <?if($canEditParams):?>
            <li>
                <a href="#panelParams" class="theme-before-bg <?if(strpos($rxLandingPanel, '#panelParams') === 0):?>active<?endif?>">
                    <div class="panel-menu-icon"><?=Helper::svg('panel', 'panel_settings')?></div>
                    <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SETTINGS_TITLE') ?>
                </a>

                <?if(!empty($arResult['PARAMS'])):?>
                    <ul class="panel-menu-dropdown">

                        <?foreach($arResult['PARAMS'] as $groupId => $group):
                            if (empty($group) || $group['THEME'] === 'N' || ($rxDemoMode && $group['DEMO'] != 'Y')) continue;
                            ?>
                            <li>
                                <a href="#panelParams<?=$groupId?>" class="theme-before-bg <?if($rxLandingPanel == '#panelParams'.$groupId):?>active<?endif?>">
                                    <?=$group['TITLE']?>
                                </a>
                            </li>
                        <?endforeach?>

                    </ul>
                <?endif?>
            </li>
            <?endif?>

            <?if($rxEditMode):?>
                <li>
                    <a href="#panelLib" class="theme-before-bg">
                        <div class="panel-menu-icon"><?=Helper::svg('panel', 'panel_lib')?></div>
                        <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_LIB_TITLE') ?>
                    </a>

                    <?if(!empty($arResult['BLOCK_GROUPS'])):?>
                        <ul class="panel-menu-dropdown">

                            <?foreach($arResult['BLOCK_GROUPS'] as $groupId => $group):
                                if (empty($group)) continue;
                                ?>
                                <li><a href="#panelLib<?=$groupId?>" class="theme-before-bg"><?=$group['TITLE']?></a></li>
                            <?endforeach?>

                        </ul>
                    <?endif?>

                </li>
            <?endif?>

            <?if(!empty($arResult['PRESET_GROUPS']) && $canEditPresets):?>
                <li>
                    <a href="#panelPresets" class="theme-before-bg <?if(strpos($rxLandingPanel, '#panelPresets') === 0):?>active<?endif?>">
                        <div class="panel-menu-icon"><?=Helper::svg('panel', 'panel_presets')?></div>
                        <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESETS_TITLE') ?>
                    </a>

                    <ul class="panel-menu-dropdown">

                        <?foreach($arResult['PRESET_GROUPS'] as $groupCode => $group):?>
                            <li>
                                <a href="#panelPresets<?=$groupCode?>" class="theme-before-bg <?if($rxLandingPanel == '#panelPresets' . $groupCode):?>active<?endif?>">
                                    <?=$group['TITLE']?>
                                </a>
                            </li>
                        <?endforeach?>

                    </ul>
                </li>
            <?endif?>

            <li>
                <a href="#panelSupport" class="theme-before-bg">
                    <div class="panel-menu-icon"><?=Helper::svg('panel', 'panel_support')?></div>
                    <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SUPPORT_TITLE') ?>
                </a>
            </li>

            <li>
                <a href="#panelUpdates" class="theme-before-bg js-panel-updates">
                    <div class="panel-menu-icon"><?=Helper::svg('panel', 'panel_updates')?></div>
                    <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_UPDATES_TITLE') ?>
                </a>
            </li>
        </ul>
    </div>
    <div class="panel-content js-simplebar">

        <?if(!$rxDemoMode && $mode !== Landing::MODE_ELEMENT && $canEditSection):?>
            <?if($mode !== Landing::MODE_ROOT_SECTION):?>
                <div class="panel-tab panel-content-add" id="panelSectionAdd">
                    <form class="form" id="panelSectionAddForm" novalidate>
                        <div class="form-group">
                            <label><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SECTION_ADD_NAME') ?><span>*</span></label>
                            <input
                                type="text" class="form-control js-auto-transliteration"
                                name="NAME" data-transliteration-target="#panelSectionAddFormCODE"
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SECTION_ADD_CODE') ?></label>
                            <input type="text" class="form-control" name="CODE" id="panelSectionAddFormCODE" placeholder="<?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SECTION_ADD_CODE_PLACEHOLDER') ?>">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_SECTION_ADD_BTN') ?></button>
                    </form>
                </div>
            <?endif?>
            <?if($mode == Landing::MODE_SECTION || $mode == Landing::MODE_ROOT_SECTION):?>
                <div class="panel-tab panel-content-add" id="panelElementAdd">
                    <form class="form" id="panelElementAddForm" novalidate>
                        <div class="form-group">
                            <label><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_ELEMENT_ADD_NAME') ?><span>*</span></label>
                            <input
                                type="text" class="form-control js-auto-transliteration"
                                name="NAME" data-transliteration-target="#panelElementAddFormCODE"
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_ELEMENT_ADD_CODE') ?></label>
                            <input type="text" class="form-control" name="CODE" id="panelElementAddFormCODE" placeholder="<?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_ELEMENT_ADD_CODE_PLACEHOLDER') ?>">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_ELEMENT_ADD_BTN') ?></button>
                    </form>
                </div>
            <?endif?>
        <?endif?>

        <?if($canEditParams):?>
        <div class="panel-tab" id="panelParams"></div>
        <? include_once 'include/params.php'; ?>
        <?endif?>

        <?if($rxEditMode):?>
            <div class="panel-tab" id="panelLib">
                <div class="alert alert-danger"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_LIB_NO_BLOCKS') ?></div>
            </div>

            <?if(!empty($arResult['BLOCK_GROUPS'])):?>

                <?foreach($arResult['BLOCK_GROUPS'] as $groupId => $group):
                    if (empty($group)) continue;
                    ?>
                    <div class="panel-tab" id="panelLib<?=$groupId?>">

                        <?if(!empty($group['NOTE'])):?>
                            <div class="alert alert-warning"><?= $group['NOTE'] ?></div>
                        <?endif?>

                        <?foreach($group['BLOCKS'] as $blockCode => $block):
                            if (empty($block)) continue;

                            $previewImg = Block::getPreviewImg($blockCode);
                            ?>
                            <div class="panel-block" data-code="<?=$blockCode?>">
                                <div class="panel-block-img">
                                    <?if($previewImg):?>
                                        <img src="<?=$previewImg?>" alt="<?=$block['NAME']?>">
                                    <?endif?>

                                    <div class="panel-block-btns">
                                        <div class="btn btn-primary panel-block-btn-add"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_LIB_BLOCK_ADD') ?></div>
                                        <div class="btn btn-primary panel-block-btn-replace"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_LIB_BLOCK_REPLACE') ?></div>
                                        <div class="panel-block-img-added theme-color"><?= Helper::svg('panel', 'added') ?></div>
                                    </div>

                                </div>
                                <div class="panel-block-title"><?=$blockCode?>. <?=$block['NAME']?></div>
                            </div>
                        <?endforeach?>

                    </div>
                <?endforeach?>

            <?endif?>
        <?endif?>

        <?if(!empty($arResult['PRESET_GROUPS']) && $canEditPresets):?>
            <div class="panel-tab" id="panelPresets"></div>

            <?foreach($arResult['PRESET_GROUPS'] as $groupCode => $group):
                $isPresetHighlighted = false;
                $isActiveTab = $rxLandingPanel == '#panelPresets' . $groupCode;
            ?>
                <div class="panel-tab panel-tab-with-footer <?if($isActiveTab):?>active<?endif?>" id="panelPresets<?=$groupCode?>">

                    <div class="panel-preset-blocks <?if(empty($group['PRESETS'])):?>empty<?endif?>">
                        <div class="row">
                            <?foreach($group['PRESETS'] as $presetCode => $preset):
                                $isAvailable = $preset['AVAILABLE'];
                            ?>
                            <div class="panel-block preset-block col-6 <?if(!$isAvailable):?>preset-block--disabled<?endif?>"
                                 data-code="<?=$presetCode?>" data-title="<?=$preset['TITLE']?>" data-detail="<?=$preset['DETAIL']?>" data-demo="<?=$preset['DEMO']?>">
                                <div class="panel-preset-img 
                                    <?if($isActiveTab && !$isPresetHighlighted): $isPresetHighlighted = true;?>theme-border<?endif?>">

                                    <img <?if(!$isActiveTab):?>src="" data-img-url="<?=$preset['PREVIEW']?>"<?else:?>src="<?=$preset['PREVIEW']?>"<?endif?>
                                         alt="<?=$preset['TITLE']?>">

                                    <?if(!$isAvailable):?>
                                        <a class="preset-block-warning theme-exclude-hover" <?if(!empty($preset['DEMO'])):?>href="<?= $preset['DEMO'] ?>" target="_blank"<?endif?>>
                                            <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESET_WRONG_VERSION', ['#VERSION#' => $preset['VERSION']]) ?>
                                        </a>
                                    <?else:?>
                                        <div class="panel-block-btns preset-block-btns">
                                            <?if($canEditBlocks):?>
                                            <div class="panel-preset-btn btn btn-transparent btn-preset-apply">
                                                <span class="btn-icon"><?= Helper::svg('panel', 'settings_apply_icon') ?></span>
                                                <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESET_BTN_APPLY') ?>
                                            </div>
                                            <?endif?>

                                            <?if (!empty($preset['DETAIL'])):?>
                                            <div class="panel-preset-btn btn btn-transparent btn-preset-show">
                                                <span class="btn-icon"><?= Helper::svg('panel', 'show') ?></span>
                                                <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESET_BTN_SHOW') ?>
                                            </div>
                                            <?endif?>

                                            <?if ($groupCode === 'CUSTOM' && $GLOBALS['USER']->CanDoOperation('rx_landing_preset_delete')): ?>
                                            <div class="panel-preset-btn btn btn-transparent btn-preset-delete">
                                                <span class="btn-icon"><?= Helper::svg('panel', 'trash') ?></span>
                                                <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESET_BTN_DELETE') ?>
                                            </div>
                                            <?endif?>
                                        </div>
                                    <?endif?>

                                </div>
                                <div class="panel-block-title"><?=$preset['TITLE']?></div>
                            </div>
                            <?endforeach?>
                        </div>
                        <div class="panel-tab-desc">
                            <div class="panel-tab-title"><?=Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESET_GROUP_EMPTY_TITLE')?></div>
                            <div class="panel-tab-text"><?=Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESET_GROUP_EMPTY_TEXT')?></div>
                        </div>
                    </div>

                    <div class="panel-block panel-preset-detail hidden" data-code="">
                        <div class="preset-detail-top">
                            <a class="preset-detail-back js-preset-back">
                                <?=Helper::svg('panel', 'back')?>
                            </a>
                            <div class="preset-detail-title"></div>
                            <div class="preset-detail-btn">
                                <a class="panel-preset-btn btn btn-transparent btn-preset-demo" href="#" target="_blank"
                                   title="<?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESET_BTN_DEMO') ?>"
                                   onclick="this.blur()">
                                    <span class="btn-icon"><?= Helper::svg('panel', 'show') ?></span>
                                </a>
                                <?if($canEditBlocks):?>
                                <div class="panel-preset-btn btn btn-primary btn-preset-apply">
                                    <span class="btn-icon"><?= Helper::svg('panel', 'settings_apply_icon') ?></span>
                                    <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESET_BTN_APPLY') ?>
                                </div>
                                <?endif?>
                            </div>
                        </div>
                        <div class="preset-detail-img">
                            <img src="" alt="">
                        </div>
                    </div>

                    <?if($groupCode === 'CUSTOM'):
                        $bShowUploadBtn = !$rxDemoMode && $GLOBALS['USER']->CanDoOperation('rx_landing_preset_upload');
                        $bShowDownloadBtn = $rxDemoMode || $GLOBALS['USER']->CanDoOperation('rx_landing_preset_download');
                        $btnColClass = $bShowUploadBtn && $bShowDownloadBtn ? 'col-6' : 'col-12';
                    ?>
                    <?if($bShowUploadBtn || $bShowDownloadBtn):?>
                    <div class="panel-tab-footer">
                        <div class="row">
                            <?if($bShowUploadBtn):?>
                            <div class="<?=$btnColClass?>">
                                <div class="custom-file">
                                    <input type="hidden" name="PRESET_UPLOAD_FILE" />
                                    <input type="file" class="custom-file-input js-upload-preset"
                                           accept=".rxlanding" id="panelPresetUpload" />
                                    <label class="custom-file-label btn btn-transparent" for="panelPresetUpload">
                                        <i class="btn-icon"><?= Helper::svg('panel', 'upload_icon') ?></i>
                                        <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESET_BTN_UPLOAD') ?>
                                    </label>
                                </div>
                            </div>
                            <?endif?>
                            <?if($bShowDownloadBtn):?>
                            <div class="<?=$btnColClass?>">
                                <button class="btn btn-block btn-transparent js-preset-download">
                                    <i class="btn-icon"><?= Helper::svg('panel', 'download_icon') ?></i>
                                    <?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_PRESET_BTN_DOWNLOAD') ?>
                                </button>
                            </div>
                            <?endif?>
                        </div>
                    </div>
                    <?endif?>
                    <?endif?>

                </div>
            <?endforeach?>
        <?endif?>

        <div class="panel-tab" id="panelSupport">
            <?php
            include_once 'include/support.php';
            ?>
        </div>

        <div class="panel-tab" id="panelUpdates"></div>

    </div>
    <div class="panel-close panel-close--shadow theme-bg-hover">
        <?=Helper::svg('panel', 'close')?>
    </div>

    <div class="panel-loading"><div class="spinner-grow theme-color"></div></div>
</div>

<?if($rxEditMode):?>
    <div id="panelDesign" class="panel panel-from-right">
        <form action="#" id="panelDesignForm">
            <input type="hidden" name="id">
            <input type="hidden" name="tabId">

            <div class="panel-header">
                <div class="panel-header-links">
                    <a href="#" class="theme-color-hover js-block-content"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_CONTENT_TITLE') ?></a>
                    <span class="active theme-exclude-hover"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_DESIGN_TITLE') ?></span>
                    <a href="#" class="theme-color-hover js-block-tabs"><?= Loc::getMessage('RX_PANEL_LANDING_TABS_TAB_TITLE'); ?></a>
                </div>
                <div class="panel-close theme-color-hover"><?=Helper::svg('panel', 'close')?></div>
            </div>
            <div class="panel-body js-simplebar">
                <div class="alert alert-danger"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_DESIGN_EMPTY') ?></div>
            </div>
        </form>
    </div>

    <div id="panelContent" class="panel panel-from-right">
        <form action="#" id="panelContentForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id">
            <input type="hidden" name="tabId">

            <div class="panel-header">
                <div class="panel-header-links">
                    <span class="active theme-exclude-hover"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_CONTENT_TITLE') ?></span>
                    <a href="#" class="theme-color-hover js-block-design"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_DESIGN_TITLE') ?></a>
                    <a href="#" class="theme-color-hover js-block-tabs"><?= Loc::getMessage('RX_PANEL_LANDING_TABS_TAB_TITLE'); ?></a>
                </div>
                <button class="btn btn-primary btn-mr" type="submit" disabled><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_FORM_APPLY') ?></button>
                <div class="panel-close theme-color-hover"><?=Helper::svg('panel', 'close')?></div>
            </div>
            <div class="panel-body js-simplebar">
                <div class="alert alert-danger"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_CONTENT_EMPTY') ?></div>
            </div>
        </form>
        <div class="panel-loading"><div class="spinner-grow theme-color"></div></div>
    </div>

    <div id="panelCard" class="panel panel-from-right">
        <form action="#" id="panelCardForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id">
            <input type="hidden" name="blockId">
            <input type="hidden" name="tabId">

            <div class="panel-header">
                <div class="panel-header-title">
                    <?= Loc::getMessage('RX_PANEL_LANDING_CARD_TAB_TITLE'); ?>
                </div>
                <button class="btn btn-primary btn-mr" type="submit" disabled><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_FORM_APPLY') ?></button>
                <div class="panel-close theme-color-hover"><?=Helper::svg('panel', 'close')?></div>
            </div>
            <div class="panel-body js-simplebar">
                <div class="alert alert-danger"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_CONTENT_EMPTY') ?></div>
            </div>
        </form>
        <div class="panel-loading"><div class="spinner-grow theme-color"></div></div>
    </div>

    <div id="panelSettings" class="panel panel-from-right">
        <form action="#" id="panelSettingsForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id">

            <div class="panel-header">
                <div class="panel-header-title">
                    <?= Loc::getMessage('RX_PANEL_LANDING_SETTINGS_TAB_TITLE'); ?>
                </div>
                <button class="btn btn-primary btn-mr" type="submit" disabled><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_FORM_APPLY') ?></button>
                <div class="panel-close theme-color-hover"><?=Helper::svg('panel', 'close')?></div>
            </div>
            <div class="panel-body js-simplebar"></div>
        </form>
        <div class="panel-loading"><div class="spinner-grow theme-color"></div></div>
    </div>

    <div id="panelTabs" class="panel panel-from-right">
        <form action="#" id="panelTabsForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id">
            <input type="hidden" name="tabId">

            <div class="panel-header">
                <div class="panel-header-links">
                    <a href="#" class="theme-color-hover js-block-content"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_CONTENT_TITLE') ?></a>
                    <a href="#" class="theme-color-hover js-block-design"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_DESIGN_TITLE') ?></a>
                    <span class="active theme-exclude-hover"><?= Loc::getMessage('RX_PANEL_LANDING_TABS_TAB_TITLE') ?></span>
                </div>
                <button class="btn btn-primary btn-mr" type="submit" disabled><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_FORM_APPLY') ?></button>
                <div class="panel-close theme-color-hover"><?=Helper::svg('panel', 'close')?></div>
            </div>
            <div class="panel-body js-simplebar"></div>
        </form>
        <div class="panel-loading"><div class="spinner-grow theme-color"></div></div>
    </div>

    <?if(!$rxDemoMode):?>
        <div id="panelGroup" class="panel panel-from-right">
            <form action="#" id="panelGroupForm" method="POST" enctype="multipart/form-data">
                <div class="panel-header">
                    <div class="panel-header-title">
                        <?= Loc::getMessage('RX_PANEL_LANDING_GROUP_TAB_TITLE'); ?>
                    </div>
                    <div class="panel-close theme-color-hover"><?=Helper::svg('panel', 'close')?></div>
                </div>
                <div class="panel-body js-simplebar">
                    <div class="alert alert-danger"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_CONTENT_EMPTY') ?></div>
                </div>
            </form>
            <div class="panel-loading"><div class="spinner-grow theme-color"></div></div>
        </div>

        <div id="panelVariant" class="panel panel-from-right">
            <form action="#" id="panelVariantForm" method="POST" enctype="multipart/form-data">
                <div class="panel-header">
                    <div class="panel-header-title">
                        <?= Loc::getMessage('RX_PANEL_LANDING_VARIANT_TAB_TITLE'); ?>
                    </div>
                    <button class="btn btn-primary btn-mr" type="submit" disabled><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_FORM_APPLY') ?></button>
                    <div class="panel-close theme-color-hover"><?=Helper::svg('panel', 'close')?></div>
                </div>
                <div class="panel-body js-simplebar">
                    <div class="alert alert-danger"><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_CONTENT_EMPTY') ?></div>
                </div>
            </form>
            <div class="panel-loading"><div class="spinner-grow theme-color"></div></div>
        </div>

        <div id="panelBlockCopy" class="panel panel-from-right">
            <form action="#" id="panelBlockCopyForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id">
                <input type="hidden" name="groupId">
                <input type="hidden" name="mode">

                <div class="panel-header">
                    <div class="panel-header-title">
                        <?= Loc::getMessage('RX_PANEL_LANDING_COPY_TAB_TITLE'); ?>
                    </div>
                    <div class="panel-close theme-color-hover"><?=Helper::svg('panel', 'close')?></div>
                </div>
                <div class="panel-body"> </div>
            </form>
            <div class="panel-loading"><div class="spinner-grow theme-color"></div></div>
        </div>

        <div id="panelMenu" class="panel panel-from-right">
            <form action="#" id="panelMenuForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type">

                <div class="panel-header">
                    <div class="panel-header-title">
                        <?= Loc::getMessage('RX_PANEL_LANDING_MENU_TAB_TITLE'); ?>
                    </div>
                    <button class="btn btn-primary btn-mr" type="submit" disabled><?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_FORM_APPLY') ?></button>
                    <div class="panel-close theme-color-hover"><?=Helper::svg('panel', 'close')?></div>
                </div>
                <div class="panel-body js-simplebar"> </div>
            </form>
            <div class="panel-loading"><div class="spinner-grow theme-color"></div></div>
        </div>
    <?endif?>

<?endif?>

<script>
    BX.ready(function(){
        BX.message({
            RX_PANEL_LANDING_TEMPLATE_JS_CONFIRM: '<?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_JS_CONFIRM') ?>',
            RX_PANEL_LANDING_TEMPLATE_JS_CP_CANCEL: '<?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_JS_CP_CANCEL') ?>',
            RX_PANEL_LANDING_TEMPLATE_JS_CP_APPLY: '<?= Loc::getMessage('RX_PANEL_LANDING_TEMPLATE_JS_CP_APPLY') ?>'
        });
    });

    function formatSize(size) {
        const map = [
            '<?=Loc::getMessage('FILE_SIZE_b')?>',
            '<?=Loc::getMessage('FILE_SIZE_Kb')?>',
            '<?=Loc::getMessage('FILE_SIZE_Mb')?>',
        ];
        let pos;
        for (pos = 0; size >= 1024 && pos < map.length; pos++) {
            size /= 1024;
        }
        return size.toFixed(2) + ' ' + map[pos];
    }
</script>
