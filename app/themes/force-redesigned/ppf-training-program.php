<?php /* Template Name: PPF Training Program Page */
get_header(); ?> 
    
	<section class="InnerBanner ceramicInnerBanner px-xxl-5 px-xl-4 px-lg-3 px-md-2">
	<?php
$post_id = 836; // Replace 123 with your actual post ID

$thumbnail_id = get_post_thumbnail_id($post_id);
if ($thumbnail_id) {
    $image_src = wp_get_attachment_image_src($thumbnail_id, 'full');
    if ($image_src) {
		
        $backimage = $image_src[0]; // Output the URL of the featured image
    
	}
	else
	{
		$backimage = '';
	}
}
?>

		<div class="InnerBanner-bg" style="background-image: url(<?php echo $backimage;?>);">&nbsp;</div>

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
		<a href="tel:4032566501" class="phoneNumber">403.<span>256</span>.6501</a>
	</section>
	
	<section class="innerPageSection howHepYou px-xxl-5 px-xl-4 px-lg-3 px-md-2">
		<div class="container-fluid">
			<div class="section-title-two text-center">                    
				<h2 class="sectionTitleTwoTitle">PPF TRAINING PROGRAM</h2>
				<span class="section-title-two__tagline">Benefits of ppf training program for your business</span> 
				<p class="py-5 pb-0 my-0 text-left"><strong>ENHANCED SERVICE</strong> <br/> PPF is one of the fastest-growing sectors in the aftermarket automotive industry.</p>
				<p class="py-2 pb-0 my-0 text-left">By offering PPF the stage is set for your business to become a one-stop shop instead of an afterthought.</p>
				<p class="py-4 pb-0 my-0 text-left"><strong>ENTICE NEW CUSTOMERS</strong> <br/> PPF services attracts the potential for more lucrative clients, while also adding valuable options for existing customers.</p>
				<p class="py-4 pb-0 my-0 text-left"><strong>INCREASE SALES</strong> <br/> PPF adds an additional revenue stream to your business growth, while giving you an extra option to provide your clients, allowing you to retain more business for yourself.</p>
			</div>
		</div>
	</section>
	
	<div class="container-custom">
	<section class="sec-content installationTraining">
		<h1 class="page-title text-center futura_ptheavy">PPF INSTALLATION TRAINING COURSE</h1>
		<div class="imgBox"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/ppf-installation-training.jpeg" alt="PPF installation training course" /></div>
		<div class="trainingType">
			<div class="courseType fiveDayCourse">
				<div class="courseHeader">
					<div class="courseTitle">
						<h4 class="title">PPF TRAINING <br/> 5 DAY COURSE</h4>
					</div>
					<div class="skillLevel">
						<h6>SKILL LEVEL</h6>
						<div class="noviceLabel"><span>NOVICE</span><br/>
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/novice.svg" alt="expert" />
						</div>
					</div>
				</div>
				<div class="courseDec">
					<p class="mb-25 font_helvetica">Students will learn the proper skills necessary to perform PPF installation, learning techniques, installation terms, the CORE software program, as well as tips and tricks to help you become a better installer.</p>
					<p class="mb-25 font_helvetica">Upon completion of the 5 day course, you will be confident on how to install PPF on your own.</p>
					<ul>
						<li>Tools and Solutions</li>
						<li>Pre-Cut Installation</li>
						<li>Installation Techniques</li>
						<li>Product Maintenance</li>
						<li>Product Removal</li>
						<li>Using a Cutting System and Software</li>
						<li>Certification</li>
					</ul>
				</div>
			</div>
			<div class="dividerV"></div>
			<div class="courseType oneDayCourse">
				<div class="courseHeader">
					<div class="courseTitle">
						<h4 class="title">PPF REFRESHER <br/> 1 DAY COURSE</h4>
					</div>
					<div class="skillLevel">
						<h6>SKILL LEVEL</h6>
						<div class="expertLabel"><span>EXPERT</span><br/>
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/expert.svg" alt="expert" />
						</div>
					</div>
				</div>
				<div class="courseDec">
					<p class="mb-25 font_helvetica">Already performed PPF installations?
				Been a few years since the last time you applied PPF?
				Need to be introduced to the latest software?</p>
					<p class="mb-25 font_helvetica">Our 1 day refersher course will go over the basics, dive into the software, and give you the confidence to jump right back into PPF installation.</p>
					<ul>
						<li>Recap of the basics</li>
						<li>Solution mixture</li>
						<li>Review cutting system and Software</li>
						<li>Crash course installation techniques</li>
						<li>Certification</li>
					</ul>
				</div>
			</div>
		</div>
		<center><a class="btn button-singup-training mb-25 font_helvetica" href="mailto:PPF@Forceautostyling.com?subject=PPF%20Training%20Sign-Up" target="blank" rel="noopener">Sign Up For Training</a></center>
	</section>
	<section class="sec-content learnTraining">
		<div class="sunTekImg"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/suntek.svg" alt="PPF installation training course" /></div>
		<div class="learnTrainingProgram">
			<div class="learnRequest">
				<h2 class="title">LEARN ABOUT OUR
				TRAINING PROGRAMS</h2>
				<a class="btn btn-request" href="mailto:PPF@Forceautostying.com?subject=PPF%20Training%20Information%20Request" target="blank" rel="noopener">REQUEST INFO <img src="<?php echo get_template_directory_uri(); ?>/assets/images/requestArrow.svg" alt="Request Info" /></a>
			</div>
			<div class="courseBook"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/courseBook.png" alt="Training course book" /></div>
		</div>
	</section>
	</div>	


<?php get_footer(); ?>  