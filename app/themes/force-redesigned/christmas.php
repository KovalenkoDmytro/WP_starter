<?php /* Template Name: Christmas */
//get_header(); ?> 

<!DOCTYPE html>
<html lang="en" >
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title>Landing Page</title>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/christmas.css">
</head>
<body>
<div class="mainContainer">
		
		<div class="topContent">
			<div class="innreWrapper">
		   <div class="topLogos">
			 <ul>
				 <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/Food Bank Logos__Calgary Food Bank Logo.jpg"></li>
				 <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/Food Bank Logos__Airdrie Food Bank Logo.jpg"></li>
				 <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/Food Bank Logos__Okotoks Food Bank Logo.jpg"></li>
			   </ul>
			</div>
			<div class="topHeader">
			  <span>Tis' the season for</span> <font>Giving</font>
			</div>
				<div class="midText">
				<strong>MK AUTO GROUP</strong> is excited to partner with the <strong>Calgary Food Bank</strong>, the <strong>Airdrie Food Bank</strong> and the <strong>Okotoks Food Bank</strong> in an effort to give back to our communities.<br><br>
Throughout the month of Decemeber, when you choose from one of our special services from one of our participating locations, <strong>MK AUTO GROUP</strong> will contribute a donation to either the <strong>Calgary Food Bank</strong>, the <strong>Airdrie Food Bank</strong> or the <strong>Okotoks Food Bank</strong>.
				</div>
                <div class="serviceWrapper">
				 <ul>
				<?php
                $services = array();
                $services = array('Glass Chip Repairs' => 'Services__Offer_Glass Chip Repair.jpg',
                                 'Mechanical Services' => 'Services__Offer_Mechanical Services.jpg',
                                 'Vehicle Detailing Premium' => 'Services__Offer_Vehicle Detailing Premium.jpg',
                                 'Vehicle Detailing Ultimate' => 'Services__Offer_Vehicle Detailing Ultimate.jpg',
                                 'Paint Protection Film Premium' => 'Services__Offer_PPF Premium.jpg',
                                 'Paint Protection Film Ultimate' => 'Services__Offer_PPF Ultimate.jpg',
                                );
               
                //$service = "Glass Chip Repair";
                //$encodedService = urlencode($service);
                //echo '<a href="https://forceautostyling.com/book-your-appointment?service=' . $encodedService . '">Book Your Online Appointment</a>';
                if(isset($services)){
                    foreach ($services as $key => $service) 
                    {
                        $encodedService = urlencode($key);
                    
                ?>
                    <li>
					  <div class="serviceDiv">
						  <img src="../../wp-content/themes/forcev2/assets/images/christmas/<?php echo $service;?>">
						<a href="https://forceautostyling.com/book-your-appointment?service=<?php echo $encodedService;?>">Book Your Online Appointment</a>
						  </div>
					 </li>
                     <?php } 
                    } ?>
					</ul>
				</div>
				</div>
		</div>
		
		<div class="footerContent">
			<div class="footerInner">
				<div class="footerLeft"><img src="../../wp-content/themes/forcev2/assets/images/christmas/logos/Individual Logos__MK Auto Group.png"></div>
				<div class="footerRight">
				 <ul>
					<li><img src="../../wp-content/themes/forcev2/assets/images/christmas/logos/Individual Logos__Carstar Airdrie.png"></li>
					 <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/logos/Individual Logos__Carstar Okotoks.png"></li>
					 <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/logos/Individual Logos__Carstar Express.png"></li>
					 <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/logos/Individual Logos__Intact Midnapore.png"></li>
					 <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/logos/Individual Logos__Force Auto Styling.png"></li>
					 <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/logos/Individual Logos__Calgary Rim Repair.png"></li>
					 <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/logos/Individual Logos__Country Hills Collision.png"></li>
					 <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/logos/Individual Logos__MK Commercial.png"></li>
					 <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/logos/Individual Logos__Elboya Mechanical.png"></li>
					</ul>
				</div>
			</div>
			<div class="poweredby">
	<label>Powered by</label><a href="https://www.smartlayer.ca/" target="_blank"><img src="../../wp-content/themes/forcev2/assets/images/christmas/sl-logo.png"></a>
	</div>
		</div>
	</div>
    </body>
    </html>
<?php //get_footer(); ?> 