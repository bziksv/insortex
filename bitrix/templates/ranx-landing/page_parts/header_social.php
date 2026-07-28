<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;

$socials = Config::getSocials();
?>

<?if(!empty($socials)):?>
    <div class="header-social <?=$classes?>">

        <?foreach($socials as $socialCode => $social):?>
            <a class="header-social-item theme-color-hover" href="<?= $social['LINK'] ?>" target="_blank" rel="nofollow" title="<?= $social['TITLE'] ?>"><?= Helper::svg('header/social', strtolower($socialCode)) ?></a>
        <?endforeach?>

    </div>
<?endif?>
