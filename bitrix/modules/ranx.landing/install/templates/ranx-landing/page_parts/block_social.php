<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;

$socials = Config::getSocials();
?>

<?if(!empty($socials)):?>
    <div class="block-socials">

        <?foreach($socials as $socialCode => $social):?>
            <a class="block-social" href="<?= $social['LINK'] ?>" target="_blank" rel="nofollow" title="<?= $social['TITLE'] ?>"><?= Helper::svg('block/social', strtolower($socialCode)) ?></a>
        <?endforeach?>

    </div>
<?endif?>
