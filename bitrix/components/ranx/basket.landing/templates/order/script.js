$(document).ready(function(){
    const template = 'order';

    function refreshBasket() {
        $('.order-basket').addClass('loading');

        rxRunComponentAction('basket', 'refresh', { data: { template } }).then((result) => {
            $('.order-basket').replaceWith(result.data);
        });
    }

    BX.addCustomEvent('onRanxLandingBasketAdd', function (obj) {
        refreshBasket();
    });

    BX.addCustomEvent('onRanxLandingBasketRemove', function (obj) {
        refreshBasket();
    });

    $(document).on('change', '.order-basket-item-select', function () {
        $('.order-basket-remove-selected').hide();
        $('.order-basket-item-select').each(function(){
            if ($(this).prop('checked')) {
                $('.order-basket-remove-selected').show();
            } else {
                $('#order_basket_select_all').prop('checked', false);
            }
        });
    });
    $(document).on('change', '#order_basket_select_all', function () {
        const isChecked = $(this).prop('checked');
        $('.order-basket-item-select').each(function(){
            $(this).prop('checked', isChecked);
        });
        if (isChecked) {
            $('.order-basket-remove-selected').show();
        } else {
            $('.order-basket-remove-selected').hide();
        }
    })
    $(document).on('click', '.js-basket-remove-selected', async function(e) {
        e.preventDefault();

        const ids = [];
        $(this).closest('.order-basket').find('.order-basket-item-select').each(function(){
            if (!$(this).prop('checked')) return;
            ids.push($(this).data('id'));
        });

        await Promise.all(ids.map(id => {
            return rxRunComponentAction('basket', 'remove', { data: { id } });
        }));

        BX.onCustomEvent('onRanxLandingBasketRemove');
    })
});
