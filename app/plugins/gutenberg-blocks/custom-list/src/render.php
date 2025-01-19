<?php
/**
 * Render callback for the Custom List Block.
 *
 * @param array $attributes Block attributes.
 *
 * @return string HTML content to render.
 */

function render_custom_block( array $attributes ): string {
    var_dump('render_custom_block');
    $block_id = uniqid('id_');
	// Get listItems from attributes or default to an empty array
	$list_items = $attributes['listItems'] ?? [];

	// Validate list items
	if ( ! is_array( $list_items ) ) {
		return '<p>' . esc_html__( 'Invalid list data.', 'custom-list-block' ) . '</p>';
	}

	// Start output buffering
	ob_start();
	?>

	<?php if($attributes['backgroundColorFullWidth'] ) :?>

        <style>
            .custom-list--render[id='<?= $block_id ?>']::before {
                content: ' ';
                position: absolute;
                background-color: <?= esc_attr($attributes['backgroundColor']) ?>;
                width: 100vw;
                left: 50%;
                margin-left: -50vw;
                height: 100%;
                z-index: -1;
                top:0;
            }
        </style>

        <?php else: ?>

            <style>
                .custom-list--render[id='<?= $block_id ?>']::before {
                    content: ' ';
                    position: absolute;
                    background-color: <?= esc_attr($attributes['backgroundColor']) ?>;
                    width: 100%;
                    height: 100%;
                    z-index: -1;
                    left:0;
                    top:0;
                }
            </style>

	<?php endif; ?>


    <div id="<?= htmlspecialchars($block_id, ENT_QUOTES, 'UTF-8') ?>"
         class="custom-list--render"
         style=" padding: <?= esc_attr($attributes['paddings']['top'] . ' ' . $attributes['paddings']['right'] . ' ' . $attributes['paddings']['bottom'] . ' ' . $attributes['paddings']['left']); ?>;
                 margin: <?= esc_attr($attributes['margins']['top'] . ' ' . $attributes['margins']['right'] . ' ' . $attributes['margins']['bottom'] . ' ' . $attributes['margins']['left']); ?>;
                 ">

        <ul class="list-items  --<?= $attributes['positions'] ?>" style="gap: <?= esc_attr($attributes['itemsGap'])?>px; color:<?= esc_attr($attributes['textColor'])?> ">

			<?php foreach ( $list_items as $index => $item ) : ?>
                <li class="list-item">
	                <?php if(($index > 0) && ($attributes['divider'])) :?>
                        <span class="divider" style="background-color: <?= esc_attr($attributes['dividerColor']); ?>"></span>
	                <?php endif; ?>

					<?php if ( ! empty( $item['media'] ) && isset( $item['media']['url'] ) ) : ?>
                        <img    style="width: <?= esc_attr($attributes['iconSizes'])?>px; height: <?= esc_attr($attributes['iconSizes'])?>px"
                                src="<?= esc_url( $item['media']['sizes']['thumbnail']['url'] ); ?>"
                                alt="<?= esc_attr( $item['media']['alt'] ?? '' ); ?>"
                                class="icon"
                        />
					<?php endif; ?>

					<?php if ( $item['isLink'] ) : ?>
                        <a class="link" href="<?= esc_url( $item['url'] ); ?>" style="color:<?= esc_attr($attributes['textColor'])?>">
							<?= esc_html( $item['text'] ); ?>
                        </a>
					<?php else: ?>
                        <span>
                                <?= esc_html( $item['text'] ?? '' ); ?>
                            </span>
					<?php endif; ?>


                </li>
			<?php endforeach; ?>
        </ul>
    </div>
	<?php

	return ob_get_clean();
}
