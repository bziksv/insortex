<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Ranx\Landing\Block;
use Ranx\Landing\Preset;
use Ranx\Landing\Landing;
use Ranx\Landing\Helpers;
use Ranx\Landing\SectionTable;
use Ranx\Landing\Api\Repository;
use Ranx\Landing\Section\Manager as SectionManager;

$wizard =& $this->GetWizard();
$siteType = $wizard->GetVar('RX_SITE_TYPE');
$selectedPages = $wizard->GetVar('RX_PAGES');
$arPages = Repository::getPagesInfo();
$aMenuLinks = [];

if (!Loader::includeModule('iblock')) return;
if ($siteType != 'multi') return;
if (!defined('WIZARD_SITE_ID')) return;

foreach ($arPages as $code => $arPage) {
    $arPage['CODE'] = $code;
    setCreationMark($arPage, $selectedPages);
    $arPages[$code] = $arPage;
}

foreach ($arPages as $arPage) {
    if (empty($arPage['NEED_CREATE'])){
        continue;
    }

    // Create section
    switch ($arPage['TYPE']) {
        case 'subsections': {
            $type = SectionTable::TYPE_SECTION;
            $rootMode = SectionTable::ROOT_MODE_SECTIONS;
            break;
        }
        case 'section': {
            $type = SectionTable::TYPE_SECTION;
            $rootMode = SectionTable::ROOT_MODE_ELEMENTS;
            break;
        }
        case 'landing': {
            $type = SectionTable::TYPE_LANDING;
            $rootMode = SectionTable::ROOT_MODE_ELEMENT;
            break;
        }
        default: $type = '';
    }
    if (empty($type) || empty($rootMode)){
        continue;
    }

    $preset = $arPage['PRESET'];
    $hasPreset = !empty($preset) && $preset != 'empty';

    $newSectionId = SectionManager::add(
        [
            'TYPE' => $type,
            'SITE_ID' => WIZARD_SITE_ID,
            'TITLE' => $arPage['TITLE'],
            'ROOT_MODE' => $rootMode,
            'PATH' => formatCode($arPage['CODE']),
        ],
        [
            'PATH_FORCE_REPLACE' => true,
            'CREATE_DEFAULT_BLOCKS' => !$hasPreset,
        ]
    );
    if (empty($newSectionId)) {
        continue;
    }

    // Set preset
    $arSection = SectionTable::getByPrimary($newSectionId)->fetchObject();
    if ($hasPreset) {
        switch ($type) {
            case SectionTable::TYPE_SECTION:
                $landingId = $arSection['IBLOCK_ID'];
                break;
            case SectionTable::TYPE_LANDING:
                $landingId = $arSection['LANDING_ID'];
                break;
        }

        $mode = $arSection['ROOT_MODE'];
        if (isset($landingId) && isset($mode)) {
            Preset::apply($preset, $landingId, $mode, WIZARD_SITE_ID);
        }
    }

    buildSectionTree($arPage, $arSection['IBLOCK_ID'], WIZARD_SITE_ID);

    // Add link to menu
    $aMenuLinks[] = [
        $arSection['TITLE'],
        $arSection['PATH'],
        [],
        [
            'HIDDEN' => 'N',
            'FULL_DROPDOWN' => 'N',
        ],
        '',
    ];
}

// Save menu
if (Loader::includeModule('fileman')) {
    $menuType = \Ranx\Landing\Config::getRootMenuType();
    $arSite = \Bitrix\Main\SiteTable::getByPrimary(WIZARD_SITE_ID)->fetch();
    \CFileMan::SaveMenu([WIZARD_SITE_ID, $arSite['DIR'].'.'.$menuType.'.menu.php'], $aMenuLinks);
}


function setCreationMark(&$page, $selectedPages, $isParentSelected = false)
{
    $isSelected = $isParentSelected || $selectedPages[$page['CODE']] == 'Y';
    $childs = $page['CHILDS'] ?? [];

    $isSelectedChild = false;
    foreach ($childs as $code => $child) {
        $child['CODE'] = $code;
        $isSelectedChild = setCreationMark($child, $selectedPages, $isSelected) || $isSelectedChild;
        $page['CHILDS'][$code] = $child;
    }

    $page['NEED_CREATE'] = $isSelected || $isSelectedChild;
    return $page['NEED_CREATE'];
}

function buildSectionTree($page, $iblockId, $siteId, $parentSectionId = 0)
{
    $childs = $page['CHILDS'] ?? [];
    foreach ($childs as $code => $child) {
        if (empty($child['NEED_CREATE'])){
            continue;
        }

        $arFields = [
            'NAME' => $child['TITLE'],
            'CODE' => formatCode($code),
            'IBLOCK_ID' => $iblockId,
            'IBLOCK_SECTION_ID' => $parentSectionId,
        ];

        if ($child['TYPE'] === 'landing') {
            $newLandingId = Helpers\Iblock::addElement($arFields);
            $mode = Landing::MODE_ELEMENT;
        }
        if ($child['TYPE'] === 'section') {
            $newLandingId = Helpers\Iblock::addSection($arFields);
            $mode = Landing::MODE_SECTION;
            buildSectionTree($child, $iblockId, $siteId, $newLandingId);
        }

        if (empty($newLandingId) || empty($mode)) {
            continue;
        }

        $preset = $child['PRESET'];
        if (!empty($preset) && $preset != 'empty') {
            Preset::apply($preset, $newLandingId, $mode, $siteId);
        }
        else if ($child['TYPE'] == 'section') {
            Block::add($newLandingId, '20_2', [], $mode, $siteId);
        }
    }
}

function formatCode($code)
{
    return str_replace('_', '-', mb_strtolower($code));
}
