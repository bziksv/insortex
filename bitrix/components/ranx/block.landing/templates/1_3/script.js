$(document).ready(function () {
    $('.block1-3-slider').each(function () {
        const $this = $(this);

        $this.not('.slick-initialized').slick({
            autoplay: $this.data('autoplay') || false,
            arrows: false,
            cssEase: 'linear',
            adaptiveHeight: true
        });
        if($this.slick('getSlick').slideCount > 1){
            $this.slick('slickSetOption', 'dots', true, true);
        }
        
        $this.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            const nextSlideChange = $this.find('.header-light-target-1-' + nextSlide);
            setWhiteHeader(nextSlideChange);
        });
        $this.find('.slick-dots li').each(function () {
            $this.find('button').addClass('theme-bg');
        });

        if ($this.hasClass('block-sort-1')) {
            setWhiteHeader($('.header-light-target-1-0'));
        }
    });

    function setWhiteHeader(nextSlide) {
        if (!$('body').hasClass('header-is-transparent')) {
            return;
        }
        if ($(nextSlide).hasClass('text-light-desktop')) {
            $('header').addClass('header-light');
        } else {
            $('header').removeClass('header-light');
        }
    }
});
