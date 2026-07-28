<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var string $yaCounter
 */

use Ranx\Landing\Config;
?>

<script>
    <?if(Config::useYametrikaDebug()):?>
        setCookie('_ym_debug', 1);
    <?else:?>
        deleteCookie('_ym_debug');
    <?endif?>

    $(document).ready(function(){
        $(document).on('click', '[data-metrics-click]', function () {
            const goal = $(this).data('metrics-click');
            submitJsGoal(goal);
        });
        $(document).on('rxFormSubmitted', function (e, data) {
            submitJsGoal(data.formCode);
        });
        $(document).on('click', 'a[href^="mailto:"]', function () {
            submitJsGoal('ranx_landing_email');
        });
        $(document).on('click', 'a[href^="tel:"]', function () {
            submitJsGoal('ranx_landing_phone');
        });

        function submitJsGoal(goalId) {
            if (typeof ym !== 'undefined') { // new version
                ym('<?=$yaCounter?>', 'reachGoal', goalId);
            } else if (typeof yaCounter<?=$yaCounter?> !== 'undefined') { // old
                yaCounter<?=$yaCounter?>.reachGoal(goalId)
            }
        }
    });
</script>
