function panelAjax(action, post = {})
{
    let settingId = getSettingId();
    let attrs = {data: {post: post, settingId: settingId}};

    if (action.slice(-8) !== 'Template') {
        attrs.mode = 'ajax';
    }

    return rxRunComponentAction('panel', action, attrs);
}

function updatePanelContent(id, $btn = false, force = false)
{
    // if this is the same block id, just open it
    const $panel = $('#panelContent');
    const curId = parseInt($panel.find('[name="id"]').val());
    const curTabId = $panel.find('[name="tabId"]').val();
    const tabId = $(`#block_${id}`).children('.block').attr('data-tab') || '';

    if (!force && parseInt(id) === curId && tabId === curTabId) {
        updateAddBtnState();
        openPanel('panelContent');
        $panel.removeClass('loading');

        if ($btn)
            endBtnLoad($btn);

        return;
    }

    $panel.addClass('loading');
    panelAjax('getContentTemplate', {id: id, tabId: tabId}).then(function(res){
        $panel.find('button[type="submit"]').prop('disabled', true);
        if ($panel.find('.simplebar-content').length) {
            $panel.find('.simplebar-content').html(res.data.html);
        } else {
            $panel.find('.panel-body').html(res.data.html);
        }
        openPanel('panelContent');
        $panel.find('input[name="id"]').val(id);
        $panel.find('input[name="tabId"]').val(tabId);
        $panel.find('.js-block-tabs').toggleClass('hidden', !(parseInt(tabId || '0') > 0));
        $panel.removeClass('loading');
        $panel.data('force', false);

        if ($btn)
            endBtnLoad($btn);

        initMasks();
        initForms();
        initSelectric();
        initDatepicker();
        initSortable({
            update: function(e, ui){
                let sort = 1;
                ui.item.closest('.panel-cards').find('.panel-card').each(function(){
                    $(this).find('input[name$="_SORT"]').val(sort);
                    sort++;
                });
                $panel.find('button[type="submit"]').prop('disabled', false);
            }
        });
        initGallerySortable();
        initPanelAc();
    });
}

function updatePanelCard(id, blockId, tabId, $btn = false, $loading = false)
{
    panelAjax('getCardTemplate', {id: id, blockId: blockId}).then(function (res) {

        if ($('#panelCard').find('.simplebar-content').length) {
            $('#panelCard').find('.simplebar-content').html(res.data.html);
        } else {
            $('#panelCard').find('.panel-body').html(res.data.html);
        }
        $('#panelCard').attr('data-force', '');
        $('#panelCard .panel-header-title').html(res.data.title);
        $('#panelCard [name="id"]').val(id);
        $('#panelCard [name="blockId"]').val(blockId);
        $('#panelCard [name="tabId"]').val(tabId);
        $('#panelCard [type="submit"]').prop('disabled', true);
        openPanel('panelCard');

        if ($btn) {
            endBtnLoad($btn);
        }
        initForms();
        initSelectric();
        initDatepicker();
        initMasks();
        initSortable({
            update: function (e, ui) {
                $('#panelCard [type="submit"]').prop('disabled', false);

                if (e && $(e.target).hasClass('form-group-pics-wrapper')) {
                    $(e.target).closest('.rx-upload-files').trigger('changeFiles');
                }
            }
        });
        if ($loading) {
            $loading.removeClass('loading');
        }
    });
}

function openPanelTab(tabId)
{
    var $link = $('#panel .panel-menu a[href="'+tabId+'"]');

    if (!$link.hasClass('active')) {
        $link.trigger('click');
    }

    if (!$('#panel').hasClass('open')) {
        $('.panel').removeClass('open');
        openPanel('panel');
    }

    initColorPicker($('#panel'));
}

function openPanel(id)
{
    if (id === 'panelContent' && $('#panelCard.open.active').length) {
        return;
    }
    if (id === 'panelGroup' && $('#panelVariant.open.active').length) {
        return;
    }
    $('.panel').removeClass('active');
    $('#' + id).addClass('open active');
}

function closePanel(id)
{
    let $panel = $('#' + id);
    if (id === 'panel') {
        $panel.data('replace', '');
        $panel.removeAttr('data-replace');
    }
    $panel.removeClass('open active');
    $('.panel.open').last().addClass('active');
}

function getTopLevelBlockWraps()
{
    // Unique landing blocks — nested .block-wrap can appear when block HTML is invalid
    let seen = {};
    return $('#blocks_wrapper').find('.block-wrap[data-id]').filter(function () {
        let id = String($(this).attr('data-id') || '');
        if (!id || seen[id]) {
            return false;
        }
        seen[id] = true;
        return true;
    });
}

function getSortedBlockWraps()
{
    return getTopLevelBlockWraps().get().sort(function (a, b) {
        return (parseInt($(a).attr('data-order'), 10) || 0) - (parseInt($(b).attr('data-order'), 10) || 0);
    });
}

function setBlockWrapOrder($el, order)
{
    $el.attr('data-order', order).css('order', order);
}

function swapBlockWrapPositions($a, $b)
{
    let orderA = parseInt($a.attr('data-order'), 10) || 0;
    let orderB = parseInt($b.attr('data-order'), 10) || 0;

    setBlockWrapOrder($a, orderB);
    setBlockWrapOrder($b, orderA);

    // Physical DOM swap — visual move must not depend only on flex order CSS
    let marker = document.createElement('div');
    $a.before(marker);
    $b.before($a);
    $(marker).replaceWith($b);

    updateBlockUpAndDown();
}

function updateBlockUpAndDown()
{
    let wraps = getSortedBlockWraps();

    getTopLevelBlockWraps().removeClass('block-wrap-first block-wrap-last');

    if (!wraps.length) {
        return;
    }

    if (wraps.length) {
        $(wraps[0]).addClass('block-wrap-first');
        $(wraps[wraps.length - 1]).addClass('block-wrap-last');
    }
}

function downloadVirtualFile(filename, text)
{
    let element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    element.setAttribute('download', filename);

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
}

function initDatepicker()
{
    $('.panel .js-datepicker').each(function (){
        let input   = $(this);
        let options = {};

        if(input.data('datepicker') !== undefined)
            return;

        if(input.data('format'))
            options['dateFormat'] = input.data('format')

        input.datepicker(options);

        let picker = input.data('datepicker');
        if(input.data('default-date'))
            picker.selectDate(new Date(input.data('default-date')));

        picker.update('onSelect', function(formattedDate, date, inst){
            $(inst.el).change();
        })
    });
}

function initSelectric()
{
    $('.panel select:not(.no-selectric)').selectric({
        maxHeight: 200
    });
}

function initSortable(options = {})
{
    $('.js-sortable').sortable(options);
}

function initColorPicker($el) {
    $el.find('.js-color-picker').each(function(){
        let $this = $(this);

        $this.spectrum({
            preferredFormat: 'hex',
            showInput: true,
            clickoutFiresChange: false,
            chooseText: BX.message('RX_PANEL_LANDING_TEMPLATE_JS_CP_APPLY'),
            cancelText: BX.message('RX_PANEL_LANDING_TEMPLATE_JS_CP_CANCEL'),
            beforeShow: function() {
                let $container = $('.sp-container');
                $container.find('.sp-choose').addClass('btn btn-primary btn-xs btn-block');
                $container.find('.sp-cancel').addClass('btn btn-transparent btn-xs btn-block');
            },
            show: function(){
                let $radioColorItem = $this.closest('.radiocolor-item');
                let width = $radioColorItem.outerWidth();
                let height = $radioColorItem.outerHeight();

                $this.spectrum('option', 'offset', {
                    left: $radioColorItem.offset().left + width - $('.sp-container:not(.sp-hidden)').width(),
                    top: $radioColorItem.offset().top + height,
                });
            },
            change: function(color) {
                let hexColor = color.toHexString();
                let $radioColor = $this.closest('.radiocolor')
                let $radioColorItem = $this.closest('.radiocolor-item');
                let radioColorGroup = $radioColor.data('group');
                let $spReplacer = $radioColorItem.find('.sp-replacer');

                $radioColorItem.data('value', hexColor);
                $('.radiocolor[data-group="'+radioColorGroup+'"]').find('.radiocolor-item').removeClass('active');
                $radioColorItem.addClass('active');
                $radioColorItem.find('.radiocolor-item-text').html(hexColor);
                $radioColor.find('input[type="hidden"]').val(hexColor);
                $radioColor.find('input[type="hidden"]').trigger('change');
                $spReplacer.show();
            },
            move: function(color) {
                let hexColor = color.toHexString();
                $this.closest('.radiocolor-item').find('.radiocolor-item-text').html(hexColor);
            },
        });
    });
}

function updateHeaderTransparency()
{
    if ($('body.js-header-is-transparent').length === 0) {
        return;
    }

    let $blocksWrapper = $('#blocks_wrapper');
    let minOrder;
    $blocksWrapper.children().each(function () {
        let order = parseInt($(this).data('order'));
        if (!minOrder || minOrder > order) {
            minOrder = order;
        }
    });

    if (!minOrder) {
        return;
    }

    let firstBlock = $blocksWrapper.children('[data-order='+minOrder+']');
    if (firstBlock.find('.block-under-header').length) {
        $('body').addClass('header-is-transparent');
    }
    else{
        $('body').removeClass('header-is-transparent');
    }
}

function updateAddBtnState()
{
    const $sectionCards = $('#panelContent').find('.panel-cards');
    const maxCardCount = parseInt($sectionCards.data('max-count'));

    if (!$sectionCards.length || !maxCardCount) {
        return false;
    }

    if ($sectionCards.find('.panel-card').length < maxCardCount) {
        $sectionCards.siblings('.js-panel-card-add').show();
    }
}
