<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
/**
 * @var array $arResult
 */

use Ranx\Landing\Helpers\Helper;
use Ranx\Landing\Helpers\File;
?>

<?= $arResult['BLOCK_START'] ?>

    <div class="maxwidth-theme">

        <?= $arResult['BLOCK_TITLE'] ?>
        <?= $arResult['BLOCK_TABS'] ?>

        <?if(!empty($arResult['GROUPS'])):?>

        <?foreach ($arResult['GROUPS'] as $arGroup):?>
            <div class="row <?=$arGroup['CLASS']?>" <?=$arGroup['ATTR']?>>
                <?foreach($arGroup['ITEMS'] as $i => $arItem):
                    $fileInfo = \CFile::GetFileArray($arItem['PROPS']['FILE']);
                    $fileSize = \CFile::FormatSize($fileInfo['FILE_SIZE']);
                    list($tmp, $fileExt) = explode('.', $fileInfo['FILE_NAME']);
                    $isImg = in_array($fileExt, File::IMG_EXTS);
                ?>

                    <div class="document">
                        <div class="document-icon document-icon--<?= $fileExt ?>"></div>
                        <div class="document-info">
                            <a class="document-name <?if($isImg):?>fancybox<?endif?>"
                               href="<?= $fileInfo['SRC'] ?>" target="_blank" download><?= $arItem['NAME'] ?></a>
                            <div class="document-size"><?= $fileSize ?></div>

                            <?if(!empty($arItem['PREVIEW_TEXT'])):?>
                                <div class="document-desc"><?= $arItem['PREVIEW_TEXT'] ?></div>
                            <?endif?>

                        </div>
                        <a class="document-btn theme-bg-hover theme-border-hover <?if($isImg):?>fancybox<?endif?>"
                           href="<?= $fileInfo['SRC'] ?>" target="_blank" download>
                            <?= $isImg ? Helper::svg('block/doc_view') : Helper::svg('block/doc_download') ?>
                        </a>
                    </div>

                <?endforeach?>
            </div>
        <?endforeach?>

        <?= $arResult['BTN'] ?>

    <?endif?>

    </div>

<?= $arResult['BLOCK_END'] ?>
