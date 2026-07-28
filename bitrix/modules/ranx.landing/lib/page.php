<?php

namespace Ranx\Landing;

use Exception;
use Bitrix\Main\IO;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;
use Bitrix\Main\Composite;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Application;
use Ranx\Landing\Sale\Basket;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\AssetLocation;
use Ranx\Landing\Captcha\CaptchaManager;

/**
 * Class for working with page content
 */
class Page
{
    public static function addAssets()
    {
        $asset = Asset::getInstance();

        $asset->addCss(SITE_TEMPLATE_PATH . '/assets/vendor/bootstrap/css/bootstrap.min.css');
        $asset->addCss(SITE_TEMPLATE_PATH . '/assets/vendor/slick/slick.css');
        $asset->addCss(SITE_TEMPLATE_PATH . '/assets/vendor/fancybox/fancybox.min.css');
        $asset->addCss(SITE_TEMPLATE_PATH . '/assets/vendor/simplebar/simplebar.min.css');
        $asset->addCss(SITE_TEMPLATE_PATH . '/assets/vendor/selectric/selectric.css');

        if (Config::getFontFamily() === 'custom' && ($fontFamilyCustom = Config::getFontFamilyCustom())) {
            $asset->addString($fontFamilyCustom, true, AssetLocation::BEFORE_CSS);
            $asset->addString(self::getCustomFontStyles(), false, AssetLocation::AFTER_CSS);
        } else {
            $asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/fonts/font' . Config::getFontFamily() . '.css');
            $asset->addString(self::getFontStyles(), false, AssetLocation::AFTER_CSS);
        }
        self::addFontStyles();

        if (Config::isOrderEnabled()) {
            $asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/basket.css');
        }

        $asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/button.css');
        $asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/form.css');
        $asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/block.css');
        $asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/theme.css');
        $asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/modal.css');
        $asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/custom.css');
        
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/jquery/jquery.min.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/popper/popper.min.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/bootstrap/js/bootstrap.min.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/slick/slick.min.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/fancybox/fancybox.min.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/jquery-inputmask/jquery.inputmask.min.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/simplebar/simplebar.min.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/selectric/selectric.min.js');

        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/header.js');
        if (Config::isHeaderfixedEnabled()) {
            $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/headerfixed.js');
        }
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/headermenu.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/megamenu.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/mobilemenu.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/modal.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/form.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/block.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/main.js');
        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/rxswipe.js');
        if (CaptchaManager::isCaptchaEnabled()) {
            $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/rx_captcha.js');
        }
        if (Config::useSearch()) {
            $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/search.js');
        }
        if (Config::isOrderEnabled()) {
            $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/basket.js');
        }

        $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/custom.js');
    }

    public static function addOtherAssets()
    {
        $asset = Asset::getInstance();

        if (Config::isPanelEnabled()) {
            $asset->addCss(SITE_TEMPLATE_PATH . '/assets/vendor/jquery-ui/jquery-ui.min.css');
            $asset->addCss(SITE_TEMPLATE_PATH . '/assets/vendor/spectrum/spectrum.css');
            $asset->addCss(SITE_TEMPLATE_PATH . '/assets/vendor/jquery-ui/jquery-ui.min.css');
            $asset->addCss(SITE_TEMPLATE_PATH . '/assets/vendor/air-datepicker/datepicker.min.css');
            $asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/datepicker.css');

            $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/jquery-ui/jquery-ui.min.js');
            $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/spectrum/spectrum.js');
            $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/air-datepicker/datepicker.min.js');

            \CJSCore::Init(['translit']);
        }
        if (Config::useFontAwesome()) {
            $asset->addCss(SITE_TEMPLATE_PATH . '/assets/vendor/font-awesome/css/all.min.css');
            $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/font-awesome/js/all.min.js');
        }
        if (Config::isCookieConfirmationEnabled()) {
            $asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/cookies.css');
            $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/cookies.js');
        }
        if (Config::isUpButtonEnabled()) {
            $asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/upbutton.css');
            $asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/upbutton.js');
        }
        if (Config::isEnabledMasonryVendor()) {
            $asset->addJs(SITE_TEMPLATE_PATH . '/assets/vendor/masonry/masonry.pkgd.min.js');
        }
    }

    private static function getCallerId()
    {
        static $callerId;
        if (empty($callerId)) {
            $callerId = 0;
        }
        $callerId++;

        return $callerId;
    }

    public static function includeMaps()
    {
        $apiKey = Option::get('fileman', 'yandex_map_api_key');

        $asset = Asset::getInstance();
        $asset->addJs('https://api-maps.yandex.ru/2.1/?lang=ru_RU' . ($apiKey ? '&apikey=' . $apiKey : ''));
    }

    public static function showLogo()
    {
        $path = Config::getLogoPath();
        $lightPath = Config::getLightLogoPath();

        if (strlen($path)) {
            echo '<img src="' . $path . '" />';
            if (strlen($lightPath)) {
                echo '<img class="logo-light" src="' . $lightPath . '" />';
            } else {
                echo '<img class="logo-light" src="' . $path . '" />';
            }
        } else { // by default return our svg
            echo IO\File::getFileContents($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/assets/img/demo/logo.svg');
            echo IO\File::getFileContents($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/assets/img/demo/logo_light.svg');
        }
    }

    private static function getLogoPath()
    {
        $path = Config::getLogoPath();
        if (empty($path)) {
            $path = Config::getLightLogoPath();
        }
        if (empty($path)) {
            $path = SITE_TEMPLATE_PATH . '/assets/img/demo/logo.svg';
        }
        return $path;
    }

    public static function setOpengraphProperties()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $ogMetaProp = [
            'og:url' => $request->getRequestUri(),
            'og:title' => $GLOBALS['APPLICATION']->GetPageProperty('title'),
            'og:description' => $GLOBALS['APPLICATION']->GetPageProperty('description'),
            'og:image' => Page::getLogoPath(),
            'og:type' => 'website',
        ];

        $host = ($request->isHttps() ? 'https' : 'http').'://'.$request->getHttpHost();
        foreach ($ogMetaProp as $propName => $propValue) {
            $propValue = str_replace('//', '/', $propValue);
            if ($propName === 'og:image' || $propName === 'og:url') {
                $propValue = $host.$propValue;
            }
            $GLOBALS['APPLICATION']->AddHeadString('<meta property="'.$propName.'" content="'.$propValue.'" />', true);
        }
    }

    public static function showFavicon()
    {
        $path = Config::getFaviconPath();
        if (!$path) {
            $path = SITE_TEMPLATE_PATH . '/assets/img/favicon.ico';
        }

        echo '<link rel="shortcut icon" href="'.$path.'" type="image/x-icon"/>';
    }

    public static function addBodyClass($className)
    {
        if (!$className) 
            return false;

        $classes = $GLOBALS['APPLICATION']->GetProperty('body_classes');
        if ($classes) {
            $classes .= ' ' . $className;
        } else {
            $classes = $className;
        }

        $GLOBALS['APPLICATION']->SetPageProperty('body_classes', $classes);
        return true;
    }

    public static function addBodyAttribute($attribute)
    {
        if (!$attribute) {
            return false;
        }

        $attributes = $GLOBALS['APPLICATION']->GetProperty('body_attributes');
        $GLOBALS['APPLICATION']->SetPageProperty('body_attributes', $attributes.' '.$attribute);
        return true;
    }

    private static function getPartPath($partName)
    {
        $file = Config::getTemplateDir() . '/page_parts/'.$partName.'.php';
        Loc::loadMessages($file);
        return $file;
    }

    /**
     * This function isn't showing only header.
     * There are also fixed header, mobile header, mega menu, mobile menu.
     * Every part can have own css file.
     * 
     * @param $options types of parts to be included
     * @return void
     */
    public static function showHeader($options = [])
    {
        $options = [
            'HEADER_TYPE' => $options['HEADER_TYPE'] > 0 ? $options['HEADER_TYPE'] : Config::getDefaultHeaderType(),
            'HEADERFIXED_TYPE' => $options['HEADERFIXED_TYPE'] > 0 ? $options['HEADERFIXED_TYPE'] : Config::getDefaultHeaderFixedType(),
            'HEADERMOBILE_TYPE' => $options['HEADERMOBILE_TYPE'] > 0 ? $options['HEADERMOBILE_TYPE'] : Config::getDefaultHeaderMobileType(),
            'MEGAMENU_TYPE' => $options['MEGAMENU_TYPE'] > 0 ? $options['MEGAMENU_TYPE'] : Config::getDefaultMegaMenuType(),
            'MOBILEMENU_TYPE' => $options['MOBILEMENU_TYPE'] > 0 ? $options['MOBILEMENU_TYPE'] : Config::getDefaultMobileMenuType(),
        ];

        if (Config::isHeaderWide($options['HEADER_TYPE'])) {
            self::addBodyClass('header-is-wide');
        }
        if (Config::isHeaderMobileSticky()) {
            self::addBodyClass('headermobile-is-sticky');
        }
        if (Config::isBtnRounded()) {
            self::addBodyClass('btn-is-rounded');
        }

        if (Config::isHeaderTransparent()) {
            self::addBodyClass('js-header-is-transparent');
        }
        if (!Config::isHeaderfixedEnabled()) {
            unset($options['HEADERFIXED_TYPE']);
        }

        // common styles for header (with fixed and mobile ones)
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/assets/css/header/common.css');

        foreach ($options as $optionKey => $optionVal) {
            if (strpos($optionKey, '_TYPE') === false) continue;

            // just get option key without _TYPE suffix
            $typeStr = strtolower(substr($optionKey, 0, strpos($optionKey, '_TYPE')));

            $cssFilePath = SITE_TEMPLATE_PATH . '/assets/css/header/'. $typeStr .'_' . $optionVal . '.css';
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $cssFilePath)) {
                Asset::getInstance()->addCss($cssFilePath);
            }

            include_once $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/page_blocks/'.$typeStr.'_'.$optionVal.'.php';
        }
    }

    public static function showFooter()
    {
        $footerType = Config::get('FOOTER_TYPE');
        
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/assets/css/footer/common.css');
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/assets/css/footer/footer_'.$footerType.'.css');

        include_once $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/page_blocks/footer_'.$footerType.'.php';
    }

    public static function showCookiesBanner()
    {
        $callerId = self::getCallerId();
        $frame = new Composite\BufferArea('rx_cookies_'.$callerId);
        $frame->begin('');

        if (Config::isCookieConfirmationEnabled() && empty($_COOKIE['COOKIES_CONFIRMED'])) {
            include_once $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/page_blocks/cookies_1.php';
        }

        $frame->end();
    }

    /**
     * Includes css file with color theme, if exists.
     * And default theme if not.
     *
     * @param string|bool $themeCode
     */
    public static function includeColorTheme($themeCode = false)
    {
        if (Config::isDevMode() && defined('RX_LANDING_DEV_COLOR') && RX_LANDING_DEV_COLOR) {
            $filePath = SITE_TEMPLATE_PATH . '/themes/rx_dev/'.RX_LANDING_DEV_COLOR.'.css';
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath)) {
                Helpers\Color::generateThemeDevCss();
            }
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath)) {
                Asset::getInstance()->addCss($filePath, true);
                return;
            }
        }

        if (!$themeCode) {
            $themeCode = Config::getColorTheme();
        }

        $filePath = SITE_TEMPLATE_PATH . '/themes/' . $themeCode . '/color.css';
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath)) {
            Asset::getInstance()->addCss($filePath, true);
            return;
        }

        // else include default
        $themeCode = Config::getDefaultColorTheme();
        $filePath = SITE_TEMPLATE_PATH . '/themes/' . $themeCode . '/color.css';
        Asset::getInstance()->addCss($filePath, true);
    }

    /**
     * Includes page title and breadcrumbs.
     * 
     * @return void
     */
    public static function showPageTitle($pageTitleType = false)
    {
        if (!Config::isPageTitleEnabled()) {
            return;
        }
        if (intval($pageTitleType) <= 0) {
            $pageTitleType = Config::getDefaultPageTitleType();
        }
        include_once $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/page_blocks/pagetitle_'.$pageTitleType.'.php';
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/assets/css/pagetitle/pagetitle_'.$pageTitleType.'.css', true);
    }

    public static function showHeaderMenu($navClasses = '', $rootItemClasses = 'theme-color-hover', $wideMenuType = 'full')
    {
        $rootMenuType = Config::getRootMenuType();
        $childMenuType = Config::getChildMenuType();

        include self::getPartPath('header_menu');
    }

    public static function showHeaderPhones($classes = '', $showFirstPhoneDesc = false)
    {
        $phones = Config::getPhones();
        if (empty($phones))
            return;

        $callerId = self::getCallerId();
        $firstPhone = reset($phones);

        $showDropdown = false;
        if (count($phones) > 1 || !empty($firstPhone['DESC'])) {
            $showDropdown = true;
        }

        $frame = new Composite\BufferArea('rx_header_phones_' . $callerId);
        $frame->begin();
        include self::getPartPath('header_phones');
        $frame->end();
    }

    public static function showHeaderBasket($classes = '')
    {
        if (!Config::isOrderEnabled()) {
            return;
        }

        include self::getPartPath('header_basket');
    }

    public static function showBasketIcon($classes = '', $showTitle = false)
    {
        if (!Config::isOrderEnabled()) {
            return;
        }

        $basketLink = Config::getOrderPageLink();
        $itemsCount = Basket::getCount();

        include self::getPartPath('basket_icon');
    }

    public static function showHeaderSearch($classes = '')
    {
        if (!Config::useSearch()) {
            return;
        }

        $searchLink = Config::getSearchPageLink();
        include self::getPartPath('header_search');
    }

    public static function showHeaderCity($classes = '')
    {
        if (!($isRegionEnabled = Config::isRegionEnabled())) {
            return;
        }
        $cityName = Config::getCity();
        if (!strlen($cityName)) {
            return;
        }

        $callerId = self::getCallerId();
        $arRegions = Region::getRegions();
        $curRegion = Region::getCurrent();

        if (Config::useRegionBranches()) {
            $curBranch = Region::getCurrentBranch();
        }

        if (Config::isRegionByIpEnabled()) {
            $regionByIp = Region::getRegionByIp();

            $isRegionSelected = isset($_COOKIE['current_region']) && $_COOKIE['current_region'];
            $isGeoIpError = !isset($_SESSION['GEOIP']) || isset($_SESSION['GEOIP']['message']);

            $showRegionConfirm = !$isGeoIpError && $regionByIp && $regionByIp['ID'] !== $curRegion['ID']
                && !($isRegionSelected && $curRegion['ID'] == $_COOKIE['current_region']);
        }

        $frame = new Composite\BufferArea('rx_header_city_' . $callerId);
        $frame->begin();
        include self::getPartPath('header_city');
        $frame->end();
    }

    public static function showHeaderPhoneIcon($classes = '')
    {
        include self::getPartPath('header_phone_icon');
    }

    public static function showHeaderSocial($classes = '')
    {
        include self::getPartPath('header_social');
    }

    public static function showLinksMenu()
    {
        $rootMenuType = Config::getRootMenuType();

        $GLOBALS['APPLICATION']->IncludeComponent(
            'bitrix:menu',
            'links',
            [
                'ROOT_MENU_TYPE' => $rootMenuType,
                'MAX_LEVEL' => 3,
                'USE_EXT' => 'Y',
                'DELAY' => 'N',
                'ALLOW_MULTI_SELECT' => 'Y',
                'MENU_CACHE_TYPE' => 'A',
                'MENU_CACHE_TIME' => 3600,
                'MENU_CACHE_USE_GROUPS' => 'Y',
                'MENU_CACHE_GET_VARS' => '',
            ],
            false,
            ['HIDE_ICONS' => 'Y']
        );
    }

    public static function showMegamenu()
    {
        $rootMenuType = Config::getRootMenuType();

        $GLOBALS['APPLICATION']->IncludeComponent(
            'bitrix:menu',
            'megamenu',
            [
                'ROOT_MENU_TYPE' => $rootMenuType,
                'MAX_LEVEL' => 1,
                'USE_EXT' => 'Y',
                'DELAY' => 'N',
                'ALLOW_MULTI_SELECT' => 'Y',
                'MENU_CACHE_TYPE' => 'A',
                'MENU_CACHE_TIME' => 3600,
                'MENU_CACHE_USE_GROUPS' => 'Y',
                'MENU_CACHE_GET_VARS' => '',
            ],
            false,
            ['HIDE_ICONS' => 'Y']
        );
    }

    public static function showHeaderBurger($classes = '')
    {
        $classes = 'js-megamenu-open ' . $classes;
        include self::getPartPath('header_burger');
    }

    public static function showMobileHeaderBurger($classes = '')
    {
        $classes = 'js-mobilemenu-open ' . $classes;
        include self::getPartPath('header_burger');
    }

    public static function showMobileMenuPhones()
    {
        $phones = Config::getPhones();
        if (empty($phones))
            return;

        $firstPhone = reset($phones);
        $isShowPhoneBtn = Config::get('PHONE_BTN_SHOW');

        include self::getPartPath('mobilemenu_phones');
    }

    public static function showMobileMenuCity()
    {
        if (!($isRegionEnabled = Config::isRegionEnabled())) {
            return;
        }
        $cityName = Config::getCity();
        if (!strlen($cityName)) {
            return;
        }

        $callerId = self::getCallerId();
        $arRegions = Region::getRegions();
        $curRegion = Region::getCurrent();

        if (Config::useRegionBranches()) {
            $curBranch = Region::getCurrentBranch();
        }

        $frame = new Composite\BufferArea('rx_mobilemenu_city_' . $callerId);
        $frame->begin();
        include self::getPartPath('mobilemenu_city');
        $frame->end();
    }

    public static function showBlockTitle($arResult = [], $showBtn = true, $showDesc = true)
    {
        if (!empty($arResult['HIDE_TITLE'])) {
            return;
        }

        include self::getPartPath('block_title');
    }

    public static function showBlockTabs($arResult)
    {
        include self::getPartPath('block_tabs');
    }

    public static function showSmartFilter($arParams, $template = '')
    {
        if (empty($arParams)) {
            return;
        }

        include self::getPartPath('block_smart_filter');
    }

    public static function showBlockFilter($arParams = [])
    {
        include self::getPartPath('block_filter');
    }

    public static function getBtn($params = [])
    {
        $btnType = $params['BTN_TYPE'] ?? 'btn-primary';
        $btnSize = $params['BTN_SIZE'] ?? '';
        $btnText = $params['BTN_TEXT'] ?? '';
        $btnShow = $params['BTN_SHOW'] && $params['BTN_SHOW'] !== 'N';
        $btnLink = $params['BTN_LINK'] ?? '';
        $btnLinkType = $params['BTN_LINK_TYPE'] ?? 'internal';
        $btnGoal = $params['BTN_GOAL'] ?? '';
        $btnClass = $params['BTN_CLASS'] ?? '';
        $subject = $params['SUBJECT'] ?? '';
        $isInlineBtn = $params['INLINE'] ?? false;

        if (!$btnShow) {
            return '';
        }

        $attrs = '';
        $classes = $params['CLASSES'] ?? '';

        if (empty($isInlineBtn)) {
            $classes .= ' btn ' . $btnType . ' ' . $btnSize;
        }

        if ($subject) {
            $attrs .= 'data-subject="'.trim(strip_tags($subject)).'"';
        }

        if ($btnLinkType === 'form') {
            $classes .= ' js-form-modal';
            $attrs .= ' data-form-code="'.$btnLink.'"';
            $btnLink = '';
        }

        if (Config::useCssClasses() && $btnClass) {
            $classes .= ' ' . $btnClass;
        }

        if ($btnLinkType === 'external' && strpos($btnLink, 'http') === false) {
            $btnLink = 'https://' . $btnLink;
            $attrs .= ' target="_blank"';
        }

        $useYaGoals = Config::getYametrikaCounter() && Config::useYametrikaGoals() && $btnGoal;
        $useGaEvents = Config::getGanalyticsResource() && Config::useGanalyticsEvents() && $btnGoal;
        if ($useYaGoals || $useGaEvents) {
            $attrs .= ' data-metrics-click="' . $btnGoal . '"';
        }

        if ($btnLinkType === 'buy' && !empty($btnLink)) {
            try {
                $btnLink = Json::decode(htmlspecialchars_decode($btnLink));

                if (!empty($btnLink['DTYPE']) && $btnLink['DTYPE'] === 'percent') {
                    $btnLink['DISCOUNT'] .= '%';
                }

                $classes .= ' js-basket-add-custom';
                $attrs .= ' data-product-name="' . $btnLink['NAME'] . '"';
                $attrs .= ' data-product-price="' . $btnLink['PRICE'] . '"';
                $attrs .= ' data-product-discount="' . $btnLink['DISCOUNT'] . '"';
            } catch (Exception $e) {} finally {
                $btnLink = '';
            }
        }

        return '<a class="' . $classes . '" href="' . $btnLink . '"' . $attrs . '>' . $btnText . '</a>';
    }

    public static function showBtn($params = [])
    {
        echo self::getBtn($params);
    }

    public static function showHeaderBtn($classes = '')
    {
        self::showBtn([
            'CLASSES' => $classes,
            'BTN_SHOW' => Config::get('HEADER_BTN_SHOW'),
            'BTN_TYPE' => Config::get('HEADER_BTN_TYPE'),
            'BTN_SIZE' => Config::get('HEADER_BTN_SIZE'),
            'BTN_TEXT' => Config::get('HEADER_BTN_TEXT'),
            'BTN_LINK_TYPE' => Config::get('HEADER_BTN_LINK_TYPE'),
            'BTN_LINK' => Config::get('HEADER_BTN_LINK'),
            'BTN_GOAL' => Config::get('HEADER_BTN_GOAL'),
            'BTN_CLASS' => Config::get('HEADER_BTN_CLASS'),
        ]);
    }

    public static function showFooterBtn($classes = '')
    {
        self::showBtn([
            'CLASSES' => $classes,
            'BTN_SHOW' => Config::get('FOOTER_BTN_SHOW'),
            'BTN_TYPE' => Config::get('FOOTER_BTN_TYPE'),
            'BTN_SIZE' => Config::get('FOOTER_BTN_SIZE'),
            'BTN_TEXT' => Config::get('FOOTER_BTN_TEXT'),
            'BTN_LINK_TYPE' => Config::get('FOOTER_BTN_LINK_TYPE'),
            'BTN_LINK' => Config::get('FOOTER_BTN_LINK'),
            'BTN_GOAL' => Config::get('FOOTER_BTN_GOAL'),
            'BTN_CLASS' => Config::get('FOOTER_BTN_CLASS'),
        ]);
    }

    public static function showPhoneBtn($classes = '')
    {
        self::showBtn([
            'CLASSES' => $classes,
            'BTN_SHOW' => Config::get('PHONE_BTN_SHOW'),
            'BTN_TEXT' => Config::get('PHONE_BTN_TEXT'),
            'BTN_LINK_TYPE' => Config::get('PHONE_BTN_LINK_TYPE'),
            'BTN_LINK' => Config::get('PHONE_BTN_LINK'),
            'BTN_GOAL' => Config::get('PHONE_BTN_GOAL'),
            'BTN_CLASS' => Config::get('PHONE_BTN_CLASS'),
            'INLINE' => true,
        ]);
    }

    public static function showFooterCopyright($classes = '')
    {
        $copyText = Config::get('FOOTER_COPYRIGHT');
        if (empty($copyText)) {
            return;
        }

        include self::getPartPath('footer_copyright');
    }

    public static function showRanxLogo($classes = '')
    {
        if (!Config::isRanxCopyEnabled()) {
            return;
        }

        include self::getPartPath('footer_ranx');
    }

    public static function showFooterSocial()
    {
        include self::getPartPath('footer_social');
    }

    public static function showFooterPayoptions()
    {
        include self::getPartPath('footer_payoptions');
    }

    public static function showFooterMenu($classes = '')
    {
        $rootMenuType = Config::getRootMenuType();

        include self::getPartPath('footer_menu');
    }

    public static function showChats()
    {
        $code = '';
        $chats = Config::getGroupOptions('CHAT');

        foreach ($chats as $chatCode => $chat) {
            $chatVal = Config::get($chatCode);

            if (!empty($chatVal)) {
                $code .= $chatVal;
            }
        }

        echo $code;
    }

    public static function showMetrics()
    {
        $code = '';
        $metrics = Config::getGroupOptions('METRICS');

        foreach ($metrics as $metricCode => $metric) {
            if ($metric['TYPE'] !== 'text') continue;

            $metricVal = Config::get($metricCode);

            if (!empty($metricVal)) {
                $code .= $metricVal;
            }
        }

        echo $code;
    }

    public static function showBlockSocial()
    {
        include self::getPartPath('block_social');
    }

    /**
     * Some actions after template
     *
     * @return void
     */
    public static function postActions()
    {
        self::addOtherAssets();
        self::includeColorTheme();

        $settingId = Config::getSettingId();
        if (!empty($settingId)) {
            self::addBodyAttribute('data-setting-id="' . $settingId . '"');
        }

        \Ranx\Landing\Event::removeOtherEvents();
    }

    public static function includePartnerModules()
    {
        $modules = Config::getIncludeModules();
        foreach ($modules as $moduleId) {
            \Bitrix\Main\Loader::includeModule($moduleId);
        }
    }

    private static function getFontStyles()
    {
        $fontFamilyName = Config::getFontFamilyName();
        return $fontFamilyName ? '<style>body{font-family: "'.$fontFamilyName.'", Arial, sans-serif;}</style>' : '';
    }

    private static function getCustomFontStyles()
    {
        $customFontFamilyName = Config::getFontFamilyCustomName();
        return $customFontFamilyName ? '<style>body{font-family: "'.$customFontFamilyName.'", Arial, sans-serif;}</style>' : '';
    }

    private static function addFontStyles()
    {
        $asset = Asset::getInstance();
        $fontWeights = [
            'bold' => 700,
            'medium' => 600,
            'regular' => 400,
        ];
        $str = '<style>';

        $cats = Config::getTitleOptionCats();
        foreach($cats as $prefix => $cat) {
            $fontFamilyDefault = Config::get($prefix . '_FONT_FAMILY_DEFAULT');
            $fontFamily        = Config::get($prefix . '_FONT_FAMILY');
            $fontFamilyCustom  = Config::get($prefix . '_FONT_FAMILY_CUSTOM');
            $fontWeight        = Config::get($prefix . '_FONT_WEIGHT');
            $fontSize          = Config::get($prefix . '_FONT_SIZE');
            $lineHeight        = Config::get($prefix . '_LINE_HEIGHT');

            $str .= '.block .block-title ' . ($prefix === 'TITLE' ? '.block-title-text{' : '.block-' . strtolower($prefix) . '{');
            if (!$fontFamilyDefault && $fontFamilyCustom) {
                $asset->addString($fontFamilyCustom, true, AssetLocation::BEFORE_CSS);
                $customFontFamilyName = Config::getFontFamilyCustomName($fontFamilyCustom);
                $str .= 'font-family: "'.$customFontFamilyName.'", Arial, sans-serif;';
            } elseif (!$fontFamilyDefault && $fontFamily) {
                $asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/fonts/font' . $fontFamily . '.css');
                $fontFamilyName = Config::getFontFamilyName($fontFamily);
                $str .= 'font-family: "'.$fontFamilyName.'", Arial, sans-serif;';
            }
            if ($fontWeight && $fontWeights[$fontWeight]) {
                $str .= 'font-weight: ' . $fontWeights[$fontWeight] . ';';
            }
            if ($fontSize) {
                if (is_numeric($fontSize)) { // px by default
                    $fontSize .= 'px';
                }
                $str .= 'font-size: ' . $fontSize . ';';
            }
            if ($lineHeight) {
                $str .= 'line-height: ' . $lineHeight . ';';
            }
            $str .= '}';
        }

        $cardTitleFontWeight = Config::get('CARD_TITLE_FONT_WEIGHT');
        if ($cardTitleFontWeight && $fontWeights[$cardTitleFontWeight]) {
            $str .= '.block-el-title{font-weight: ' . $fontWeights[$cardTitleFontWeight] . ' !important;}';
        }

        $str .= '</style>';
        $asset->addString($str, false, AssetLocation::AFTER_CSS);
    }

    public static function setContentWidth()
    {
        self::addBodyClass('maxwidth-theme-'.Config::getContentWidth());
    }

    public static function showGoogleTagManager()
    {
        $arCode = Config::getGoogleTagManager();
        if (!is_array($arCode) || count($arCode) != 2) {
            return;
        }

        if (!empty($arCode[0])) {
            Asset::getInstance()->addString($arCode[0]);
        }
        if (!empty($arCode[1])) {
            echo $arCode[1];
        }
    }

    public static function includePartnerSupport()
    {
        $file = Loader::getLocal('php_interface/ranx_landing_support.php');
        if ($file) {
            include_once $file;
            return true;
        }
        return false;
    }

    public static function showUpButton()
    {
        if (Config::isUpButtonEnabled()) {
            include self::getPartPath('up_button');
        }
    }

    public static function includeYametrikaGoals()
    {
        if (($yaCounter = Config::getYametrikaCounter()) && Config::useYametrikaGoals()) {
            include self::getPartPath('metrics/yametika_goals');
        }
    }

    public static function includeGanalyticsEvents()
    {
        if (($gaResource = Config::getGanalyticsResource()) && Config::useGanalyticsEvents()) {
            include self::getPartPath('metrics/ganalytics_events');
        }
    }

    public static function showSearch()
    {
        if (!Config::useSearch()) {
            return;
        }

        include_once $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/page_blocks/search_1.php';
    }

    public static function showCounter($id)
    {
        include self::getPartPath('counter');
    }

    public static function showBasketBtn($id, $withCounter = true)
    {
        $basketLink = Config::getOrderPageLink();

        include self::getPartPath('basket_btn');
    }
}
