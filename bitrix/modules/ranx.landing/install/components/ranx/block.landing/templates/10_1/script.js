$(document).ready(function () {
    $(document).on('click', '.block10-slider .slick-cloned', function(e) {
        var $slides = $(this).parent().children('.slick-slide:not(.slick-cloned)');
        $slides.eq(($(this).attr("data-slick-index") || 0) % $slides.length)
            .trigger("click.fb-start", { $trigger: $(this) });

        return false;
    });

    $('.block10-slider').each(function () {

        var sliderEl = $(this).attr('slider-target');
        var slider = sliderEl+' .block10-1-items';

        if ($(slider).hasClass('slick-initialized')) {
            return;
        }

        $().fancybox({
            selector : slider + ' .slick-slide:not(.slick-cloned) .fancybox-slider',
            backFocus : false,
            afterShow : function(instance, current) {
                current.opts.$orig.closest(".slick-initialized").slick('slickGoTo', parseInt(current.index), true);
            }
        });

        $(slider).on('init reInit afterChange', function(e, slick, currentSlide){
            if (typeof lazyLoadInstance !== 'undefined') {
                lazyLoadInstance.update();
            }

            $(sliderEl).find('.slick-dots li button').each(function () {
                $(this).addClass('theme-bg');
            });

            const slideCount = slick.slideCount;
            if (slideCount < 2 || slideCount > 9) {
                $(sliderEl).find('.slick-dots').addClass('hide-dots');
            }

            if (slideCount > 9) {
                const $counter = $(slider).closest('.block').find('.slide-counter');

                $counter.find('.current-slide').text((currentSlide ? currentSlide : 0) + 1);
                $counter.find('.total-slide').text(slideCount);
                $counter.show();
            }
        });

        $(slider).not('.slick-initialized').slick({
            arrows: true,
            centerMode: true,
            centerPadding: '400px',
            dots: true,
            cssEase: 'linear',
            prevArrow: sliderEl+' .arrow-prev',
            nextArrow: sliderEl+' .arrow-next',
            responsive: [
                {
                  breakpoint: 1400,
                  settings: {
                    slidesToShow: 1,
                    centerMode: true,
                    centerPadding: '100px',
                  }
                }, 
                {
                    breakpoint: 1600,
                    settings: {
                      slidesToShow: 1,
                      centerMode: true,
                      centerPadding: '200px',
                    }
                  },
                  {
                    breakpoint: 1200,
                    settings: {
                      slidesToShow: 1,
                      centerMode: false,
                    }
                  }
            ]
        });
    });
});
