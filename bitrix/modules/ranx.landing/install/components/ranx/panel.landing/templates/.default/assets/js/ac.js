$(document).ready(function(){
    $(document).on('click', '.js-panel-ac-item-remove', function(e){
        e.preventDefault();

        let id = $(this).siblings('[type="hidden"]').val();
        let $item = $(this).closest('.panel-ac-item');
        let $panel = $(this).closest('.panel');
        if (id) {
            $panel.find('[type="submit"]').prop('disabled', false);
            let $items = $(this).closest('.panel-ac-items');
            let data = $items.data('items');

            delete data[id];
            $item.remove();
        }
    });
});

function initPanelAc()
{
    $('.js-panel-ac').each(function(){
        let action = $(this).data('action');
        let additional = $(this).data('additional') || {};
        $(this).autocomplete({
            minLength: 2,
            source: function( request, response ) {
                let term = request.term;
    
                panelAjax(action, {query: term, additional: additional}).then(function(res){
                    if (res.status == 'success') {
                        response(res.data);
                    }
                });
            },
            select: function (e, ui) {
                let $items = $(e.target).siblings('.panel-ac-items');
                if ($items.length) {
                    let data = $items.data('items');
                    if (!data) {
                        data = {};
                    }
    
                    data[ui.item.id] = ui.item;
                    $items.data('items', data);
    
                    updatePanelAc($items);
                    $(e.target).val('');
    
                    return false;
                }
            }
        });
    });
}

function updatePanelAc($el)
{
    let items = $el.data('items');

    $example = $el.find('.panel-ac-item--example').clone();
    $el.html('');
    $el.append($example);

    if (items) {
        for (let itemId in items) {
            $current = $example.clone();
            $current.removeClass('panel-ac-item--example');
            $current.find('span').html(items[itemId].label);
            $current.find('[type="hidden"]').val(itemId);
            $el.append($current);
        }
    }
}
