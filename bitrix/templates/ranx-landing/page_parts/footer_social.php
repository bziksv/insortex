<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;

$socials = Config::getSocials();
?>

<?if(!empty($socials)):?>
<div class="footer-social">

    <?foreach($socials as $socialCode => $social):?>
    <a class="footer-social-block theme-exclude-hover" href="<?= $social['LINK'] ?>" target="_blank" rel="nofollow" title="<?= $social['TITLE'] ?>"><?= Helper::svg('footer/social', strtolower($socialCode)) ?></a>
    <?endforeach?>

</div>
<?endif?>
