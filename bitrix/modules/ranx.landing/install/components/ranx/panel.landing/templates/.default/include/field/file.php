<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $field
 * @var string $templateFolder
 */

use Ranx\Landing\Config;
use Ranx\Landing\Helpers\File;
use Ranx\Landing\Helpers\Helper;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (empty($field)) {
    return;
}

$field['VALUE'] = $field['VALUE'] ?? [];
if (!is_array($field['VALUE'])) {
    $field['VALUE'] = (array) $field['VALUE'];
}

$field['BTN_TEXT'] = $field['BTN_TEXT'] ?? Loc::getMessage('RX_PANEL_LANDING_INCLUDE_FIELD_FILE_UPLOAD');
$field['MULTI'] = $field['MULTI'] ?? false;
$field['FILE_TYPE'] = File::formatExt($field['FILE_TYPE']);
$field['EXTS'] = array_filter(explode(', ', $field['FILE_TYPE']));

if (empty($field['ACCEPT'])) {
    if (!empty($field['FILE_TYPE'])) {
        $field['ACCEPT'] = $field['FILE_TYPE'];
    }
    elseif (!empty($field['MIME_TYPE'])) {
        $field['ACCEPT'] = $field['MIME_TYPE'].'/*';
    }
}

if (!is_numeric($field['MAX_FILE_SIZE']) || $field['MAX_FILE_SIZE'] <= 0) {
    $field['MAX_FILE_SIZE'] = false;
}
if (Config::isDemoMode()) {
    $field['MAX_FILE_SIZE'] = Config::getMaxUploadFileSizeInDemoMode();
}

?>

<div class="form-group rx-upload-files">
    <label><?=$field['TITLE']?></label>
    <div class="custom-file">
        <input type="file"
               class="custom-file-input js-upload-files"
               id="<?=$field['ID']?>"
               <?if(!empty($field['ACCEPT'])):?>accept="<?=$field['ACCEPT']?>" <?endif;?>
               <?if(!empty($field['EXTS'])):?>data-exts='<?=json_encode($field['EXTS'])?>' <?endif?>
               <?if(!empty($field['MIME_TYPE'])):?>data-mime="<?=$field['MIME_TYPE']?>" <?endif?>
               <?if(!empty($field['MAX_FILE_SIZE'])):?>data-max-size="<?=$field['MAX_FILE_SIZE']?>" <?endif?>
               <?if(!empty($field['MULTI'])):?>multiple<?endif?>/>
        <label class="custom-file-label btn btn-transparent" for="<?=$field['ID']?>">
            <?=$field['BTN_TEXT']?>
        </label>
    </div>

    <div class="form-group-pics-wrapper <?= $field['MULTI'] ? 'js-sortable' : '' ?>">
        <?foreach ($field['VALUE'] as $fileId):
            $fileInfo = \CFile::GetFileArray($fileId);
            if (empty($fileInfo)) {
                continue;
            }

            $fileSize = \CFile::FormatSize($fileInfo['FILE_SIZE']);
            $fileExt = pathinfo($fileInfo['ORIGINAL_NAME'], PATHINFO_EXTENSION);
            $fileSrc = $fileInfo['SRC'];
            $previewSrc = '';
            if (Helper::isImage($fileInfo['CONTENT_TYPE'])) {
                $img = \CFile::ResizeImageGet($fileId, ['width' => 80, 'height' => 80]);
                $previewSrc = $img['src'];
            }
        ?>
            <div class="form-group-pics">
                <input type="hidden" name="<?=$field['NAME']?>" value="<?=$fileId?>"/>
                <a href="<?=$fileSrc?>" target="_blank" class="form-group-preview-wrap"
                   title="<?= Loc::getMessage('RX_PANEL_LANDING_INCLUDE_FIELD_FILE_DOWNLOAD') ?>" download>
                    <?if (!empty($previewSrc)):?>
                        <img src="<?=$previewSrc?>" alt="">
                    <?else:?>
                        <?=Helper::svg('panel', 'file_preview')?>
                    <?endif?>
                </a>
                <div class="form-group-pics-info">
                    <div class="form-group-pics-info-ext"><?=strtoupper($fileExt)?></div>
                    <div class="form-group-pics-info-size"><?=$fileSize?></div>
                </div>
                <div class="pics-close js-pics-close theme-color-hover">
                    <?=Helper::svg('panel', 'remove')?>
                </div>
            </div>
        <?endforeach?>
    </div>

    <div class="form-group-pics template hidden">
        <input type="hidden" data-name="<?=$field['NAME']?>"/>
        <span class="form-group-preview-wrap theme-color">
            <?=Helper::svg('panel', 'file_preview')?>
        </span>
        <div class="form-group-pics-info">
            <div class="form-group-pics-info-ext"></div>
            <div class="form-group-pics-info-size"></div>
        </div>
        <div class="pics-close js-pics-close theme-color-hover">
            <?=Helper::svg('panel', 'remove')?>
        </div>
    </div>
</div>
