$(document).ready(function () {
    $(document).on('click', '.js-panel-updates', function (e) {
        e.preventDefault();

        let $panel = $(this).closest('.panel');
        $panel.addClass('loading');
        panelAjax('getUpdatesTemplate').then(function(res) {
            $('#panelUpdates').html(res.data.html);
            $panel.removeClass('loading');
        }, function (res) {
            $('#panelUpdates').html('<div class="alert alert-danger">'+res.errors[0].message+'</div>');
            $panel.removeClass('loading');
        });
    });

    $(document).on('click', '.js-updates-show', function (e) {
        e.preventDefault();

        const $updatesCard = $(this).closest('.updates-card');
        const code = $updatesCard.data('code');

        const $panelTabUpdates = $updatesCard.closest('.panel-tab-updates');
        const $updateDetail = $panelTabUpdates.find('.update-detail[data-code="'+code+'"]');

        $panelTabUpdates.children('.updates-previews').addClass('update-hidden');
        $updateDetail.removeClass('update-hidden');

        $updateDetail.find('[data-src]').each(function() {
            let dataSrc = $(this).data('src');
            const src = $(this).attr('src');
            if (!src) {
                if (dataSrc.indexOf('/') === 0) {
                    dataSrc = 'https://ranx.ru' + dataSrc;
                }
                $(this).attr('src', dataSrc);
            }
        });

        $updateDetail.find('a').attr('target', '_blank');
    });

    $(document).on('click', '.js-updates-back', function(e) {
        e.preventDefault();

        const $updateDetail = $(this).closest('.update-detail');
        const $panelTabUpdates = $updateDetail.closest('.panel-tab-updates');

        $panelTabUpdates.children('.updates-previews').removeClass('update-hidden');
        $updateDetail.addClass('update-hidden');
    });
});
