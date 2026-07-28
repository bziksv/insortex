<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Helpers\Helper;
?>

<a class="header-search js-fixed-search <?= $classes ?>" href="<?= $searchLink ?>">
    <div class="header-search-icon"><?= Helper::svg('header/search') ?></div>
</a>
