// aarray fields js
$(document).ready(function(){
    $(document).on('click', '.js-add-aarray-field', function(e){
        e.preventDefault();

        let $parent = $(this).parent();
        let $last = $parent.find('.aarray-field').last();
        let $newEl = $last.clone();

        $newEl.find('input[type="text"]').each(function(){
            $(this).val('');

            let index = $(this).data('index');
            index++;
            $(this).data('index', index);
            $(this).attr('data-index', index);

            let name = $(this).attr('name');
            let firstBracket = name.indexOf('[');
            let secondBracket = name.indexOf(']');
            let firstPart = name.slice(0, firstBracket + 1);
            let secondPart = name.slice(secondBracket);

            name = firstPart + index + secondPart;

            $(this).attr('name', name);
        });

        $last.after($newEl);
        initMasks();
    });
});
