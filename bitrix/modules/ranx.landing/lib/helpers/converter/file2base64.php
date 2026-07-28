<?php


namespace Ranx\Landing\Helpers\Converter;

use Ranx\Landing\Helpers\Converter;

class File2base64
{
    const RX_EXT_PREFIX = 'extension:';

    public static function convert($id)
    {
        if (empty($id) || intval($id) <= 0) {
            return '';
        }

        $fileInfo = \CFile::GetFileArray($id);
        if (empty($fileInfo)) {
            return '';
        }

        $filePath = $_SERVER['DOCUMENT_ROOT'] . $fileInfo['SRC'];
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeType = self::detectMimeType($fileInfo['CONTENT_TYPE'], $ext);
        if (empty($mimeType)) {
            return '';
        }

        $data = file_get_contents($filePath);
        return self::RX_EXT_PREFIX.$ext.';data:'.$mimeType.';base64,'.base64_encode($data);
    }

    public static function restore($data, $allowedType = '', $allowedExts = [])
    {
        $matches = [];
        $originalExt = self::cutOriginalExt($data);

        if (preg_match('/^data:([a-z]+)\/([a-z-+.]+);base64,/', $data, $matches) || preg_match('/^dat[\s]a:([a-z]+)\/([a-z-+.]+);base64,/', $data, $matches)) { // 2nd condition needs for validate files bitrix web anitvirus
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($matches[1]);
            $subtype = strtolower($matches[2]);
            $mimeType = $type.'/'.$subtype;
            $ext = !empty($originalExt) ? $originalExt : Converter\MimeType2Ext::convert($mimeType);

            if (!empty($allowedType) && $allowedType != $type ||
                !empty($allowedExts) && !in_array($ext, $allowedExts)) {
                return 0;
            }

            $data = base64_decode($data);
        } else {
            return 0;
        }

        $arFile = [];
        if ($data && $ext) {
            $tmpDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/tmp';

            if (!file_exists($tmpDir)) {
                mkdir($tmpDir);
            }
            if (!file_exists($tmpDir . '/ranx.landing')) {
                mkdir($tmpDir . '/ranx.landing');
            }

            $uniqId = uniqid();
            $tmpFileName = $tmpDir . '/ranx.landing/' . $uniqId . '.' . $ext;
            file_put_contents($tmpFileName, $data);

            $arFile = \CFile::MakeFileArray($tmpFileName, $mimeType);
        }

        return $arFile ? $arFile : 0;
    }

    private static function detectMimeType($bxMimeType, $ext)
    {
        $detectedExt = Converter\MimeType2Ext::convert($bxMimeType);
        if ($detectedExt == $ext) {
            return $bxMimeType;
        }

        if (Converter\MimeType2Ext::isContain($bxMimeType)) {
            return $bxMimeType;
        }

        $mimeTypes = Converter\MimeType2Ext::restore($ext);
        if (!empty($mimeTypes)) {
            return reset($mimeTypes);
        }

        return false;
    }

    private static function cutOriginalExt(&$data)
    {
        if (strpos($data, self::RX_EXT_PREFIX) !== 0) {
            return false;
        }

        [$extPrefix, $data] = explode(';', $data, 2);
        return substr($extPrefix, strlen(self::RX_EXT_PREFIX));
    }
}
