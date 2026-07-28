<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var string $gaResource
 */
?>

<script>
    $(document).ready(function () {
        const gaResource = '<?= $gaResource ?>';
        const isGanalytics4 = <?= json_encode(strpos((string)$gaResource, 'G-') === 0) ?>;

        $(document).on('click', '[data-metrics-click]', function () {
            const goal = $(this).data('metrics-click');
            sendGanalyticsEvent(goal, 'click');
        });
        $(document).on('rxFormSubmitted', function (e, data) {
            sendGanalyticsEvent(data.formCode, 'submit');
        });
        $(document).on('click', 'a[href^="mailto:"]', function () {
            sendGanalyticsEvent('ranx_landing_email', 'click');
        });
        $(document).on('click', 'a[href^="tel:"]', function () {
            sendGanalyticsEvent('ranx_landing_phone', 'click');
        });

        function sendGanalyticsEvent(name, action) {
            if (typeof gtag !== 'undefined') { // gtag.js
                let params = {'send_to': gaResource};

                if (isGanalytics4 === true) {
                    gtag('event', name, params); // GA4
                } else if (isGanalytics4 === false) {
                    params['event_category'] = name;
                    gtag('event', action, params);
                }
            } else if (typeof ga !== 'undefined') { // analytics.js
                ga('send', 'event', name, action);
            }
        }
    });
</script>
