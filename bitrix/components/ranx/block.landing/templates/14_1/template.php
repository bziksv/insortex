<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
use Ranx\Landing\Config;
$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

    <?if(!empty($arResult['IMG'])):?>
    <div class="block14-1-bg-image <?if($arResult['PICTURE_ALIGN'] == 'left'):?>text-right<?else:?>text-left<?endif?>">
        <img class="lazy" <?if($useLazyLoad):?>data-src="<?=$arResult['IMG']?>"
             <?else:?>src="<?=$arResult['IMG']?>"<?endif?> alt="<?= htmlspecialchars($arResult['NAME']) ?>">
    </div>
    <?endif?>

    <div class="maxwidth-theme">
        <div class="row">

            <div class="col-lg-6 col-md-12 <?if($arResult['PICTURE_ALIGN'] == 'left'):?>offset-lg-6<?endif?>">
                <?= $arResult['BLOCK_TITLE'] ?>
            </div>

        </div>

    </div>

<?= $arResult['BLOCK_END'] ?>

<script>
    $(document).ready(function(){
        let $block = $('#block_<?=$arResult['ID']?> .block');
        let blockPadding = $block.innerHeight() - $block.height();
        let $img = $block.find('img');

        $img.on('load', function () {
            let imgHeight = $block.find('img').height();
            $block.css('min-height', (blockPadding + imgHeight) + 'px');
        }).each(function () {
            if(this.complete) {
                $(this).trigger('load');
            }
        });
    });
</script>
