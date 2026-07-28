<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var string $groupId
 * @var array $group
 * @var string $rxLandingPanel
 * @var bool $rxDemoMode
 */

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
?>

<div class="panel-tab panel-settings panel-tab-with-footer <?if($rxLandingPanel == '#panelParams'.$groupId):?>active<?endif?>" id="panelParams<?=$groupId?>">

    <div class="panel-tab-desc">
        <div class="panel-tab-title"><?= $group['TITLE'] ?></div>

        <?if(!empty($group['NOTE'])):?>
            <div class="panel-tab-text"><?= $group['NOTE'] ?></div>
        <?endif?>
    </div>

    <?foreach($group['OPTIONS'] as $optionCode => $option):
        if ($option['TYPE'] !== 'string' || $option['DISABLED'] || $option['THEME'] === 'N'
            || ($rxDemoMode && $option['DEMO'] != 'Y')) continue;

        $optionVal = Config::get($optionCode);
    ?>
        <div class="panel-row flex-wrap panel-row-social">
            <div class="form-group">
                <input type="text" class="form-control" name="<?= $optionCode ?>" placeholder="<?= $option['TITLE'] ?>" value="<?= $optionVal ?>">
                <div class="form-social-icon"><?= Helper::svg('block/social', strtolower($optionCode)) ?></div>
            </div>
        </div>
    <?endforeach;?>

    <?php
        include 'params_tab_footer.php'
    ?>

</div>
