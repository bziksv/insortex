<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var string $groupId
 * @var array $group
 * @var string $rxLandingPanel
 */

use Ranx\Landing\Config;
use Bitrix\Main\Web\Json;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<div class="panel-tab panel-settings panel-tab-with-footer <?if($rxLandingPanel == '#panelParams'.$groupId):?>active<?endif?>" id="panelParams<?=$groupId?>">

    <div class="panel-row flex-wrap">
        <?php
            $field = $group['OPTIONS']['FONT_FAMILY'];
            $field['NAME'] = 'FONT_FAMILY';
            $field['VALUE'] = Config::get('FONT_FAMILY');
            $field['FORM_GROUP_CLASSES'] = 'pr-0';

            include __DIR__ . '/field/selectbox.php';
        ?>
        <div class="w-100 pl-0 js-show-if collapse" data-show-if='<?= Json::encode($group['OPTIONS']['FONT_FAMILY_CUSTOM']['SHOW_IF']) ?>'>
            <?php
                $field = $group['OPTIONS']['FONT_FAMILY_CUSTOM'];
                $field['NAME'] = 'FONT_FAMILY_CUSTOM';
                $field['VALUE'] = Config::get('FONT_FAMILY_CUSTOM');

                include __DIR__ . '/field/string.php';
            ?>
        </div>
    </div>

    <?php
        $cats = Config::getTitleOptionCats();
    ?>
    <?foreach($cats as $prefix => $cat):
        $fontFamilyDefault = Config::get($prefix . '_FONT_FAMILY_DEFAULT');
        $fontFamily        = Config::get($prefix . '_FONT_FAMILY');
        $fontFamilyCustom  = Config::get($prefix . '_FONT_FAMILY_CUSTOM');
        $fontWeight        = Config::get($prefix . '_FONT_WEIGHT');
        $fontSize          = Config::get($prefix . '_FONT_SIZE');
        $lineHeight        = Config::get($prefix . '_LINE_HEIGHT');
    ?>
        <div class="panel-row flex-wrap">
            <div class="form-group">
                <label class="panel-font-cat-title"><?= $cat['TITLE'] ?> <a class="panel-font-edit theme-color-hover">
                        <?= Loc::getMessage('RX_PANEL_LANDING_PARAMS_FONT_EDIT') ?>
                    </a></label>
                <div class="panel-font-params-preview">
                    <?if($fontFamilyDefault):?>
                        <?= Loc::getMessage('RX_PANEL_LANDING_PARAMS_FONT_DEFAULT') ?>
                    <?elseif($fontFamilyCustom):?>
                        <?= Loc::getMessage('RX_PANEL_LANDING_PARAMS_FONT_CUSTOM') ?>
                    <?else:?>
                        <?= $group['OPTIONS'][$prefix . '_FONT_FAMILY']['LIST'][$fontFamily]['TITLE'] ?>
                    <?endif?>
                    <?= ucfirst($fontWeight) ?>
                    <?= $fontSize ?><?if($lineHeight):?>/<?= $lineHeight ?><?endif?>
                </div>
                <div class="panel-font-params">
                    <?php
                        $fieldCheckbox = $group['OPTIONS'][$prefix . '_FONT_FAMILY_DEFAULT'];
                        $fieldCheckbox['NAME'] = $prefix . '_FONT_FAMILY_DEFAULT';
                        $fieldCheckbox['VALUE'] = Config::get($prefix . '_FONT_FAMILY_DEFAULT');

                        $field = $group['OPTIONS'][$prefix . '_FONT_FAMILY'];
                        $field['NAME'] = $prefix . '_FONT_FAMILY';
                        $field['VALUE'] = Config::get($prefix . '_FONT_FAMILY');

                        include __DIR__ . '/field/selectbox.php';
                        unset($fieldCheckbox);
                    ?>
                    <div class="w-100 mb-3 js-show-if collapse" data-show-if='<?= Json::encode($group['OPTIONS'][$prefix . '_FONT_FAMILY_CUSTOM']['SHOW_IF']) ?>'>
                        <?php
                            $field = $group['OPTIONS'][$prefix . '_FONT_FAMILY_CUSTOM'];
                            $field['NAME'] = $prefix . '_FONT_FAMILY_CUSTOM';
                            $field['VALUE'] = Config::get($prefix . '_FONT_FAMILY_CUSTOM');

                            include __DIR__ . '/field/string.php';
                        ?>
                    </div>

                    <?php
                        $field = $group['OPTIONS'][$prefix . '_FONT_WEIGHT'];
                        $field['NAME'] = $prefix . '_FONT_WEIGHT';
                        $field['VALUE'] = Config::get($prefix . '_FONT_WEIGHT');

                        include __DIR__ . '/field/selectbox.php';
                    ?>
                    <div class="form-row">
                        <?php
                            $field = $group['OPTIONS'][$prefix . '_FONT_SIZE'];
                            $field['NAME'] = $prefix . '_FONT_SIZE';
                            $field['VALUE'] = Config::get($prefix . '_FONT_SIZE');
                            $field['FORM_GROUP_CLASSES'] = 'col-6';

                            include __DIR__ . '/field/string.php';

                            $field = $group['OPTIONS'][$prefix . '_LINE_HEIGHT'];
                            $field['NAME'] = $prefix . '_LINE_HEIGHT';
                            $field['VALUE'] = Config::get($prefix . '_LINE_HEIGHT');
                            $field['FORM_GROUP_CLASSES'] = 'col-6';

                            include __DIR__ . '/field/string.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?endforeach?>

    <div class="panel-row flex-wrap">
        <?php
            $field = $group['OPTIONS']['CARD_TITLE_FONT_WEIGHT'];
            $field['NAME'] = 'CARD_TITLE_FONT_WEIGHT';
            $field['VALUE'] = Config::get('CARD_TITLE_FONT_WEIGHT');
            $field['FORM_GROUP_CLASSES'] = 'pr-0';

            include __DIR__ . '/field/selectbox.php';
        ?>
    </div>

    <?php
    include 'params_tab_footer.php'
    ?>

</div>
