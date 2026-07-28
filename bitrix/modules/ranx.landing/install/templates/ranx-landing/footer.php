<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Ranx\Landing\Page;
use Ranx\Landing\Config;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

    <? Page::showUpButton(); ?>
    <? Page::showFooter(); ?>
    <? Page::showCookiesBanner(); ?>
    <? Page::showSearch(); ?>

    <?php
        include_once 'custom.php';
    ?>

    <!-- Modals start -->
    <?php
        include_once 'modals.php';
    ?>
    <!-- Modals end -->

    <?if(!Config::isPageSpeedTest()):?>
        <? Page::showChats(); ?>
        <? Page::showMetrics(); ?>
    <?endif?>

    <?php
        $phoneMask = Config::getPhoneMask();
    ?>
    <script>
        function initMasks()
        {
            <?if(!empty($phoneMask)):?>
            $('.phone').inputmask('<?=($phoneMask)?>', {
                'showMaskOnHover': false
            });
            <?endif?>
            $('.js-mask-date').inputmask('9{1,2}.9{1,2}.9999');
            $('.js-mask-integer').inputmask('integer', {
                rightAlign: false,
                placeholder: ''
            });
            $('.js-mask-interval-time').each(function () {
                let _this = $(this);
                _this.inputmask({
                    alias: 'datetime',
                    placeholder: '--:-- - --:--',
                    inputFormat: "HH:MM - HH:MM",
                    insertMode: false,
                    insertModeVisual: false,
                    showMaskOnHover: false,
                });
                _this.inputmask('setvalue', _this.attr('value'));
            });
        }
        $(document).on('change', '.js-mask-interval-time', function () {
            $(this).attr('value', $(this).val());
        });
    </script>

    <? Page::includeYametrikaGoals(); ?>
    <? Page::includeGanalyticsEvents(); ?>

    <?if(Config::isLazyLoadEnabled()):?>
        <script data-skip-moving="true" src="//cdn.jsdelivr.net/npm/intersection-observer@0.7.0/intersection-observer.js"></script>
        <script data-skip-moving="true" src="//cdn.jsdelivr.net/npm/vanilla-lazyload@15.1.1/dist/lazyload.min.js"></script>
        <script>
            var lazyLoadInstance = new LazyLoad({
                elements_selector: ".lazy"
            });
        </script>
    <?endif?>

    <?if(Config::isEnabledParallaxVendor()):?>
        <script src="https://cdn.jsdelivr.net/npm/simple-parallax-js@5.6.1/dist/simpleParallax.min.js"></script>
    <?endif?>

    <? Page::postActions(); ?>

</body>
</html>
