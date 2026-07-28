<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();

$gridClasses = 'col-12 col-md-6 col-lg-4'.($arResult['COLS'] == 4 ? ' col-xl-3' : '');
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>

        <div class="block10-4-gallery block10-4-gallery--<?=$arResult['COLS']?>">
            <? if (!empty($arResult['ITEMS'])): ?>
                <div class="row block10-4-items masonry-grid">
                    <? foreach ($arResult['ITEMS'] as $i => $arItem): ?>
                        <div class="<?= $gridClasses ?> block10-4-item-card masonry-item">
                            <? if ($arItem['DETAIL_IMG']) : ?>
                                <a class="fancybox" href="<?=$arItem['DETAIL_IMG']?>"
                                   data-fancybox="gallery<?=$arResult['ID']?>"
                                   rel="gallery<?=$arResult['ID']?>"
                                   data-caption="<?=$arItem['PREVIEW_TEXT']?>"
                                >
                                    <div class="dark-hover"></div>
                                    <img class="lazy"
                                         alt="<?=$arItem['PROPS']['PICTURE_ALT']?>"
                                         title="<?=$arItem['PROPS']['PICTURE_TITLE']?>"
                                        <?if($useLazyLoad):?> data-src="<?=$arItem['IMG']?>"
                                        <?else:?> src="<?=$arItem['IMG']?>"<?endif?>
                                    />
                            </a>
                            <? endif ?>
                        </div>
                    <? endforeach ?>
                </div>
            <? endif ?>
        </div>

        <?= $arResult['BTN'] ?>
    </div>

<?= $arResult['BLOCK_END'] ?>

<script>
    $(document).ready(function () {
        const $grid = $('#block_<?=$arResult['ID']?> .masonry-grid');

        function initMasonry() {
            $grid.masonry({
                itemSelector: '.masonry-item',
                percentPosition: true,
            });
        }

        $grid.find('img.lazy').on('load', function() {
            initMasonry();
        }).each(function() {
            if(this.complete) {
                $(this).trigger('load');
            }
        });

        initMasonry();
    });
</script>
