<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">
    <?= $arResult['BLOCK_TITLE'] ?>

    <?$GLOBALS['APPLICATION']->IncludeComponent(
        'ranx:order.landing',
        '',
        []
    );?>
</div>

<?= $arResult['BLOCK_END'] ?>
