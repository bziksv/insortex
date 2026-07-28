$(document).ready(function(){
    $(document).on('mouseenter', '.header-basket', function () {
        $(this).find('.basket-box').fadeIn();
    });
    $(document).on('mouseleave', '.header-basket .basket-box', function () {
        $(this).fadeOut();
    });

    const template = 'header';

    function refreshBasket(obj, open = false) {
        $(obj).closest('.basket-box').addClass('loading');
        const $header = $(obj).closest('#headerfixed').length 
            ? $(obj).closest('#headerfixed')
            : ($(obj).closest('header').length ? $(obj).closest('header') : false);

        rxRunComponentAction('basket', 'refresh', { data: { template } }).then((result) => {
            $('.header-basket').replaceWith(result.data);
            if (open && $header) {
                $header.find('.header-basket .basket-box').show();
            }
        });
    }

    BX.addCustomEvent('onRanxLandingBasketAdd', function (obj) {
        refreshBasket(obj);
    });

    BX.addCustomEvent('onRanxLandingBasketRemove', function (obj) {
        refreshBasket(obj, true);
    });
});
