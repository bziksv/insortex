<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <?if(!empty($arResult['DESC']['TEXT'])):?>
		<div class="row">
			<div class="col-md-12 block15-2-content">
				<?= $arResult['DESC']['TEXT'] ?>
			</div>
		</div>
        <?endif?>

        <?if(!empty($arResult['BTN'])):?>
            <?= $arResult['BTN'] ?>
        <?endif?>

    </div>

<?= $arResult['BLOCK_END'] ?>
