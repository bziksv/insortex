$(document).ready(function(){
    $(document).on('click', '.js-basket-add', function(e){
        e.preventDefault();

        const id = $(this).data('id');
        const quantity = $(this).siblings('.counter').find('input').val() || 1;
        if (id <= 0) {
            return;
        }

        rxRunComponentAction('basket', 'add', { data: { id, quantity } }).then((result) => {
            BX.onCustomEvent('onRanxLandingBasketAdd', [this]);

            showProductNotice(result);
        });
    });
    $(document).on('click', '.js-basket-add-custom', function(e){
        e.preventDefault();

        const $btn = $(this);
        const name = $(this).data('product-name');
        const price = $(this).data('product-price');
        const discount = $(this).data('product-discount');

        if (!name || !price) {
            return;
        }

        startBtnLoad($btn);

        rxRunComponentAction('basket', 'addCustom', { data: { name, price, discount } }).then((result) => {
            BX.onCustomEvent('onRanxLandingBasketAdd', [this]);

            showProductNotice(result);
            endBtnLoad($btn);
        });
    });
    $(document).on('click', '.js-basket-remove', function(e){
        e.preventDefault();

        const id = $(this).data('id');
        if (id <= 0) {
            return;
        }

        rxRunComponentAction('basket', 'remove', { data: { id } }).then((result) => {
            BX.onCustomEvent('onRanxLandingBasketRemove', [this]);
        });
    });

    function showProductNotice (result) {
        const $notice = $('#basket_add_notice');
        $notice.find('.notice-item-desc').html(result.data.NAME);
        $notice.find('.notice-item-img img').attr('src', result.data.PICTURE);

        $notice.toast('show');
    } 

    BX.addCustomEvent('onRanxLandingBasketAdd', function (obj) {
        $('.js-basket-add[data-id="' + $(obj).data('id') + '"]').parent().addClass('in-basket');
    });
    BX.addCustomEvent('onRanxLandingBasketRemove', function (obj) {
        $('.js-basket-add[data-id="' + $(obj).data('id') + '"]').parent().removeClass('in-basket');
    });

    // counter
    $(document).on('click', '.counter .counter-minus', function(){
        const $input = $(this).closest('.counter').find('input');
        const id = $input.data('id');
        let curVal = $input.val();

        curVal--;
        if (curVal <= 1) {
            curVal = 1;
        }

        if (curVal == $input.val()) {
            return;
        }

        $input.val(curVal);

        BX.onCustomEvent('onRanxLandingBasketQuantity', [this]);
    });
    $(document).on('click', '.counter .counter-plus', function(){
        const $input = $(this).closest('.counter').find('input');
        const id = $input.data('id');
        let curVal = $input.val();

        curVal++;

        $input.val(curVal);

        BX.onCustomEvent('onRanxLandingBasketQuantity', [this]);
    });
    $(document).on('change', '.counter .counter-value', function(){
        const $input = $(this).closest('.counter').find('input');
        const id = $input.data('id');
        let curVal = $input.val();

        if (curVal <= 1) {
            curVal = 1;
        }

        $input.val(curVal);

        BX.onCustomEvent('onRanxLandingBasketQuantity', [this]);
    });
});
