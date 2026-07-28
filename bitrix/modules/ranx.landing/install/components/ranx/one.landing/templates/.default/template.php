<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

/**
 * @var array $arParams
 * @var array $arResult
 */

use Ranx\Landing\Config;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Panel\Manager as PanelManager;

if (empty($arResult['LANDING'])) {
    return;
}

PanelManager::show($arParams['MODE'], $arResult['SECTION']['TYPE']);

$blocksOrderCount = (Config::isEditMode()) ? $arResult['MAX_SORT'] + 100 : $arResult['MAX_SORT'];

$blockGroups = Config::getBlockGroupNames($arResult['SECTION']['TYPE'], $arParams['MODE']);
?>

<?if(Config::isAnchorsEnabled() && !empty($arResult['BLOCKS'])): // add anchors menu
    $anchors = [];
    foreach ($arResult['BLOCKS'] as $arBlock) {
        if (!$arBlock['ANCHOR']) continue; 
        $anchors[] = $arBlock;
    }    
?>

    <?if(!empty($anchors)):?>
    <?$this->SetViewTarget('header_anchors');?>

    <div id="anchors">
        <div class="maxwidth-theme">
            <div class="anchors-list">

                <?foreach($anchors as $anchor):?>
                    <div><a href="#block_<?=$anchor['ID']?>" class="theme-after-bg"><span><?= $anchor['ANCHOR'] ?></span></a></div>
                <?endforeach?>

            </div>
        </div>
    </div>

    <?$this->EndViewTarget();?>
    <?endif?>

<?endif?>


<div id="blocks_wrapper" <?if(empty($arResult['BLOCKS'])):?>class="empty"<?endif?>
    data-landing-id="<?=$arResult['LANDING']['ID']?>" data-mode="<?= $arParams['MODE'] ?>"
     data-section-type="<?=$arResult['SECTION']['TYPE']?>">

    <?foreach($arResult['BLOCKS'] as $arBlock):?>
    <div class="block-wrap flex-order" id="block_<?=$arBlock['ID']?>" data-order="<?=$arBlock['SORT']?>" 
        data-id="<?=$arBlock['ID']?>" <?if(Config::isEditMode()):?>data-name="<?=Config::getBlockTitle($arBlock['CODE'])?>"<?endif?>>
        <?php
            $GLOBALS['APPLICATION']->IncludeComponent(
                'ranx:block.landing',
                $arBlock['CODE'],
                [
                    'DETAIL_ID' => $arBlock['ID'],
                    'IBLOCK_TYPE' => 'ranx_landing',
                    'IBLOCK_ID' => $arBlock['IBLOCK_ID'],
                    'ACTIVE' => $arBlock['ACTIVE'],
                    'CARDS_COUNT' => isset($arBlock['PROPS']['AUTO_COUNT']) ? $arBlock['PROPS']['AUTO_COUNT'] : Config::getSectionElementsCount(),
                ],
                false,
                [
                    'HIDE_ICONS' => 'Y', // hide hermitage
                ]
            );
        ?>
    </div>
    <?endforeach?>

</div>

<?if(Config::isEditMode()):?>
<div id="no_blocks">
    <div class="noblocks-inner">
        <div class="noblocks-title"><?= Loc::getMessage('RX_ONE_LANDING_TEMPLATE_ADD_FIRST_BLOCK') ?></div>

        <?if(!empty($blockGroups)):?>
        <div class="noblocks-cats">

            <?foreach($blockGroups as $groupId => $groupName):?>
            <div class="noblocks-cat" data-open-panel="#panelLib<?= $groupId ?>"><?= $groupName ?></div>
            <?endforeach?>

        </div>
        <?endif?>

        <div class="noblocks-btns">
            <button class="btn btn-primary btn-lg" data-open-panel="#panelLib"><?= Loc::getMessage('RX_ONE_LANDING_TEMPLATE_PANEL_LIB') ?></button>
            <button class="btn btn-transparent btn-lg" data-open-panel="#panelPresets"><?= Loc::getMessage('RX_ONE_LANDING_TEMPLATE_PANEL_PRESETS') ?></button>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.block-wrap').each(function(){
            let name = $(this).data('name');
            $(this).find('.block_edit_label').html(name);
        });
    });
</script>
<?endif?>

<style><?for($i = 1; $i <= $blocksOrderCount; $i++):?>.flex-order[data-order="<?=$i?>"]{order:<?=$i?>;}<?endfor?></style>
