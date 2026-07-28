// panel-select js
$(document).ready(function(){
    $(document).on('click', '.panel-select-option', function(e){
        let $panelSelect = $(this).closest('.panel-select');
        $panelSelect.find('.panel-select-option').removeClass('active');
        $(this).addClass('active');

        let val = $(this).data('value');
        let oldVal = $panelSelect.find('input[type="hidden"]').val();
        $panelSelect.find('input[type="hidden"]').val(val);
        if (val != oldVal) {
            $panelSelect.find('input[type="hidden"]').trigger('change');
        }
    });
});
