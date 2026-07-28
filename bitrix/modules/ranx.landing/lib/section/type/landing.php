<?php


namespace Ranx\Landing\Section\Type;

use Ranx\Landing\Config;
use Ranx\Landing\Helpers;
use Ranx\Landing\SectionTable;
use Ranx\Landing\Helpers\Domain;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Helpers\Htaccess;
use Ranx\Landing\Section\FieldValidator;

class Landing extends Main
{
    protected function getType()
    {
        return SectionTable::TYPE_LANDING;
    }

    protected function checkPath($path, $pathWithSiteDir, $fullPath, $isForceReplace)
    {
        Base::checkPath($path, $pathWithSiteDir, $fullPath, $isForceReplace);

        if (FieldValidator::isMainPath($path)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_PATH_MAIN_ONLY_MAIN'));
        }
    }

    public function setDomain($domain)
    {
        $domain = Domain::format($domain);
        if (!$this->isChanged('DOMAIN', $domain)) {
            return;
        }

        if (!empty($domain)) {
            if (!Domain::isValid($domain)) {
                throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_DOMAIN_CORRECTNESS_ERROR'));
            }
            if (!Domain::isUnique($domain)) {
                throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_DOMAIN_UNIQUENESS_ERROR'));
            }
        }

        $this->set('DOMAIN', $domain);
    }

    protected function changeDomain()
    {
        if (!$this->isChanged('DOMAIN')) {
            return;
        }

        Helpers\SiteTemplate::updateDomainCondition(
            $this->get('SITE_ID'),
            $this->newFields['DOMAIN'],
            $this->arSection['DOMAIN']
        );
        Htaccess::updateDomainRewriteRules(
            $this->newFields['DOMAIN'],
            $this->get('LANDING_ID'),
            $this->get('PATH'),
            $this->arSite['DOC_ROOT']
        );
    }

    protected function removeDomain()
    {
        $landingId = $this->get('LANDING_ID');
        $docRoot = $this->arSite['DOC_ROOT'];
        $siteId = $this->get('SITE_ID');
        $domain = $this->get('DOMAIN');

        Htaccess::removeDomainRewriteRules($landingId, $docRoot);
        Helpers\SiteTemplate::removeDomainCondition($siteId, $domain);
    }

    public function setOwnSettings($ownSettings)
    {
        $ownSettings = !empty($ownSettings);
        if ($this->isChanged('OWN_SETTINGS', $ownSettings)) {
            $this->set('OWN_SETTINGS', $ownSettings);
        }
    }

    protected function changeOwnSettings()
    {
        if (empty($this->newFields['OWN_SETTINGS'])) {
            return;
        }

        $id = $this->get('ID');
        $name = 'top_'.$id;
        Helpers\Menu::generateDefaultMenu($this->arSite['DOC_ROOT'].'/', $name);
        Config::setRootMenuType($name, $id, $this->get('SITE_ID'));
    }

    protected function removeOwnSetting()
    {
        Config::deleteAllOptionsForSection($this->arSection);

        $docRoot = $this->arSite['DOC_ROOT'];
        $fullMenuPath = $docRoot.'/.top_'.$this->get('ID').'.menu.php';
        if (file_exists($fullMenuPath)) {
            unlink($fullMenuPath);
        }
    }

    protected function removeDirectory()
    {
        Base::removeDirectory();
    }
}
