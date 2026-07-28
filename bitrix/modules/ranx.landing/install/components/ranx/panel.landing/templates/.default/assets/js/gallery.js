$(document).ready(function(){
    $(document).on('click', '.js-panel-gallery-card-remove', function(e) {
        e.preventDefault();

        const $card = $(this).closest('.panel-gallery-card');
        const $cards = $card.closest('.panel-gallery-cards');
        const $cardEditor = $cards.find('.panel-gallery-card-edit');
        const $form = $cards.closest('form');

        closeCardEditor($cardEditor);
        $cards.append($cardEditor);

        let order = 0;
        $cards.find('.panel-gallery-card').each(function () {
            $(this).attr('data-order', order);
            $(this).find('[name$="_SORT"]').val(order);
            order++;
        });

        $card.remove();
        $form.find('[type="submit"]').prop('disabled', false);
    });

    $(document).on('click', '.js-panel-gallery-card-deact', function(e) {
        e.preventDefault();
        const $card = $(this).closest('.panel-gallery-card');
        const cardId = $card.data('id');
        $card.removeClass('active');
        $card.find('[name="ELEMENT_'+cardId+'_ACTIVE"]').val('N');
        $card.closest('form').find('[type="submit"]').prop('disabled', false);
    });

    $(document).on('click', '.js-panel-gallery-card-act', function(e) {
        e.preventDefault();
        const $card = $(this).closest('.panel-gallery-card');
        const cardId = $card.data('id');
        $card.addClass('active');
        $card.find('[name="ELEMENT_'+cardId+'_ACTIVE"]').val('Y');
        $card.closest('form').find('[type="submit"]').prop('disabled', false);
    });

    $(document).on('click', '.js-panel-gallery-card-edit', function(e){
        e.preventDefault();

        const $card = $(this).closest('.panel-gallery-card');
        const $cards = $card.closest('.panel-gallery-cards');
        const $cardEditor = $cards.find('.panel-gallery-card-edit');
        const $pics = $cardEditor.find('.form-group-pics');
        const cardId = $card.attr('data-id');
        const imgSrc = $card.find('.panel-gallery-card-img').attr('src');
        const imgName = $card.find('.panel-gallery-card-name').text();
        const imgSize = $card.find('.panel-gallery-card-size').text();

        closeCardEditor($cardEditor);

        let order = $card.data('order');
        if (order === undefined || !cardId) {
            return;
        }
        if (cardId === $cardEditor.attr('data-card-id')) {
            $cardEditor.removeAttr('data-card-id');
            return;
        }

        do {
            order++;
        } while (order % 4 !== 1 && $cards.find('[data-order="'+order+'"]').length);
        $cards.find('[data-order="'+(order-1)+'"]').after($cardEditor);

        $cardEditor.find('.form-control').each(function () {
            const inputName = $(this).attr('name');
            const cardInputName = 'ELEMENT_' + cardId + inputName.substring('ELEMENT'.length);
            const cardInputValue = $card.find('[name="'+cardInputName+'"]').attr('value');
            $(this).val(cardInputValue);
            $(this).trigger('change');
        });

        $pics.removeClass('hidden');
        $pics.find('img').attr('src', imgSrc);
        $pics.find('.form-group-pics-info-ext').text(imgName);
        $pics.find('.form-group-pics-info-size').text(imgSize);
        if (!imgSrc) {
            $pics.addClass('hidden');
        }

        $cardEditor.attr('data-card-id', cardId);
        $card.addClass('selected');
        $cardEditor.removeClass('hidden');
    });

    $(document).on('click', '.js-panel-gallery-edit-close', function(e) {
        e.preventDefault();

        const $cardEditor = $(this).closest('.panel-gallery-card-edit');
        closeCardEditor($cardEditor);
    });

    $(document).on('click', '.js-panel-gallery-edit-img-remove', function(e) {
       e.preventDefault();

        if (confirm(BX.message('RX_PANEL_LANDING_TEMPLATE_JS_CONFIRM'))) {
            const $cardEditor = $(this).closest('.panel-gallery-card-edit');
            const $card = getCard($cardEditor);

            removePicture($card);
            $cardEditor.find('.form-group-pics').addClass('hidden');
        }
    });

    $(document).on('change', '.panel-gallery-edit-file-input', async function (e){
        const imageParams = getImageParams();
        const file = await loadFile($(this), imageParams);
        const $cardEditor = $(this).closest('.panel-gallery-card-edit');
        const $card = getCard($cardEditor);
        const cardId = $card.data('id');
        if (!file || !$card) {
            return false;
        }

        $card.find('[name="ELEMENT_'+cardId+'_NAME"]').val(file.name);
        $card.find('[name="ELEMENT_'+cardId+'_DETAIL_PICTURE"]').val(file.data);
        $card.find('.panel-gallery-card-img').replaceWith('<img src="'+file.data+'" class="panel-gallery-card-img">');
        $card.find('.panel-gallery-card-name').text(file.name);
        $card.find('.panel-gallery-card-size').text(formatSize(file.size));

        $card.find('.js-panel-gallery-card-edit').trigger('click');
    });

    $(document).on('click', '.js-panel-gallery-edit-img-replace', function(e) {
        $(this).closest('.form-group-pics').siblings('.custom-file').children('label').trigger('click');
    });

    $(document).on('change keyup paste', '.panel-gallery-card-edit [name="ELEMENT_PROPERTY_PICTURE_TITLE"]', processInput);
    $(document).on('change keyup paste', '.panel-gallery-card-edit [name="ELEMENT_PROPERTY_PICTURE_ALT"]', processInput);
    $(document).on('change keyup paste', '.panel-gallery-card-edit [name="ELEMENT_PREVIEW_TEXT"]', processInput);

    $(document).on('change', '.panel-gallery-upload-input', async function() {
        const $panelContent = $('#panelContent');
        $panelContent.addClass('loading');

        const imageParams = getImageParams();
        const files = await loadFiles($(this).prop('files'), imageParams);
        const $panelGalleryBody = $(this).closest('.panel-gallery-acc-body');
        const $cards = $panelGalleryBody.find('.panel-gallery-cards');
        const $cardEditor = $cards.find('.panel-gallery-card-edit');
        const $cardTemplate = $panelGalleryBody.find('.panel-gallery-card.template');

        closeCardEditor($cardEditor);

        let maxOrder = 0;
        let minId = 0;
        $cards.find('.panel-gallery-card').each(function () {
            maxOrder = Math.max(maxOrder, $(this).data('order'));
            minId = Math.min(minId, $(this).data('id'));
        });

        $.each(files, function () {
            const $newCard = $cardTemplate.clone();
            const newOrder = ++maxOrder;
            const newId = --minId;

            const updateFields = [{field:'ID', value: newId}, {field:'SORT', value: newOrder}, {field:'ACTIVE', value:'Y'},
                {field:'NAME', value: this.name}, {field:'DETAIL_PICTURE', value: this.data}];
            $.each(updateFields, (i, obj) => $newCard.find('[name="'+obj.field+'"]').val(obj.value));

            $newCard.children('input[type="hidden"]').each(function () {
                const nameField = $(this).attr('name');
                $(this).attr('name', 'ELEMENT_'+newId+'_'+nameField);
            });

            $newCard.find('.panel-gallery-card-img').attr('src', this.data);
            $newCard.find('.panel-gallery-card-name').text(this.name);
            $newCard.find('.panel-gallery-card-size').text(formatSize(this.size));

            $newCard.attr('data-id', newId);
            $newCard.attr('data-order', newOrder);
            $newCard.removeClass('template');
            $newCard.addClass('ui-sortable-handle');

            $cards.append($newCard);
        });

        $panelGalleryBody.closest('form').find('[type="submit"]').prop('disabled', false);
        $panelGalleryBody.closest('.panel').attr('data-force', true);
        $panelContent.removeClass('loading');
    });

    $(document).on('dragenter', '.panel-gallery-upload', function () {
        $(this).addClass('drop');
    });

    $(document).on('dragleave', '.panel-gallery-upload', function () {
        $(this).removeClass('drop');
    });

    $(document).on('drop', '.panel-gallery-upload', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const $fileInput = $(this).find('.panel-gallery-upload-input');
        $fileInput.prop('files', e.originalEvent.dataTransfer.files);
        $fileInput.trigger('change');

        $(this).removeClass('drop');
        return false;
    });

    $(document).on('dragover', '.panel-gallery-upload', function(e) {
        e.preventDefault();
    });

    function removePicture($card) {
        const cardId = $card.attr('data-id');

        $card.find('.panel-gallery-card-img').replaceWith('<div class="panel-gallery-card-img"></div>');
        $card.find('.panel-gallery-card-name').text(' ');
        $card.find('.panel-gallery-card-size').text(' ');

        $card.find('[name="ELEMENT_'+cardId+'_DETAIL_PICTURE"]').val('del');
        $card.closest('form').find('[type="submit"]').prop('disabled', false);
    }


    function closeCardEditor($cardEditor) {
        const $card = getCard($cardEditor);

        $card.removeClass('selected');
        $cardEditor.attr('data-card-id', '');
        $cardEditor.addClass('hidden');
    }

    function getCard($cardEditor) {
        const cardId = $cardEditor.attr('data-card-id');
        const $cards = $cardEditor.closest('.panel-gallery-cards');
        return $cards.find('.panel-gallery-card[data-id="'+cardId+'"]');
    }

    function processInput() {
        const $cards = $(this).closest('.panel-gallery-cards');

        const inputName = $(this).attr('name');
        const cardId = $(this).closest('.panel-gallery-card-edit').attr('data-card-id');
        const cardInputName = 'ELEMENT_' + cardId + inputName.substring('ELEMENT'.length);

        if (!cardId) {
            return;
        }

        $cards.find('[name="'+cardInputName+'"]').val($(this).val());
    }

    function getImageParams() {
        return {
            exts: ['.jpg', '.jpeg', '.gif', '.png'],
            mime: 'image',
        };
    }
});

function initGallerySortable() {
    if (!$('#panelContent').find('.panel-gallery-acc-body').length) {
        return;
    }

    $('.js-gallery-sortable').sortable({
        update: function(e, ui){
            let sort = 1;
            ui.item.closest('.panel-gallery-cards').find('.panel-gallery-card').each(function(){
                $(this).attr('data-order', sort);
                $(this).find('input[name$="_SORT"]').val(sort);
                sort++;
            });
            $('#panelContent').find('button[type="submit"]').prop('disabled', false);
        },

        start: function(e, ui){
            const $cards = ui.item.closest('.panel-gallery-cards');
            const $cardEditor = $cards.children('.panel-gallery-card-edit');
            const cardId = $cardEditor.attr('data-card-id');
            const $card = $cards.find('.panel-gallery-card[data-id="'+cardId+'"]');

            $card.removeClass('selected');
            $cardEditor.addClass('hidden');
            $cards.append($cardEditor);
        }
    });
}
