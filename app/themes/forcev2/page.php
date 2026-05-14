<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package forcev2
 */

get_header();
// Check if the current page is the cart page
if (is_cart() || is_checkout()) {
    // This is the cart page
    ?>
<section class="InnerBanner singlePorductBanner px-5">
	<div class="InnerBanner-bg" style="background-image: url(../v2/wp-content/uploads/2024/01/decal-shop-bg.jpg);">&nbsp;</div>
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
	<?php
}
?>

	<main id="primary" class="site-main">
		<div class="container">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

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
