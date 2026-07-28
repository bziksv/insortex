$(document).ready(function(){

    updateBlockUpAndDown();
    initSelectric();
    initDatepicker();
    initMasks();

    if ($('#panel').hasClass('open')) {
        initColorPicker($('#panel'));
    }

    // open panel and specific tab
    $(document).on('click', '[data-open-panel]', function(e){
        e.preventDefault();

        let tabId = $(this).data('open-panel');
        openPanelTab(tabId);
    });
    // close panel
    $('.panel .panel-close').on('click', function(e){
        e.preventDefault();

        if ($(this).closest('#panel').length) {
            $('#panel').data('before-block', '');
            $('#panel').data('after-block', '');
        }

        closePanel($(this).closest('.panel').attr('id'));
    });

    // open tab on panel
    $('#panel .panel-menu a').on('click', function(e){
        e.preventDefault();

        var tabId = $(this).attr('href');

        $('#panel .panel-menu a').removeClass('active');
        $('#panel .panel-tab').removeClass('active');

        // activate parent or child if exists
        if ($(this).closest('.panel-menu-dropdown').length) {
            $parent = $(this).closest('.panel-menu-dropdown')
            $parent.siblings('a').addClass('active');
        } else if ($(this).siblings('.panel-menu-dropdown').length) {
            var $childLink = $(this).siblings('.panel-menu-dropdown').find('li:first-child > a');
            $childLink.addClass('active');
            tabId = $childLink.attr('href');
        }

        $(this).addClass('active');
        $('#panel ' + tabId).addClass('active');
    });

    // adding new block
    $('.panel-block-img').on('click', function(){
        let $panelBlock = $(this).parent();
        let $panel = $(this).closest('.panel');

        if ($panelBlock.hasClass('added')) {
            return false;
        }

        if ($panel.data('replace')) {
            let id = $panel.data('replace');
            let code = $panelBlock.data('code');
            let $block = $('#block_' + id);
            let tabId = $block.find('.block').attr('data-tab');

            if (id > 0 && $block.length) {
                $block.find('.block').addClass('loading');
                panelAjax('replaceBlock', {id: id, code: code, tabId: tabId}).then(function(res){
                    $block.replaceWith(res.data.html);
                    $block = $('#block_' + id);
                    $block.find('.block_edit_label').html($block.data('name'));
                    closePanel($panel.attr('id'));
                    initBlocks();
                });
            }
        } else {
            let landingId = $('#blocks_wrapper').data('landing-id');
            let mode = $('#blocks_wrapper').data('mode');
            let code = $panelBlock.data('code');

            RxLandingBlock.add(landingId, code, mode);
            $panelBlock.addClass('added');
            setTimeout(function(){
                $panelBlock.removeClass('added');
            }, 2000);
        }
    });

    // enter and exit edit mode
    $('.js-enter-edit-mode').on('click', function(e){
        e.preventDefault();

        setCookie('RX_LANDING_EDIT', 'Y');
        document.location.reload();
    });
    $('.js-exit-edit-mode').on('click', function(e){
        e.preventDefault();

        deleteCookie('RX_LANDING_EDIT');
        document.location.reload();
    });

    $(document).on('click', '.panel-acc-header', function(e){
        let $parent = $(this).parent();
        $parent.toggleClass('open');
    });
    $(document).on('click', 'input[type="checkbox"][data-toggle]', function(){
        let $toggle = $($(this).data('toggle'));
        if ($toggle.length) {
            if ($(this).prop('checked')) {
                $toggle.show();
            } else {
                $toggle.hide();
            }
        }
    });
    // auto transliteration
    $(document).on('change keyup paste', '.panel form .js-auto-transliteration:input', function(e){
        const options = {'replace_space': '-', 'replace_other': '-'};

        let input  = $(this);
        let target = $(input.data('transliteration-target'));

        if(BX.translit)
            target.val(BX.translit(input.val(), options));
    });

    // allow to apply changes
    function onPanelFieldChanged(e){
        let $panel = $(this).closest('.panel');
        $panel.find('button[type="submit"]').prop('disabled', false);

        if ($panel.attr('id') === 'panelDesign' && !$panel.hasClass('loading')) {
            if (e) {
                let $target = $(e.target);
                if ($target.hasClass('custom-file-input') ||
                    $target.attr('type') === 'text' && e.type === 'keyup') {
                    return;
                }
            }

            $panel.addClass('loading');
            $panel.find('form').submit();
        }
    }
    $(document).on('change keyup paste', '.panel form :input', onPanelFieldChanged);
    // for bitrix html editor
    BX.addCustomEvent('OnContentChanged', function(){
        onPanelFieldChanged.call(this.dom.cont)
    });

    // Events for upload files
    $(document).on('changeFiles', '.rx-upload-files', function (e) {
        $(this).closest('.panel').attr('data-force', true);
        onPanelFieldChanged.call(this);
    });
    $(document).on('change', '.rx-upload-files .js-upload-files', async function (e) {
        const isMultiple = $(this).is('[multiple]');
        const files = $(this).prop('files');
        const $btn = $(this).siblings('label');
        const params = {
            exts: $(this).data('exts'),
            mime: $(this).data('mime'),
            size: $(this).data('max-size'),
        };

        startBtnLoad($btn);

        const loadedFiles = await loadFiles(files, params);
        const $formGroup = $(this).closest('.form-group.rx-upload-files');
        const $picsWrapper = $formGroup.find('.form-group-pics-wrapper');
        const $picsTemplate = $formGroup.find('.form-group-pics.template');

        $.each(loadedFiles, function () {
            const $newPics = $picsTemplate.clone();
            const $previewWrap = $newPics.find('.form-group-preview-wrap');
            const $input = $newPics.find('input[type="hidden"]');
            const isImage = this.mime.split('/').shift() === 'image';
            const ext = getFileExt(this.name);
            const extPrefix = getFileExtPrefix(this.name);

            if (isImage) {
                const $previewImg = $(`<img src="${this.data}" alt="">`);
                $previewWrap.html($previewImg);
            }

            $input.attr('name', $input.data('name'));
            $input.attr('value', extPrefix + this.data);
            $input.trigger('change');

            $newPics.find('.form-group-pics-info-ext').text(ext.toUpperCase());
            $newPics.find('.form-group-pics-info-size').text(formatSize(this.size));

            $newPics.removeClass('template hidden');
            if (isMultiple)
                $picsWrapper.append($newPics);
            else
                $picsWrapper.html($newPics);
        });

        $formGroup.trigger('changeFiles');
        endBtnLoad($btn);
    })
    $(document).on('click', '.rx-upload-files .js-pics-close', function(e) {
        e.preventDefault();

        if (confirm(BX.message('RX_PANEL_LANDING_TEMPLATE_JS_CONFIRM'))) {
            $(this).closest('.form-group-pics').addClass('hidden');
            $(this).siblings('input[type="hidden"]').val('del');
            $(this).closest('.rx-upload-files').trigger('changeFiles');

        }
    });


    $(document).on('change', '.js-panel-link [data-link]', function(){
        let val = $(this).val();
        let $panelLink = $(this).closest('.js-panel-link');
        let linkName = $panelLink.find('[data-link]').data('link-name');

        $panelLink.find('[data-link-type]').hide();
        $panelLink.find('[data-link-type] input, [data-link-type] select').attr('name', '');

        $panelLink.find('[data-link-type="'+val+'"]').show();

        const $links = $panelLink.find('[data-link-type="'+val+'"] input:not(.selectric-input), [data-link-type="'+val+'"] select');
        $links.each(function(){
            if ($(this).data('link-name-skip')) {
                return;
            }

            const dataLinkName = $(this).data('link-name');
            if (dataLinkName) {
                $(this).attr('name', dataLinkName);
            } else {
                $(this).attr('name', linkName);
            }
        });
    });

    // close panel by ESC
    $(document).keyup(function(e) {
        if (e.keyCode === 27) {
            closePanel($('.panel.active.open').last().attr('id'));
        }
    });
    // close panel by click
    $(document).mousedown(function(e){
        let excluded = [
            '.sp-container', '.datepicker', '.ui-autocomplete',
            '.bx-core-dialog-overlay', '.bx-core-window', '.bxhtmled-popup',
            '.bxhtmled-overlay', '.bx-core-popup-menu', '.bx-html-editor',
            '#bxmedialib', '#BX_file_dialog', '.bxml-subdialog-cont',
        ];
        let $panel = $('.panel.active.open').last();

        if($panel.is(e.target) || $panel.has(e.target).length)
            return;

        for(let i = 0; i < excluded.length; i++)
        {
            let $element = $(excluded[i]);

            if($element.is(e.target) || $element.has(e.target).length)
                return;
        }

        closePanel($panel.attr('id'));
    });

    $('#panelParamsForm').on('submit', function(e){
        e.preventDefault();

        let $panel = $(this).closest('.panel');
        let panelTabId = $(this).find('.panel-tab.active').attr('id');

        let formData = $(this).serializeArray();
        let params = convertFormArrToObj(formData);

        $panel.addClass('loading');
        panelAjax('editParams', {params: params}).then(function(res){
            if (panelTabId) {
                setCookie('RX_LANDING_PANEL', '#' + panelTabId);
            }
            document.location.reload(true);
        });
    });

    $('#panelParamsForm .js-settings-restore').on('click', function(e){
        e.preventDefault();

        if (!confirm(BX.message('RX_PANEL_LANDING_TEMPLATE_JS_CONFIRM'))) {
            return;
        }

        let $panel = $(this).closest('.panel');

        $panel.addClass('loading');
        panelAjax('restoreParams').then(function(res){
            document.location.reload(true);
        });
    });

    $('#panelParamsForm .js-show-if').each(function(){
        let option   = $(this);
        let settings = option.closest('#panelParamsForm');
        let checker;

        // bootstrap Collapse
        function visibilityHandler(isVisible)
        {
            this.collapse(isVisible ? 'show' : 'hide');
        }

        checker = new RX.ShowIf.ShowIfChecker(settings, option, visibilityHandler);
        option.data('show-if-checker', checker);
    });

    $('.panel-chat-toggle').on('click', function(e){
        e.preventDefault();

        $(this).siblings('.form-group').toggle();
    });
    $('.panel-font-edit').on('click', function(e){
        e.preventDefault();

        $(this).parent().siblings('.panel-font-params').toggle();
        $(this).parent().siblings('.panel-font-params-preview').toggle();
    });

    $(document).on('click', '.js-clear-text-field', function (e) {
        e.preventDefault();

        const $inputText = $(this).siblings('input[type="text"]');
        $inputText.val('');
        $inputText.trigger('change');
        $inputText.focus();
    });
});
