$(document).ready(function () {
    $(document).on('click', '.js-block-tabs', function(e){
        e.preventDefault();

        const $this = $(this);
        const $panel = $('#panelTabs');
        const $block = $this.closest('.block');
        let blockId, tabId;

        if ($block.length) {
            blockId = parseInt($block.data('id'));
            tabId = $block.attr('data-tab');
        }
        else {
            const $form = $this.closest('form');
            blockId = parseInt($form.find('[name="id"]').val());
            tabId = $form.find('[name="tabId"]').val();
        }

        if (!blockId) {
            return;
        }

        if ($this.closest('.panel').length) {
            $this.closest('.panel').removeClass('open');
        }
        startBtnLoad($this);
        openTabsPanel($panel, blockId, tabId, function () {
            endBtnLoad($this);
        });
    });

    $(document).on('submit', '#panelTabsForm', function(e){
        e.preventDefault();

        const $panel = $(this).closest('.panel');
        const $cards = $panel.find('.panel-cards');
        const $cardTemplate = $cards.find('.panel-card.template');
        const blockId = parseInt($panel.find('[name="blockId"]').val());
        const $block = $('#block_' + blockId);

        $cardTemplate.remove();

        const formData = $(this).serializeArray();
        const data = convertFormArrToObj(formData);

        $block.find('.block').addClass('loading');
        $panel.addClass('loading');

        panelAjax('editTabs', data).then(function(res){
            $block.html(res.data.html);
            $block.find('.block_edit_label').html($block.data('name'));
            $block.find('.block').removeClass('loading');
            initBlocks();

            const tabId = $block.find('.block').attr('data-tab');
            openTabsPanel($panel, blockId, tabId, function () {
                $panel.find('button[type="submit"]').prop('disabled', true);
                $panel.removeClass('loading');
            });
        });
    });

    $(document).on('click', '.js-panel-tabs-add', function(e) {
        const $panelBody = prepareAction(e, $(this), true, '.panel-body');
        const $panelCards = $panelBody.find('.panel-cards');
        const $cardTemplate = $panelCards.find('.panel-card.template');
        const $newCard = $cardTemplate.clone();

        $newCard.removeClass('template');
        $panelCards.prepend($newCard);
    });

    $(document).on('click', '.js-panel-tabs-title', function(e){
        const $panelCard = prepareAction(e, $(this), false);
        $panelCard.find('.panel-card-body').toggle();
        $panelCard.toggleClass('open');
    });

    $(document).on('click', '.js-panel-tabs-deact', function(e){
        const $panelCard = prepareAction(e, $(this));
        $panelCard.find('[name="ACTIVE[]"]').val('N');
        $panelCard.removeClass('active');
    });

    $(document).on('click', '.js-panel-tabs-act', function(e){
        const $panelCard = prepareAction(e, $(this));
        $panelCard.find('[name="ACTIVE[]"]').val('Y');
        $panelCard.addClass('active');
    });

    $(document).on('click', '.js-panel-tabs-remove', function(e){
        const $panelCard = prepareAction(e, $(this));
        $panelCard.remove();
    });

    $(document).on('change', '.js-panel-tabs-name', function (e) {
        $(this).closest('.panel').find('button[type="submit"]').prop('disabled', false);
    })

    function prepareAction(e, $node, isEditAction = true, searchSelector = '.panel-card') {
        if (e) {
            e.preventDefault();
        }

        const $searchNodes = $node.closest(searchSelector);
        if (isEditAction) {
            $searchNodes.closest('.panel').find('button[type="submit"]').prop('disabled', false);
        }
        return $searchNodes;
    }

    function openTabsPanel($panel, blockId, tabId, func)
    {
        panelAjax('getTabsTemplate', {blockId: blockId, tabId: tabId}).then(function(res){
            if ($panel.find('.simplebar-content').length) {
                $panel.find('.simplebar-content').html(res.data.html);
            } else {
                $panel.find('.panel-body').html(res.data.html);
            }
            openPanel('panelTabs');
            $panel.find('input[name="id"]').val(blockId);
            $panel.find('input[name="tabId"]').val(tabId);
            initSimplebar();
            initSortable({
                update: function(e, ui){
                    $panel.find('button[type="submit"]').prop('disabled', false);
                }
            });

            func();
        });
    }
});
