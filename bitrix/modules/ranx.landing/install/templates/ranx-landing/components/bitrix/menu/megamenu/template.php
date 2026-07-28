<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Ranx\Landing\Helpers\Menu;

$this->setFrameMode(true);
?>

<?if(!empty($arResult)):?>

    <?foreach($arResult as $arItem):
        if ($arItem['PARAMS']['HIDDEN'] === 'Y') continue;
    ?>
        <a <?=Menu::formatLink($arItem['LINK'])?> class="<?if($arItem['SELECTED']):?>active<?endif?>"><?= $arItem['TEXT'] ?></a>
    <?endforeach?>

<?endif?>