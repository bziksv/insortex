// radiocolor js
$(document).ready(function() {
    $(document).on('click', '.radiocolor .radiocolor-item:not(.active)', function (e) {
        // get parent block(s)
        let $this = $(this);
        let $parent = $(this).closest('.radiocolor');

        if (colorGroup = $parent.data('group')) {
            $parent = $('.radiocolor[data-group="' + colorGroup + '"]');
        }
        $parent.find('input[type="hidden"]').val('');

        // save value only in current group
        let colorValue = $this.data('value');

        if (!colorValue) {
            return;
        }

        if (!$this.hasClass('js-color-picker')) {
            $(this).siblings('input[type="hidden"]').val(colorValue).trigger('change');
        }

        $parent.find('.radiocolor-item').removeClass('active');
        $(this).addClass('active');
    });
    $(document).on('click', '.radiocolor-item-big', function (e) {
        let $replacer = $(this).find('.sp-replacer');
        $replacer.addClass('sp-display-important');
        $replacer.click();
        $replacer.removeClass('sp-display-important');
    });
    $(window).on('scroll', function () {
        $('.sp-container').addClass('sp-hidden');
    });
});