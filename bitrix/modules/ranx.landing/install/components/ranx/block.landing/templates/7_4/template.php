<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

$useLazyLoad = Config::isLazyLoadEnabled();
?>

<?= $arResult['BLOCK_START'] ?>

<div class="maxwidth-theme">
    <?=$arResult['BLOCK_TITLE']?>

    <div class="row cards">
        <?foreach ($arResult['ITEMS'] as $arItem):
			$isWide = $arItem['PROPS']['CHECK'] === 'Y';
		?>
			<div class="<?if(!$isWide):?>col-xl-<?= (12 / $arResult['COLS']) ?> col-md-6<?endif?> col-12">
				<div class="card <?if($isWide):?>card--wide<?endif?>">
                    <div class="card-wrap">
                        <div class="card-preview lazy" <?if($useLazyLoad):?>data-bg="<?=$arItem['IMG_SRC']?>"
                             <?else:?>style="background-image: url('<?=$arItem['IMG_SRC']?>');"<?endif?>>

							<img class="lazy" <?if($useLazyLoad):?>data-src="<?=$arItem['IMG_SRC']?>"<?else:?>src="<?=$arItem['IMG_SRC']?>"<?endif?>>

							<div class="card-shadow"></div>
							<?if (!empty($arItem['LINK'])):?><div class="cover"></div><?endif?>
                        </div>

						<div class="card-info">
							<div class="card-name"><?=$arItem['NAME']?></div>
							<?if(!empty($arItem['PREVIEW_TEXT'])):?><div class="card-desc"><?=$arItem['PREVIEW_TEXT']?></div><?endif?>
							<?if (!empty($arItem['LINK'])):?>
								<a class="card-link <?=$arItem['LINK']['CLASS']?>" <?=$arItem['LINK']['ATTRS']?>>
									<?=$arItem['PROPS']['LINK_TEXT']?>
								</a>
							<?endif?>
						</div>

                    </div>
                </div>
            </div>
        <?endforeach?>
    </div>

</div>

<?= $arResult['BLOCK_END'] ?>
