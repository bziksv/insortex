<?php

use Bitrix\Main\Loader;
use Bitrix\Main\GroupTable;
use Bitrix\Main\EventManager;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\TaskTable;

Loc::loadMessages(__FILE__);

/**
 * RANX: Creator module installing and uninstalling
 *
 * @copyright 2020 RANX
 */

class ranx_landing extends CModule
{
    const PARTNER_CODE = 'ranx';
    const SOLUTION_CODE = 'landing';
    const TEMPLATE_NAME = 'ranx-landing';
    const EVENT_CLASS = 'Ranx\\Landing\\Event';

    const PHP_MIN_VERSION = '7.2';
    const BX_MIN_VERSION = '20';
    const EDITOR_GROUP = 'rx_landing_editor';

    public $componentsList = [
        'basket',
        'block.filter',
        'block',
        'form',
        'one',
        'order',
        'panel',
        'pub',
        'region.popup',
    ];
    public $defaultComponentsList = [
        'bitrix' => [
            'catalog.section.list' => [
                'rx_sections_1',
                'rx_sections_2',
            ],
            'catalog.smart.filter' => [
                'rx_compact',
            ],
            'news.list' => [
                'rx_section_1',
                'rx_section_2',
            ],
            'system.pagenavigation' => [
                'rx_simple',
            ],
            'search.page' => [
                'rx_search',
            ],
        ],
    ];

    public $MODULE_ID = 'ranx.landing';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS = 'Y';

    private $errors;

    public function __construct()
    {
        $arModuleVersion = array();

        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('RXLANDING_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('RXLANDING_MODULE_DESC');
        $this->PARTNER_NAME = Loc::getMessage('RXLANDING_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('RXLANDING_PARTNER_URI');
    }

    function DoInstall()
    {
        global $APPLICATION, $step;

        if (!$this->checkVersions()) {
            $APPLICATION->ThrowException(implode("<br>", $this->errors));
            return false;
        }

        $this->installDB();
        $this->installEvents();
        $this->installFiles();
        $this->installTasks();
        $this->installGroups();

        $APPLICATION->IncludeAdminFile(Loc::getMessage('RXLANDING_INSTALL_TITLE'), __DIR__ . '/step.php');
    }

    function DoUninstall()
    {
        global $APPLICATION, $step;

        $step = intval($step);
        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage('RXLANDING_UNINSTALL_TITLE'), __DIR__ . '/unstep1.php');
        } elseif ($step == 2) {
            $this->uninstallTasks();
            $this->uninstallFiles();
            $this->uninstallEvents();
            $this->uninstallDB([
                'savedata' => $_REQUEST['savedata'],
            ]);

            $APPLICATION->IncludeAdminFile(Loc::getMessage('RXLANDING_UNINSTALL_TITLE'), __DIR__ . '/unstep2.php');
        }
    }

    function installDB()
    {
        global $DB, $DBType;

        ModuleManager::registerModule($this->MODULE_ID);
        if (!Loader::includeModule($this->MODULE_ID)) {
            return false;
        }

        if (!\Ranx\Landing\SectionTable::isTableExists()) {
            \Ranx\Landing\SectionTable::createTable();
        }
        if (!\Ranx\Landing\Config::isDevMode()) {
            $this->registerClient();
        }

        Option::set($this->MODULE_ID, 'GROUP_DEFAULT_RIGHT', 'D');

        \CAgent::addAgent(
            'Ranx\Landing\Api\Instagram::refreshToken();',
            $this->MODULE_ID,
            'N',
            2592000 // 30 days
        );

        return true;
    }

    function uninstallDB($params = [])
    {
        global $DB, $DBType;

        if (!Loader::includeModule($this->MODULE_ID)) {
            return false;
        }

        \CAgent::removeModuleAgents($this->MODULE_ID);

        if (!array_key_exists('savedata', $params) || $params['savedata'] != 'Y') {
            \Ranx\Landing\SectionTable::dropTable();
        }

        Option::delete($this->MODULE_ID);
        ModuleManager::unRegisterModule($this->MODULE_ID);

        return true;
    }

    function installFiles()
    {
        CopyDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin', true);
        CopyDirFiles(__DIR__.'/components/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/components', true, true);
        CopyDirFiles(__DIR__.'/wizards/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards', true, true);
        CopyDirFiles(__DIR__.'/templates/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/templates', true, true);

        CopyDirFiles(__DIR__.'/css/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/css/'.self::PARTNER_CODE.'.'.self::SOLUTION_CODE, true, true);
        CopyDirFiles(__DIR__.'/js/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/js/'.self::PARTNER_CODE.'.'.self::SOLUTION_CODE, true, true);
        CopyDirFiles(__DIR__.'/images/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/images/'.self::PARTNER_CODE.'.'.self::SOLUTION_CODE, true, true);
        CopyDirFiles(__DIR__.'/services/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/services/'.self::PARTNER_CODE.'.'.self::SOLUTION_CODE, true, true);

        return true;
    }

    function uninstallFiles()
    {
        DeleteDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin');
        DeleteDirFilesEx('/bitrix/css/'.self::PARTNER_CODE.'.'.self::SOLUTION_CODE.'/');
        DeleteDirFilesEx('/bitrix/js/'.self::PARTNER_CODE.'.'.self::SOLUTION_CODE.'/');
        DeleteDirFilesEx('/bitrix/images/'.self::PARTNER_CODE.'.'.self::SOLUTION_CODE.'/');
        DeleteDirFilesEx('/bitrix/wizards/'.self::PARTNER_CODE.'/'.self::SOLUTION_CODE.'/');
        DeleteDirFilesEx('/bitrix/services/'.self::PARTNER_CODE.'.'.self::SOLUTION_CODE.'/');
        DeleteDirFilesEx('/bitrix/templates/'.self::TEMPLATE_NAME.'/');

        $this->uninstallDefaultTemplateComponents();
        $this->uninstallComponents();

        return true;
    }

    function uninstallDefaultTemplateComponents()
    {
        foreach ($this->defaultComponentsList as $partner => $components) {
            foreach ($components as $component => $templates) {
                foreach ($templates as $template) {
                    DeleteDirFilesEx('/bitrix/templates/.default/components/' . $partner . '/' . $component . '/' . $template . '/');
                }
            }
        }

        return true;
    }

    function uninstallComponents()
    {
        foreach ($this->componentsList as $component) {
            DeleteDirFilesEx('/bitrix/components/'.self::PARTNER_CODE.'/'.$component.'.'.self::SOLUTION_CODE.'/');
        }

        return true;
    }

    function installEvents()
    {
        $em = EventManager::getInstance();
        $em->registerEventHandler('main', 'OnEndBufferContent', $this->MODULE_ID, self::EVENT_CLASS, 'onEndBufferContent');
    }

    function uninstallEvents()
    {
        $em = EventManager::getInstance();
        $em->unRegisterEventHandler('main', 'OnEndBufferContent', $this->MODULE_ID, self::EVENT_CLASS, 'onEndBufferContent');
    }

    /**
     * Create special user group for landing editors.
     * And set up permissions.
     */
    function installGroups()
    {
        $group = GroupTable::getList(['filter' => ['STRING_ID' => self::EDITOR_GROUP]])->fetch();
        $groupId = $group['ID'];
        if (empty($group)) {
            $res = GroupTable::add([
                'ACTIVE' => 'Y',
                'C_SORT' => 100,
                'ANONYMOUS' => 'N',
                'IS_SYSTEM' => 'N',
                'NAME' => Loc::getMessage('RXLANDING_INSTALL_EDITOR_GROUP_NAME'),
                'DESCRIPTION' => Loc::getMessage('RXLANDING_INSTALL_EDITOR_GROUP_DESC'),
                'STRING_ID' => self::EDITOR_GROUP,
            ]);
            $groupId = $res->getId();
        }

        if (!$groupId) {
            return;
        }

        $task = TaskTable::getList(['filter' => ['MODULE_ID' => $this->MODULE_ID, 'LETTER' => 'X']])->fetch();
        if (!empty($task['ID'])) {
            \CGroup::SetModulePermission($groupId, $this->MODULE_ID, $task['ID']);
        }

        $moduleId = 'security';
        $task = TaskTable::getList(['filter' => ['MODULE_ID' => $moduleId, 'LETTER' => 'F']])->fetch();
        if (!empty($task['ID'])) {
            \CGroup::SetModulePermission($groupId, $moduleId, $task['ID']);
        }

        $GLOBALS['APPLICATION']->SetGroupRight('form', $groupId, 'W');

        $PERM = [];
        include $_SERVER['DOCUMENT_ROOT'] . '/bitrix/.access.php';
        $GLOBALS['APPLICATION']->SetFileAccessPermission(
            '/bitrix/admin/',
            array_merge(
                (array)($PERM['admin'] ?? []),
                [$groupId => 'R']
            )
        );
    }

    function checkVersions()
    {
        $phpVersion = phpversion();
        $bxVersion = ModuleManager::getVersion('main');

        $this->errors = [];

        if (!CheckVersion($phpVersion, self::PHP_MIN_VERSION)) {
            $this->errors[] = Loc::getMessage('RXLANDING_INSTALL_PHP_VERSION_ERORR', ['#VERSION#' => self::PHP_MIN_VERSION]);
        }

        if (!CheckVersion($bxVersion, self::BX_MIN_VERSION)) {
            $this->errors[] = Loc::getMessage('RXLANDING_INSTALL_BX_VERSION_ERORR', ['#VERSION#' => self::BX_MIN_VERSION]);
        }

        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }

    /**
     * Get module rights.
     * @return array
     */
    public function getModuleRightList()
    {
        return [
            'reference_id' => ['D', 'X'],
            'reference' => [
                '[D] ' . Loc::getMessage('RXLANDING_RIGHT_D'),
                '[X] ' . Loc::getMessage('RXLANDING_RIGHT_X')
            ],
        ];
    }

    /**
     * Get access tasks for module.
     * @return array
     */
    function getModuleTasks()
    {
        return [
            'rx_landing_deny' => [
                'LETTER' => 'D',
                'BINDING' => 'module',
                'OPERATIONS' => [],
            ],
            'rx_landing_full' => [
                'LETTER' => 'X',
                'BINDING' => 'module',
                'OPERATIONS' => [
                    'rx_landing_section_edit',
                    'rx_landing_settings_edit',
                    'rx_landing_block_edit',

                    'rx_landing_preset_download',
                    'rx_landing_preset_upload',
                    'rx_landing_preset_delete',
                ],
            ],
        ];
    }

    function registerClient()
    {
        \Ranx\Landing\Helpers\Helper::getDataByUrl('https://soft.landing-demo.ru/ss/reg.php?url=' . $_SERVER['HTTP_HOST']);
    }
}
