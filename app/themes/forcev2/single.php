<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package forcev2
 */

get_header();
?>
<section class="InnerBanner singlePorductBanner px-5">
	<div class="InnerBanner-bg" style="background-image: url(../wp-content/uploads/2024/01/decal-shop-bg.jpg);">&nbsp;</div>
	<div class="container-fluid">                
		<div class="row justify-content-end">
			<!--Services Two Single Start-->
			<div class="col-xl-6 col-lg-6 wow fadeInUp" data-wow-delay="100ms">
				<div class="section-title text-end" style="display:none;">                    
					<h2 class="section-title__title">FORCE AUTO STYLING</h2>                            
					<p class="InnerBanner__text-1 py-4 my-0">A Revolution in Vehicle Customization - Force Auto Styling is a full service auto salon specializing in Vehicle Wraps, Custom graphics, Window tint and Paint Protection.</p>
					<p class="InnerBanner__text-1 py-4 my-0">We offer a state of the art facility, professional installers, in-house design & print production, and concierge services.</p>
					<p class="InnerBanner__text-1 py-4 my-0">For you next Vehicle Customization, choose Force Auto Styling.</p>
					<p class="InnerBanner__text-1 py-4 pt-2 my-0">Connect with us today for a free consultation.</p>
				</div>
			</div>
			<!--Services Two Single End-->
		</div>
	</div>
	<a href="#" class="phoneNumber">403.256.6501</a>
</section>
<div class="product-list-decal-print">
	<div class="container">

	
<?php
$current_product_id = get_the_ID();
//$product = wc_get_product($current_product_id);
$categories = wp_get_post_terms($current_product_id, 'product_cat');
if( $categories[0]->name == 'Decal Print'){
	?>
	<ul>
		<?php
$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => 24, // Replace $category_id with the ID of the category
        ),
    ),
);

$products_query = new WP_Query( $args );
// echo "<pre>";
// print_r($products_query);

if ( $products_query->have_posts() ) :
    while ( $products_query->have_posts() ) :
        $products_query->the_post();
		global $product;

		// Get product data
		$product_id = $product->get_id();
		$product_title = $product->get_name();
		$product_price = $product->get_price_html();
		$product_permalink = get_permalink($product_id);
		if($current_product_id==$product_id)
		{
			$class="active";
		}
		else
		{
			$class="";
		}
		?>
		<li><a href="<?php echo esc_url($product_permalink);?>" data-cat="<?php echo $product_id;?>" class="<?php echo $class;?>"><?php echo esc_html($product_title);?></a></li>
        <?php
		// You can display other product information as needed

    endwhile;
    wp_reset_postdata();
else :
    echo 'No products found';
endif;
?>
</ul>
<?php } ?>
</div>
</div>
	<main id="primary" class="site-main">
		<div class=" container">
			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', get_post_type() );

				the_post_navigation(
					array(
						'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'forcev2' ) . '</span> <span class="nav-title">%title</span>',
						'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'forcev2' ) . '</span> <span class="nav-title">%title</span>',
					)
				);

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>
		</div>
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
