$(document).ready(function (e) {
    $(document).on('click', '.service-anchor-link', function () {
        var scrollTo = '[data-name="' + $(this).attr('data-href') + '"]';
        var headerFixedHeight = $("#headerfixed").height() || 0;
        if ($(scrollTo).length) {
            $('html, body').animate({
                scrollTop: ($(scrollTo).offset().top - headerFixedHeight - 20)
            }, 500);
        }
    });
});