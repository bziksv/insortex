$(document).ready(function(){
    $(document).on('submit', '.js-order-form', function (e) {
        e.preventDefault();

        const $form = $(this);

        if (!$form[0].checkValidity()) {
            e.stopPropagation();
        } else {
            $form.find('.order-result').addClass('loading');

            const $contact = $form.find('.order-contact');
            const $delivery = $form.find('.order-delivery');

            const fields = {
                NAME: $contact.find('[name="NAME"]').val(),
                PHONE: $contact.find('[name="PHONE"]').val(),
                EMAIL: $contact.find('[name="EMAIL"]').val(),
                COMPANY: $contact.find('[name="COMPANY"]').val(),
                COMMENT: $contact.find('[name="COMMENT"]').val(),
                ADDRESS: $delivery.find('[name="ADDRESS"]').val()
            };

            rxRunComponentAction('order', 'submit', { data: { fields } }).then((result) => {
                $form.closest('.order-form').addClass('order-complete order-complete-success');

                if ($form.siblings('.order-payment').length && result.data && result.data.payment) {
                    $form.siblings('.order-payment').html(result.data.payment);
                    $form.parent().find('.order-payment form').submit();
                }
            }, (result) => {
                $form.closest('.order-form').addClass('order-complete order-complete-error')
            });
        }

        $form.addClass('was-validated');
    });

    $(document).on('change', '[name="ORDER_DELVIERY"]', function(e){
        const index = $(this).data('index');

        $('.order-delivery-cost-item').hide();
        $('#order_delivery_cost_' + index).show();

        $('.order-delivery-caption-item').hide();
        $('#order_delivery_caption_' + index).show();

        const $address = $(this).closest('.order-delivery').find('.order-delivery-address');
        if ($(this).data('show-address')) {
            $address.show();
        } else {
            $address.hide();
        }

        rxRunComponentAction('order', 'delivery', { data: { index } }).then((result) => {
            refreshOrderResult();
        });
    });

    function refreshOrderResult() {
        $('.order-result').addClass('loading');

        rxRunComponentAction('order', 'refresh').then((result) => {
            if (!result.data) {
                window.location.reload();
                return;
            }
            $('.order-result-info').replaceWith(result.data);
            $('.order-result').removeClass('loading');
        });
    }

    BX.addCustomEvent('onRanxLandingBasketAdd', function (obj) {
        refreshOrderResult();
    });

    BX.addCustomEvent('onRanxLandingBasketRemove', function (obj) {
        refreshOrderResult();
    });

    const debounceTimers = []
    function debounceQuantity(func, timeout = 300){
        return (...args) => {
          clearTimeout(debounceTimers[args[0]]);
          debounceTimers[args[0]] = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }
    const updateOrderBasketCounter = debounceQuantity((id, quantity) => {
        rxRunComponentAction('basket', 'quantity', { data: { id, quantity } });
        refreshOrderResult();
    }, 500);

    BX.addCustomEvent('onRanxLandingBasketQuantity', function (obj) {
        const id = $(obj).parent().find('input').data('id');
        const quantity = $(obj).parent().find('input').val();

        updateOrderBasketCounter(id, quantity);
    });

    $(document).on('click', '.js-order-agreement', function(e){
        e.preventDefault();

        const settingId = getSettingId();

        rxRunComponentAction('order', 'agreement', {data: { settingId }}).then(function(res){
            $('#agreementModal .modal-title').html(res.data.title);
            $('#agreementModal .modal-body').html(res.data.body);
            $('#agreementModal').modal();
        });
    });
});
