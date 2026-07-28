$(document).ready(function(){
    let $blocksWrapper = $('#blocks_wrapper');

    $blocksWrapper.on('click', '.js-block-group', function(e){
        e.preventDefault();

        let $this = $(this);
        let $panel = $('#panelGroup');
        let id = parseInt($this.closest('.block').data('id'));
        let groupId = parseInt($this.closest('.block').data('group-id'));

        if (!id) {
            id = parseInt($this.closest('form').find('[name="id"]').val());
        }

        if (id > 0) {
            startBtnLoad($this);

            panelAjax('getGroupTemplate', {blockId: id, groupId: groupId}).then(function(res){
                if ($panel.find('.simplebar-content').length) {
                    $panel.find('.simplebar-content').html(res.data.html);
                } else {
                    $panel.find('.panel-body').html(res.data.html);
                }
                openPanel('panelGroup');

                endBtnLoad($this);
                initSimplebar();
                initSortable({
                    update: function(e, ui){
                        let sort = 1;
                        ui.item.closest('.panel-cards').find('.panel-card').each(function(){
                            let curSort = $(this).find('input[name$="_SORT"]').val();
                            let id = $(this).data('id');
                            if (curSort != sort && id) {
                                $(this).find('input[name$="_SORT"]').val(sort);
                                panelAjax('sortBlock', {id: id, sort: sort});
                            }
                            sort++;
                        });
                    }
                });
            });
        }
    });

    $(document).on('click', '.js-panel-variant-edit', function(e){
        e.preventDefault();

        let $panel = $(this).closest('.panel');
        let newBlockId = $(this).closest('.panel-card').data('id');
        let blockId = $panel.find('input[name="blockId"]').val();
        let groupId = $panel.find('input[name="id"]').val();

        let $groupBlock = $('.block[data-group-id="'+groupId+'"]');
        let $block;
        if (groupId && $groupBlock.length) {
            $block = $groupBlock;
        } else {
            $block = $('.block[data-id="'+blockId+'"]');
        }

        let $blockWrap = $block.parent();
        if ($block.length && newBlockId) {
            $block.addClass('loading');
            panelAjax('refreshBlock', {id: newBlockId}).then(function(res){
                $blockWrap.html(res.data.html);
                $block.find('.block_edit_label').html($block.data('name'));

                $blockWrap.attr('id', 'block_' + newBlockId);
                $blockWrap.attr('data-id', newBlockId);
                $blockWrap.data('id', newBlockId);
                $panel.find('input[name="blockId"]').val(newBlockId);

                closePanel($panel.attr('id'));
                $blockWrap.find('.js-block-content').click();

                initBlocks();
            });
        }
    });

    $(document).on('click', '.js-panel-variant-add', function(e){
        e.preventDefault();

        let $this = $(this);
        let $panel = $this.closest('.panel');
        let groupId = parseInt($panel.find('[name="id"]').val()) || 0;
        let blockId = parseInt($panel.find('[name="blockId"]').val()) || 0;

        if (blockId || groupId) {
            startBtnLoad($this);
            panelAjax('addVariant', {groupId: groupId, blockId: blockId}).then(function(res){
                $panel.find('[name="id"]').val(res.data.groupId);
                updatePanelGroup();

                if (!groupId) {
                    let $block = $('#block_' + blockId).find('.block');
                    $block.data('group-id', res.data.groupId);
                    $block.attr('data-group-id', res.data.groupId);
                }

                endBtnLoad($this);
            });
        }
    });

    $(document).on('click', '.js-panel-variant-deact', function(e){
        e.preventDefault();

        let $card = $(this).closest('.panel-card');
        let blockId = $card.data('id');
        if (blockId > 0) {
            panelAjax('hideBlock', {id: blockId}).then(function(res){
                $card.removeClass('active');
            });
        }
    });
    $(document).on('click', '.js-panel-variant-act', function(e){
        e.preventDefault();

        let $card = $(this).closest('.panel-card');
        let blockId = $card.data('id');
        if (blockId > 0) {
            panelAjax('showBlock', {id: blockId}).then(function(res){
                $card.addClass('active');
            });
        }
    });
    $(document).on('click', '.js-panel-variant-remove', function(e){
        e.preventDefault();

        let $panel = $(this).closest('.panel');
        let $card = $(this).closest('.panel-card');
        let blockId = $card.data('id');
        let groupId = $panel.find('[name="id"]').val() || 0;

        if ($panel.find('.panel-card').length > 1) {
            groupId = 0;
        }

        if (blockId > 0) {
            panelAjax('removeBlock', {id: blockId, groupId: groupId}).then(function(res){
                $card.remove();
                if (!$panel.find('.panel-card').length) {
                    closePanel($panel.attr('id'));
                    if (groupId > 0) {
                        $('.block[data-group-id="' + groupId + '"]').parent().slideUp();
                    } else {
                        $('#block_' + blockId).slideUp();
                    }
                }
            });
        }
    })

    $(document).on('click', '.js-panel-variant', function(e){
        e.preventDefault();

        let $this = $(this);
        let $panel = $('#panelVariant');
        let id = $this.closest('.panel-card').data('id');

        if (id > 0) {
            startBtnLoad($this);
            panelAjax('getVariantTemplate', {id: id}).then(function(res){
                if ($panel.find('.simplebar-content').length) {
                    $panel.find('.simplebar-content').html(res.data.html);
                } else {
                    $panel.find('.panel-body').html(res.data.html);
                }
                $panel.find('.panel-header-title').html(res.data.title);
                $panel.find('[type="submit"]').prop('disabled', true);
                openPanel('panelVariant');

                endBtnLoad($this);
                initForms();
                initSelectric();
                initDatepicker();
                initMasks();
            });
        }
    });

    $(document).on('submit', '#panelVariantForm', function(e){
        e.preventDefault();

        let $panel = $(this).closest('.panel');
        let $form = $(this);
        let $btn = $form.find('[type="submit"]');

        let dataArr = $form.serializeArray();
        let data = convertFormArrToObj(dataArr);

        let id = parseInt($form.find('[name="id"]').val());

        if (id > 0) {
            $panel.addClass('loading');

            panelAjax('editVariant', data).then(function(res){
                $panel.removeClass('loading');
                updatePanelGroup();
            });
        }
    });
});

function updatePanelGroup()
{
    let $panel  = $('#panelGroup');
    let groupId = $panel.find('[name="id"]').val();
    let blockId = $panel.find('[name="blockId"]').val();

    if (groupId > 0 || blockId > 0) {
        $panel.addClass('loading');
        panelAjax('getGroupTemplate', {groupId: groupId, blockId: blockId}).then(function(res){
            if ($panel.find('.simplebar-content').length) {
                $panel.find('.simplebar-content').html(res.data.html);
            } else {
                $panel.find('.panel-body').html(res.data.html);
            }
            $panel.removeClass('loading');

            initSimplebar();
            initSortable({
                update: function(e, ui){
                    let sort = 1;
                    ui.item.closest('.panel-cards').find('.panel-card').each(function(){
                        let curSort = $(this).find('input[name$="_SORT"]').val();
                        let id = $(this).data('id');
                        if (curSort != sort && id) {
                            $(this).find('input[name$="_SORT"]').val(sort);
                            panelAjax('sortBlock', {id: id, sort: sort});
                        }
                        sort++;
                    });
                }
            });
        });
    }
}
