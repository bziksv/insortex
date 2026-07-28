<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>

<?if (empty($arResult['CATEGORIES'])) return;?>
<div class="search-items js-simplebar">
    <?foreach($arResult['CATEGORIES'] as $categoryId => $arCategory):?>
        <? if ($categoryId === 'all') continue; ?>

        <?foreach($arCategory['ITEMS'] as $i => $arItem):?>
            <? $imgSrc = $arResult['IMAGES'][$arItem['ITEM_ID']]; ?>
            <a class="search-item" href="<?=$arItem['URL']?>">
                <div class="maxwidth-theme item-wrap">
                    <?if (!empty($imgSrc)):?>
                        <img class="item-img" src="<?=$imgSrc?>" alt="" title="">
                    <?endif?>
                    <span class="item-name"><?=htmlspecialchars_decode($arItem['NAME'])?></span>
                </div>
            </a>
        <?endforeach;?>
    <?endforeach;?>
</div>
<?if (!empty($arResult['CATEGORIES']['all']['ITEMS'])):?>
    <? $item = reset($arResult['CATEGORIES']['all']['ITEMS']); ?>
    <a class="btn-all btn btn-block btn-transparent" href="<?=$item['URL']?>">
        <?=$item['NAME']?>
    </a>
<?endif?>
