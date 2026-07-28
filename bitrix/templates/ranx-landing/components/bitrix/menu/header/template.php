<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

/**
 * @var CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\Menu;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

$hasMenuEditBtn = Config::isEditMode() && !Config::isDemoLanding() && $GLOBALS['USER']->CanDoOperation('rx_landing_section_edit');
?>

<?if(!empty($arResult) || $hasMenuEditBtn):?>
<div class="header-nav-wrap">
    <table class="header-nav <?=$arParams['CUSTOM_NAV_CLASSES']?>">
        <tr>

            <?if($hasMenuEditBtn):?>
                    <td class="menu-edit-item">
                    <a href="#" class="header-nav-edit js-panel-menu-edit active <?=$arParams['CUSTOM_ROOT_ITEM_CLASSES']?>"
                       data-type="<?= $arParams['ROOT_MENU_TYPE'] ?>" data-path="<?=$APPLICATION->GetCurDir()?>">
                        <span><?= Loc::getMessage('RX_LANDING_MENU_HEADER_TEMPLATE_EDIT') ?></span>
                    </a>
                </td>
            <?endif?>

            <?foreach($arResult as $arItem1):
                if ($arItem1['PARAMS']['HIDDEN'] === 'Y') continue;
            ?>
                <td>
                    <a class="<?=$arParams['CUSTOM_ROOT_ITEM_CLASSES']?> <?if($arItem1['SELECTED']):?>active<?endif?>" <?=Menu::formatLink($arItem1['LINK'])?>>
                        <span><?= $arItem1['TEXT'] ?></span>
                    </a>

                    <?if(!empty($arItem1['CHILD'])):?>
                        <?if($arItem1['PARAMS']['FULL_DROPDOWN'] === 'Y'):?>
                        <div class="header-nav-full-dropdown loading <?=$arParams['WIDE_MENU_CLASS']?>">
                            <div class="container-fluid pl-0 pr-0">
                                <ul class="row no-gutters justify-content-start">
                                    <?foreach ($arItem1['CHILD'] as $arItem2):?>
                                    <li class="col-<?=$arParams['COL']?>">
                                        <div class="header-nav-subitem <?=$arParams['IMAGE_CLASS']?>">
                                            <?if(!empty($arItem2['IMG']) && $arParams['IS_SHOW_IMAGE']):?>
                                            <a class="header-nav-img" <?=Menu::formatLink($arItem2['LINK'])?>><img src="<?=$arItem2['IMG']?>" alt="<?=$arItem2['TEXT']?>">
                                            </a>
                                            <?endif?>
                                            <div class="header-nav-wrapper">
                                                <a class="header-nav-subitem-link" <?=Menu::formatLink($arItem2['LINK'])?>>
                                                    <?=$arItem2['TEXT']?>
                                                </a>
                                                <?if (!empty($arItem2['CHILD'])):?>
                                                <ul>
                                                    <? foreach ($arItem2['CHILD'] as $arItem3): ?>
                                                        <li class="header-nav-subsubitem <?=$arParams['SUBITEMS_CLASS']?>">
                                                            <a class="header-nav-subsubitem-link" <?=Menu::formatLink($arItem3['LINK'])?>>
                                                            <?=$arItem3['TEXT']?>
                                                            </a>
                                                        </li>
                                                    <?endforeach?>
                                                </ul>
                                                <?endif?>
                                            </div>
                                        </div>
                                    </li>
                                    <?endforeach?>
                                </ul>
                            </div>
                        </div>
                        <?endif?>

                        <ul class="header-nav-dropdown <?=$arItem1['DROPDOWN_MODIFIER']?>">
                            <?foreach($arItem1['CHILD'] as $arItem2):?>
                            <li>
                                <a <?=Menu::formatLink($arItem2['LINK'])?> <?if($arItem2['SELECTED']):?>class="active"<?endif?>>
                                    <?=$arItem2['TEXT']?>
                                </a>
                                <?if(!empty($arItem2['CHILD'])):?>
                                <ul class="header-nav-dropdown">
                                    <?foreach($arItem2['CHILD'] as $arItem3):?>
                                    <li>
                                        <a <?=Menu::formatLink($arItem3['LINK'])?> <?if($arItem3['SELECTED']):?>class="active"<?endif?>>
                                            <?=$arItem3['TEXT']?>
                                        </a>
                                        <?if(!empty($arItem3['CHILD'])):?>
                                        <ul class="header-nav-dropdown">
                                            <?foreach($arItem3['CHILD'] as $arItem4):?>
                                            <li>
                                                <a <?=Menu::formatLink($arItem4['LINK'])?> <?if($arItem4['SELECTED']):?>class="active"<?endif?>>
                                                    <?=$arItem4['TEXT']?>
                                                </a>
                                            </li>
                                            <?endforeach?>
                                        </ul>
                                        <?endif?>
                                    </li>
                                    <?endforeach?>
                                </ul>
                                <?endif?>
                            </li>
                            <?endforeach?>
                        </ul>

                    <?endif?>

                </td>

            <?endforeach?>

            <td class="header-nav-more">
                <a class="header-nav-dots theme-fill-hover" href="#"><?=Helper::svg('header/dots')?></a>
                <ul class="header-nav-dropdown"></ul>
            </td>
        </tr>
    </table>

</div>
<?endif?>
