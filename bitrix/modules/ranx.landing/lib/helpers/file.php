<?php


namespace Ranx\Landing\Helpers;

use Ranx\Landing\Helpers\Converter;

class File
{
    const IMG_EXTS = ['jpg', 'jpeg', 'gif', 'png', 'svg', 'ico'];

    public static function formatExt($extensions = '')
    {
        $extensions = str_replace('.', ',', $extensions);
        $arExtensions = explode(',', $extensions);
        $arExtensions = array_map('trim', $arExtensions);
        $arExtensions = array_filter($arExtensions);

        if (empty($arExtensions)) {
            return '';
        }

        $arExtensions = array_unique($arExtensions);
        $arExtensions = array_map(function ($val) { return '.'.$val; }, $arExtensions);
        return implode(', ', $arExtensions);
    }

    // crutch to correctly determine the mime-type of svg files
    public static function getDemo($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $types = Converter\MimeType2Ext::restore($ext);

        $mimeType = false;
        if (!empty($types)) {
            $mimeType = reset($types);
        }

        return \CFile::MakeFileArray($path, $mimeType);
    }

    public static function getImageFromBase64($data)
    {
        return Converter\File2base64::restore($data, 'image', self::IMG_EXTS);
    }

    public static function fromBase64($data, $allowedType = '', $allowedExts = [])
    {
        return Converter\File2base64::restore($data, $allowedType, $allowedExts);
    }

    public static function toBase64($fileId)
    {
        return Converter\File2base64::convert($fileId);
    }

    public static function isBase64($data)
    {
        return strpos($data, ';base64,') !== false;
    }

    public static function removeTemp()
    {
        $tmpDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/tmp/ranx.landing';
        $files = glob($tmpDir . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
