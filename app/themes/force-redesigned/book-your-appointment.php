<?php /* Template Name: Book your appointment */
//get_header(); 
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

function getAvailableTimeslotCount($location, $date, $time) 
{
	global $wpdb;
    $table_name = $wpdb->prefix . 'customer_bookings';

    // Check the number of bookings for the specific location, date, and time (Morning/Afternoon)
    $query = $wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE location = %s AND booking_date = %s AND booking_time = %s",
        $location, $date, $time
    );
    $count = $wpdb->get_var($query);

    return $count; // Return the count of bookings
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'check_timeslots') {
    global $wpdb;
    $table_name = $wpdb->prefix . 'customer_bookings';

    $timeslotBookedVal = false;

    // Retrieve and sanitize form data
    $selected_location = sanitize_text_field($_POST['location']);
    $selected_date = sanitize_text_field($_POST['booking_date']);
    $selected_time = sanitize_text_field($_POST['time']); // Adjusted to match 'time' from AJAX data
    $selected_promotion = sanitize_text_field($_POST['promotion']);

    // Check timeslot availability, excluding 'Glass chip repair'
    if ($selected_promotion != 'Glass chip repair') {
        // Call a function to get the count of bookings for this location, date, and time
        $availableSlots = getAvailableTimeslotCount($selected_location, $selected_date, $selected_time);

        // If 2 or more bookings already exist, mark timeslot as booked
        if ($availableSlots >= 2) {
            $timeslotBookedVal = true;
        }
    }

    // Return 'true' if booked, 'false' otherwise
    echo json_encode(['booked' => $timeslotBookedVal]);
    exit; // Terminate script execution after response
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) 
{
	
	global $wpdb;
    // Table name where the data will be saved
    $table_name = $wpdb->prefix . 'customer_bookings';


    $timeslotBooked = false; 

	$selected_location = sanitize_text_field($_POST['location']);
	$selected_date = sanitize_text_field($_POST['booking_date']);
    $selected_time = sanitize_text_field($_POST['booking_time']);
    
	$selected_promotion = sanitize_text_field($_POST['promotion']);
	

	// Check if the timeslot is fully booked, except for 'Glass chip repair'
	if ($selected_promotion != 'Glass chip repair') 
	{
		// Fetch availability count from db for the selected timeslot
		$availableSlots = getAvailableTimeslotCount($selected_location, $selected_date, $selected_time);
		//echo $availableSlots;die;

		if ($availableSlots >= 2) {
			$timeslotBooked = true;
		}
	}

	// If timeslot is booked, display the message
	if ($timeslotBooked) 
	{
		$errorMessage = 'This timeslot is already fully booked.';
	} 
	else 
	{
    // echo "<pre>";
    // print_r($_POST);
    // die;
		// Prepare data for insertion
		$insert_data = array(
			'customer_name'    => sanitize_text_field($_POST['customer_name']),
			'customer_email'   => sanitize_email($_POST['customer_email']),
			'customer_phone'   => sanitize_text_field($_POST['customer_phone']),
			'booking_year'     => intval($_POST['booking_year']),
			'booking_make'     => sanitize_text_field($_POST['booking_make']),
			'booking_model'    => sanitize_text_field($_POST['booking_model']),
			'trimlevel'        => sanitize_text_field($_POST['trimlevel']),
			'promotion'        => sanitize_text_field($_POST['promotion']),
			'promotion_option' => sanitize_text_field($_POST['promotionOption']),
			'location'         => sanitize_text_field($_POST['location']),
			'booking_date'     => sanitize_text_field($_POST['booking_date']),
			'booking_time'     => sanitize_text_field($_POST['booking_time']),
			'marketing'        => intval($_POST['choice-radio']),
		);
		$insert_result = $wpdb->insert($table_name, $insert_data);

		// Optionally, handle success or error
		if ($wpdb->insert_id) {
			echo 'Booking successfully saved!';
		} else {
			echo 'There was an error saving your booking.';
		}

		// Send email to admin if data is inserted successfully
		if ($insert_result) {
			// Admin email address
			$admin_email = get_option('admin_email'); //info@forceautostyling.com
			//$admin_email = 'stiwari@smartlayer.ca'; // Replace with dynamic option if needed
			$subject = "New Appointment Booking";

			// Prepare HTML message body
			$message = "
			<html>
			<head>
				<style>
					body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
					.email-container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f4f4; border-radius: 5px; }
					h2 { color: #333; text-align: center; }
					table { width: 100%; margin-top: 20px; border-collapse: collapse; }
					th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
					th { background-color: #f2f2f2; }
					.highlight { font-weight: bold; color: #000; }
					.status { color: green; }
				</style>
			</head>
			<body>
				<div class='email-container'>
					<h2>New Appointment Booking Received</h2>
					<table>
						<tr>
							<th>Customer Name</th>
							<td>" . sanitize_text_field($_POST['customer_name']) . "</td>
						</tr>
						<tr>
							<th>Email</th>
							<td>" . sanitize_email($_POST['customer_email']) . "</td>
						</tr>
						<tr>
							<th>Phone</th>
							<td>" . sanitize_text_field($_POST['customer_phone']) . "</td>
						</tr>
						<tr>
							<th>Vehicle Year</th>
							<td>" . intval($_POST['booking_year']) . "</td>
						</tr>
						<tr>
							<th>Vehicle Make</th>
							<td>" . sanitize_text_field($_POST['booking_make']) . "</td>
						</tr>
						<tr>
							<th>Vehicle Model</th>
							<td>" . sanitize_text_field($_POST['booking_model']) . "</td>
						</tr>
						<tr>
							<th>Trim Level</th>
							<td>" . sanitize_text_field($_POST['trimlevel']) . "</td>
						</tr>
						<tr>
							<th>Promotion</th>
							<td>" . sanitize_text_field($_POST['promotion']) . "</td>
						</tr>
						<tr>
							<th>Promotion Option</th>
							<td>" . sanitize_text_field($_POST['promotionOption']) . "</td>
						</tr>
						<tr>
							<th>Location</th>
							<td>" . sanitize_text_field($_POST['location']) . "</td>
						</tr>
						<tr>
							<th>Booking Date</th>
							<td>" . sanitize_text_field($_POST['booking_date']) . "</td>
						</tr>
						<tr>
							<th>Booking Time</th>
							<td>" . sanitize_text_field($_POST['booking_time']) . "</td>
						</tr>
						<tr>
							<th>Marketing Opt-In</th>
							<td>" . ($_POST['choice-radio'] == '1' ? 'Yes' : 'No') . "</td>
						</tr>
					</table>
					<p class='status'>The booking has been successfully submitted.</p>
				</div>
			</body>
			</html>";

			// Send the email
			$headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Cc: jwycoco@mkcommercial.ca' // Add the CC email address here
    );
    
			wp_mail($admin_email, $subject, $message, $headers);

			// Show a success message
			$message = "Your appointment has been successfully submitted!";
		} else {
			// Show an error message if the data wasn't inserted
			$message = "There was an error processing your request. Please try again.";
		}
	}
}



// Get the service parameter from the URL
$service = isset($_GET['service']) ? urldecode($_GET['service']) : '';
// Define promotion and promotion option mappings
$availablePromotions = $availablePromotionOption = $location = array();



$availablePromotions = ['Glass Chip Repair','Mechanical Services','Vehicle Detailing','Paint Protection Film'];
$availablePromotionOptions = ['First Rock Chip Repair','Premium Spa Package','Ultimate Spa Package','Premium PPF Package','Ultimate PPF Package','Oil change', '4-Wheel Alignment', 'Tire Rotation'];
$locations = ['Elboya Mechanical','Intact Centre (Midnapore)','Airdrie','MK Commercial','Okotoks','Country Hills Collision','CARSTAR EXPRESS'];

if($service=='Mechanical Services')
{
	$availablePromotionOptions = ['Oil change', '4-Wheel Alignment', 'Tire Rotation'];
	$locations = ['Elboya Mechanical','Intact Centre (Midnapore)'];
}
else if($service=='Glass Chip Repairs')
{
	$availablePromotionOptions = ['NA'];
	$locations = ['Airdrie','MK Commercial','Okotoks','Country Hills Collision','CARSTAR EXPRESS'];
}
else if($service=='Vehicle Detailing Premium' || $service=='Vehicle Detailing Ultimate')
{
	$availablePromotionOptions = ['Premium Spa Package','Ultimate Spa Package'];
  $locations = ['Elboya Mechanical','Airdrie','MK Commercial','Okotoks','Country Hills Collision','CARSTAR EXPRESS'];
}
else if($service=='Paint Protection Film Premium' || $service=='Paint Protection Film Ultimate')
{
	$availablePromotionOptions = ['Premium PPF Package','Ultimate PPF Package'];
}
$timeoption = ['Morning','Afternoon'];

// Set default values for the dropdowns
$selectedPromotion = '';

if (strpos($service, 'Glass Chip Repairs') !== false) {
    $selectedPromotion = 'NA';
} elseif (strpos($service, 'Ultimate') !== false) {
    if (strpos($service, 'Vehicle Detailing') !== false) {
        $selectedPromotion = 'Ultimate Spa Package';
    } elseif (strpos($service, 'Paint Protection Film') !== false) {
        $selectedPromotion = 'Ultimate PPF Package';
    }
} elseif (strpos($service, 'Premium') !== false) {
    if (strpos($service, 'Vehicle Detailing') !== false) {
        $selectedPromotion = 'Premium Spa Package';
    } elseif (strpos($service, 'Paint Protection Film') !== false) {
        $selectedPromotion = 'Premium PPF Package';
    }
}
?>

<!DOCTYPE html>
<html lang="en" >
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title>Landing Page</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity= 
    "sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"> 
    </script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/christmas.css">

</head>
<body>
<div class="mainContainer">
  <div class="topContent">
    <div class="innreWrapper">
	<div class="homeBtn"><a href="https://forceautostyling.com/season-for-giving">Home</a></div>
      <div class="topLogos">
        <ul>
          <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/Food Bank Logos__Calgary Food Bank Logo.jpg"></li>
          <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/Food Bank Logos__Airdrie Food Bank Logo.jpg"></li>
          <li><img src="../../wp-content/themes/forcev2/assets/images/christmas/Food Bank Logos__Okotoks Food Bank Logo.jpg"></li>
        </ul>
      </div>
      <div class="topHeader"> <span>Tis' the season for</span> <font>Giving</font> </div>
      <div class="formDiv">
        <p>Fill out the form below and submit to book your appointment today!</p>
        <div class="mainForm">
			<form id="myForm" action="https://forceautostyling.com/book-your-appointment" method="POST" >
          <div class="field">
            <label>Name</label>
            <input type="text" class="formInput" name="customer_name" value="" required>
          </div>
          <div class="field">
            <label>Email</label>
            <input type="text" class="formInput" name="customer_email" value="" required>
          </div>
          <div class="field">
            <label>Phone Number</label>
            <input type="text" class="formInput" name="customer_phone" value="" required>
          </div>
          <div class="tripleFields">
            <div class="field">
              <label>Vehicle Year</label>
              <input type="text" class="formInput" name="booking_year" value="" required>
            </div>
            <div class="field">
              <label>Vehicle Make</label>
              <input type="text" class="formInput" name="booking_make" value="" required>
            </div>
            <div class="field">
              <label>Vehicle Model</label>
              <input type="text" class="formInput" name="booking_model" value="" required>
            </div>
          </div>
          <div class="field">
            <label>Trim Level (Optional)</label>
            <input type="text" class="formInput" name="trimlevel" value="">
          </div>
          <div class="field">
			<label>Select Promotion</label>
			<select name="promotion" id="promotion" required>
				<option value="">--select--</option>
				<?php foreach ($availablePromotions as $promotion) : ?>
					<option value="<?php echo htmlspecialchars($promotion); ?>" <?php 
                // Check if $service contains the base promotion string
                if (strpos($service, $promotion) !== false) { 
                    echo 'selected'; 
                } 
                ?>>
						<?php echo htmlspecialchars($promotion); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>

		<!-- HTML for the "Select Promotional Option" dropdown -->
		<div class="field">
			<label>Select Promotional Option</label>
			<select name="promotionOption" id="promotionOption" required>
				<option value="">--select--</option>
				<?php foreach ($availablePromotionOptions as $option) : ?>
					<option value="<?php echo htmlspecialchars($option); ?>" <?php echo $selectedPromotion === $option ? 'selected' : ''; ?>>
						<?php echo htmlspecialchars($option); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
          <div class="field">
            <label>Select Location</label>
             <select name="location" required>
				<option value="">--select--</option>
				<?php foreach ($locations as $location) : 
					?>
					<option value="<?php echo htmlspecialchars($location); ?>">
						<?php echo htmlspecialchars($location); ?>
					</option>
				<?php endforeach; ?>
			</select>
          </div>
          <div class="field">
            <label>Select Date</label>
            <div class="dateTimeConatiner">
              <div class="calendarWrapper">
                <div class="calendar">
                  <div class="month"><a href="#" class="nav"> <i class="fas fa-angle-left"></i></a>
                    <div>December <span class="year">2024</span></div>
                    <a href="#" class="nav"><i class="fas fa-angle-right"></i></a> </div>
                  <div class="days"> <span>Su</span> <span>Mo</span> <span>Tu</span> <span>We</span> <span>Th</span> <span>Fr</span> <span>Sa</span> </div>
                  <div class="dates">
                    <button class="weakend disabled">
                    <time>1</time>
                    </button>
                    <button>
                    <time>2</time>
                    </button>
                    <button>
                    <time>3</time>
                    </button>
                    <button>
                    <time>4</time>
                    </button>
                    <button>
                    <time>5</time>
                    </button>
                    <button>
                    <time>6</time>
                    </button>
                    <button class="weakend disabled">
                    <time>7</time>
                    </button>
                    <button class="weakend disabled">
                    <time>8</time>
                    </button>
                    <button>
                    <time>9</time>
                    </button>
                    <button>
                    <time>10</time>
                    </button>
                    <button>
                    <time>11</time>
                    </button>
                    <button>
                    <time>12</time>
                    </button>
                    <button>
                    <time>13</time>
                    </button>
                    <button class="weakend disabled">
                    <time>14</time>
                    </button>
                    <button class="weakend disabled">
                    <time>15</time>
                    </button>
                    <button>
                    <time>16</time>
                    </button>
                    <button>
                    <time>17</time>
                    </button>
                    <button class="today">
                    <time>18</time>
                    </button>
                    <button>
                    <time>19</time>
                    </button>
                    <button>
                    <time>20</time>
                    </button>
                    <button class="weakend disabled">
                    <time>21</time>
                    </button>
                    <button class="weakend disabled">
                    <time>22</time>
                    </button>
                    <button>
                    <time>23</time>
                    </button>
                    <button>
                    <time>24</time>
                    </button>
                    <button class="disabled">
                    <time>25</time>
                    </button>
                    <button class="disabled">
                    <time>26</time>
                    </button>
                    <button class="disabled">
                    <time>27</time>
                    </button>
                    <button class="weakend disabled">
                    <time>28</time>
                    </button>
                    <button class="weakend disabled">
                    <time>29</time>
                    </button>
                    <button>
                    <time>30</time>
                    </button>
                    <button>
                    <time>31</time>
                    </button>
                    <button>
                    <time></time>
                    </button>
                    <button>
                    <time></time>
                    </button>
                    <button>
                    <time></time>
                    </button>
                    <button class="weakend disabled">
                    <time></time>
                    </button>
                  </div>
                </div>
              </div>
			  <div class="error-message">
			  <?php if (isset($errorMessage)) : ?>
        
            <?php echo $errorMessage; ?>
        
    <?php endif; ?>
	</div>
              <div class="fieldsWithCalendar">
                <div class="field">
                  <label>Date</label>
                  <input type="text" class="formInput" name="booking_date" value="" required>
                </div>
                <div class="field">
                  <label>Select Time</label>
				  <select name="booking_time" id="time" required>
					<option value="">--select--</option>
					<?php foreach ($timeoption as $time) : ?>
						<option value="<?php echo htmlspecialchars($time); ?>">
							<?php echo htmlspecialchars($time); ?>
						</option>
					<?php endforeach; ?>
				</select>
                </div>
                <div class="field radioField">
                  <label>Would you like to receive future marketing material form MK Auto Group?</label>
                 <!-- <input type="radio" class="" value="1" name="marketing" required>-->
				
                </div>
				 <div class="yesNo">
				  <label>
					<input type="checkbox" name="choice-radio" value="1">
					Yes
				  </label>
				  <label>
					<input type="checkbox" name="choice-radio" value="0">
					No
				  </label>
				</div>
                <div class="field">
                  <input type="submit" class="submitButton" name="submit">
                </div>
					</form>
              </div>
            </div>
          </div>
        </div>
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
<script>
	$(document).ready(function() {

	function checkTimeslots() 
	{
		let promotion = $('#promotion').val();
		let time = $('#time').val();
		let location = $('select[name="location"]').val();
		let date = $('input[name="booking_date"]').val(); // Assuming this holds the selected date

		// Check if the necessary fields are filled
		// if (!promotion || !location || !date || !time) {
		// 	alert("Please select promotion, location, date, and time before checking timeslots.");
		// 	return;
		// }

		// Send AJAX request to check timeslots
		$.ajax({
			url: 'https://forceautostyling.com/book-your-appointment',
			type: 'POST',
			data: {
				promotion: promotion,
				location: location,
				booking_date: date,
				time: time,
				action: 'check_timeslots'
			},
			success: function(response) {
				const data = typeof response === "string" ? JSON.parse(response) : response;

				if (data.booked) {
					console.log("The timeslot is fully booked.");
					//$('input[name="booking_date"]').val('');
					$(".error-message").text('This timeslot is already fully booked.');
					// Additional actions for fully booked timeslot
				} else {
					console.log("The timeslot is available.");
					$(".error-message").empty();
					// Additional actions for available timeslot
				}
			},
			error: function() {
				alert("Error fetching available timeslots. Please try again later.");
			}
		});
	}

	$('#time').on('change', function() {
    	checkTimeslots(); // Call the AJAX function
	});	

	$('.dates button').on('click', function(event) {
		var dateval = $(this).text().trim();
		$('.dates button').removeClass('today');
		$(this).addClass('today');
		console.log(dateval);
		const timeDropdown = $('select[name="booking_time"]');
    
		// Store previously selected time, if any
		const previousTimeSelection = timeDropdown.val();
        timeDropdown.empty(); // Clear current options
		if(dateval=='24')
		{
			timeDropdown.append(new Option('Morning', 'Morning'));
		}
		else
		{
			timeDropdown.append(new Option('Morning', 'Morning'));
            timeDropdown.append(new Option('Afternoon', 'Afternoon'));
		}
		$('input[name="booking_date"]').val(dateval+'-12-2024');

		// Set the previously selected time, if it exists in new options
		if (previousTimeSelection && timeDropdown.find(`option[value="${previousTimeSelection}"]`).length) {
        	timeDropdown.val(previousTimeSelection);
    	}



		checkTimeslots();
        event.preventDefault(); // Prevent form submission
    });

    // Define promotion options and locations based on promotion selection
    const promotionData = {
        "Mechanical Services": {
            options: ["Oil change", "4-Wheel Alignment", "Tire Rotation"],
            locations: ["Elboya Mechanical", "Intact Centre (Midnapore)"]
        },
        "Glass Chip Repair": {
            options: ["NA"],
            locations: ["Airdrie", "MK Commercial", "Okotoks", "Country Hills Collision", "CARSTAR EXPRESS"]
        },
        "Vehicle Detailing": {
            options: ["Premium Spa Package","Ultimate Spa Package"],
            locations: ["Airdrie", "MK Commercial", "Okotoks", "Country Hills Collision", "CARSTAR EXPRESS","Elboya Mechanical"]
        },
        "Paint Protection Film": {
            options: ["Premium PPF Package","Ultimate PPF Package"],
            locations: ["Airdrie", "MK Commercial", "Okotoks", "Country Hills Collision", "CARSTAR EXPRESS","Elboya Mechanical", "Intact Centre (Midnapore)"]
        }
    };

    $('.yesNo input[type="checkbox"]').on('change', function() {
  // If "Yes" is checked, uncheck "No"
  if ($(this).val() === '1' && $(this).is(':checked')) {
    $('.yesNo input[type="checkbox"]').not(this).prop('checked', false);
  } 
  // If "No" is checked, uncheck "Yes"
  else if ($(this).val() === '0' && $(this).is(':checked')) {
    $('.yesNo input[type="checkbox"]').not(this).prop('checked', false);
  }
});

    // Handle the change event on the promotion dropdown
    $('#promotion').on('change', function() {
		//debugger;
        const selectedPromotion = $(this).val();

        // Get the data for the selected promotion
        const promotionOptions = promotionData[selectedPromotion]?.options || [];
        const locations = promotionData[selectedPromotion]?.locations || [];

        // Populate the promotionOption dropdown
        const $promotionOption = $('#promotionOption');
        $promotionOption.empty().append('<option value="">--select--</option>'); // Reset options
        promotionOptions.forEach(option => {
            $promotionOption.append(`<option value="${option}">${option}</option>`);
        });

		// Automatically select "NA" if the promotion is "Glass Chip Repair" and the only option is "NA"
		if (selectedPromotion === "Glass Chip Repair" && promotionOptions.length === 1 && promotionOptions[0] === "NA") {
        $promotionOption.val("NA");
    }

        // Populate the location dropdown
        const $location = $('select[name="location"]');
        $location.empty().append('<option value="">--select--</option>'); // Reset options
        locations.forEach(location => {
            $location.append(`<option value="${location}">${location}</option>`);
        });
    });


	// calendar 

	function updateCalendarButtons() {
        const selectedPromotion = $('#promotion').val();
        const selectedLocation = $('select[name="location"]').val();
        
        // Clear any previously applied 'disabled' and 'today' classes on the calendar buttons
        //$('.dates button').removeClass('disabled today');
		$('.dates button').not(':contains(25), :contains(26), :contains(27), .weakend').removeClass('disabled today');
		$('input[name="booking_date"]').val('');
        // Apply 'today' class and disable others based on specific conditions
        if (selectedPromotion === 'Glass Chip Repair') {
			if (selectedLocation === 'Airdrie') 
			{
                $('.dates button').each(function() {
                    if ($(this).text().trim() === '3') {
						$('input[name="booking_date"]').val('03-12-2024');
                        $(this).addClass('today');
                    } else {
                        $(this).addClass('disabled');
                    }
                });
            }
            if (selectedLocation === 'MK Commercial') 
			{
                $('.dates button').each(function() {
                    if ($(this).text().trim() === '3') {
						$('input[name="booking_date"]').val('03-12-2024');
                        $(this).addClass('today');
                    } else {
                        $(this).addClass('disabled');
                    }
                });
            }
            if (selectedLocation === 'Okotoks') 
			{
                $('.dates button').each(function() {
                    if ($(this).text().trim() === '4') {
						$('input[name="booking_date"]').val('04-12-2024');
                        $(this).addClass('today');
                    } else {
                        $(this).addClass('disabled');
                    }
                });
            }
            if (selectedLocation === 'Country Hills Collision' || selectedLocation === 'CARSTAR EXPRESS') 
			{
                $('.dates button').each(function() {
                    if ($(this).text().trim() === '5') {
						$('input[name="booking_date"]').val('05-12-2024');
                        $(this).addClass('today');
                    } else {
                        $(this).addClass('disabled');
                    }
                });
            }
        }

		// Update time dropdown options based on selected promotion and location
        const timeDropdown = $('select[name="booking_time"]');
        timeDropdown.empty(); // Clear current options

        // Check conditions and set time options
        if (selectedPromotion === 'Glass Chip Repair') 
		{
			if(selectedLocation==='MK Commercial')
			{
				timeDropdown.append(new Option('Afternoon', 'Afternoon'));
			}
			else
			{
				timeDropdown.append(new Option('Morning', 'Morning'));
			}
        }
		else
		{
			timeDropdown.append(new Option('Morning', 'Morning'));
            timeDropdown.append(new Option('Afternoon', 'Afternoon'));
		}
    }

    // Event listeners for dropdown changes
    $('#promotion').on('change', updateCalendarButtons);
    $('select[name="location"]').on('change', updateCalendarButtons);

    // Initial check in case there are default values selected
    updateCalendarButtons();
});

</script>
</body>
</html>
