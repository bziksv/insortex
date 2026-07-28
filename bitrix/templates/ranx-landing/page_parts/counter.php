<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var int $id
 */

?>

<div class="counter">
    <div class="counter-minus"></div>
    <input class="counter-value" type="text" name="quantity" value="1" data-id="<?= $id ?>">
    <div class="counter-plus"></div>
</div>
