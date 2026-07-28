// TODO: refactor the code below
var RxLandingBlock = {

    show: function (id, groupId) {
        panelAjax('showBlock', {id: id, groupId: groupId});
    },
    hide: function (id, groupId) {
        panelAjax('hideBlock', {id: id, groupId: groupId});
    },
    down: function (id, nextId, groupId, nextGroupId) {
        panelAjax('downBlock', {id: id, nextId: nextId, groupId: groupId, nextGroupId: nextGroupId}).then(function(){
            updateBlockUpAndDown();
        });
    },
    up: function (id, prevId, groupId, prevGroupId) {
        panelAjax('upBlock', {id: id, prevId: prevId, groupId: groupId, prevGroupId: prevGroupId}).then(function(){
            updateBlockUpAndDown();
        });
    },
    add: function (landingId, code, mode) {

        nextBlocks = this.getNextBlocks();

        panelAjax('addBlock',
            {
                landingId: landingId,
                mode: mode,
                code: code,
                nextBlocks: nextBlocks
            }
        ).then(function(res){
            $('.block-wrap').each(function(){
                if (nextBlocks.includes($(this).data('id'))) {
                    let curBlockOrder = parseInt($(this).attr('data-order'));
                    $(this).attr('data-order', curBlockOrder + 1);
                }
            });

            let $el = $(res.data.html);
            $('#blocks_wrapper').append($el);
            $('#blocks_wrapper').removeClass('empty');

            // update slick after some time
            setTimeout(function(){
                if ($el.find('.slick-slider.slick-initialized').length) {
                    // the same as reinit, but works better
                    $el.find('.slick-slider.slick-initialized').slick('setOption', {}, true);
                }
            }, 300);

            initBlocks();
            initMasks();
            $el.find('.block_edit_label').html($el.data('name'));

            // small but very powerful feature
            $('html, body').animate({
                scrollTop: $el.offset().top - 150
            }, 500);

            updateBlockUpAndDown();
        });
    },
    remove: function (id, groupId) {
        var nextBlocks = [];
        var blockOrder = parseInt($('.block-wrap[data-id="'+id+'"]').attr('data-order'));
        $('.block-wrap').each(function(){
            var curBlockOrder = parseInt($(this).attr('data-order'));
            if (curBlockOrder > blockOrder) {
                nextBlocks.push($(this).data('id'));
            }
        });

        panelAjax('removeBlock',
            {
                id: id,
                groupId: groupId
            }
        ).then(function(res){
            $('.block-wrap').each(function(){
                if ($(this).data('id') == id) {
                    $(this).slideUp('normal', function() {
                        $(this).remove();

                        if (!$('.block-wrap').length) {
                            $('#blocks_wrapper').addClass('empty');
                        }
                    });
                }
                if (nextBlocks.includes($(this).data('id'))) {
                    var curBlockOrder = parseInt($(this).attr('data-order'));
                    $(this).attr('data-order', curBlockOrder - 1);
                }
            });

            updateBlockUpAndDown();
        });
    },
    getNextBlocks: function() {
        var nextBlocks = [];

        if ($('.panel').data('before-block')) {
            var beforeBlockId = $('.panel').data('before-block');
            var $beforeBlock = $('.block-wrap[data-id="'+beforeBlockId+'"]');
            var beforeBlockOrder = parseInt($beforeBlock.attr('data-order'));

            $('.block-wrap').each(function(){
                var curBlockOrder = parseInt($(this).attr('data-order'));
                if (curBlockOrder >= beforeBlockOrder) {
                    nextBlocks.push($(this).data('id'));
                }
            });
        } else if ($('.panel').data('after-block')) {
            var afterBlockId = $('.panel').data('after-block');
            var $afterBlock = $('.block-wrap[data-id="'+afterBlockId+'"]');
            var afterBlockOrder = parseInt($afterBlock.attr('data-order'));

            $('.block-wrap').each(function(){
                var curBlockOrder = parseInt($(this).attr('data-order'));
                if (curBlockOrder > afterBlockOrder) {
                    nextBlocks.push($(this).data('id'));
                }
            });
        } else {
            // try to get first visible block and get next blocks relative to it
            let scrollTop = $(window).scrollTop();

            let order = 1;
            while (true) {
                let $block = $('.block-wrap[data-order="'+order+'"]');
                if (!$block.length) {
                    break;
                }

                if ($block.offset().top > scrollTop) {
                    nextBlocks.push($block.data('id'));
                }

                order++;
            }
        }

        return nextBlocks;
    }
};

$(document).ready(function(){
    $(document).on('click', '.js-block-show', function(e){
        e.preventDefault();

        let $block = $(this).closest('.block');
        $block.removeClass('hidden');

        RxLandingBlock.show($block.data('id'), $block.data('group-id'));
    });
    $(document).on('click', '.js-block-hide', function(e){
        e.preventDefault();

        let $block = $(this).closest('.block');
        $block.addClass('hidden');

        RxLandingBlock.hide($block.data('id'), $block.data('group-id'));
    });
    $(document).on('click', '.js-block-down', function(e){
        e.preventDefault();

        let scrollTop = $(document).scrollTop();
        let $blockWrap = $(this).closest('.block-wrap');
        let wraps = getSortedBlockWraps();
        let idx = wraps.indexOf($blockWrap[0]);
        let $nextBlockWrap = idx >= 0 && idx < wraps.length - 1 ? $(wraps[idx + 1]) : $();

        if (!$nextBlockWrap.length || !$nextBlockWrap.attr('data-id')) {
            return;
        }

        let $block = $blockWrap.find('.block').first();
        let $nextBlock = $nextBlockWrap.find('.block').first();
        let blockId = $block.data('id');
        let headerHeight = $('#headerfixed').height() || 62;

        swapBlockWrapPositions($blockWrap, $nextBlockWrap);
        RxLandingBlock.down(blockId, $nextBlock.data('id'), $block.data('group-id'), $nextBlock.data('group-id'));

        $(document).scrollTop(scrollTop);
        $('html, body').animate({ scrollTop: $('#block_' + blockId).offset().top - headerHeight }, 1000);
    });
    $(document).on('click', '.js-block-up', function(e){
        e.preventDefault();

        let scrollTop = $(document).scrollTop();
        let $blockWrap = $(this).closest('.block-wrap');
        let wraps = getSortedBlockWraps();
        let idx = wraps.indexOf($blockWrap[0]);
        let $prevBlockWrap = idx > 0 ? $(wraps[idx - 1]) : $();

        if (!$prevBlockWrap.length || !$prevBlockWrap.attr('data-id')) {
            return;
        }

        let $block = $blockWrap.find('.block').first();
        let $prevBlock = $prevBlockWrap.find('.block').first();
        let blockId = $block.data('id');
        let headerHeight = $('#headerfixed').height() || 62;

        swapBlockWrapPositions($blockWrap, $prevBlockWrap);
        RxLandingBlock.up(blockId, $prevBlock.data('id'), $block.data('group-id'), $prevBlock.data('group-id'));

        $(document).scrollTop(scrollTop);
        $('html, body').animate({ scrollTop: $('#block_' + blockId).offset().top - headerHeight }, 1000);
    });

    $(document).on('click', '.js-block-prepend', function(e){
        e.preventDefault();

        var blockId = $(this).closest('.block').data('id');
        $('.panel').data('after-block', '');
        $('.panel').data('before-block', blockId);
    });
    $(document).on('click', '.js-block-append', function(e){
        e.preventDefault();

        var blockId = $(this).closest('.block').data('id');
        $('.panel').data('before-block', '');
        $('.panel').data('after-block', blockId);
    });

    $(document).on('click', '.js-block-remove', function(e){
        e.preventDefault();

        if (confirm(BX.message('RX_PANEL_LANDING_TEMPLATE_JS_CONFIRM'))) {
            let $block = $(this).closest('.block');
            let blockId = $block.data('id');
            let groupId = $block.data('group-id');

            $block.addClass('loading');
            RxLandingBlock.remove(blockId, groupId);
        }
    });
    $(document).on('click', '.js-block-copy', function(e){
        e.preventDefault();

        let $this = $(this);
        let $panel = $('#panelBlockCopy');
        let id = parseInt($(this).closest('.block').data('id'));
        let groupId = parseInt($(this).closest('.block').data('group-id'));

        if (id > 0) {
            startBtnLoad($this);

            panelAjax('getCopyTemplate', {}).then(function(res){
                openPanel('panelBlockCopy');
                $panel.find('.panel-body').html(res.data.html);
                $panel.find('input[name="id"]').val(id);
                $panel.find('input[name="groupId"]').val(groupId);
                $panel.find('input[name="mode"]').val('');

                endBtnLoad($this);
            });

        }
    });
    $(document).on('click', '.js-block-replace', function(e){
        e.preventDefault();

        let $this = $(this);
        let $panel = $('#panel');
        let id = $this.closest('.block').data('id');

        if (id > 0) {
            openPanelTab('#panelLib');
            $panel.data('replace', id);
            $panel.attr('data-replace', id);
        }
    });

    $(document).on('click', '.js-block-design', function(e){
        e.preventDefault();

        let $this = $(this);
        let $panel = $('#panelDesign');
        let tabId = parseInt($this.closest('.block').attr('data-tab'));
        let id = parseInt($this.closest('.block').data('id'));
        if (!id) {
            id = parseInt($this.closest('form').find('[name="id"]').val());
        }
        if (!tabId) {
            tabId = parseInt($this.closest('form').find('[name="tabId"]').val());
        }

        if (id > 0) {
            if ($this.closest('.panel').length) {
                $this.closest('.panel').removeClass('open');
            }

            startBtnLoad($this);

            panelAjax('getDesignTemplate', {id: id}).then(function(res){
                $panel.find('button[type="submit"]').prop('disabled', true);
                if ($panel.find('.simplebar-content').length) {
                    $panel.find('.simplebar-content').html(res.data.html);
                } else {
                    $panel.find('.panel-body').html(res.data.html);
                }
                openPanel('panelDesign');
                $panel.find('input[name="id"]').val(id);
                $panel.find('input[name="tabId"]').val(tabId);
                $panel.find('.js-block-tabs').toggleClass('hidden', !(parseInt(tabId || '0') > 0));

                endBtnLoad($this);
                initColorPicker($('#panelDesign'));
                initForms();
                initSelectric();
                initDatepicker();
                initMasks();
                initTabs();
            });
        }
    });

    $(document).on('click', '.js-block-content', function(e){
        e.preventDefault();

        let $this = $(this);
        let id = parseInt($this.closest('.block').data('id'));
        if (!id) {
            id = parseInt($this.closest('form').find('[name="id"]').val());
        }

        if (id > 0) {
            if ($this.closest('.panel').length) {
                $this.closest('.panel').removeClass('open');
            }

            startBtnLoad($this);
            updatePanelContent(id, $this);
        }
    });

    $(document).on('click', '.js-block-settings', function(e){
        e.preventDefault();

        let $this = $(this);
        let $panel = $('#panelSettings');
        let id = parseInt($this.closest('.block').data('id'));
        if (!id) {
            id = parseInt($this.closest('form').find('[name="id"]').val());
        }

        if (id > 0) {
            if ($this.closest('.panel').length) {
                $this.closest('.panel').removeClass('open');
            }

            startBtnLoad($this);

            panelAjax('getSettingsTemplate', {id: id}).then(function(res){
                $panel.find('button[type="submit"]').prop('disabled', true);
                if ($panel.find('.simplebar-content').length) {
                    $panel.find('.simplebar-content').html(res.data.html);
                } else {
                    $panel.find('.panel-body').html(res.data.html);
                }
                openPanel('panelSettings');
                $panel.find('input[name="id"]').val(id);

                endBtnLoad($this);
                initColorPicker($('#panelSettings'));
                initForms();
                initSelectric();
                initDatepicker();
                initMasks();
            });
        }
    });

    $(document).on('click', '.js-add-input-text', function(e){
        e.preventDefault();

        let newInput = $(this).siblings('.cleaner-wrap').last().clone();
        $(this).before(newInput);
        $(this).siblings('.cleaner-wrap').last().find('input[type="text"]').val('');
    });

    // panel card actions
    $(document).on('click', '.js-panel-card-deact', function(e){
        e.preventDefault();

        let $card = $(this).closest('.panel-card');
        let id = $card.data('id');

        if (id) {
            $card.removeClass('active');
            $card.find('[name="ELEMENT_'+id+'_ACTIVE"]').val('N');
        }

        $card.closest('form').find('[type="submit"]').prop('disabled', false);
    });
    $(document).on('click', '.js-panel-card-act', function(e){
        e.preventDefault();

        let $card = $(this).closest('.panel-card');
        let id = $card.data('id');

        if (id) {
            $card.addClass('active');
            $card.find('[name="ELEMENT_'+id+'_ACTIVE"]').val('Y');
        }

        $card.closest('form').find('[type="submit"]').prop('disabled', false);
    });
    $(document).on('click', '.js-panel-card-remove', function(e){
        e.preventDefault();

        let $card = $(this).closest('.panel-card');
        let order = $card.data('order');

        // decrease order of next cards
        while (true) {
            order++;
            let $nextCard = $card.parent().find('.panel-card[data-order="'+order+'"]');

            if ($nextCard.length) {
                let nextOrder = $nextCard.data('order');
                let nextId = $nextCard.data('id');
                nextOrder--;

                $nextCard.data('order', nextOrder);
                $nextCard.attr('data-order', nextOrder);
                $nextCard.find('[name="ELEMENT_'+nextId+'_SORT"]').val(nextOrder);
            } else {
                break;
            }
        }

        $card.closest('form').find('[type="submit"]').prop('disabled', false);
        $card.remove();
    });
    $(document).on('click', '.js-panel-card', function(e){
        e.preventDefault();

        let $this = $(this);
        let id = $this.closest('.panel-card').data('id');
        let blockId = $this.closest('form').find('[name="id"]').val();
        let tabId = $this.closest('form').find('[name="tabId"]').val();

        if (id > 0 && blockId > 0) {
            startBtnLoad($this);
            updatePanelCard(id, blockId, tabId, $this);
        }
    })
    $(document).on('click', '.js-panel-card-add', function(e){
        e.preventDefault();

        let $this = $(this);
        let blockId = $this.closest('form').find('[name="id"]').val();
        let tabId = $this.closest('form').find('[name="tabId"]').val();

        if (blockId > 0) {
            startBtnLoad($this);
            updatePanelCard(0, blockId, tabId, $this);
        }
    });
    $(document).on('submit', '#panelCardForm', function(e){
        e.preventDefault();

        let $panel = $(this).closest('.panel');
        let $form = $(this);
        let $btn = $form.find('[type="submit"]');

        let dataArr = $form.serializeArray();
        let data = convertFormArrToObj(dataArr);

        let blockId = parseInt($form.find('[name="blockId"]').val());
        let id = parseInt($form.find('[name="id"]').val());
        let tabId = $form.find('[name="tabId"]').val();
        let isRefresh = ($panel.attr('data-force') === 'true');

        if (blockId > 0) {
            let $block = $('#block_' + blockId);

            $block.find('.block').addClass('loading');
            $panel.addClass('loading');
            $panel.find('.alert-danger').remove();

            if (data['PROPERTY_DISCOUNT_PRICE'] && $form.find('.js-panel-card-discount-type').val() === 'percent') {
                data['PROPERTY_DISCOUNT_PRICE'] += '%';
            }

            panelAjax('editCard', data).then(function (res) {
                $block.html(res.data.html);
                $block.find('.block_edit_label').html($block.data('name'));

                $block.find('.block').removeClass('loading');
                initBlocks(); // important that this is before updating panel
                updatePanelContent(blockId, false, true);

                if (!id) {
                    closePanel('panelCard');
                }
                else if (isRefresh) {
                    updatePanelCard(id, blockId, tabId, false, $panel);
                }

                $panel.removeClass('loading');
            }, function (res) {
                $block.find('.block').removeClass('loading');

                $panel.removeClass('loading');
                $panel.find('.panel-body').prepend('<div class="alert alert-danger">' + res.errors[0].message + '</div>');
            });

            $btn.prop('disabled', true);
        }
    });
});

$(document).ready(function(){
    $(document).on('submit', '#panelDesignForm', function(e){
        e.preventDefault();

        let $form = $(this);
        let $btn = $form.find('[type="submit"]');
        let $panel = $(this).closest('.panel');

        let dataArr = $form.serializeArray();
        let data = convertFormArrToObj(dataArr);

        let id = data['id'];
        let $blockWrap = $('#block_' + id);
        let $block = $blockWrap.find('.block');
        $block.addClass('loading');

        if (id > 0) {
            panelAjax('editDesign', data).then(function(res){
                $blockWrap.html(res.data.html);
                $block.removeClass('loading');
                $blockWrap.find('.block_edit_label').html($blockWrap.data('name'));
                $panel.removeClass('loading');
                initBlocks();
            }, function (res) {
                $panel.removeClass('loading');
                $panel.find('.panel-body').prepend(`<div class="alert alert-danger">${res.errors[0].message}</div>`);
                $block.find('.block').removeClass('loading');
            });
        }
    });
    $(document).on('submit', '#panelContentForm', function(e){
        e.preventDefault();

        let $panel = $(this).closest('.panel');
        let $form = $(this);
        let $btn = $form.find('[type="submit"]');

        let dataArr = $form.serializeArray();
        let data = convertFormArrToObj(dataArr);

        let id = data['id'];
        let isForce = ($panel.data('force') === true);
        $panel.removeAttr('data-force');

        if (id > 0) {
            let $block = $('#block_' + id);

            $block.find('.block').addClass('loading');
            $panel.addClass('loading');
            $panel.find('.alert-danger').remove();

            panelAjax('editContent', data).then(function(res){
                $block.html(res.data.html);
                $block.find('.block_edit_label').html($block.data('name'));

                $block.find('.block').removeClass('loading');
                initBlocks();
                updatePanelContent(id, false, isForce);
            }, function (res) {
                $block.find('.block').removeClass('loading');

                $panel.removeClass('loading');
                $panel.find('.panel-body').prepend('<div class="alert alert-danger">'+res.errors[0].message+'</div>');
            });

            $btn.prop('disabled', true);
        }
    });
    $(document).on('submit', '#panelSettingsForm', function(e){
        e.preventDefault();

        let $form = $(this);
        let $panel = $(this).closest('.panel');

        let dataArr = $form.serializeArray();
        $form.find('input:checkbox').each(function(){
            if (!this.checked) {
                dataArr.push({ name: this.name, value: '' });
            }
        });
        let data = convertFormArrToObj(dataArr);

        let id = data['id'];
        delete data['id'];
        let $blockWrap = $('#block_' + id);
        let $block = $blockWrap.find('.block');
        $block.addClass('loading');

        if (id > 0) {
            panelAjax('editSettings', {id: id, settings: data}).then(function(res){
                $blockWrap.html(res.data.html);
                $block.removeClass('loading');
                $blockWrap.find('.block_edit_label').html($blockWrap.data('name'));
                $panel.removeClass('loading');
                initBlocks();
            });
        }
    });
    $(document).on('change', '#panelBlockCopy select[name="iblock"]', function(e){
        let val = $(this).val();
        let $panel = $('#panelBlockCopy');

        $panel.find('[data-iblock]').hide();
        $panel.find('[data-iblock]').find('select').prop('disabled', true);
        $panel.find('[data-iblock="'+val+'"]').show();
        $panel.find('[data-iblock="'+val+'"]').find('select').prop('disabled', false);
        $panel.find('[data-iblock="'+val+'"]').find('select').trigger('change');

        if (parseInt(val) > 0) {
            $panel.find('.btn').prop('disabled', false);
        } else {
            $panel.find('.btn').prop('disabled', true);
        }
    });
    $(document).on('change', '#panelBlockCopy select[name^="landing_"]', function(e){
        let mode = $(this).find('option[value="'+$(this).val()+'"]').data('mode');
        let $panel = $('#panelBlockCopy');

        $panel.find('[name="mode"]').val(mode);
    });
    $(document).on('click', '#panelBlockCopyForm button[data-action]', function(e){
        e.preventDefault();

        let $panel = $(this).closest('.panel');
        let action = $(this).data('action');

        let blockId = $panel.find('[name="id"]').val();
        let groupId = $panel.find('[name="groupId"]').val();
        let iblockId = $panel.find('[name="iblock"]').val();
        let landingId = $panel.find('[name="landing_'+iblockId+'"]').val();
        let mode = $panel.find('[name="mode"]').val();
        let through = $panel.find('[name="through"]').prop('checked');

        if (action && blockId && iblockId && landingId) {
            $panel.addClass('loading');

            let data = {
                id: blockId,
                groupId: groupId,
                iblock: iblockId,
                landing: landingId,
                mode: mode
            };
            if (through) {
                data.through = 1;
            }

            panelAjax(action + 'Block', data).then(function (res) {
                $panel.removeClass('loading');

                if (action === 'move') {
                    let $link = $('<a href="' + res.data.link + '">'+res.data.link+'</a>');
                    $panel.find('.panel-copy--moved span').html($link);
                    $panel.addClass('has-moved');

                    $('#block_' + blockId).slideUp('fast', function () {
                        $('#block_' + blockId).remove();
                    });
                } else {
                    let $link = $('<a href="' + res.data.link + '">'+res.data.link+'</a>');
                    $panel.find('.panel-copy--copied span').html($link);
                    $panel.addClass('has-copied');
                }
            }, function (res) {
                $panel.removeClass('loading');
                $panel.find('.panel-body').prepend($('<div class="alert alert-danger">'+res.errors[0].message+'</div>'))
            });
        }
    });
    $(document).on('change', '#panelBlockCopyForm input[name="through"]', function(){
        let $panel = $(this).closest('.panel');

        if ($(this).prop('checked')) {
            $panel.find('button[data-action="move"]').hide();
        } else {
            $panel.find('button[data-action="move"]').show();
        }
    });

    $(document).on('change keyup', '#panelCard .form-row--prices input, #panelCard .form-row--prices select', function(e){
        let $prices = $(this).closest('.form-row--prices');
        let $priceField = $prices.find('.js-panel-card-price');
        let $discountPriceField = $prices.find('.js-panel-card-discount-price');
        let $discountTypeField = $prices.find('.js-panel-card-discount-type');

        let price = $priceField.val();
        let discountPrice = $discountPriceField.val();
        let discountType = $discountTypeField.val();

        let $totalPrice = $prices.find('.form-group--pricetotal');
        if (discountPrice) {
            if (discountType === 'percent') {
                discountPrice += '%';
            }

            panelAjax('getTotalPrice', {price: price, discount: discountPrice}).then(function(res){
                $totalPrice.find('span').html(res.data.total_formatted)
                $totalPrice.show();
            });

        } else {
            $totalPrice.hide();
        }
    });
    $(document).on('change', 'select[name="CARDS_SORT"]', function(){
        const $panel = $(this).closest('.panel');
        $panel.data('force', true);
    });
});

$(document).ready(function(){
    $(document).on('change', '#panelContentForm input[name="SHOW_IMPORT_ELEMENTS"], #panelContentForm input[name="AUTO_BLOCK"]', function(){
        let $cardForm = $('#panelContentForm input[name="SHOW_ELEMENTS"]').closest('.panel-acc');
        let $tabsForm = $('#panelContentForm input[name="CONTENT_TABS"]').closest('.panel-row');

        if ($(this).prop('checked')) {
            $cardForm.hide();
            $tabsForm.hide();
        }
        else {
            $cardForm.show();
            $tabsForm.show();
        }
    });

    $(document).on('change', '#panelContent [name="IMPORT_FILTERS"]', function () {

        let code = $(this).find(':selected').data('code');

        let $formGroups = $(this).closest('.panel-acc').find('.form-group[data-code]');
        let $curFormGroup = $(this).closest('.panel-acc').find('.form-group[data-code="'+code+'"]');

        $formGroups.hide();
        $curFormGroup.show();
    });

    $(document).on('change', '#panelContent [name="IMPORT_ID"]', function(e){
        let id = $(this).val();
        let $iblockGroup = $(this).closest('.form-group');
        let $sectionGroup = $(this).closest('.panel-acc').find('[name="IMPORT_SECTION_ID"]').closest('.form-group');

        if (id > 0) {
            panelAjax('getIblockSectionsForSelect', {iblockId: id, name: 'IMPORT_SECTION_ID'}).then(function(res){
                if (res.data.html) {
                    $sectionGroup.children(':not(label)').remove();
                    $sectionGroup.append(res.data.html);
                }
                $iblockGroup.removeClass('col-12');
                $iblockGroup.addClass('col-6');
                $sectionGroup.show();
                initSelectric();
                initDatepicker();
                initMasks();
            });
        } else {
            $iblockGroup.addClass('col-12');
            $iblockGroup.removeClass('col-6');
            $sectionGroup.hide();
        }

        let config = $('.js-panel-ac[data-name="IMPORT_ELEM_IDS"]').data('additional');
        config['iblock'] = id;
        $('.js-panel-ac[data-name="IMPORT_ELEM_IDS"]').data('additional', config);
    });
    $(document).on('change', '[name="IMPORT_SECTION_ID"]', function(e){
        let id = $(this).val();

        let config = $('.js-panel-ac[data-name="IMPORT_ELEM_IDS"]').data('additional');
        config['section'] = id;
        $('.js-panel-ac[data-name="IMPORT_ELEM_IDS"]').data('additional', config);
    });
});
