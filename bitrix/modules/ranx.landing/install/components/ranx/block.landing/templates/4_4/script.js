$(window).ready(function() {
    $block                      = $('.block4-4');
    $service                    = $block.find('.service-container');
    $serviceInfoPaddingTop      = parseInt($block.find('.service-info').css('padding-top'));
    $serviceInfoPaddingBottom   = parseInt($block.find('.service-info').css('padding-bottom'));
    $serviceInfoPadding         = $serviceInfoPaddingTop + $serviceInfoPaddingBottom;
    $serviceTitleHeight         = $block.find('.service-title').outerHeight();
    $serviceMoreInfoHeight      = $block.find('.service-more-info').outerHeight();

    $service.height($serviceTitleHeight + $serviceMoreInfoHeight + $serviceInfoPadding);

});