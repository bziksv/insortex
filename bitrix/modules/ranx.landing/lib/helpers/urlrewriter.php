<?php


namespace Ranx\Landing\Helpers;

use Bitrix\Main\UrlRewriter as CUrlRewriter;

class UrlRewriter
{
    public static function update($siteId, $oldPath, $newPath)
    {
        if ($oldPath) {
            $currentRules = CUrlRewriter::getList($siteId, [
                'CONDITION' => '#^/'.trim($oldPath, '/ ').'/#',
            ]);
            $currentRule = reset($currentRules);
        }

        if (!empty($currentRule)) {
            CUrlRewriter::update(
                $siteId,
                [
                    'CONDITION' => '#^/'.trim($oldPath, '/ ').'/#',
                ],
                [
                    'CONDITION' => '#^/'.trim($newPath, '/ ').'/#',
                    'RULE' => NULL,
                    'ID'   => NULL,
                    'PATH' => '/'.trim($newPath, '/ ').'/index.php',
                    'SORT' => 100,
                ]
            );
        } else {
            self::remove($siteId, $newPath);
            CUrlRewriter::add(
                $siteId,
                [
                    'CONDITION' => '#^/'.trim($newPath, '/ ').'/#',
                    'RULE' => NULL,
                    'ID'   => NULL,
                    'PATH' => '/'.trim($newPath, '/ ').'/index.php',
                    'SORT' => 100,
                ]
            );
        }
    }

    public static function remove($siteId, $path)
    {
        CUrlRewriter::delete($siteId, [
            'CONDITION' => '#^/'.trim($path, '/ ').'/#',
        ]);
    }
}
