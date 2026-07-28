<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

/**
 * @var array $changes
 * @var array $replace
 * @var array $structures
 */

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<p><strong><?= Loc::getMessage('RX_WIZ_STEP_PREPARE_INSTALL_CHANGES') ?></strong></p>

<?foreach ($changes as $group => $actions):?>
    <p>
        <?= Loc::getMessage('RX_WIZ_STEP_PREPARE_INSTALL_'.$group.'_GROUP', $replace['GROUP'][$group] ?? []) ?>
        <ul>
            <?foreach ($actions as $action):?>
                <li>
                    <?= Loc::getMessage('RX_WIZ_STEP_PREPARE_INSTALL_'.$action.'_ACTION', $replace['ACTION'][$action] ?? []) ?>
                    <?= $structures[$action] ?>
                </li>
            <?endforeach?>
        </ul>
    </p>
<?endforeach?>
