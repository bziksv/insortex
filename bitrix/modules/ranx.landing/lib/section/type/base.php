<?php


namespace Ranx\Landing\Section\Type;

use Bitrix\Iblock;
use Bitrix\Main\IO;
use Ranx\Landing\Helpers;
use Ranx\Landing\Landing;
use Ranx\Landing\SectionTable;
use Bitrix\Main\Localization\Loc;
use Ranx\Landing\Section\UrlTemplate;
use Ranx\Landing\Section\FieldValidator;

abstract class Base
{
    protected $arSection;
    protected $arSite;
    protected $newFields;
    protected $isImportantChange;

    public function __construct($sectionId = 0)
    {
        $this->newFields = [];
        $this->isImportantChange = false;

        if (empty($sectionId)) {
            $this->arSection = SectionTable::createObject();
            $this->set('TYPE', $this->getType());
        }
        else {
            $this->arSection = SectionTable::getByPrimary($sectionId)->fetchObject();
            $this->setSiteInfo($this->get('SITE_ID'));
        }
    }

    abstract protected function getType();

    protected function set($field, $value)
    {
        $this->newFields[$field] = $value;

        if (in_array($field, $this->getRequiredFields())) {
            $this->isImportantChange = true;
        }
    }

    protected function get($field)
    {
        return $this->newFields[$field] ?? $this->arSection[$field];
    }

    public function setSiteId($siteId)
    {
        if (!empty($this->arSection['SITE_ID'])) {
            return;
        }

        if (!FieldValidator::isValidSiteId($siteId)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_SITE_ID_INCORRECT'));
        }

        $this->set('SITE_ID', $siteId);
        $this->setSiteInfo($siteId);
    }

    public function setTitle($title)
    {
        $title = htmlspecialcharsEx(trim($title));
        if (empty($title)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_TITLE_IS_EMPTY'));
        }

        if ($this->isChanged('TITLE', $title)) {
            $this->set('TITLE', $title);
        }
    }

    public function setIblockId($iblockId)
    {
        if ($this->isChanged('IBLOCK_ID', $iblockId)) {
            $this->set('IBLOCK_ID', $iblockId);
        }
    }

    public function setLandingId($landingId)
    {
        if ($this->isChanged('LANDING_ID', $landingId)) {
            $this->set('LANDING_ID', $landingId);
        }
    }

    public function setPath($path, $isForceReplace = false)
    {
        if (empty($this->arSite)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_EMPTY_SITE_DATA'));
        }

        $path = $this->formatPath($path);
        $pathWithSiteDir = str_replace('//', '/', $this->arSite['DIR'].$path);
        $fullPath = $this->arSite['DOC_ROOT'].$pathWithSiteDir;

        if (!$this->isChanged('PATH', $pathWithSiteDir)) {
            return;
        }

        $this->checkPath($path, $pathWithSiteDir, $fullPath, $isForceReplace);
        $this->set('PATH', $pathWithSiteDir);
    }

    protected function checkPath($path, $pathWithSiteDir, $fullPath, $isForceReplace)
    {
        if (!FieldValidator::isCorrectPathFormat($path)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_PATH_WRONG'));
        }
        if (FieldValidator::containInvalidDir($path)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_PATH_WRONG'));
        }
        if ($name = FieldValidator::isExistPathInTable($pathWithSiteDir, $this->get('SITE_ID'))){
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_PATH_ALREADY_EXISTS_IN_TABLE', ['#NAME#' => $name]));
        }
        if (FieldValidator::isExistPath($fullPath, $isForceReplace)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_PATH_ALREADY_EXISTS'));
        }
    }

    public function setDomain($domain)
    {
        if (!empty($domain)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_DOESNT_SET_DOMAIN'));
        }
    }

    public function setOwnSettings($ownSettings)
    {
        if (!empty($ownSettings)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_DOESNT_SET_OWN_SETTINGS'));
        }
    }

    public function setRootMode($rootMode)
    {
        if (!FieldValidator::isAllowedRootMode($rootMode)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_NOT_ALLOWED_ROOT_MODE'));
        }

        $this->set('ROOT_MODE', $rootMode);
    }

    public function save($arOptions)
    {
        $this->changePath();
        $this->changeRootMode();

        if (empty($this->get('ID'))) {
            $this->saveNewSection();
            $this->createIblock();

            if ($arOptions['CREATE_DEFAULT_BLOCKS']) {
                $this->addDefaultBlocks();
            }
        }
        else {
            $this->saveFields(true);
        }

        $this->changeDomain();
        $this->changeOwnSettings();

        if ($this->isImportantChange) {
            $this->changeTitle();
            $this->changeSectionFiles();
            $this->changePathsToIblock();
        }

        $this->cleanCache();
        $this->saveFields();
        $this->isImportantChange = false;

        return $this->get('ID');
    }

    protected function cleanDirectory($fullPath)
    {
        if (empty($fullPath)) {
            return false;
        }

        $demoSectionDir = new IO\Directory($this->getDemoSectionPath());
        if (!$demoSectionDir->isExists()) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_DEMO_DIR_EXISTS_ERROR'));
        }

        $cleaningDir = new IO\Directory($fullPath);
        if (!$cleaningDir->isExists()) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_CLEANING_DIR_EXISTS_ERROR'));
        }

        $arDemoItems = $demoSectionDir->getChildren();
        foreach ($arDemoItems as $arDemoItem) {
            $fullItemPath = $fullPath.$arDemoItem->getName();

            if ($arDemoItem->isDirectory()) {
                IO\Directory::deleteDirectory($fullItemPath);
            }
            if ($arDemoItem->isFile()) {
                IO\File::deleteFile($fullItemPath);
            }
        }

        $isEmpty = empty($cleaningDir->getChildren());
        if ($isEmpty) {
            $cleaningDir->delete();
        }

        return true;
    }

    protected function changePath()
    {
        if (!$this->isChanged('PATH')) {
            return false;
        }

        $siteId = $this->get('SITE_ID');
        $oldPath = $this->arSection['PATH'];
        $newPath = $this->newFields['PATH'];
        $fullNewPath = $this->arSite['DOC_ROOT'].$newPath;

        $siteTemplateResult = Helpers\SiteTemplate::updatePathCondition(
            $siteId,
            ($oldPath == '/' ? $oldPath.'index.php' : $oldPath),
            ($newPath == '/' ? $newPath.'index.php' : $newPath)
        );
        if (!$siteTemplateResult) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_PATH_CANNOT_UPDATE_SITE_TEMPLATE'));
        }

        if (!empty($oldPath) && !FieldValidator::isMainPath($oldPath)) {
            $fullOldPath = $this->arSite['DOC_ROOT'].$oldPath;
            $this->cleanDirectory($fullOldPath);
        }

        if (!IO\Directory::isDirectoryExists($fullNewPath) && !IO\Directory::createDirectory($fullNewPath)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_PATH_MKDIR_ERROR'));
        }

        return true;
    }

    protected function saveNewSection()
    {
        foreach ($this->getRequiredFields() as $field) {
            if (!isset($this->newFields[$field])) {
                throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_MISSING_REQUIRED_FIELDS'));
            }

            $this->arSection->set($field, $this->newFields[$field]);
            unset($this->newFields[$field]);
        }

        $result = $this->arSection->save();
        if (!$result->isSuccess()) {
            $errorMsg = $result->getErrorMessages();
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_MISSING_CREATE').': '.$errorMsg[0]);
        }
    }

    protected function createIblock()
    {
        $createdIblockId = Helpers\Iblock::createLandingListIblock($this->get('ID'), $this->getAllValues());
        $this->set('IBLOCK_ID', $createdIblockId);

        $this->addDefaultProperties();
        \Ranx\Landing\Cache::ClearTagIBlock();
    }

    protected function addDefaultProperties(){}
    protected function addDefaultBlocks(){}
    protected function changeDomain(){}
    protected function changeOwnSettings(){}
    protected function changeRootMode() {}

    protected function changePathsToIblock()
    {
        $sectionIblockId = $this->get('IBLOCK_ID');
        $sectionPath = $this->get('PATH');

        // if we have site dir in the beginning, then cut it off
        if (mb_strpos($sectionPath, $this->arSite['DIR']) === 0) {
            $sectionPath = mb_substr($sectionPath, mb_strlen($this->arSite['DIR']));
        }
        $rootMode = $this->get('ROOT_MODE');

        $urlTemplates = UrlTemplate::get($rootMode, $sectionPath);
        Iblock\IblockTable::update($sectionIblockId, $urlTemplates);
    }

    protected function changeTitle()
    {
        $newName = $this->get('TITLE');
        $iblockId = $this->get('IBLOCK_ID');

        Helpers\Iblock::updateIblock($iblockId, ['NAME' => $newName]);
    }

    protected function getDemoSectionPath()
    {
        $docRoot = $this->arSite['DOC_ROOT'];
        $type = $this->get('TYPE');
        $rootMode = $this->get('ROOT_MODE');

        return realpath($docRoot.'/bitrix/modules/ranx.landing/demo/sections/'.$type.'/'.$rootMode);
    }

    protected function changeSectionFiles()
    {
        $docRoot = $this->arSite['DOC_ROOT'];
        $fullPath = $docRoot.$this->get('PATH');

        $demoFilesDir = $this->getDemoSectionPath();
        if (!CopyDirFiles($demoFilesDir, $fullPath)) {
            throw new \Exception(Loc::getMessage('RX_LANDING_SECTION_PATH_COPYFILES_ERROR'));
        }

        $arMacros = [];
        $arSectionValues = $this->getAllValues();
        foreach ($arSectionValues as $sectionKey => $sectionVal) {
            $arMacros['#RX_' . $sectionKey . '#'] = $sectionVal;
        }

        Helpers\Helper::replaceMacrosInFile($fullPath . '.left.menu_ext.php', $arMacros);
        Helpers\Helper::replaceMacrosInFile($fullPath . '.section.php', $arMacros);
        Helpers\Helper::replaceMacrosInFile($fullPath . 'index.php', $arMacros);
    }

    protected function saveFields($isRequiredOnly = false)
    {
        $requiredFields = $this->getRequiredFields();

        foreach ($this->newFields as $field => $value) {
            if ($isRequiredOnly && !in_array($field, $requiredFields)) {
                continue;
            }

            $this->arSection->set($field, $value);
            unset($this->newFields[$field]);
        }

        $this->arSection->save();
    }

    public function remove()
    {
        $this->removeDirectory();
        $this->removeSiteTemplate();
        $this->removeUrlRewrite();
        $this->removeLandings();
        $this->removeIblock();
        $this->removeDomain();
        $this->removeOwnSetting();
        $this->removeParams();

        SectionTable::delete($this->get('ID'));
    }

    protected function removeDirectory()
    {
        $path = $this->get('PATH');
        $fullPath = $this->arSite['DOC_ROOT'].$path;

        if (!empty($path) && !FieldValidator::isMainPath($path)) {
            $this->cleanDirectory($fullPath);
        }
    }

    protected function removeSiteTemplate()
    {
        $path = $this->get('PATH');
        Helpers\SiteTemplate::removePathCondition(
            $this->get('SITE_ID'),
            ($path == '/' ? $path.'index.php' : $path)
        );
    }

    protected function removeUrlRewrite()
    {
        $path = $this->get('PATH');
        if ($path == '/') {
            return;
        }

        Helpers\UrlRewriter::remove($this->get('SITE_ID'), $path);
    }

    protected function removeLandings()
    {
        $elementIds = $this->getElementIds();
        foreach ($elementIds as $elementId) {
            Landing::remove($elementId, Landing::MODE_ELEMENT);
        }

        $sectionIds = $this->getSectionIds();
        foreach ($sectionIds as $sectionId) {
            Landing::remove($sectionId, Landing::MODE_SECTION);
        }
    }

    protected function removeIblock()
    {
        Helpers\Iblock::removeIblock($this->get('IBLOCK_ID'));
    }

    protected function removeDomain() {}
    protected function removeOwnSetting() {}
    protected function removeParams() {}

    protected function isChanged($field, $newValue = null)
    {
        $oldValue = $this->arSection[$field];
        if (!isset($newValue)) {
            $newValue = $this->newFields[$field];
        }
        return $oldValue !== $newValue && isset($newValue);
    }

    protected function formatPath($path)
    {
        if (empty($path)) {
            return $path;
        }

        if (mb_strpos($path, '/') !== 0) {
            $path = '/' . $path;
        }

        $path = trim(mb_strtolower($path));
        if (mb_substr($path, -1) !== '/') {
            $path .= '/';
        }

        return $path;
    }

    protected function setSiteInfo($siteId)
    {
        $arSite = \Bitrix\Main\SiteTable::getList([
            'filter' => [
                'LID' => $siteId,
            ],
        ])->fetch();

        if (empty($arSite['DOC_ROOT'])) {
            $arSite['DOC_ROOT'] = $_SERVER['DOCUMENT_ROOT'];
        }

        $this->arSite = $arSite;
    }

    protected function cleanCache()
    {
        $cache = \Bitrix\Main\Application::getInstance()->getManagedCache();
        $cache->cleanDir('b_iblock');
        $cache->cleanAll();

        \Bitrix\Main\Data\Cache::clearCache('ranx_one_landing');
    }

    protected function getRequiredFields()
    {
        $result = [];
        $arMap = SectionTable::getMap();
        foreach ($arMap as $name => $field) {
            if ($field->isRequired()) {
                $result[] = $name;
            }
        }

        return $result;
    }

    protected function getAllValues()
    {
        $result = [];
        $arMap = SectionTable::getMap();
        foreach ($arMap as $name => $field) {
            $result[$name] = $this->get($name);
        }

        return $result;
    }

    protected function getElementIds()
    {
        $arLandings = Helpers\Iblock::getElementList([
            'filter' => ['IBLOCK_ID' => $this->get('IBLOCK_ID')],
            'select' => ['ID']
        ]);
        return array_filter(array_column($arLandings, 'ID'));
    }

    protected function getSectionIds()
    {
        $arSections = Helpers\Iblock::getSectionList([
            'filter' => ['IBLOCK_ID' => $this->get('IBLOCK_ID')],
            'select' => ['ID'],
            'order'  => ['LEFT_MARGIN' => 'DESC'] // correct order to delete
        ]);
        return array_filter(array_column($arSections, 'ID'));
    }
}
