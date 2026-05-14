<?php /* Template Name: Window Tint Training Program Page */
get_header(); ?> 
    
	<section class="InnerBanner ceramicInnerBanner px-xxl-5 px-xl-4 px-lg-3 px-md-2">
	<?php
$post_id = 839; // Replace 123 with your actual post ID

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
				<h2 class="sectionTitleTwoTitle">WINDOW TINT TRAINING PROGRAM</h2>
				<span class="section-title-two__tagline">Benefits of window tint training program for your business</span> 
				<p class="py-5 pb-0 my-0 text-left"><strong>ENHANCED SERVICE</strong> <br/> PAutomotive window tint offers a variety of benefits including:</p>
				<ul>
					<li>Aesthetically appealing</li>
					<li>Enhanced comfort</li>
					<li>Interior heat reduction</li>
					<li>Fade reduction of automotive interior</li>
					<li>Glare reduction</li>
					<li>UV protection</li>
				</ul>				
				<p class="py-4 pb-0 my-0 text-left"><strong>ENTICE NEW CUSTOMERS</strong> <br/> Automotive window tinting is an additional offering to attracts the potential for more lucrative clients, while also adding valuable options for existing customers.</p>
				<p class="py-4 pb-0 my-0 text-left"><strong>INCREASE SALES</strong> <br/> Automotive window tint adds an additional revenue stream to your business growth, while giving you an extra option to provide your clients, allowing you to retain more business for yourself.</p>
			</div>
		</div>
	</section>
	
	<div class="container-custom">
		<section class="sec-content installationTraining">
			<h1 class="page-title text-center futura_ptheavy">WINDOW TINT INSTALLATION TRAINING COURSE</h1> 
			<div class="imgBox">
				<img src="../../wp-content/themes/force/assets/images/window-tint.jpeg" alt="Window tint"/>
			</div>
			<div class="trainingType">
				<div class="courseType fiveDayCourse">
					<div class="courseHeader">
						<div class="courseTitle">
							<h4 class="title"><span>PPF TRAINING</span><br/><span>5 DAY COURSE</span></h4>
						</div>
						<div class="skillLevel">
							<h6>SKILL LEVEL</h6>
							<div class="noviceLabel">
								<span>NOVICE</span><br/>
								<img src="../../wp-content/themes/force/assets/images/novice.svg" alt="expert">
							</div>
						</div>
					</div>				
					<div class="courseDec">
						<p class="mb-25">Regardless of skill level, we will help you understand the basics to more advanced techniques on how to install window tint properly.</p>
						<p class="mb-25">Through both bulk installations and pattern installations, you will experience various ways on how to cut and install window tint.</p>
						<ul>
							<li>Tools and Solutions</li>
							<li>Glass Surface and Film Preparation</li>
							<li>Installation Techniques</li>
							<li>Trimming Film</li>
							<li>Flush Mount</li>
							<li>Introduction to Safety Film</li>
							<li>Glass Types and Film Recommendations</li>
							<li>Certification</li>
						</ul>
					</div>				
				</div>
				<div class="dividerV"></div>
				<div class="courseType oneDayCourse">
					<div class="courseHeader">
						<div class="courseTitle">
							<h4 class="title"><span>PPF REFRESHER</span><br/><span>1 DAY COURSE</span></h4>
						</div>
						<div class="skillLevel">
							<h6>SKILL LEVEL</h6>
							<div class="expertLabel">
								<span>EXPERT</span><br/>
								<img src="../../wp-content/themes/force/assets/images/expert.svg" alt="expert">
							</div>
						</div>
					</div>				
					<div class="courseDec">
						<p class="mb-25">Already performed window tint installations? <br/>
	Been a few years since the last time you applied window tint? <br/>
	Need a recap of the basics? </p>
						<p class="mb-25">Our 1 day refersher course will go over the basics, dive into the software, and  give you the confidence to jump right back into window tinting.  </p>
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
			<center><a href="mailto:PPF@Forceautostying.com?subject=Tint%20Training%20Sign-Up" target="blank" class="btn button-singup-training mb-25 helvetica_neue_lt" rel="noopener">Sign Up For Training</a></center>
		</section>
		<section class="sec-content learnTraining">
			<div class="sunTekImg llumarImg">
				<h3 class="title">MULTIPLE SHADES AVAILABLE</h3>
				<p>WITH VARYING LIGHT TRANSMISSION LEVELS</p>
				<img src="../../wp-content/themes/force/assets/images/llumar-window-film.svg" alt="PPF installation training course"/>
			</div>
			<div class="learnTrainingProgram">
				<div class="learnRequest">
					<h2 class="title">LEARN ABOUT OUR <br/>TRAINING PROGRAMS</h2>
					<a href="mailto:PPF@Forceautostying.com?subject=Tint%20Training%20Information%20Request" target="blank" class="btn btn-request" rel="noopener">REQUEST INFO <img src="../../wp-content/themes/force/assets/images/requestArrow.svg" alt="Request Info"/></a>
				</div>
				<div class="courseBook">
					<img src="../../wp-content/themes/force/assets/images/tint-courseBook.png" alt="Training course book"/>
				</div>
			</div>
		</section>
	</div>
	
	
	
<?php get_footer(); ?> 