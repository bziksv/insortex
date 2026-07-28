$(document).ready(function(){
    $(document).on('click', '.js-panel-menu-edit', function(e){
        e.preventDefault();

        let $panel = $('#panelMenu');
        let type = $(this).data('type');
        let path = $(this).data('path');

        let $blocksWrapper = $('#blocks_wrapper');
        let landingId = $blocksWrapper.data('landing-id');
        let mode = $blocksWrapper.data('mode');

        let data = {
            type: type,
            path: path,
            landingId: landingId,
            mode: mode,
        }

        if (type.length) {
            $panel.addClass('loading');
            panelAjax('getMenuTemplate', data).then(function(res){
                $panel.find('button[type="submit"]').prop('disabled', true);
                if ($panel.find('.simplebar-content').length) {
                    $panel.find('.simplebar-content').html(res.data.html);
                } else {
                    $panel.find('.panel-body').html(res.data.html);
                }
                $panel.find('input[name="type"]').val(type);
                $panel.removeClass('loading');

                openPanel('panelMenu');

                initSortable({
                    update: function(e, ui){
                        $panel.find('button[type="submit"]').prop('disabled', false);
                    }
                });
                initSelectric();
                initDatepicker();
                initTooltip();
                initMasks();
            });
        }
    });
    $(document).on('click', '#panelMenu .panel-card-title', function(e){
        e.preventDefault();

        $(this).closest('.panel-card').find('.panel-card-body').toggle();
        $(this).closest('.panel-card').toggleClass('open');
    });
    $(document).on('change', '.js-panel-menu-wide', function(){
        let val = $(this).prop('checked') ? 'Y' : 'N';
        $(this).closest('.panel-card').find('[name="ITEM_WIDE[]"]').val(val);
    });
    $(document).on('click', '.js-panel-menu-act', function(e){
        e.preventDefault();
        let $card = $(this).closest('.panel-card');
        $card.addClass('active');
        $card.find('[name="ITEM_HIDDEN[]"]').val('N');
        $(this).closest('.panel').find('button[type="submit"]').prop('disabled', false);
    });
    $(document).on('click', '.js-panel-menu-deact', function(e){
        e.preventDefault();
        let $card = $(this).closest('.panel-card');
        $card.removeClass('active');
        $card.find('[name="ITEM_HIDDEN[]"]').val('Y');
        $(this).closest('.panel').find('button[type="submit"]').prop('disabled', false);
    });
    $(document).on('click', '.js-panel-menu-remove', function(e){
        e.preventDefault();
        $(this).closest('.panel').find('button[type="submit"]').prop('disabled', false);
        $(this).closest('.panel-card').remove();
    });
    $(document).on('click', '.js-panel-menu-add', function(e){
        e.preventDefault();

        let $cards = $(this).siblings('.panel-cards');
        let $newCard = $cards.find('.panel-card--example').clone();
        let cardsCount = $cards.find('.panel-card:not(.panel-card--example)').length;

        let newId = cardsCount + 1;
        let newCheckboxId = 'panelMenuItemWide_' + newId;
        while ($('#' + newCheckboxId).length) {
            newId++;
            newCheckboxId = 'panelMenuItemWide_' + newId;
        }

        $newCard.removeClass('panel-card--example');
        $newCard.find('[id^="panelMenuItemWide_"]').attr('id', newCheckboxId);
        $newCard.find('[for^="panelMenuItemWide_"]').attr('for', newCheckboxId);
        $newCard.find('select').removeClass('no-selectric');

        $cards.prepend($newCard);
        $newCard.find('.panel-card-title').click();

        initSelectric();
        initDatepicker();
        initMasks();
        $(this).closest('.panel').find('button[type="submit"]').prop('disabled', false);
    });
    $(document).on('change', '.js-panel-menu-linktype', function(e){
        let type = $(this).val();
        $(this).closest('.panel-card-body').find('[data-linktype]').hide();
        $(this).closest('.panel-card-body').find('[data-linktype="'+type+'"]').show();
    });
    $(document).on('change', '#panelMenu [data-linktype="landing"] select', function(){
        let landing = $(this).val();
        let $panelCard = $(this).closest('.panel-card');
        $panelCard.find('[name="ITEM_LINK[]"]').val(landing);
        resetSelect($panelCard, 'anchor');
    });
    $(document).on('change', '#panelMenu [data-linktype="anchor"] select', function() {
        const blockId = $(this).val();
        const link = window.location.pathname + "#block_" + blockId;
        const $paneCard = $(this).closest('.panel-card');
        $paneCard.find('[name="ITEM_LINK[]"]').val(link);
        resetSelect($paneCard, 'landing');
    });
    $(document).on('change keyup paste', '#panelMenu [data-linktype="custom"] input', function() {
        const $panelCard = $(this).closest('.panel-card');
        resetSelect($panelCard, 'landing');
        resetSelect($panelCard, 'anchor');
    });
    $(document).on('submit', '#panelMenuForm', function(e){
        e.preventDefault();

        let $panel = $(this).closest('.panel');
        let $cards = $panel.find('.panel-cards');
        let $cardExample = $cards.find('.panel-card--example');
        let $cardExampleCopy = $cardExample.clone();

        $cardExample.remove();

        let formData = $(this).serializeArray();
        let data = convertFormArrToObj(formData);

        $cards.prepend($cardExampleCopy);

        $panel.addClass('loading');
        panelAjax('editMenu', data).then(function(res){
            document.location.reload();
        });
    });

    function resetSelect($card, selectType) {
        $card.find('[data-linktype="'+selectType+'"] select').prop('selectedIndex', 0).selectric('refresh');
    }
});
