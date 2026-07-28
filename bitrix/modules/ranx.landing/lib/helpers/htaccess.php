<?php


namespace Ranx\Landing\Helpers;


class Htaccess
{
    private static function generateDomainRewriteRules($domain, $landingId, $path)
    {
        if (empty($domain) || empty($landingId) || empty($path)) {
            return false;
        }
        $begin = "\n\t#RANX_DOMAINS_LANDING_" . $landingId . "_BEGIN";

        $path = str_replace("//", "/", $path);
        if (strpos($path, "/") === 0) {
            $path = substr($path, 1);
            if (empty($path)) {
                return false;
            }
        }

        $domains = [$domain, 'www.'.$domain];
        $strRules = '';
        foreach ($domains as $domain) {
            $domainCondRule = '';
            $domainCondRule .= "\n\tRewriteCond %{HTTP_HOST} ^([^:]+)(:[0-9]+)?$";
            $domainCondRule .= "\n\tRewriteCond %1 ^" . $domain . "$ [NC]";

            //robots
            $strRules .= $domainCondRule;
            $strRules .= "\n\tRewriteCond %{REQUEST_FILENAME} /robots.txt$";
            $strRules .= "\n\tRewriteRule ^(.*)$ " . $path . "robots.txt [L,NC]";

            //sitemap
            $strRules .= $domainCondRule;
            $strRules .= "\n\tRewriteCond %{REQUEST_URI} /sitemap(.*)\.xml$";
            $strRules .= "\n\tRewriteRule ^sitemap(.*)\.xml$ " . $path . "sitemap$1.xml [L,NC]";

            $strRules .= $domainCondRule;
            $strRules .= "\n\tRewriteCond %{REQUEST_URI} ^/bitrix/urlrewrite.php [OR]";
            $strRules .= "\n\tRewriteCond %{REQUEST_URI} !^/bitrix";
            $strRules .= "\n\tRewriteCond %{REQUEST_FILENAME} .php$ [OR]";
            $strRules .= "\n\tRewriteCond %{REQUEST_FILENAME} -d [OR]";
            $strRules .= "\n\tRewriteCond %{REQUEST_FILENAME} -l";
            $strRules .= "\n\tRewriteRule ^(.*)$ " . $path . "index.php [L,NC]";
        }

        $end = "\n\t#RANX_DOMAINS_LANDING_" . $landingId . "_END";
        return $begin . $strRules . $end;
    }

    public static function addRewriteRules($rules, $documentRoot, $backup = true)
    {
        $filePath = $documentRoot.'/.htaccess';
        if (!file_exists($filePath)) {
            return false;
        }

        $file = file_get_contents($filePath);
        if (strpos($file, 'RewriteEngine On') === false) {
            return false;
        }

        if ($backup) {
            self::createBackup($documentRoot);
        }

        if (!empty($rules)) {
            $file = str_replace('RewriteEngine On', 'RewriteEngine On' . $rules, $file);
            file_put_contents($filePath, $file);
        }
        return true;
    }

    public static function updateDomainRewriteRules($domain, $landingId, $path, $documentRoot, $backup = true)
    {
        if ($backup) {
            self::createBackup($documentRoot);
        }

        self::removeDomainRewriteRules($landingId, $documentRoot, false);
        $rules = self::generateDomainRewriteRules($domain, $landingId, $path);
        if (!self::addRewriteRules($rules, $documentRoot, false)) {
            self::restoreBackup($documentRoot);
            return false;
        }

        return true;
    }

    public static function removeDomainRewriteRules($landingId, $documentRoot, $backup = true)
    {
        if (empty($landingId)) {
            return false;
        }

        $filePath = $documentRoot.'/.htaccess';
        if (!file_exists($filePath)) {
            return false;
        }
        $file = file_get_contents($filePath);

        $begin = "\n\t#RANX_DOMAINS_LANDING_" . $landingId . "_BEGIN";
        $beginPos = strpos($file, $begin);
        $end = "#RANX_DOMAINS_LANDING_" . $landingId . "_END";
        $endPos = strpos($file, $end);
        if (empty($beginPos) || empty($endPos)) {
            return false;
        }

        if ($backup) {
            self::createBackup($documentRoot);
        }

        $topPart = substr($file, 0, $beginPos);
        $bottomPart = substr($file, $endPos + strlen($end));
        file_put_contents($filePath, $topPart.$bottomPart);
        return true;
    }

    public static function createBackup($documentRoot) {
        $filePath = $documentRoot.'/.htaccess';
        if (!file_exists($filePath)) {
            return false;
        }
        return copy($filePath, $filePath.'_rxbackup');
    }

    public static function restoreBackup($documentRoot) {
        $filePath = $documentRoot.'.htaccess';
        $backupPath = $filePath.'_rxbackup';
        if (!file_exists($backupPath)) {
            return false;
        }

        return copy($backupPath, $filePath);
    }
}
