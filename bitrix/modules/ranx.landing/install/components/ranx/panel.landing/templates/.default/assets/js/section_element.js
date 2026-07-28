// section and element adding
$(document).ready(function() {
    $('#panelSectionAddForm, #panelElementAddForm').on('submit', function (e) {
        e.preventDefault();

        let $panel = $(this).closest('.panel');
        let $form = $(this);
        $form.find('.alert-danger').remove();

        let landingId = $('#blocks_wrapper').data('landing-id');
        let mode = $('#blocks_wrapper').data('mode');
        let type = $('#blocks_wrapper').data('section-type');
        let name = $(this).find('input[name="NAME"]').val();
        let code = $(this).find('input[name="CODE"]').val();

        let action = '';
        if ($(this).attr('id') === 'panelSectionAddForm') {
            action = 'addSection';
        } else if ($(this).attr('id') === 'panelElementAddForm') {
            action = 'addElement';
        }

        if (action && name && landingId && mode) {
            $panel.addClass('loading');
            panelAjax(action, {
                landingId: landingId,
                mode: mode,
                type: type,
                name: name,
                code: code
            }).then(function () {
                document.location.reload(false);
            }, function (res) {
                $panel.removeClass('loading');
                $form.prepend($('<div class="alert alert-danger">' + res.errors[0].message + '</div>'))
            });
        }
    });
    $(document).on('click', '.js-section-remove', function (e) {
        e.preventDefault();

        let $block = $(this).closest('.section-item');
        let id = parseInt($(this).data('id'));
        let landingId = parseInt($('#blocks_wrapper').data('landing-id'));

        if (id > 0 && landingId > 0) {
            if (confirm(BX.message('RX_PANEL_LANDING_TEMPLATE_JS_CONFIRM'))) {
                panelAjax('removeSection', {id: id, landingId: landingId}).then(function (res) {
                    $block.fadeOut('fast', function () {
                        $block.remove();
                    });
                });
            }
        }
    });
    $(document).on('click', '.js-element-remove', function (e) {
        e.preventDefault();

        let $block = $(this).closest('.section-item');
        let id = parseInt($(this).data('id'));

        if (id > 0) {
            if (confirm(BX.message('RX_PANEL_LANDING_TEMPLATE_JS_CONFIRM'))) {
                panelAjax('removeElement', {id: id}).then(function (res) {
                    $block.fadeOut('fast', function () {
                        $block.remove();
                    });
                });
            }
        }
    });
});
