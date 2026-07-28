$(document).ready(function () {
    $(document).on('click', '.js-fixed-search', function (e) {
        e.preventDefault();

        const $fixedSearch = $('.rx-fixed-search');
        $fixedSearch.addClass('show');
        $fixedSearch.find('.search-input').focus();

        $('body').addClass('fixed-search-show');
        $('<div class="rx-fixed-search-backdrop"></div>').appendTo('body');
    });

    $(document).on('click', '.rx-fixed-search .search-close, .rx-fixed-search-backdrop', function (e) {
        e.preventDefault();

        $('.rx-fixed-search-backdrop').remove();
        $('body').removeClass('fixed-search-show');
        $('.rx-fixed-search').removeClass('show');
    });
});
