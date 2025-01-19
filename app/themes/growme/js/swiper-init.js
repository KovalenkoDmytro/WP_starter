
$(document).ready(function () {
    $('.slickSlider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 0, // No delay between scrolls
        speed: 5000, // Adjust speed for smoothness (higher = slower)
        cssEase: 'linear', // Ensure smooth continuous scrolling
        variableWidth: true, // Allow content to adjust dynamically
        arrows: false, // No navigation arrows
        pauseOnHover: false, // Prevent pausing on hover
        pauseOnFocus: false, // Prevent pausing on focus
    });

    $('.slider-container').each(function () {
        // Cache the current slider
        const $container = $(this);
        const $slider = $container.find('.slider_items');
        const $prevArrow = $container.find('.custom-prev');
        const $nextArrow = $container.find('.custom-next');

        // Initialize Slick for the current slider
        $slider.slick({
            slidesToShow: 2.3,
            slidesToScroll: 1,
            infinite: false,
            arrows: true,
            prevArrow: $prevArrow, // Assign the specific custom previous arrow
            nextArrow: $nextArrow, // Assign the specific custom next arrow
        });
    });
});


