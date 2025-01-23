<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Extract attributes with default values
$content        = $attributes['content'] ?? 'Enter your text';
$text_color     = $attributes['textColor'] ?? 'black';
$font_size      = (int) ( $attributes['fontSize'] ?? 16 );
$slides_per_view = (int) ( $attributes['slidesPerView'] ?? 1 );
$media          = $attributes['media'] ?? [];

// Start output buffering
ob_start();

?>

<div class="copyright-date-block">


	<?php if ( is_array( $media ) && ! empty( $media ) ) : ?>
        <div class="swiper-container" data-slides-per-view="<?php echo esc_attr( $slides_per_view ); ?>">
            <div class="swiper-wrapper">
				<?php foreach ( $media as $item ) : ?>
                    <div class="swiper-slide">
						<?php if ( isset( $item['url'] ) ) : ?>
                            <img
                                    src="<?php echo esc_url( $item['url'] ); ?>"
                                    alt="<?php echo esc_attr( $item['alt'] ?? __( 'Media preview', 'copyright-date-block' ) ); ?>"
                                    style="width: 100%;"
                            />
						<?php endif; ?>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
	<?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiperContainers = document.querySelectorAll('.swiper-container');
            swiperContainers.forEach(function (container) {
                new Swiper(container, {
                    slidesPerView: parseInt(container.getAttribute('data-slides-per-view'), 10) || 1,
                    spaceBetween: 10,
                });
            });
        });
    </script>
</div>





