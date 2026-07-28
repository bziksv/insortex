<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Ranx\Landing\Config;
global $APPLICATION;

$pageTitleAlign = Config::getPageTitleAlign();
?>
<section class="page-top">
    <div class="maxwidth-theme">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 pagetitle-wrap pagetitle-<?=$pageTitleAlign?>">

                <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","",Array(
                        "START_FROM" => "0", 
                        "PATH" => "", 
                        "SITE_ID" => SITE_ID, 
                    )
                );?>

                <h1 id="pagetitle"><?= $APPLICATION->ShowTitle(false); ?></h1>

            </div>
        </div>
    </div>
</section>
