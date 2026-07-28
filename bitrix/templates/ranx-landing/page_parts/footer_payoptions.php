<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;

$payoptions = Config::getPayoptions();
?>

<?if(!empty($payoptions)):?>
<div class="footer-payoptions">

    <?foreach($payoptions as $payoptionCode => $payoptionTitle):?>
    <div class="footer-payoption footer-payoption-<?=$payoptionCode?>" title="<?=$payoptionTitle?>"><?= Helper::svg('footer/payoptions', $payoptionCode) ?></div>
    <?endforeach?>

</div>
<?endif?>
