<?php /* Template Name: Home Page */
get_header(); ?>

<section class="mainSlider">
            <div class="fadeOut owl-carousel owl-theme">
			<?php 
				$i = 1;
				$args = array( 'post_type' => 'slider', 'posts_per_page' => 10, 'orderby' => 'menu_order', 'order' => 'ASC' );
				$loop = new WP_Query( $args );
				while ( $loop->have_posts() ) : $loop->the_post();
				$id = get_the_ID();
				$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'full');
			?>
				
                <div class="item">
                    <div class="slide" style="background-image:url(<?php echo esc_url($large_image_url[0]); ?>)">                           
                        <div class="content clearfix">    
                            <div class="title"><?php the_content(); ?></div>    
                            <h2><?php the_title(); ?></h2> 
                            <a href="#whyChoose" class="mouse-btn-down scroll-to-target"></a>    
                        </div>
                    </div>
                </div>
                <!-- <div class="item">
                    <div class="slide" style="background-image:url(<?php //echo get_template_directory_uri(); ?>/assets/img/banner-slide1.jpg)">                           
                        <div class="content clearfix">    
                            <div class="title">Full Service And Excellent Quality</div>    
                            <h2>DETAILING</h2> 
                            <a href="#whyChoose" class="mouse-btn-down scroll-to-target"></a>    
                        </div>
                    </div>
                </div> -->
				<?php
					$i++;
					endwhile;
				?>
            </div>
            <a href="#serviceSection" class="mouse-btn-down scroll-to-target">Scroll Down <img src="<?php echo get_template_directory_uri(); ?>/assets/img/small-arrow.webp" alt=""/></a>
        </section>
		

        <section id="serviceSection" class="serviceSection">
            <div class="row mx-0">
			<?php
			// Replace 'parent-page-slug' with the slug of your parent page
			$parent_page_slug = 'services';

			$args = array(
				'child_of' => get_page_by_path($parent_page_slug)->ID, // Get the ID of the parent page
				'sort_order' => 'ASC',
				//'sort_column' => 'post_title',
				'sort_column' => 'menu_order',
				'hierarchical' => 0,
				'exclude' => '',
				'include' => '',
				'meta_key' => '',
				'meta_value' => '',
				'authors' => '',
				'parent' => -1,
				'exclude_tree' => '',
				'number' => '',
				'offset' => 0,
				'post_type' => 'page',
				'post_status' => 'publish'
			);

			$child_pages = get_pages($args);

			// Loop through the child pages
			foreach ($child_pages as $child_page) {
				$id = $child_page->ID;
				$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'full');
				$child_page_title = $child_page->post_title;
				$child_page_content = $child_page->post_content;
				$page_link = get_permalink($id);
				if(!empty($large_image_url[0])){
			?>
                <div class="col-lg-3 col-6-3 col-sm-6 px-0" data-id="<?php echo $id;?>">                    
                    <div class="services_item wow fadeInUp" data-wow-delay="100ms">
                        <div class="serviceImg">
                            <img src="<?php echo esc_url($large_image_url[0]); ?>" alt=""/>
                        </div>
                        <div class="services_detail">
                            <h2 class="services_item_title"><?php echo esc_html($child_page_title); ?></h2>
                            <p class="services_item_text"><?php echo esc_html($child_page_content); ?></p>
                            <span class="link_icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/arrow-right.png" alt=""/></span>                            
                        </div>
                        <a class="services_item_link" href="<?php echo $page_link;?>">&nbsp;</a>
                    </div>
                </div>
			<?php } } ?>	
                
            </div>

        </section>

        <section class="whoWeAre">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-12 col-sm-12 wow fadeInLeft">
                        <div class="upper-box">
                            <h4 class="upper-title">Who we are</h4>
                            <h1 class="section-title__title">WE DO AUTO STYLING</h1>
                            <h2 class="section-title__tagline">Passion and Quality Reflected Through Our Work</h2>
                        </div>
                        <div class="sec-title mb-5">
                            <h2>Why choose Force?</h2>
                        </div>
                        <div class="text pr-5">
                            <p>Force Auto Styling was born out of a desire to offer MORE. More choice, MORE quality, and MORE services. Force Auto Styling strives to offer our clients professional detailing and cosmetic services at a competitive rate, but also treating each vehicle like our own. </p>
                            <p>Our team of dedicated professionals will explain and answer any questions you may have. We’re not all professionals, but you can rest assured that when you come and visit Force Auto Styling, we’ll make sure you’re confident in any and all of your decisions.</p>
							<p>
								Visit us today at Force to learn how we can make your vehicle look and feel it’s best!
							</p>
                        </div>
                        <div class="link-box mt-5">                                  
                            <a href="<?php echo home_url('/');?>contact" class="theme-btn btn-style-two getaquote">
                                <span class="txt">Get a Quote</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-12 col-sm-12"></div>
                    <div class="col-lg-6 col-md-12 col-sm-12 wow fadeInRight">
                        <img class="w-100" src="<?php echo get_template_directory_uri(); ?>/assets/img/who_we_are_img.png" alt=""/>
                    </div>
                </div>
                <div class="spacer"></div>
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="chooseForce_item wow fadeInUp" data-wow-delay="100ms">
                            <div class="chooseForceIcon">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/art_facility_icon.png" alt=""/>
                            </div>
                            <div class="chooseForce_detail">
                                <h2 class="chooseForce_title">STATE OF THE ART FACILITY</h2>
                                <p class="chooseForce_text">Our fully-outfitted facility in the heart of Calgary is ready to serve all of your auto styling needs.</p>                                
                            </div>
                            <span class="threeDot_icon"></span>                            
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="chooseForce_item wow fadeInUp" data-wow-delay="200ms">
                            <div class="chooseForceIcon">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/professional_installers.png" alt=""/>
                            </div>
                            <div class="chooseForce_detail">
                                <h2 class="chooseForce_title">PROFESSIONAL INSTALLERS</h2>
                                <p class="chooseForce_text">Our staff is professionally trained and experience in providing quality, warranty-approved installs.</p>                                                           
                            </div>
                            <span class="threeDot_icon"></span> 
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="chooseForce_item wow fadeInUp" data-wow-delay="300ms">
                            <div class="chooseForceIcon">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/dessign_production_icon.png" alt=""/>
                            </div>
                            <div class="chooseForce_detail">
                                <h2 class="chooseForce_title">DESIGN & PRODUCTION</h2>
                                <p class="chooseForce_text">Our on-site design staff and production equipment mean fast, attentive turn-around at the absolute best prices.</p>                                                         
                            </div>
                            <span class="threeDot_icon"></span>   
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="chooseForce_item wow fadeInUp" data-wow-delay="400ms">
                            <div class="chooseForceIcon">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/concierge_services.png" alt=""/>
                            </div>
                            <div class="chooseForce_detail">
                                <h2 class="chooseForce_title">CONCIERGE SERVICE</h2>
                                <p class="chooseForce_text">Does your vehicle need some TLC? Our extended services include body repair, flatbed towing, detailing, car rental and financing options.</p>                                                      
                            </div>
                            <span class="threeDot_icon"></span>      
                        </div>
                    </div>
                </div>
            </div>
            <div class="marquee">
                <h1 class="marquee-text1">FORCE AUTO STYLING</h1>
            </div>
        </section>
        <section class="trainingProgramSection">
            <div class="row mx-0">
                <div class="col-lg-6 px-0 wow slideInLeft">                    
                    <div class="training_item">
                        <div class="trainingImg">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/ppf-training.jpg" alt="">
                        </div>
                        <div class="training_detail">
                            <h3 class="training_item_text">Dealer Services</h3>
                            <h2 class="training_item_title">PPf Training program</h2>
                            <div class="link-box">                                  
                                <a href="<?php echo home_url('/');?>ppf-training-program" class="theme-btn btn-style-three">
                                    <span class="txt">BOOK NoW</span>
                                </a>
                            </div>                       
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 px-0 wow slideInRight">                    
                    <div class="training_item">
                        <div class="trainingImg">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/window-tint-training.jpg" alt="">
                        </div>
                        <div class="training_detail">                            
                            <h2 class="training_item_text">Dealer Services</h2>
                            <h3 class="training_item_title">Window Tint Training program</h3>
                            <div class="link-box">                                  
                                <a href="<?php echo home_url('/');?>window-tint-training-program" class="theme-btn btn-style-three">
                                    <span class="txt">BOOK NoW</span>
                                </a>
                            </div>                           
                        </div>                        
                    </div>
                </div>
            </div>
        </section>
        <section class="testmonialSection">
            <div class="container">
                <div class="testmonialWrap owl-carousel wow fadeInUp">
                    <div class="testmonial_item">
                        <div class="quoteIcon">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/quotes_icon.png" alt="">
                        </div>
                        <div class="testmonial_detail">
                            <p class="testmonial_text">If you’re looking for a place that will take care of a customer, this is the place to go in Calgary. As a newcomer to Calgary from out of town, I was worried about finding a place I could trust with my car. Employees here understand how other car guys think when entrusting their pride and joy with a shop and go above and beyond to make sure your ride is taken care of and safe! Not to mention the amount of work they put into explaining everything being done to your vehicle step by step, so if you are confused, you won’t be after talking to them. I also should mention that they take some awesome glamour shots after the work is done. Seriously, cannot recommend this place enough. They have earned my business, and they’ll earn yours too. Don’t think twice, take it to Force Auto Styling!</p>                                
                        </div>
                        <div class="clientName">
                            <h4>Chayce Mihalicz</h4>
                            <span>Client</span>
                            <div class="clientImg">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/chayce-mihalicz.png" alt="">
                            </div>
                        </div>                     
                    </div>
                    <div class="testmonial_item">
                        <div class="quoteIcon">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/quotes_icon.png" alt="">
                        </div>
                        <div class="testmonial_detail">
                            <p class="testmonial_text">Fantastic service. They were very thorough on details and ensured everything was covered in a quote and went above and beyond to make our experience was top notch. Had protection done on my new 2023 RAM 3500 mega dually. My big concern was the fenders of my dually were not getting the protection they needed. Truly got more on the whole scope of work than ever imagined. Added vinyl for some of the badging and already looking to book back to get other vinyl done. Very well priced and no-nonsense or surprise costs at delivery. Thanks to the whole team and look forward to future work.</p>                                
                        </div>
                        <div class="clientName">
                            <h4>Clayton Monkman</h4>
                            <span>Client</span>
                            <div class="clientImg">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Clayton-Monkman.png" alt="">
                            </div>
                        </div> 				
                    </div>
					<div class="testmonial_item">
                        <div class="quoteIcon">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/quotes_icon.png" alt="">
                        </div>
                        <div class="testmonial_detail">
                            <p class="testmonial_text">I had hood PPF, and window tint installed by Force Auto in March of 2023. The team did an excellent job, and my car looks fantastic. In March of 2024, the tint on my driver-side front window began to peel off. I contacted Force Auto Styling, and they immediately took steps to book me in and have the tint removed and reapplied, free of charge. Force Auto Styling stands by its work and consistently provides excellent customer service.</p>                                
                        </div>
                        <div class="clientName">
                            <h4>Ross Robert</h4>
                            <span>Client</span>
                            <div class="clientImg">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/ross-robert.png" alt="">
                            </div>
                        </div> 				
                    </div>
					<div class="testmonial_item">
                        <div class="quoteIcon">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/quotes_icon.png" alt="">
                        </div>
                        <div class="testmonial_detail">
                            <p class="testmonial_text">Just amazing! I’m so happy with the service at Force Auto Styling. They explained the whole process in detail at my consultation and took me in the day after I got my new car. The protective film is hardly noticeable and very well done. I got great service and a great price. I highly recommend them! I was referred there by KhS, and I can see why. People there were so knowledgeable, and professional, and gave great customer service. Bravo!</p>                                
                        </div>
                        <div class="clientName">
                            <h4>Diana J Scott</h4>
                            <span>Client</span>
                            <div class="clientImg">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/diana-j-scott.png" alt="">
                            </div>
                        </div> 				
                    </div>
					<div class="testmonial_item">
                        <div class="quoteIcon">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/quotes_icon.png" alt="">
                        </div>
                        <div class="testmonial_detail">
                            <p class="testmonial_text">With very little time, Matthew (with Mark's help) and his team helped us in getting our 6 sandwich boards re-faced. Not only did they provide fast service, but the quality of materials they used was also prime. I am sure we will use the boards for many years to come.</p>                                
							<p class="testmonial_text w-100">Amazing people, will come back and highly recommend them.</p>
                        </div>
                        <div class="clientName">
                            <h4>Reina Acurantes</h4>
                            <span>Client</span>
                            <div class="clientImg">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/reina-acurantes.png" alt="">
                            </div>
                        </div> 				
                    </div>
                </div>
            </div>
        </section>
        <section class="contactSection">
            <div class="contact-bg" style="background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/contact_bg.png);">&nbsp;</div>
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-lg-6 wow slideInRight">
                        <div class="upper-box">
                            <h4 class="upper-title">Contact US</h4>
                            <h1 class="section-title__title">Have a question?<br> Get in touch!</h1>
                        </div>
                        <div class="contactOne__right" style="visibility: visible; animation-name: slideInLeft;">
						<?php echo do_shortcode('[contact-form-7 id="873" title="Get in touch home"]');?>
						<?php //echo do_shortcode('[contact-form-7 id="147" title="Contact form"]');?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<?php get_footer(); ?>