<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

/**
 * @var array $arResult
 */

use Ranx\Landing\Config;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<div class="panel-variant-body">
    <input type="hidden" name="id" value="<?= $arResult['ID'] ?>">

    <div class="form-group">
        <label><?= Loc::getMessage('RX_PANEL_LANDING_VARIANT_REGION_INCLUDE') ?></label>

        <?php
            $acItems = $arResult['REGION_INCLUDE'];
            $acName = 'REGION_INCLUDE';
            $acAction = 'searchRegions';

            include __DIR__ . '/../include/ac.php';
        ?>
    </div>

    <div class="form-group">
        <label><?= Loc::getMessage('RX_PANEL_LANDING_VARIANT_REGION_EXCLUDE') ?></label>

        <?php
            $acItems = $arResult['REGION_EXCLUDE'];
            $acName = 'REGION_EXCLUDE';
            $acAction = 'searchRegions';

            include __DIR__ . '/../include/ac.php';
        ?>
    </div>

    <?if(Config::useRegionBranches()):?>
        <div class="form-group">
            <label><?= Loc::getMessage('RX_PANEL_LANDING_VARIANT_BRANCH_INCLUDE') ?></label>

            <?php
                $acItems = $arResult['BRANCH_INCLUDE'];
                $acName = 'BRANCH_INCLUDE';
                $acAction = 'searchBranches';

                include __DIR__ . '/../include/ac.php';
            ?>
        </div>

        <div class="form-group">
            <label><?= Loc::getMessage('RX_PANEL_LANDING_VARIANT_BRANCH_EXCLUDE') ?></label>

            <?php
                $acItems = $arResult['BRANCH_EXCLUDE'];
                $acName = 'BRANCH_EXCLUDE';
                $acAction = 'searchBranches';

                include __DIR__ . '/../include/ac.php';
            ?>
        </div>
    <?endif?>
</div>

<script>
    initPanelAc();
</script>
