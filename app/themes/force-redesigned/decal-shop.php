<?php /* Template Name: Decal Shop Page */
get_header(); ?> 
    
	<section class="InnerBanner ceramicInnerBanner px-5">
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
		<a href="tel:4032566501" class="phoneNumber">403.256.6501</a>
	</section>
        
	<section class="innerPageSection px-5 pb-0">
		<div class="container-fluid">
			<div class="section-title-two text-center mb-0">                    
				<h2 class="sectionTitleTwoTitle">THE DECAL SHOP</h2>
				<span class="section-title-two__tagline">Customize your vehicle</span> 				                           
			</div>
		</div>
	</section>
		
	<section class="innerPageSection wrapGallerySection px-5">
		<div class="container-fluid">			
			<div class="gallerySection  pb-5 d-none">
				<div class="row">
					<div class="col-lg-6">
						<?php
							$args = array(
								'category_name' =>'vehicle-wrap-gallery',
								'post_type' => 'attachment',
								'numberposts' => -1,
							);
						   
							$attachments = get_posts($args);
							//echo "<pre>";
							//print_r($attachments);
						?>
						<div class="thumbnails">
							<?php foreach ($attachments as $key => $value)
                    {
                    ?>
					<div <?php if($key==0){ echo "class='active thumbImg'";} else{echo "class='thumbImg'";}?>><img src="<?php echo $value->guid;?>"></div>
					  <?php } ?>
					
				</div>
					</div>
					<div class="col-lg-6">
						<div class="master">
							<img src="<?php echo $attachments[0]->guid;?>">
							<i class="fas fa-chevron-left"></i>
							<i class="fas fa-chevron-right"></i>
						</div>
					</div>
				</div>
				
				

			</div>



			<div class="gallerySection  pb-5">
				<div class="row">
						<?php 
							if (function_exists('do_shortcode')) {
								// Use do_shortcode to render the show_all_products shortcode
								echo do_shortcode('[show_all_products]');
							}
						?>
					<!-- <div class="col-lg-6">
						<div class="master">
							<img src="<?php //echo $attachments[0]->guid;?>">
							<i class="fas fa-chevron-left"></i>
							<i class="fas fa-chevron-right"></i>
						</div>
					</div> -->
				</div>
				
				

			</div>
				
				
			<div class="innerPageSection bennerCustomDesignSection">
				<div class="innerPageSection-bg customDesignBennerSection-bg" style="background-image: url(../v2/wp-content/uploads/2024/01/custom-design-banner-bg.jpg);">&nbsp;</div>		
				<div class="row">
					<div class="col-lg-5">
						<div class="section-title-two text-end">
							<h3 class="sectionTitleTwoTitle">CUSTOM STICKERS & <br/> DESIGN SERVICES <br/>AVAILABLE</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	


        
<?php get_footer(); ?>    


  

    