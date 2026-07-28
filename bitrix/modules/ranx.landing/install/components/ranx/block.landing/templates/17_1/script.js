$(document).ready(function() {
    $('.block17-1').each(function () {
        let component = $(this);
        component.find('[data-tariffs_slider]').each(function () {
            let tariffsSliderPrevBtn = $(this).siblings('[data-prev-slide]'),
                tariffsSliderNextBtn = $(this).siblings('[data-next-slide]'),
                tariffsSlider = $(this);

            if (tariffsSlider.hasClass('slick-initialized'))
                return;

            $(tariffsSlider).on('init', function () {
                $(this).find('.slick-dots li').each(function () {
                    $(this).addClass('theme-bg');
                });

                setTimeout(function () {
                    setHeight();
                    $(component).removeClass('loading');
                });
            });

            $(component).on('tabs.change', function() {
                $(tariffsSlider).slick('setPosition');
                setHeight();
            });

            function setHeight() {
                equalHeight(component.find('.tariff-text'));
                equalHeight(tariffsSlider.find('.tariff-prices_wrapper'));
            }

            $(tariffsSlider).slick({
                infinite: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                arrows: true,
                dots: true,
                cssEase: 'linear',
                prevArrow: $(tariffsSliderPrevBtn),
                nextArrow: $(tariffsSliderNextBtn),
                responsive: [
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    }
                ]
            });
        });
    });
});

function equalHeight(selector) {
    $(selector).height('');
    let highestBox = 0;
    $(selector).each(function() {
        if ($(this).height() > highestBox) {
            highestBox = $(this).height();
        }
    });
    $(selector).height(highestBox);
}
