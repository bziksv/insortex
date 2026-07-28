$(document).ready(function(){
    $(document).on('click', '.js-preset-download', function(e){
        e.preventDefault();

        let $this = $(this);
        let $blocksWrapper = $('#blocks_wrapper');
        let landingId = $blocksWrapper.data('landing-id');
        let mode = $blocksWrapper.data('mode');

        if (landingId && mode) {
            startBtnLoad($this);

            panelAjax('downloadPreset', {landingId: landingId, mode: mode}).then(function(res){
                if (res.data.content) {
                    downloadVirtualFile(res.data.name, res.data.content);
                }

                endBtnLoad($this);
            });
        }
    });
    $(document).on('change', '.js-upload-preset', async function (e) {
        const $input = $(this).siblings('[name="PRESET_UPLOAD_FILE"]');
        const file = await loadFile($(this), { exts: ['.rxlanding'] });
        if (!file) {
            return;
        }

        $input.val(file.data);
        $input.trigger('change');
    });
    $(document).on('change', '[name="PRESET_UPLOAD_FILE"]', function(e){
        let fileData = $(this).val();
        let $btn = $(this).siblings('.btn');
        let $panelTab = $(this).closest('.panel-tab');
        let panelTabId = $panelTab .attr('id');

        if (fileData) {
            startBtnLoad($btn);

            panelAjax('uploadPreset', {data: fileData}).then(function(res){
                endBtnLoad($btn);
                setCookie('RX_LANDING_PANEL', '#' + panelTabId);
                
                document.location.reload(true);
            }, function(res){
                endBtnLoad($btn);
                $panelTab.prepend('<div class="alert alert-danger">' + res.errors[0].message + '</div>');
            });
        }
    });
    $(document).on('click', '.btn-preset-apply', function(e){
        let code = $(this).closest('.panel-block').data('code');
        let $blocksWrapper = $('#blocks_wrapper');
        let $panel = $(this).closest('.panel');
        let landingId = $blocksWrapper.data('landing-id');
        let mode = $blocksWrapper.data('mode');

        if (code && landingId && mode && confirm(BX.message('RX_PANEL_LANDING_TEMPLATE_JS_CONFIRM'))) {
            $panel.addClass('loading');

            panelAjax('applyPreset', {code: code, landingId: landingId, mode: mode}).then(function(res){
                document.location.reload();
            });
        }
    });

    $(document).on('click', '.btn-preset-delete', function (e) {
        let $panelBlock = $(this).closest('.panel-block');
        let code = $panelBlock.data('code');

        let $panel = $(this).closest('.panel');
        if (code && confirm(BX.message('RX_PANEL_LANDING_TEMPLATE_JS_CONFIRM'))) {
            $panel.addClass('loading');

            panelAjax('deleteCustomPreset', {code: code}).then(function (res) {
                $panel.removeClass('loading');
                $panelBlock.remove();
                if (!$('#panelPresetsCUSTOM .panel-preset-blocks .preset-block').length) {
                    $('#panelPresetsCUSTOM .panel-preset-blocks').addClass('empty');
                }
            }, function (res) {
                $panel.removeClass('loading');
                $(this).closest('.panel-tab').prepend(
                    $('<div class="alert alert-danger">'+res.errors[0].message+'</div>')
                );
            });
        }
    });

    $(document).on('click', '.btn-preset-show', function (e) {
        let $panelBlock = $(this).closest('.panel-block');
        let code = $panelBlock.data('code');
        let title = $panelBlock.data('title');
        let detail = $panelBlock.data('detail');
        let demo = $panelBlock.data('demo');

        let $panelPresetBlocks = $(this).closest('.panel-preset-blocks');
        let $panelPresetDetail = $(this).closest('.panel-tab').find('.panel-preset-detail');

        $panelPresetDetail.attr('data-code', code);
        $panelPresetDetail.find('.preset-detail-title').text(title);
        $panelPresetDetail.find('.preset-detail-img img').attr({'src':detail, 'alt':title });

        if (demo) {
            $panelPresetDetail.find('.btn-preset-demo').attr('href', demo);
            $panelPresetDetail.find('.btn-preset-demo').show();
        } else {
            $panelPresetDetail.find('.btn-preset-demo').hide();
        }

        $panelPresetBlocks.hide();
        $panelPresetDetail.removeClass('hidden');
    });

    $(document).on('click', '.js-preset-back', function (e) {
        let $panelPresetDetail = $(this).closest('.panel-preset-detail');
        let $panelPresetBlocks = $(this).closest('.panel-tab').find('.panel-preset-blocks');

        $panelPresetDetail.addClass('hidden');
        $panelPresetBlocks.show();
    });

    $(document).on('click', 'a[href^="#panelPresets"]', function(e) {
        let $panelsPresets = $('div[id^="panelPresets"]');

        $panelsPresets.find('.panel-preset-detail').addClass('hidden');
        $panelsPresets.find('.panel-preset-blocks').show();
    });

    $(document).on('click', 'a[href^="#panelPresets"]', function () {
        $('.panel-preset-img img').each(function() {
            const imgUrl = $(this).data('img-url');
            const src = $(this).attr('src');
            if (!src) {
                $(this).attr('src', imgUrl);
            }
        });
    });
});
