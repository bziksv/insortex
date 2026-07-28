<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$count_item_between_cur_page = 2; // count numbers left and right from cur page
$count_item_dotted = 2; // count numbers to end or start pages

$arResult["nStartPage"] = $arResult["NavPageNomer"] - $count_item_between_cur_page;
$arResult["nStartPage"] = $arResult["nStartPage"] <= 0 ? 1 : $arResult["nStartPage"];
$arResult["nEndPage"] = $arResult["NavPageNomer"] + $count_item_between_cur_page;
$arResult["nEndPage"] = $arResult["nEndPage"] > $arResult["NavPageCount"] ? $arResult["NavPageCount"] : $arResult["nEndPage"];

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>


<?if($arResult['NavPageCount'] > 1):?>
<ul class="pagination justify-content-center">

    <?if($arResult["nStartPage"] > 1):?>
        <li class="page-item"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryString?>">1</a></li>
        <?if(($arResult["nStartPage"] - $count_item_dotted) > 1):?>
            <li class="page-item"><a class='point_sep'>...</a></li>
        <?elseif(($firstPage = $arResult["nStartPage"] - 1) > 1 && $arResult["nStartPage"] != 2):?>
            <li class="page-item"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$firstPage?>"><?=$firstPage?></a></li>
        <?endif;?>
    <?endif;?>
    <?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
        <?if($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
            <li class="page-item active"><a class="theme-bg-active theme-exclude-hover"><?=$arResult["nStartPage"]?></a></li>
        <?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
            <li class="page-item"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a></li>
        <?else:?>
            <li class="page-item"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a></li>
        <?endif;?>
        <?$arResult["nStartPage"]++;?>
    <?endwhile;?>
    <?if($arResult["nEndPage"] < $arResult["NavPageCount"]):?>
        <?if(($arResult["nEndPage"] + $count_item_dotted) < $arResult["NavPageCount"]):?>
        <li class="page-item"><a class='point_sep'>...</a></li>
        <?elseif(($lastPage = $arResult["nEndPage"] + 1) < $arResult["NavPageCount"]):?>
            <li class="page-item"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$lastPage?>"><?=$lastPage?></a></li>
        <?endif;?>
        <li class="page-item"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a></li>
    <?endif;?>

</ul>
<?endif?>
