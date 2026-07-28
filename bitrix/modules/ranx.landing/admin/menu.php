<?php

use Bitrix\Main\Loader,
    Bitrix\Main\EventManager,
    Bitrix\Main\Localization\Loc;

$em = EventManager::getInstance();
$em->addEventHandler('main', 'OnBuildGlobalMenu', 'onBuildGlobalMenuHandlerRanxLanding');

function onBuildGlobalMenuHandlerRanxLanding(&$arGlobalMenu, &$arModuleMenu){
    global $USER;

    if(!defined('RANX_LANDING_MENU_INCLUDED')){
        define('RANX_LANDING_MENU_INCLUDED', true);

        $moduleId = 'ranx.landing';

        Loc::loadMessages(__FILE__);
        $GLOBALS['APPLICATION']->SetAdditionalCss('/bitrix/css/'.$moduleId.'/menu.css'); // Asset::addCss() doesn't work

        if($USER->CanDoOperation('rx_landing_section_edit') || $USER->CanDoOperation('rx_landing_settings_edit')){

            if (!Loader::includeModule($moduleId)) {
                return false;
            }

            $arSections = [];
            if ($USER->CanDoOperation('rx_landing_section_edit')) {
                $sections = \Ranx\Landing\SectionTable::getList([
                    'order' => [
                        'TITLE' => 'ASC',
                    ],
                ])->fetchAll();

                $sort = 1;
                foreach ($sections as $section) {
                    $arSections[$section['SITE_ID']][] = [
                        'text' => $section['TITLE'] . ' ' . $section['PATH'],
                        'title' => $section['TITLE'] . ' ' . $section['PATH'],
                        'sort' => $sort,
                        'icon' => 'rx_landing_section',
                        'items_id' => 'ranx_landing_section_' . $section['ID'],
                        'url' => '/bitrix/admin/' . $moduleId . '_section.php?SECTION_ID=' . $section['ID'],
                    ];
                    $sort++;
                }

                if (count($arSections) > 1) {
                    $sort = 1;
                    foreach ($arSections as $siteId => $arSection) {
                        $arSections[$siteId] = [
                            'text' => $siteId,
                            'title' => $siteId,
                            'sort' => $sort,
                            //'icon' => 'rx_landing_sections',
                            'items_id' => 'ranx_landing_sections' . $siteId,
                            'items' => $arSection,
                        ];
                        $sort++;
                    }
                    $arSections = array_values($arSections);
                } elseif (!empty($arSections)) {
                    $arSections = reset($arSections);
                }
            }

            $items = [];
            if ($USER->CanDoOperation('rx_landing_settings_edit')) {
                $items[] = [
                    'text' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_SETTINGS_TEXT'),
                    'title' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_SETTINGS_TITLE'),
                    'sort' => 10,
                    'icon' => 'rx_landing_settings',
                    'url' => '/bitrix/admin/' . $moduleId . '_settings.php',
                    'items_id' => 'ranx_landing_settings',
                ];
            }
            if ($USER->CanDoOperation('rx_landing_section_edit')) {
                $items[] = [
                    'text' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_SECTIONS_TEXT'),
                    'title' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_SECTIONS_TITLE'),
                    'sort' => 20,
                    'icon' => 'rx_landing_sections',
                    'items_id' => 'ranx_landing_sections',
                    'items' => array_merge($arSections, [[
                        'text' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_SECTION_ADD_TEXT'),
                        'title' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_SECTION_ADD_TITLE'),
                        'sort' => $sort,
                        'icon' => 'rx_landing_section_add',
                        'items_id' => 'ranx_landing_section_add',
                        'url' => '/bitrix/admin/' . $moduleId . '_section.php',
                    ]]),
                ];
            }

            if ($USER->IsAdmin()) {
                $items[] = [
                    'text' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_GENERATE_FILES_TEXT'),
                    'title' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_GENERATE_FILES_TITLE'),
                    'sort' => 30,
                    'icon' => ' ',
                    'items_id' => 'ranx_landing_generate_files',
                    'items' => [
                        [
                            'text' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_GENERATE_ROBOTS_TEXT'),
                            'title' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_GENERATE_ROBOTS_TITLE'),
                            'sort' => 10,
                            'icon' => '',
                            'items_id' => 'ranx_landing_generate_robots',
                            'url' => '/bitrix/admin/'.$moduleId.'_generate_robots.php',
                        ],
                        [
                            'text' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_GENERATE_SITEMAP_TEXT'),
                            'title' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_GENERATE_SITEMAP_TITLE'),
                            'sort' => 20,
                            'icon' => '',
                            'items_id' => 'ranx_landing_generate_sitemap',
                            'url' => '/bitrix/admin/'.$moduleId.'_generate_sitemap.php',
                        ],
                    ],
                ];
            }

            if(!isset($arGlobalMenu['global_menu_ranx'])){
                $arGlobalMenu['global_menu_ranx'] = array(
                    'menu_id' => 'global_menu_ranx',
                    'text' => Loc::getMessage('GLOBAL_MENU_RANX_TEXT'),
                    'title' => Loc::getMessage('GLOBAL_MENU_RANX_TITLE'),
                    'sort' => 1000,
                    'items_id' => 'global_menu_ranx_items',
                    'items' => [
                        [
                            'text' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_TEXT'),
                            'title' => Loc::getMessage('GLOBAL_MENU_RANX_LANDING_TITLE'),
                            'sort' => 10,
                            'icon' => 'rx_landing',
                            'items_id' => 'ranx_landing',
                            'items' => $items,
                        ],
                    ],
                );
            }
        }
    }
}
