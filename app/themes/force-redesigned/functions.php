<?php
/* Start custom code for old theme 15-04-2024 */
/** remove extra roles **/
remove_role( 'subscriber' );
remove_role( 'editor' );
remove_role( 'contributor' );
remove_role( 'author' );
/** Add new role type for Dealership users **/
add_role('dealership', __(
    'Dealership'),
    array(
        'read' => true, // Allows a user to read
    )
);

// echo realpath(__DIR__); die;
// Corrected code to prevent "triggered too early" notice
add_action('init', function() {
  $bookly_autoload = realpath(__DIR__).'/../../plugins/appointment-booking/autoload.php';
  if (file_exists($bookly_autoload)) {
    include_once $bookly_autoload;
  }
}, 1);

use Bookly\Lib\Utils\DateTime;
//include_once realpath(__DIR__).'/../../plugins/appointment-booking/autoload.php';
//use Bookly\Lib\Utils\DateTime;
/** Register a menu to theme **/
function register_my_menus() {
  register_nav_menus(
    array(
      'main-menu' => __( 'Main Menu' )
    )
  );
}
add_action( 'init', 'register_my_menus' );

/**
 *  Add custom classes to ul element of navigation
 */
class My_Walker_Nav_Menu extends Walker_Nav_Menu {
  function start_lvl(&$output, $depth = 0, $args = null ) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"dropdown-menu sub-menu\">\n";
  }
}

/**
 * Add custom classes to li element of navigation
 *
 * @param array  $classes The CSS classes that are applied to the menu item's <li> element.
 * @param object $item    The current menu item.
 * @return array (maybe) modified nav menu class.
 */
function wpdocs_special_nav_class( $classes, $item ) {
    $classes[] = "dropdown";
    return $classes;
}
add_filter( 'nav_menu_css_class' , 'wpdocs_special_nav_class' , 10, 2 );

/**
 *  Add support for featured image to custom post types
 */
function my_cptui_featured_image_support() {
	$cptui_post_types = cptui_get_post_type_slugs();
	add_theme_support( 'post-thumbnails', $cptui_post_types );
}
add_action( 'after_setup_theme', 'my_cptui_featured_image_support' );

/** 
 * It will fetch services based on Category id 
 * call shortcode like below
 * [get_service_list name="Window Film" type="2"]
 * @params: name is used for Category name and type is used for different layout type
 */
 
 function get_duration($service){
	if(!empty($service->extra_duration_text)){
	 $duration_range = $service->extra_duration_text;
	}
	elseif(!empty($service->is_duration_hours)){
	$duration_range = $service->is_duration_hours;
	}
	else{
	$duration = DateTime::secondsToInterval($service->duration);
	$duration_range = $duration;
	}
	return $duration_range;
 }

/**
 * Get services list 
 * @global type $wpdb
 * @param type $args
 * @return string (html)
 */
function get_services($args) {
    global $wpdb;
    if(!isset($args['name']) || empty($args['name'])) {
        return '';
    }
	
    $type = (isset($args['type']) && !empty($args['type'])) ? $args['type'] : 1;
    $hide = (isset($args['hide']) && !empty($args['hide'])) ? $args['hide'] : 0;
    $services_tbl = 'wp_ab_services';
    $category_tbl = 'wp_ab_categories';
    $loop = $wpdb->get_results( $wpdb->prepare(
        "SELECT s.* from $services_tbl s LEFT JOIN $category_tbl c ON c.id=s.category_id WHERE c.name='%s'",
            $args['name']
    ));
    // echo "<pre>"; print_r($loop); die;
    $output = '';
    foreach ($loop as $service) {
        /** add service id to array list that you don't want to display in services list **/
        if($type == 3) {
            if(!in_array($service->id, array(59, 60, 61, 66)))
                continue;
        } elseif($type == 4) {
            if(!in_array($service->id, array(67, 68)))
                continue;
        } else {
            if(in_array($service->id, array(32, /*34, 35, 36,*/ 55, 56, 57, 58, 59, 60, 61, 62, 66, 67, 68)))
                continue;
        }
		// $duration = DateTime::secondsToInterval($service->duration);
		$service_duration = '';
		$service_duration = get_duration($service);
        if(!empty($hide) && $hide == 1 && isDealerUser()) {
            $price = ($service->price == 0 || $service->price == 0.00) ? '<span class="price" style="visibility: hidden;"></span>' : '<span class="price"></span>';
        } else {
            if((int)$service_duration == 24) $service_duration = '1 day';
            if(in_array($service->id, array(59, 62))) { 
                $service_duration = 'Overnight Service'; //for UNDERCOAT protection
                $price = ($service->price == 0 || $service->price == 0.00) ? '<span class="price" style="visibility: hidden;"><span style="margin-left: -30px;">Starting at </span><br> <font>$</font> </span>' : '<span class="price" style="width:150px;"><span style="margin-left: -30px;">Starting at </span> <br> <font>$'.str_replace('.00', '', $service->price).'</font></span>';
            }
            else
                $price = ($service->price == 0 || $service->price == 0.00) ? '<span class="price" style="visibility: hidden;">Starting at <br> <font>$</font> </span>' : '<span class="price">Starting at <br> <font>$'.str_replace('.00', '', $service->price).'</font></span>';
        }
        $title = strtoupper($service->title);
        //get image according to service
        $img_defender = get_service_image($service);
        if(isset($type) && ($type == 1 || $type == 3 || $type == 4)) {
            global $post;
            $is_display = false;
            $cls = 'fill';
            if($post->ID == 562 || $post->ID == 564 || $type == 3 || $type == 4 || $service->id == 62) { //interior-detailing page & exterior-wax-polish
                $is_display = true;
                $cls = "emptyp";
            }
            if($is_display) {
                $output .= '<div class="pack-col-container '.($service->id == 61 ? 'extra-height' : '').'">';
            }
            $output .= '
                <div class="pack-col" data-id="'.$service->id.'" data-name="'.$service->title.'">
                        <h3 class="pack-title">'.$service->title.' <span class="series">SERIES</span></h3>
                        '.$img_defender.'
                        <p class="'.$cls.'"><span>'.$service->info.'</span></p>
                        <div class="pack-btm">
                            '.$price.'
                            <a class="btn" href="'.home_url().'/book-an-appointment?sid='.$service->id.'&cid='.$service->category_id.'">Book An Appointment</a>
                        </div>
                        <div class="overlay"></div>
                </div>';
            if($post->ID == 564) { //exterior-wax-polish
                $output .= '<table class="additions"><tr><th colspan="2">Additions</th></tr>
                    <tr><td>Dark colors <em>Add</em> </td><td class="tdPrice">+$50</td></tr>
                    <tr><td>Oversize Vehicles <em>Add</em> </td><td class="tdPrice">+$50</td></tr>
                    <tr><td>Contaminant removal extra <em>(tar, sap, overspray etc.)</em> </td><td class="tdPrice">See below</td></tr>
                    </table>';
                $output .= '</div>';
            } elseif($type == 3) {
                $extra = '';
                $add = '+$50';
                if($service->id == 60)
                    $add = '+$50';
                if($service->id == 66)
                    $add = '+$75';
                if($service->id == 59)
                    $add = '+$100';
                if($service->id == 61) {
                    $add = '+$50';
                    $extra = '<tr><td>Conditioning <em>Add</em> </td><td class="tdPrice">+$50</td></tr>';
                }
                $output .= '<table class="additions"><tr><th colspan="2">Additions</th></tr>
                    <tr><td>Oversize <em>Add</em> </td><td class="tdPrice">'.$add.'</td></tr>';
                $output .= $extra;
                $output .= '</table>';
                $output .= '</div>';
            } elseif($type == 4) {
                $extra = '';
                $add = '+$50';
				$addition = '+$50';
                if($service->id == 67) {
                    $add = '+$49.95';
					$addition = '$59.95';
				}
                if($service->id == 68) {
                    $add = '+$59.95';
					$addition = '$69.95';	
				}
					$output .= '<table class="additions"><tr><th colspan="2">Additions</th></tr>
                    <tr><td>Yearly Restorer  </td><td class="tdPrice">'.$addition.'</td></tr>';
                $output .= $extra;
                $output .= '</table>';
                $output .= '</div>';
            } elseif($service->id == 62) { //full package for paint protection
                $output .= '<table class="additions"><tr><th colspan="2">Additions</th></tr>
                    <tr><td>Oversize Vehicles <em>Add</em> </td><td class="tdPrice">+$150</td></tr>
                    </table>';
                $output .= '</div>';
            } elseif($is_display) {
                if(!in_array($service->id, array(42, 43))) {
                $output .= '<table class="additions"><tr><th colspan="2">Additions</th></tr>
                    <tr><td>For Luxury / High End <em>Add</em> </td><td class="tdPrice">+$50</td></tr>
                    </table>';
                }
                $output .= '</div>';
            }
        }
				else {
			if(isset($args['not']) && $args['not'] == 'WINDSHIELD PROTECTION' && $args['not'] == $service->title) {
				continue;
			}
            $tint_img = '2window-img.png';
            if($service->title == '3 WINDOW TINT (FIXED)' || $service->title == '3 WINDOW TINT (NON-FIXED)' || ($service->id == 8 || $service->id == 35)) {
                $tint_img = '3window-img.png';
            } elseif($service->title == '5 WINDOW TINT (FIXED)' || $service->title == '5 WINDOW TINT (NON-FIXED)' || ($service->id == 9 || $service->id == 36)) {
                $tint_img = '5window-img.png';
            } elseif($service->title == '7 WINDOW TINT' || $service->id == 17) {
                $tint_img = '7window-img.png';
            } elseif($service->title == '9 WINDOW TINT' || $service->id == 37) {
                $tint_img = '9window-img.png';
            }
            $hidden_span = '';
            /*if(in_array($service->id, array(8, 9, 17, 37)))
                $hidden_span = '<span style="visibility:hidden">Windows Windows</span>';*/
            
            $output .= '
                <div class="pack-col">
                    <h3 class="pack-title">'.$service->title.'</h3>
                    <img src="'.home_url().'/wp-content/themes/force/assets/images/'.$tint_img.'" alt="">
                    <p><span>'.$service->info.'<br> ANY SHADE'.$hidden_span.'</span></p>
                    <div class="pack-btm">
                        '.$price.'
                        <a class="btn" href="'.home_url().'/book-an-appointment?sid='.$service->id.'&cid='.$service->category_id.'">Book An Appointment</a>
                    </div>
                    <div class="overlay"></div>
                </div>';
            
        }
    }
    return $output;
}
add_shortcode( 'get_service_list' , 'get_services' );

function add_username_to_body_class($classes) {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $username_class = 'user-' . sanitize_html_class($current_user->user_login);
        $classes[] = $username_class;
    }
    return $classes;
}
add_filter('body_class', 'add_username_to_body_class');


//get images acc. to service
function get_service_image($service) {
    if($service->id == 34) { 
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/smallCars.png" alt="SMALL CARS">';
    } elseif($service->id == 38) { 
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/medium&fullsize.png" alt="MEDIUM / FULL SIZE">';
    } elseif($service->id == 39) { 
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/minivans&largeSUV.png" alt="MINIVANS & LARGE SUVs">';
    } elseif($service->id == 40) { 
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/small_suv.png" alt="SMALL SUVs">';
    } elseif($service->id == 42) { 
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/truckExtended&Crewcab.png" alt="TRUCKS EXTENDED & CREW CAB">';
    } elseif($service->id == 43) { 
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/trunk-shampoo.png" alt="TRUNK SHAMPOO">';
    } elseif($service->id == 67) { 
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/medium&fullsize.png" alt="CAR">';
    } elseif($service->id == 68) { 
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/minivans&largeSUV.png" alt="OVERSIZED CAR">';
    } elseif($service->id == 44) { 
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/medium&fullsize.png" alt="OVERSIZED CAR">';
    } elseif($service->id == 45) { 
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/minivans&largeSUV.png" alt="OVERSIZED CAR">';
    } elseif($service->title == 'BRONZE') {
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/shield.png" alt="">';
    } elseif($service->title == 'SILVER'){
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/defender.png" alt="">';
    } elseif($service->title == 'GOLD'){
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/defender-plus.png" alt="">';
    } elseif($service->title == 'FORTIFIED'){
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/fortifide.png" alt="">';
    } elseif($service->title == 'CUSTOM' && $service->category_id == 1){
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/custom.png" alt="">';
    } else {
        $img_defender = '<img src="'.home_url().'/wp-content/themes/force/assets/images/custom.png" alt="">';
    }
    return $img_defender;
}
function get_services_squarefeet($args) {
    global $wpdb;
    if(!isset($args['name']) || empty($args['name'])) {
        return '';
    }
    $type = (isset($args['type']) && !empty($args['type'])) ? $args['type'] : 1;
    $services_tbl = 'wp_ab_services';
    $category_tbl = 'wp_ab_categories';
    $loop = $wpdb->get_results( $wpdb->prepare(
        "SELECT s.* from $services_tbl s LEFT JOIN $category_tbl c ON c.id=s.category_id WHERE c.name='%s'",
            $args['name']
    ));
    
    $output = '';
    foreach ($loop as $service) {
		// $duration = DateTime::secondsToInterval($service->duration);
		$service_duration = '';
		$service_duration = get_duration($service);
        $price = ($service->price == 0 || $service->price == 0.00) ? '' : '<span class="price">Starting at <br> <font>$'.str_replace('.00', '', $service->price).'</font>PER SQ/FT</span>';
        if(isset($type) && $type == 1) {
            $output .= '
                <div class="pack-col">
                        <h3 class="pack-title">'.$service->title.' <span class="series">SERIES</span></h3>
                        <img src="'.home_url().'/wp-content/themes/force/assets/images/defender.png" alt="">
                        <p><span>'.$service->info.'</span></p>
                        <div class="pack-btm">
                            '.$price.'
                            <a class="btn" href="'.home_url().'/book-an-appointment?sid='.$service->id.'&cid='.$service->category_id.'">Book An Appointment</a>
                        </div>
                        <div class="overlay"></div>
                </div>';
        }
				else {
			if(isset($args['not']) && $args['not'] == 'WINDSHIELD PROTECTION' && $args['not'] == $service->title) {
				continue;
			}
            $output .= '
                <div class="pack-col">
                    <h3 class="pack-title">'.$service->title.'</h3>
                    <img src="'.home_url().'/wp-content/themes/force/assets/images/2window-img.png" alt="">
                    <p><span>'.$service->info.'<br> ANY SHADE</span></p>
                    <div class="pack-btm">
                        '.$price.'
                        <a class="btn" href="'.home_url().'/book-an-appointment?sid='.$service->id.'&cid='.$service->category_id.'">Book An Appointment</a>
                    </div>
                    <div class="overlay"></div>
                </div>';
        }
    }
    return $output;
}
add_shortcode( 'get_services_squarefeet' , 'get_services_squarefeet' );



function get_price($args) {
    global $wpdb;
    if(!isset($args['name']) || empty($args['name'])) {
        return '';
    }
    $type = (isset($args['type']) && !empty($args['type'])) ? $args['type'] : 1;
    $price_txt = (isset($args['price_txt']) && !empty($args['price_txt'])) ? $args['price_txt'] : '';
    $cat = (isset($args['cat']) && !empty($args['cat'])) ? $args['cat'] : '';
    $services_tbl = 'wp_ab_services';
    $category_tbl = 'wp_ab_categories';
    if(!empty($cat)) {
        $service = $wpdb->get_row( $wpdb->prepare(
            "SELECT s.* from $services_tbl s WHERE title='%s' AND category_id='%d'",
                $args['name'], $cat
        ));
    } else {
        $service = $wpdb->get_row( $wpdb->prepare(
            "SELECT s.* from $services_tbl s WHERE title='%s'",
                $args['name']
        ));
    }
    
    $output = '';
	// $duration = DateTime::secondsToInterval($service->duration);
	$service_duration = '';
	$service_duration = get_duration($service);
        if((int)$service_duration == 24) $service_duration = '1 day';
        if(empty($price_txt)) {
            $price_txt = 'Starting at';
        }
        $price = ($service->price == 0 || $service->price == 0.00) ? '' : '<span class="price">'.$price_txt.' <br> <font>$'.str_replace('.00', '', $service->price).'</font> </span>';
		
        $output .= '
                <div class="pack-btm">
                        '.$price.'
                            <a class="btn" href="'.home_url().'/book-an-appointment?sid='.$service->id.'&cid='.$service->category_id.'">Book An Appointment</a>
                </div>';
   
    return $output;
}
add_shortcode( 'get_price' , 'get_price' );

//Add class to a href in menu

function add_class_to_menu_item($item_output, $item, $depth, $args)
{
    if( 55 == $item->ID ) {
        return str_replace('<a', '<a class="my-dropdown" data-toggle="dropdown"', $item_output);
    }
    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'add_class_to_menu_item', 10, 4);

function add_class_to_menu_items($item_output, $item, $depth, $args)
{
    if( 54 == $item->ID ) {
        return str_replace('<a', '<a class="new-dropdown" data-toggle="dropdown"', $item_output);
    }
    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'add_class_to_menu_items', 10, 4);

function install_location(){
	$output .='
            <div class="row mx-0">
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 px-0 bookly-box bookly-custom-field-row model" data-id="34648" data-type="text-field">
                    <div class="bookly-form-group">
                    <label>What is the Year, Make and Model of your vehicle<span class="ques-mark">?</span></label>
                        <div class="makemodel">
                            <input class="bookly-custom-field bookly-js-select-model" value="'.(isset($_SESSION['year_make_model']) ? $_SESSION['year_make_model'] : '').'" type="text" name="year_make_model">
                        </div>
                    <div class="bookly-js-vehicle-model-error bookly-label-error bookly-custom-field-error" style="display:none;">
                        Please enter Year, Make and Model of your vehicle
                    </div>
                    </div>
                </div>

                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 px-0 bookly-box bookly-custom-field-row trim" data-id="34648" data-type="text-field">
                    <div class="bookly-form-group">
                    <label>What is the Trim on your vehicle<span class="ques-mark">? </span><span>(Examples: GT, XLT, LTE)</span></label>
                        <div>
                            <input class="bookly-custom-field bookly-js-select-trim" value="'.(isset($_SESSION['trim_of_vehicle']) ? $_SESSION['trim_of_vehicle'] : '').'" type="text" name="trim_of_vehicle">
                        </div>
                    <div class="bookly-js-select-trim-error bookly-label-error bookly-custom-field-error" style="display:none;">
                        Please enter the trim value 
                    </div>

                    </div>
                </div>
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 px-0 bookly-box bookly-custom-field-row model" data-type="text-field" style="display:none;">
                    <input type="hidden" name="ppcst_parameters" class="bookly-js-ppcst-parameters" value="'.(isset($_SESSION['ppcst_parameters']) ? $_SESSION['ppcst_parameters'] : '').'">
                    <input type="hidden" name="ppcst_price" class="bookly-js-ppcst-price" value="'.(isset($_SESSION['ppcst_price']) ? $_SESSION['ppcst_price'] : '0.00').'">
                </div>

            </div>';
	return $output;
}
/**
 * Remove update for Bookly plugin 
 * because its modified version of plugin, so don't try to update it
 * @param type $value
 * @return type
 */
function filter_plugin_updates( $value ) {
    unset( $value->response['appointment-booking/main.php'] );
    unset( $value->response['profile-builder/index.php'] );
    return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );

/** For Dealership users 180418 **/
/** Add new field to user form **/
add_action('user_new_form', 'new_user_fields');
add_action('show_user_profile', 'new_user_fields');
add_action('edit_user_profile', 'new_user_fields');
/**
 * Insert new custom fields html to use form
 * @global type $wpdb
 * @param type $user
 */
function new_user_fields($user) {
    /** get staff list for drop down **/
    global $wpdb;
    $staff_tbl = 'wp_ab_staff';
    $staff = $wpdb->get_results( $wpdb->prepare(
        "SELECT * from $staff_tbl WHERE id != '%d'",
            1
    ));
    //echo "<pre>";print_r($staff);die;    
    if(empty($user->ID) || $user->ID != 1) {
    ?>
    <table class="form-table assign_staffTbl" style="display:none;">
        <tr class="form-field">
            <th scope="row"><label for="public_calendar">Display Public Calendar? </label></th>
            <td>
                <label for="public_calendar">
                    <?php $public_calendar = get_user_meta($user->ID, 'is_public_calendar', true); ?>
                    <?php 
                        $checked_1 = $checked_2 = '';
                        if($public_calendar == 1) { $checked_1 = 'checked="checked"'; }
                        elseif($public_calendar == 0) { $checked_2 = 'checked="checked"'; }
                        else { $checked_2 = 'checked="checked"'; }
                    ?>
                        <input type="radio" name="is_public_calendar" class="public_calendar_radio" value="1" <?php echo $checked_1; ?> style="width: 15px;">Yes &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="is_public_calendar" class="public_calendar_radio" value="0" <?php echo $checked_2; ?> style="width: 15px;">No
                                                       
                        </select>
                </label>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="assign_staff">Assign Staff Member </label></th>
            <td>
                <label for="assign_staff">
                    <?php $assign_staff = get_user_meta($user->ID, 'assign_staff', true); ?>
                        <select name="assign_staff" id="assign_staff">
                            <?php if(empty($user->ID)) { ?>
                            <option value="">Select Staff Member</option>
                            <?php } ?>
                            <?php if(!empty($staff) && is_array($staff)) { 
                                foreach($staff as $row) { 
                                    $select = !empty($assign_staff) ? 'selected="selected"' : '';
                            ?>
                                    <option value="<?php echo $row->id; ?>" <?php echo $select; ?>><?php echo $row->full_name; ?></option>
                            <?php }
                            } ?>
                            
                        </select>
                </label>
            </td>
        </tr>
    </table>
<script>
    jQuery(document).ready(function() {
        var roleId = jQuery('#role').val();
        if(jQuery.trim(roleId) == 'dealership') {
            jQuery('.assign_staffTbl').show();
        } else {
            jQuery('.assign_staffTbl').hide();
        }
        var public_calendar_radio = jQuery('.public_calendar_radio:checked').val();
        if(public_calendar_radio == 1) {
            jQuery('#assign_staff').attr('disabled', 'disabled');
        } else {
            jQuery('#assign_staff').removeAttr('disabled');
        }
        
        jQuery('#role').change(function() {
            var roleId = jQuery(this).val();
            if(jQuery.trim(roleId) == 'dealership') {
                jQuery('.assign_staffTbl').show();
            } else {
                jQuery('.assign_staffTbl').hide();
            }
        });
        jQuery('#send_user_notification').removeAttr('checked');
        
        jQuery('.public_calendar_radio').change(function() {
            if(jQuery(this).is(':checked') && jQuery(this).val() == 1) {
                jQuery('#assign_staff').attr('disabled', 'disabled');
            } else {
                jQuery('#assign_staff').removeAttr('disabled');
            }
        });
    });
</script>
<?php }
}

// handle registrations on user creation
add_action( 'user_register', 'new_user_fields_after_registration', 10, 1 );
/**
 * Save custom fields during user add
 * @param type $user_id
 */
function new_user_fields_after_registration( $user_id ) {
    if (isset($_POST['assign_staff']) && !empty($_POST['assign_staff'])) {
        if(isset($_POST['role']) && $_POST['role'] == 'dealership') {
            update_usermeta($user_id, 'assign_staff', $_POST['assign_staff']);
        }
    }
    if (isset($_POST['is_public_calendar']) && !empty($_POST['is_public_calendar'])) {
        if(isset($_POST['role']) && $_POST['role'] == 'dealership') {
            update_usermeta($user_id, 'is_public_calendar', $_POST['is_public_calendar']);
        }
    }
}

//Save new field for user in users_meta table
//add_action('user_register', 'save_new_user_fields_field');
add_action('edit_user_profile_update', 'save_new_user_fields_field');
/**
 * Save custom fields during user edit
 * @param type $user_id
 * @return boolean
 */
function save_new_user_fields_field($user_id) {

    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    if (isset($_POST['assign_staff']) && !empty($_POST['assign_staff'])) {
        if(isset($_POST['role']) && $_POST['role'] == 'dealership') {
            update_usermeta($user_id, 'assign_staff', $_POST['assign_staff']);
        }
    }
    if (isset($_POST['is_public_calendar']) && ($_POST['is_public_calendar'] == 0 || $_POST['is_public_calendar'] == 1)) {
        if(isset($_POST['role']) && $_POST['role'] == 'dealership') {
            update_usermeta($user_id, 'is_public_calendar', $_POST['is_public_calendar']);
        }
    }
}

function get_assigned_staff() {
    return get_user_meta(get_current_user_id(), 'assign_staff', true);
}
/**
 * Get Bookly assigned services to staff member
 * @global type $wpdb
 * @param type $staff_id
 * @return type object
 */
function get_assigned_services_to_staff($staff_id) {
    global $wpdb;
    $tbl = 'wp_ab_staff_services';
    $services_tbl = 'wp_ab_services';
    $services = $wpdb->get_results( $wpdb->prepare(
        "SELECT st.id, st.title, st.category_id from $tbl ss JOIN $services_tbl st ON ss.service_id=st.id WHERE staff_id = '%d'",
            $staff_id
    ));
    return $services;
    //echo "<pre>";print_r($services);print_r($wpdb);die;
}
/**
 * Get Bookly categories listing
 * @global type $wpdb
 * @param type $category_id
 * @return type
 */
function get_categories_for_services($category_id) {
    global $wpdb;
    $tbl = 'wp_ab_categories';
    $category = $wpdb->get_row( $wpdb->prepare(
        "SELECT * from $tbl WHERE id = '%d'",
            $category_id
    ));
    return $category;
}

/**
 * Redirect non-admin users to home page
 *
 * This function is attached to the 'admin_init' action hook.
 */
 
/*function redirect_non_admin_users() {
    $user = wp_get_current_user();
    $role = ( array ) $user->roles;
   
    if(!empty(get_current_user_id()) && (isset($role[0]) && $role[0] != 'administrator' && $role[0] != 'manager') && !wp_doing_ajax()) { //not admin & not ajax call
        //echo 'get_current_user_id :'.get_current_user_id();die;
        wp_redirect( home_url() );
        exit;
    }
}
add_action( 'admin_init', 'redirect_non_admin_users' );*/

function redirect_non_admin_users() {
    $user = wp_get_current_user();
    $role = (array) $user->roles;

    if (!empty(get_current_user_id()) && isset($role[0]) && !in_array($role[0], ['administrator', 'manager', 'dealership']) && !wp_doing_ajax()) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'redirect_non_admin_users');


function wpse28782_remove_menu_items() {
    if( current_user_can( 'manager' ) ):
        remove_menu_page( 'edit.php?post_type=slider' );
        remove_menu_page( 'edit.php?post_type=custom_package' );
        remove_menu_page( 'edit.php?post_type=package' );
        remove_menu_page( 'tools.php' ); 
        remove_menu_page( 'profile.php' ); 
        remove_menu_page( 'edit.php' ); 
        remove_menu_page( 'edit-comments.php' ); 
        remove_menu_page( 'wpcf7' );
        remove_menu_page( 'options-general.php' ); 
        remove_menu_page( 'wp_file_manager' );
        //remove_menu_page( 'profile-builder-basic-info' );
        remove_menu_page( 'monsterinsights_dashboard' );
        // Top-level CPT UI menu (if needed)
        remove_menu_page( 'cptui_main_menu' );

        // Submenu - Add/Edit Post Types
        remove_submenu_page( 'cptui_main_menu', 'cptui_manage_post_types' );

        // Submenu - Add/Edit Taxonomies
        remove_submenu_page( 'cptui_main_menu', 'cptui_manage_taxonomies' );

        // Submenu - Registered Types/Taxes
        remove_submenu_page( 'cptui_main_menu', 'cptui_listings' );

        // Submenu - Tools
        remove_submenu_page( 'cptui_main_menu', 'cptui_tools' );

        // Submenu - Help/Support
        remove_submenu_page( 'cptui_main_menu', 'cptui_support' );

        // Submenu - About CPT UI
        remove_submenu_page( 'cptui_main_menu', 'cptui_main_menu' );

         // Remove Profile Builder menu and submenus
         remove_menu_page( 'profile-builder' );

         // Submenu - Basic Information
         remove_submenu_page( 'profile-builder', 'profile-builder-basic-info' );
 
         // Submenu - General Settings
         remove_submenu_page( 'profile-builder', 'profile-builder-general-settings' );
 
         // Submenu - Admin Bar Settings
         remove_submenu_page( 'profile-builder', 'profile-builder-admin-bar-settings' );
 
         // Submenu - Manage Fields
         remove_submenu_page( 'profile-builder', 'manage-fields' );
 
         // Submenu - Add-Ons
         remove_submenu_page( 'profile-builder', 'profile-builder-add-ons' );
    endif;
}
add_action( 'admin_menu', 'wpse28782_remove_menu_items' );

/**
 *  After login redirect front user to appointment booking form  
 * 
 * This function is attached to the 'login_redirect' action hook.
 */
function members_login_redirect($redirect_to, $request, $user) {
    //echo "<pre>";print_r($user);
    $role = 'dealership';
    if((isset($user->roles)) && in_array($role, $user->roles)) {
        //return 'our-protection-packages';
        return 'book-an-appointment';
    }
    return 'wp-admin/';
}

add_filter('login_redirect', 'members_login_redirect', 10, 3);

/**
 * When user logs-out
 * handle redirection page
 */
function auto_redirect_after_logout(){
    if(!empty(get_current_user_id()) /*&& get_current_user_id() != 1*/) {
        $user = wp_get_current_user();
        $role = ( array ) $user->roles;
        if(isset($role[0]) && $role[0] != 'administrator') {
            wp_redirect( home_url('dealer-login') );
            exit();
        }
    }
}
add_action('wp_logout','auto_redirect_after_logout');

function check_book_an_appointment_page() {
    if (is_page('book-an-appointment')) 
    {
        if (!session_id()) {
            session_start();
        }
        if (function_exists('is_user_logged_in') && !is_user_logged_in()) {
            wp_redirect( home_url('dealer-login') );
            exit();
        }
    }
}
add_action('template_redirect', 'check_book_an_appointment_page');

/**
 * Check whether current logged-in user is with dealership role
 */
function isDealerUser() {
    $user = wp_get_current_user();
    $role = ( array ) $user->roles;
    if(!empty(get_current_user_id()) && (isset($role[0]) && $role[0] != 'administrator')) {
        return get_current_user_id();
    } else {
        return false;
    }
}
//Rename Slots for Dealer Login
function renameSlots($key){
    $time_slot = 'Slot'." ".($key+1);
    return $time_slot;
}
//Add Noon for After Noon Timing
function returnNoonLabel($data){
    $noon = '12:00:00';
    $new_start = Date('H:i:s', strtotime($data[0][2]));
    if($new_start >= $noon){
     return 'Noon';
    }
    return '';
}


function cptui_register_my_cpts_custom_package() {

    /**
     * Post Type: Custom Features.
     */
    
    // Define labels for the custom post type
    $labels = array(
        "name" => __( "Custom Features", "custom-post-type-ui" ),
        "singular_name" => __( "Custom Feature", "custom-post-type-ui" ),
    );

    // Define arguments for the custom post type
    $args = array(
        "label" => __( "Custom Features", "custom-post-type-ui" ),
        "labels" => $labels,
        "description" => "Custom Paint Protection Package features",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "delete_with_user" => false,
        "show_in_rest" => false,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "exclude_from_search" => false,
        //"capability_type" => array('custom_feature', 'custom_features'), // Custom capabilities
        "map_meta_cap" => true, // Use mapped capabilities
        "hierarchical" => false,
        "rewrite" => array( "slug" => "custom_package", "with_front" => true ),
        "query_var" => true,
        "supports" => array( "title", "thumbnail", "custom-fields", "page-attributes" ),
    );

    // Register the custom post type
    register_post_type( "custom_package", $args );
}

// Hook into the 'init' action to register the custom post type
add_action( 'init', 'cptui_register_my_cpts_custom_package' );

// Modify the query for the admin list view to show only the current user's posts
function restrict_custom_package_posts_to_current_user( $query ) {
    if ( is_admin() && $query->is_main_query() && $query->get('post_type') === 'custom_package' ) {

        $current_user_id = get_current_user_id();
        $current_user = get_userdata($current_user_id);

        // Check if the user has the 'dealership' role
        if ( in_array('dealership', (array) $current_user->roles) ) {
            // Restrict to current user's posts if the user has the 'dealership' role
            $query->set('author', $current_user_id);
        }
    }
}

// Hook into the 'pre_get_posts' action
add_action( 'pre_get_posts', 'restrict_custom_package_posts_to_current_user' );


function add_custom_feature_capabilities() {
    $role = get_role('dealership'); // Ensure this role exists

    if ($role) {
        $role->add_cap('edit_custom_feature');
        $role->add_cap('edit_others_custom_features');
        $role->add_cap('publish_custom_features');
        $role->add_cap('read_custom_feature');
        $role->add_cap('delete_custom_feature');
    }
}

add_action('admin_init', 'add_custom_feature_capabilities');

function cptui_register_my_taxes_package_type() {
    /**
     * Taxonomy: Packages.
     */

    $labels = array(
        "name" => __( "Packages", "custom-post-type-ui" ),
        "singular_name" => __( "Package", "custom-post-type-ui" ),
    );

    $args = array(
        "label" => __( "Packages", "custom-post-type-ui" ),
        "labels" => $labels,
        "public" => true,
        "publicly_queryable" => true,
        "hierarchical" => false,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => array( 'slug' => 'package_type', 'with_front' => true, ),
        "show_admin_column" => false,
        "show_in_rest" => true,
        "rest_base" => "package_type",
        "rest_controller_class" => "WP_REST_Terms_Controller",
        "show_in_quick_edit" => false,
    );

    register_taxonomy( "package_type", array( "custom_package" ), $args );
}
add_action( 'init', 'cptui_register_my_taxes_package_type' );

// Filter the taxonomy terms to show only those associated with the logged-in user's posts
function filter_package_type_terms($terms, $taxonomies, $args) {
    if (is_admin() && isset($args['taxonomy']) && $args['taxonomy'][0] === 'package_type') {

        $current_user_id = get_current_user_id();

        if ($current_user_id) {
            // Get current user's roles
            $current_user = get_userdata($current_user_id);
            $user_roles = $current_user->roles;

            // Check if user has the 'dealership' role
            if (in_array('dealership', $user_roles)) {

                $post_ids = get_posts(array(
                    'post_type' => 'custom_package',
                    'author' => $current_user_id,
                    'post_status' => 'publish',
                    'fields' => 'ids', // Only get the post IDs
                    'numberposts' => -1,
                ));

                if (empty($post_ids)) {
                    return array(); // No terms will show if the user has no posts
                }

                // Get terms associated with the user's posts
                $filtered_terms = [];
                foreach ($terms as $term) {
                    // Check if the term is associated with any of the user's posts
                    $term_posts = get_posts(array(
                        'post_type' => 'custom_package',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'package_type',
                                'field' => 'term_id',
                                'terms' => $term->term_id,
                            ),
                        ),
                        'fields' => 'ids',
                    ));

                    // If the term is associated with the user's posts, include it
                    if (array_intersect($post_ids, $term_posts)) {
                        $filtered_terms[] = $term;
                    }
                }

                return $filtered_terms; // Return only the terms associated with the user's posts
            }
        }
    }

    return $terms; // Return the original terms if no filtering is needed
}

// Hook the filter into the get_terms action
add_filter('get_terms', 'filter_package_type_terms', 10, 3);




/**
 * Get list of Paint Protection custom package features
 */
function getCustomPackagesList() {
    $html = '';
    $taxonomy = 'package_type';

    // Get the current user ID
    $current_user_id = get_current_user_id();
    
    // Show a message if the user is not logged in
    if (!$current_user_id) {
        return "<p style='text-align:center;'>No packages available. Please <a href='/dealer-login'>log in</a> to view your packages.</p>";
    }
    
    // Fetch posts owned by the logged-in user
    $user_posts = get_posts(array(
        'post_type'   => 'custom_package',
        'author'      => $current_user_id,
        'post_status' => 'publish',
        'fields'      => 'ids', // Only get post IDs
        'numberposts' => -1
    ));

    // Exit if no posts by the user
    if (empty($user_posts)) {
        return "<p>No packages available for the current user.</p>";
    }

    // Get terms associated only with the user's posts
    $terms = get_terms(array(
        'taxonomy'   => $taxonomy,
        'orderby'    => 'count',
        'hide_empty' => 0,
        'object_ids' => $user_posts
    ));

    // Check if terms were retrieved
    if (empty($terms)) {
        return "<p>No package types found for the current user.</p>";
    }

    // Generate the HTML content based on the retrieved terms and posts
    $paintclass = '';
    if(count($terms) < 4)
    {
        $paintclass = 'packLessthenFour';
    }
    $html .= '<p class="custom-tool-note"><em>*We WRAP the leading edge of hoods with our packages.</em></p>
              <div class="sec-content paint_protection_selector '.$paintclass.'" id="paint_protection_selector">
              <div class="tab" role="tabpanel">
                  <ul class="nav nav-tabs" role="tablist">';
    
    $section = 1;
    foreach ($terms as $term) {
        $html .= '<li role="presentation" class="nav-item" data-price="' . (empty($term->description) ? '0.00' : $term->description) . '">
                  <a data-bs-target="#Section' . $section . '" class="nav-link ' . ($section == 1 ? 'active' : '') . '" role="tab" data-bs-toggle="tab">'
                  . $term->name . (strtolower($term->name) == 'custom' ? '<small>(Select Below)</small>' : ' Package') . '</a></li>';
        $section++;
    }
    
    $html .= '</ul><div class="tab-content tabs"><div class="paint_protection_title">
              <span><img src="https://forceautostyling.com/wp-content/themes/force/assets/images/paint-selector-logo.png" alt=""/></span>
              <h2>Paint Protection Selector</h2></div>';

    $section = 1;
    foreach ($terms as $term) {
        $args = array(
            'post_type'      => 'custom_package',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'tax_query'      => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $term->slug,
                ),
            ),
            'author'         => $current_user_id // Only get posts by the current user
        );

        $loop = new WP_Query($args);

        $html .= '<div role="tabpanel" class="tab-pane fade ' . ($section == 1 ? 'show active' : '') . '" id="Section' . $section . '">
                  <div class="selector-list"><ul>';

        while ($loop->have_posts()) : $loop->the_post();
            $id = get_the_ID();
            $price = get_post_meta($id, 'price', true);
            $vehicle_side = get_post_meta($id, 'vehicle_side', true);

            $html .= '<li><label class="paintlist">' . get_the_title($id) . '<input type="checkbox" ' . (strtolower($term->name) != 'custom' ? 'checked="checked" disabled' : '') . ' class="customFeaturesCheck" data-type="' . $vehicle_side . '" data-price="' . $price . '" data-class="ovrly' . $id . '" data-id="' . $id . '" value="' . get_the_title($id) . '">
                      <span class="checkmark ' . (strtolower($term->name) != 'custom' ? 'checked' : '') . '"></span></label></li>';
        endwhile;
        
        $html .= '</ul></div><div class="sedan-car"><img class="fconfig" src="https://forceautostyling.com/wp-content/themes/force/assets/images/sedan.jpg" alt="Sedan" style="opacity:1" />';
        
        while ($loop->have_posts()) : $loop->the_post();
            $id = get_the_ID();
            $image_path = get_post_meta($id, 'image_path', true);
            $vehicle_side = get_post_meta($id, 'vehicle_side', true);
            $cls = ($vehicle_side == 'rear') ? 'rearImgs' : 'frontImgs';
            
            $html .= '<img class="fconfig ' . $cls . ' ovrly' . $id . ' ' . (strtolower($term->name) != 'custom' ? 'opacity1' : '') . '" src="' . $image_path . '" data-type="' . $vehicle_side . '" alt="' . $image_path . '"/>';
        endwhile;

        $html .= '<div class="rear-side">
                  <h3>Rear</h3><img class="fconfig" src="https://forceautostyling.com/wp-content/themes/force/assets/images/sedan_overlays/rear_side.jpg" alt="Sedan" style="opacity:1" />
                  </div></div></div>';
        $section++;
    }
    $html .= '</div></div>
                <div class="priceSec">
                    <form name="saveCustomParams" id="saveCustomParams" method="post">
                        <div class="pack-total-outer"><div class="pack-total">Total= <span id="price_symbol"></span><span id="cpackage_price">0.00</span></div>
                        <span class="custom-price-note"><em>*Prices may vary depending on the vehicle make and model</em></span></div>
                        <input type="hidden" name="custom_parameters" id="custom_parameters">
                        <input type="hidden" name="total_price" id="total_price" value="0.00">
                        <input type="hidden" name="package_type" id="package_type" value="BRONZE PACKAGE">
                        <input type="hidden" name="pageAction" value="saveCustomParams">
                        <button class="btn">Book An Appointment</button>
                    </form>
                </div>
        </div>';

    
    $html .= '
    <script>
        //var totalPrice = 0.00;
        function setPackagePrice(tabEle) {
            jQuery("#price_symbol").text("$");
			 var pkg_txt = jQuery(tabEle).find("a").text();
			 if(pkg_txt == "Gold Package") {
				jQuery("#cpackage_price").text("999");
				jQuery("#total_price").val("999");
            }else{
				jQuery("#cpackage_price").text(jQuery(tabEle).data("price"));
				jQuery("#total_price").val(jQuery(tabEle).data("price"));
			}

            var checkboxes = jQuery(".tab-pane.active").find(".customFeaturesCheck");
            var customParam = [];
            checkboxes.each(function() {
                if(jQuery(this).is(":checked")) {
                    customParam.push(jQuery(this).val())
                }
            });
            //jQuery("#custom_parameters").val(customParam.toString());
            jQuery("#custom_parameters").val(customParam.join(", "));
           
            if(pkg_txt == "Bronze Package") {
                pkg_txt = "Bronze";
            } else if(pkg_txt == "Silver Package") {
                pkg_txt = "Silver";
            } else if(pkg_txt == "Gold Package") {
                pkg_txt = "Gold";
            }
            console.log(pkg_txt);
            jQuery("#package_type").val(pkg_txt);
        }
        function calculatePrice() {
            var totalPrice = 0.00;
            var customParams = [];
            jQuery(".customFeaturesCheck").each(function() {
                if(jQuery(this).is(":checked")) {
                    //calculate the price
                    totalPrice += jQuery(this).data("price");
                    customParams.push(jQuery(this).val());
                }
            });
            jQuery("#price_symbol").text("");
            var fixed = parseFloat(totalPrice).toFixed(2);
            jQuery("#total_price").val(fixed);
            //jQuery("#custom_parameters").val(customParams.toString());
            jQuery("#custom_parameters").val(customParams.join(", "));
            if(fixed == 0 || fixed == 0.00) {
                jQuery("#cpackage_price").text("Call for Price");
            } else {
                jQuery("#cpackage_price").text("$"+ fixed);
            }
        }
        jQuery(document).ready(function() {
            /** disabled the book btn if price is 0 **/
            jQuery(document).on("click", "#saveCustomParams .btn", function() {
                //if(jQuery("#total_price").val() == "0.00") {
                if(jQuery(".customFeaturesCheck:checked").length <= 0 && jQuery("#Section4").hasClass("active")) { 
                    alert("Please select atleast one service under Custom Package");
                    return false;
                } else {
                    jQuery("#saveCustomParams").submit();
                }
            });
            var imgHtml = jQuery("img[data-type=rear]").clone();
            jQuery("img[data-type=rear]").remove();
            jQuery(".rear-side img.fconfig").after(imgHtml);
            setPackagePrice(".tab ul.nav-tabs li.active");
            
            jQuery(document).on("click", ".tab ul.nav-tabs li", function(e) {
            e.preventDefault();
            console.log("clicked");
                setPackagePrice(jQuery(this));
                jQuery(".customFeaturesCheck").prop("checked", false);
                jQuery("#custom_parameters").val("");
                jQuery(".sedan-car").find("img.frontImgs").css("opacity", 0);
                jQuery(".rear-side").find("img.rearImgs").css("opacity", 0);
                if(jQuery(this).data("price") != "0.00") { jQuery(".rear-side").hide(); }
            });
            
            jQuery(document).on("click", ".customFeaturesCheck", function() {
                if(jQuery(this).is(":checked")) {
                    //cancles out unwanted images
                    /*if(jQuery(this).data("id") == 239) {
                        //cancels out luggage area
                        jQuery(".customFeaturesCheck[data-id=237]").prop("checked", false);
                    }*/
                    switch(jQuery(this).data("id")) {
                        case 547: //rear bumper
                            //cancels out luggage area
                            jQuery(".customFeaturesCheck[data-id=545]").prop("checked", false);
                            jQuery(".sedan-car").find("img.ovrly545").css("opacity", 0);
                            //calculate the price
                            //totalPrice -= jQuery(".customFeaturesCheck[data-id=237]").data("price");
                            //jQuery(".customFeaturesCheck[data-id=237]").trigger("change");
                            jQuery(".customFeaturesCheck[data-id=551]").prop("checked", false); //cancels out full vehicle
                            jQuery(".sedan-car").find("img.frontImgs.ovrly551").css("opacity", 0);
                            break;
                        case 545: //luggage area
                            //cancels out rear bumper
                            jQuery(".customFeaturesCheck[data-id=547]").prop("checked", false);
                            jQuery(".sedan-car").find("img.ovrly547").css("opacity", 0);
                            //calculate the price
                            //totalPrice -= jQuery(".customFeaturesCheck[data-id=239]").data("price");
                            //jQuery(".customFeaturesCheck[data-id=239]").trigger("change");
                            jQuery(".customFeaturesCheck[data-id=551]").prop("checked", false); //cancels out full vehicle
                            jQuery(".sedan-car").find("img.frontImgs.ovrly551").css("opacity", 0);
                            break;
                        case 537: //Partial Hood & Fenders
                            //cancels out full hood & fenders, full vehicle
                            jQuery(".customFeaturesCheck[data-id=548]").prop("checked", false);
                            jQuery(".sedan-car").find("img.ovrly548").css("opacity", 0);
                            //calculate the price
                            //totalPrice -= jQuery(".customFeaturesCheck[data-id=240]").data("price");
                            //jQuery(".customFeaturesCheck[data-id=240]").trigger("change");
                            jQuery(".customFeaturesCheck[data-id=551]").prop("checked", false); //cancels out full vehicle
                            jQuery(".sedan-car").find("img.frontImgs.ovrly551").css("opacity", 0);
                            break;
                        case 541: //A-pillars & Roofline
                            //cancels out Full Pillars & Roof
                            jQuery(".customFeaturesCheck[data-id=549]").prop("checked", false);
                            jQuery(".sedan-car").find("img.ovrly549").css("opacity", 0);
                            //calculate the price
                            //totalPrice -= jQuery(".customFeaturesCheck[data-id=241]").data("price");
                            //jQuery(".customFeaturesCheck[data-id=241]").trigger("change");
                            jQuery(".customFeaturesCheck[data-id=551]").prop("checked", false); //cancels out full vehicle
                            jQuery(".sedan-car").find("img.frontImgs.ovrly551").css("opacity", 0);
                            break;
                        case 543: //Door Cups & Edges
                            //cancels out Full Doors
                            jQuery(".customFeaturesCheck[data-id=550]").prop("checked", false);
                            jQuery(".sedan-car").find("img.ovrly550").css("opacity", 0);
                            //calculate the price
                            //totalPrice -= jQuery(".customFeaturesCheck[data-id=242]").data("price");
                            //jQuery(".customFeaturesCheck[data-id=242]").trigger("change");
                            jQuery(".customFeaturesCheck[data-id=551]").prop("checked", false); //cancels out full vehicle
                            jQuery(".sedan-car").find("img.frontImgs.ovrly551").css("opacity", 0);
                            break;
                        case 550: //Full Doors
                            //cancels out Door Cups & Edges
                            jQuery(".customFeaturesCheck[data-id=543]").prop("checked", false);
                            jQuery(".sedan-car").find("img.ovrly543").css("opacity", 0);
                            //calculate the price
                            //totalPrice -= jQuery(".customFeaturesCheck[data-id=235]").data("price");
                            //jQuery(".customFeaturesCheck[data-id=235]").trigger("change");
                            jQuery(".customFeaturesCheck[data-id=551]").prop("checked", false); //cancels out full vehicle
                            jQuery(".sedan-car").find("img.frontImgs.ovrly551").css("opacity", 0);
                            break;
                        case 549: //Full Hood & Fenders
                            //cancels out Partial Hood & Fenders
                            jQuery(".customFeaturesCheck[data-id=541]").prop("checked", false);
                            jQuery(".sedan-car").find("img.ovrly541").css("opacity", 0);
                            //calculate the price
                            //totalPrice -= jQuery(".customFeaturesCheck[data-id=233]").data("price");
                            //jQuery(".customFeaturesCheck[data-id=233]").trigger("change");
                            jQuery(".customFeaturesCheck[data-id=551]").prop("checked", false); //cancels out full vehicle
                            jQuery(".sedan-car").find("img.frontImgs.ovrly551").css("opacity", 0);
                            break;
                        case 548: //Full Hood & Fenders
                            //cancels out Partial Hood & Fenders
                            jQuery(".customFeaturesCheck[data-id=537]").prop("checked", false);
                            jQuery(".sedan-car").find("img.ovrly537").css("opacity", 0);
                            //calculate the price
                            //totalPrice -= jQuery(".customFeaturesCheck[data-id=230]").data("price");
                            //jQuery(".customFeaturesCheck[data-id=230]").trigger("change");
                            jQuery(".customFeaturesCheck[data-id=551]").prop("checked", false); //cancels out full vehicle
                            jQuery(".sedan-car").find("img.frontImgs.ovrly551").css("opacity", 0);
                            break;
                        
                    }
                    if(jQuery.trim(jQuery(this).data("id")) == 551) { //in-case of full vehicle selection
                        //calculate the price
                        totalPrice = 0;
                    
                        jQuery(".customFeaturesCheck").not(jQuery(this)).prop("checked", false);
                        jQuery(".sedan-car").find("img.frontImgs").css("opacity", 0);
                        jQuery(".sedan-car").find("img."+jQuery(this).data("class")).css("opacity", 1);
                        jQuery(".rear-side").hide();
                        jQuery(".rearImgs").css("opacity", 0);
                    } else {
                        //calculate the price
                        //totalPrice += jQuery(this).data("price");
                    
                        jQuery(".sedan-car").find("img."+jQuery(this).data("class")).css("opacity", 1);
                        if(jQuery(this).data("type") == "rear") {
                            jQuery(".rear-side").show();
                        }
                        jQuery(".customFeaturesCheck[data-id=551]").prop("checked", false); //cancels out full vehicle
                        jQuery(".sedan-car").find("img.frontImgs.ovrly551").css("opacity", 0);
                    }
                } else {
                    //calculate the price
                    //totalPrice -= jQuery(this).data("price");
                        
                    jQuery(".customFeaturesCheck[data-id=551]").prop("checked", false); //cancels out full vehicle
                    jQuery(".sedan-car").find("img.frontImgs.ovrly551").css("opacity", 0); //hide full vehicle image
                    jQuery(".sedan-car").find("img."+jQuery(this).data("class")).css("opacity", 0);
                    var showRear = false;
                    jQuery(".rearImgs").each(function() {
                        if(jQuery(this).css("opacity") == 1) {
                            showRear = true;
                        }
                    });
                    if(showRear) jQuery(".rear-side").show();
                    else jQuery(".rear-side").hide();
                }
                calculatePrice();
                /*jQuery("#price_symbol").text("");
                var fixed = parseFloat(totalPrice).toFixed(2);
                if(fixed == 0 || fixed == 0.00) {
                    jQuery("#cpackage_price").text("Call for Price");
                } else {
                    jQuery("#cpackage_price").text("$"+ fixed);
                }*/
            });
        });
        </script>
        ';
    return $html;
}
add_shortcode( 'get_custom_packages' , 'getCustomPackagesList' );


function limit_admin_menu_for_dealership() {
    // Check if the current user has the 'dealership' role
    if (current_user_can('dealership')) {
        global $menu, $submenu;
        
        if (current_user_can('dealership')) {
        // Remove unwanted admin menu items
        remove_menu_page('edit.php?post_type=package'); // Dashboard
        remove_menu_page('wpcf7'); // Dashboard
        remove_menu_page('edit.php'); // Posts
        remove_menu_page('tools.php'); // Posts
        remove_menu_page('edit-comments.php'); // Media
        remove_menu_page('edit.php?post_type=page'); // Pages
        remove_menu_page('edit.php?post_type=slider'); // Pages
        
        }
    }
}
add_action('admin_menu', 'limit_admin_menu_for_dealership', 999);


if(isset($_POST['pageAction']) && $_POST['pageAction'] == 'saveCustomParams') { //saveCustomParams
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['ppcst_parameters'] = (isset($_POST['custom_parameters'])) ? $_POST['custom_parameters'] : '';
    $_SESSION['ppcst_price'] = (isset($_POST['total_price'])) ? $_POST['total_price'] : '';
    $_SESSION['pppackage_type'] = (isset($_POST['package_type'])) ? $_POST['package_type'] : '';
    wp_redirect(home_url('book-an-appointment'));
    //echo "<pre>";print_r($_SESSION);die;
}


function get_events($id = null, $current_user = null) {
    if(empty($current_user)) return false;
    global $wpdb;
    $where = '';
    $appointments_tbl = 'wp_ab_appointments';
    $customers_tbl = 'wp_ab_customers';
    $customer_appointments_tbl = 'wp_ab_customer_appointments';
    $services_tbl = 'wp_ab_services';
    if(!empty($id)) $where = "AND a.id=$id";
    $appointments = $wpdb->get_results( $wpdb->prepare(
        "SELECT a.*, s.title, c.full_name, c.phone, c.email, ca.status from $appointments_tbl a LEFT JOIN $services_tbl s ON s.id=a.service_id "
        . "LEFT JOIN $customer_appointments_tbl ca ON ca.appointment_id=a.id "
        . "LEFT JOIN $customers_tbl c ON ca.customer_id=c.id "
        . "WHERE a.user_id = '%d' $where ",
            $current_user
        ), ARRAY_A
    );
    
    //echo "<pre>";print_r($appointments);die;
    $data_events = array();
    $html = '';
    foreach($appointments as $value) {
        if(!empty($id)) {
            //loop through record and create html
            $html = '<table>'
                . '<tr><td class="width35">Service</td><td class="width20p">:</td><td>'.$value['title'].'</td></tr>'
                . '<tr><td class="width35">Timing</td><td class="width20p">:</td><td>'.date('Y-m-d h:i a', strtotime($value['start_date'])).' - '.date('Y-m-d h:i a', strtotime($value['end_date'])).'</td></tr>'
                . '<tr><td class="width35">Booked By</td><td class="width20p">:</td><td>'.$value['full_name'].'</td></tr>'
                . '<tr><td class="width35">Phone</td><td class="width20p">:</td><td>'.$value['phone'].'</td></tr>'
                . '<tr><td class="width35">Email</td><td class="width20p">:</td><td>'.$value['email'].'</td></tr>'
                . '<tr><td class="width35">Status</td><td class="width20p">:</td><td>'.$value['status'].'</td></tr>'
                . '<tr><td class="width35">PO Number</td><td class="width20p">:</td><td>'.$value['po_number'].'</td></tr>'
                . '<tr><td class="width35">Stock Number</td><td class="width20p">:</td><td>'.$value['stock_number'].'</td></tr>'
                . '<tr><td class="width35">Color</td><td class="width20p">:</td><td>'.$value['vehicle_color'].'</td></tr>'
                . '<tr><td class="width35">VIN</td><td class="width20p">:</td><td>'.$value['vin'].'</td></tr>'
                . '<tr><td class="width35">Year, Make and Model</td><td class="width20p">:</td><td>'.$value['year_make_model'].'</td></tr>'
                . '<tr><td class="width35">Trim of Vehicle</td><td class="width20p">:</td><td>'.$value['trim_of_vehicle'].'</td></tr>'
                . '</table>';
        }
        $data_events[] = array(
            "id" => $value['id'],
            "title" => "
                ". 'Service: '.$value['title']."
                ".'Status: '.$value['status'],
            "start" => date('Y-m-d H:i:s', strtotime($value['start_date'])),
            "end"   => date('Y-m-d H:i:s', strtotime($value['end_date'])),
            //"allDay" => false
        );
    }
//echo "<pre>";print_r($data_events);die;
    if(!empty($id)) {
        echo $html;
    } else {
        echo json_encode(array("events" => $data_events));
    }
    exit();
}
if(isset($_REQUEST['start']) && !empty($_REQUEST['start'])) {
    $current_user = get_current_user_id();
    get_events($_REQUEST['id'], $current_user);
}

//Get only price for provided service
function get_price_only($args) {
    global $wpdb;
    if(!isset($args['name']) || empty($args['name'])) {
        return '';
    }
    $cat = (isset($args['cat']) && !empty($args['cat'])) ? $args['cat'] : '';
    $services_tbl = 'wp_ab_services';
    $category_tbl = 'wp_ab_categories';
    if(!empty($cat)) {
        $service = $wpdb->get_row( $wpdb->prepare(
            "SELECT s.* from $services_tbl s WHERE title='%s' AND category_id='%d'",
                $args['name'], $cat
        ));
    } else {
        $service = $wpdb->get_row( $wpdb->prepare(
            "SELECT s.* from $services_tbl s WHERE title='%s'",
                $args['name']
        ));
    }
           
    return (($service->price == 0 || $service->price == 0.00) ? '' : str_replace('.00', '', $service->price));
}
add_shortcode( 'get_price_only' , 'get_price_only' );

//Get only price for provided service
function get_booking_link_only($args) {
    global $wpdb;
    if(!isset($args['name']) || empty($args['name'])) {
        return '';
    }
    $cat = (isset($args['cat']) && !empty($args['cat'])) ? $args['cat'] : '';
    $services_tbl = 'wp_ab_services';
    $category_tbl = 'wp_ab_categories';
    if(!empty($cat)) {
        $service = $wpdb->get_row( $wpdb->prepare(
            "SELECT s.* from $services_tbl s WHERE title='%s' AND category_id='%d'",
                $args['name'], $cat
        ));
        //echo "<pre>";print_r($wpdb);die;
    } else {
        $service = $wpdb->get_row( $wpdb->prepare(
            "SELECT s.* from $services_tbl s WHERE title='%s'",
                $args['name']
        ));
    }
    return '<a class="btn" href="'.home_url().'/book-an-appointment?sid='.$service->id.'&cid='.$service->category_id.'">Book An Appointment</a>';
}
add_shortcode( 'get_booking_link_only' , 'get_booking_link_only' );

/* End custom code for old theme 15-04-2024 */

/**
 * forcev2 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package forcev2
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function forcev2_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on forcev2, use a find and replace
		* to change 'forcev2' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'forcev2', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'forcev2' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'forcev2_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'forcev2_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function forcev2_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'forcev2_content_width', 640 );
}
add_action( 'after_setup_theme', 'forcev2_content_width', 0 );


// Add this code to your theme's functions.php file or a custom plugin
function custom_woocommerce_template_loop_add_to_cart() {
    global $product;

    // Display the add to cart button
    echo '<a href="#" data-product_id="' . $product->get_id() . '" class="button add_to_cart_button custom-add-to-cart-button">Add to Cart</a>';
}
add_action('woocommerce_after_shop_loop_item', 'custom_woocommerce_template_loop_add_to_cart', 10);

// Add AJAX handler to increment cart count
add_action('wp_ajax_custom_add_to_cart', 'custom_add_to_cart_callback');
add_action('wp_ajax_nopriv_custom_add_to_cart', 'custom_add_to_cart_callback');

function custom_add_to_cart_callback() {
    // Get the product ID from the AJAX request
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

    if ($product_id > 0) {
        // Add the product to the cart
        WC()->cart->add_to_cart($product_id);

        // Get the cart count
        $cart_count = WC()->cart->get_cart_contents_count();

        // Return the cart count as JSON response
        wp_send_json_success($cart_count);
    } else {
        // If product ID is invalid, return error response
        wp_send_json_error('Invalid product ID');
    }

    // Always exit to avoid further execution
    exit;
}


/**
 * Custom walker class.
 */
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * Adds classes to the unordered list sub-menus.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		// Depth-dependent classes.
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'nav navbar-nav',
			( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
			( $display_depth >=2 ? 'sub-sub-menu' : '' ),
			'menu-depth-' . $display_depth
		);
		$class_names = implode( ' ', $classes );

		// Build HTML for output.
		$output .= "\n" . $indent . '<div class="dropdown-menu"><ul class="' . $class_names . '">' . "\n";
	}

	/**
	 * Start the element output.
	 *
	 * Adds main/sub-classes to the list items and links.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

		// Depth-dependent classes.
		$depth_classes = array(
			( $depth == 0 ? 'nav-item navbar-dropdown' : 'sub-menu-item' ),
			( $depth >=2 ? 'sub-sub-menu-item' : '' ),
			( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
			'menu-item-depth-' . $depth
		);
		$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

		// Passed classes.
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

		// Build HTML.
		$output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

		// Link attributes.
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'nav-link' ) . '"';

		$custom_cart = '';
		if($item->title=='Cart'){
			$item_output .= '<div class="cart-box" style="width:55px;"><a href="'.wc_get_cart_url().'"><img src="'.get_template_directory_uri().'/assets/img/cartIcon.svg" alt=""><span class="cart-count" style="
			position: absolute;
			left: 37px;
			color: red;
			font-weight: bold;
			background: white;
			border-radius: 50px;
			width: 25px;
			text-align: center;
		">'.WC()->cart->get_cart_contents_count().'</span></a></div>';
		}
		else if($item->title=='Dealer Login')
        {
            if (function_exists('is_user_logged_in') && !is_user_logged_in()) 
            {
                $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
                $args->before,
                $attributes,
                $args->link_before,
                apply_filters( 'the_title', $item->title, $item->ID ),
                $args->link_after,
                $args->after
            );
            }
            else
            {
                $logout_url = wp_logout_url(home_url('/')); // Change to desired logout URL
                $item_output .= "<a href='$logout_url'>Logout</a>";
            }
        }
        else
		{
			// Build HTML output and pass through the proper filter.
			$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args->before,
			$attributes,
			$args->link_before,
			apply_filters( 'the_title', $item->title, $item->ID ),
			$args->link_after,
			$args->after
		);
		}

		
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}


// Define custom shortcode to display all WooCommerce products
function custom_show_all_products($atts) {
    // Query arguments
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 15,
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat', // Assuming 'category' is the taxonomy of your product
				'field' => 'id',
				'terms' => array( '24' ), // Replace 'category_id_to_exclude' with the ID of the category you want to exclude
				'operator' => 'NOT IN'
			)
		)
	);

    // Query WooCommerce products
    $products = new WP_Query($args);
//print_r($products);
    // Check if any products found
    if ($products->have_posts()) {
        // Start buffer to capture output
        ob_start();

        // Loop through products and display them
        while ($products->have_posts()) {
            $products->the_post();
			global $product;

			// Get product data
			$product_id = $product->get_id();
			$product_title = $product->get_name();
			$product_price = $product->get_price_html();
			$product_permalink = get_permalink($product_id);
			$product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'full');
            //wc_get_template_part('content', 'product');
			?>
			<div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
				<div class="porduct_single">
					<div class="product_single_img">
						<a href="<?php echo esc_url($product_permalink);?>"><img src="<?php echo esc_url($product_image[0]);?>" alt="<?php echo esc_url($product_title);?>"></a>
					</div>

					<h2><a href="<?php echo esc_url($product_permalink);?>"><?php echo esc_html($product_title);?></a></h2>
					<p><?php echo $product_price;?></p>
					<?php custom_woocommerce_template_loop_add_to_cart(); ?>
				</div>
			</div>
			<?php
        }

        // Reset post data
        wp_reset_postdata();

        // Return buffered content
        return ob_get_clean();
    } else {
        return '<p>No products found</p>';
    }
}
add_shortcode('show_all_products', 'custom_show_all_products');


add_action( 'init', 'move_related_products_before_tabs' );
function move_related_products_before_tabs( ) {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
    add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 5 );
}
// 1. Register the setting
add_action('admin_init', function() {
  register_setting('general', 'company_phone');

  // 2. Add the section/field to the General Settings page
  add_settings_field(
    'company_phone',
    'Company Phone Number',
    'render_phone_field_html',
    'general'
  );
});

// 3. The HTML for the input field
function render_phone_field_html() {
  $value = get_option('company_phone', '');
  echo '<input type="text" name="company_phone" value="' . esc_attr($value) . '" class="regular-text" placeholder="(555) 555-5555" />';
}


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function forcev2_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'forcev2' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'forcev2' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'forcev2_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function forcev2_scripts() {
	wp_enqueue_style( 'forcev2-style', get_stylesheet_uri(), array(), filemtime( get_template_directory() . '/style.css' ) );
	wp_style_add_data( 'forcev2-style', 'rtl', 'replace' );

	wp_enqueue_script( 'forcev2-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

  wp_enqueue_style(
    'globe-main-style',
    get_template_directory_uri() . '/assets/css/components/main.css',
    array(),
    filemtime( get_template_directory() . '/assets/css/components/main.css' )
  );

  wp_enqueue_style(
    'globe-buttons-style',
    get_template_directory_uri() . '/assets/css/layouts/buttons.css',
    array(),
    filemtime( get_template_directory() . '/assets/css/layouts/buttons.css' )
  );

  wp_enqueue_style(
    'contact-section-style',
    get_template_directory_uri() . '/assets/css/components/contact-section.css',
    array(),
    filemtime( get_template_directory() . '/assets/css/components/contact-section.css' )
  );

    wp_enqueue_style(
            'ppf-packages-section-style',
            get_template_directory_uri() . '/assets/css/components/ppf-packages-section.css',
            array(),
            filemtime( get_template_directory() . '/assets/css/components/ppf-packages-section.css' )
    );

    wp_enqueue_style(
            'services-section-style',
            get_template_directory_uri() . '/assets/css/components/services-section.css',
            array(),
            filemtime( get_template_directory() . '/assets/css/components/services-section.css' )
    );

    wp_enqueue_style(
            'compare-services-section-style',
            get_template_directory_uri() . '/assets/css/components/compare-services-section.css',
            array(),
            filemtime( get_template_directory() . '/assets/css/components/compare-services-section.css' )
    );

  wp_enqueue_style(
    'text-and-tiles-section-style',
    get_template_directory_uri() . '/assets/css/components/text-and-tiles-section.css',
    array(),
    filemtime( get_template_directory() . '/assets/css/components/text-and-tiles-section.css' )
  );

  wp_enqueue_style(
    'statistics-section-style',
    get_template_directory_uri() . '/assets/css/components/statistics-section.css',
    array(),
    filemtime( get_template_directory() . '/assets/css/components/statistics-section.css' )
  );

  wp_enqueue_style(
    'value-proposition-section-style',
    get_template_directory_uri() . '/assets/css/components/value-proposition-section.css',
    array(),
    filemtime( get_template_directory() . '/assets/css/components/value-proposition-section.css' )
  );

  wp_enqueue_style(
    'coverage-cta-section-style',
    get_template_directory_uri() . '/assets/css/components/coverage-cta-section.css',
    array(),
    filemtime( get_template_directory() . '/assets/css/components/coverage-cta-section.css' )
  );

  wp_enqueue_style(
    'other-services-section-style',
    get_template_directory_uri() . '/assets/css/components/other-services-section.css',
    array(),
    filemtime( get_template_directory() . '/assets/css/components/other-services-section.css' )
  );

  wp_enqueue_style(
    'process-section-style',
    get_template_directory_uri() . '/assets/css/components/process-section.css',
    array(),
    filemtime( get_template_directory() . '/assets/css/components/process-section.css' )
  );

  wp_enqueue_style(
    'faq-section-style',
    get_template_directory_uri() . '/assets/css/components/faq.css',
    array(),
    filemtime( get_template_directory() . '/assets/css/components/faq.css' )
  );



  wp_enqueue_style(
    'hero-banner-style',
    get_template_directory_uri() . '/assets/css/components/hero-banner.css',
    array(),
    filemtime( get_template_directory() . '/assets/css/components/hero-banner.css' )
  );


  wp_enqueue_script(
    'main-script',
    get_template_directory_uri() . '/assets/js/main.js',
    array(),
    filemtime( get_template_directory() . '/assets/js/main.js' ),
    true
  );

}
add_action( 'wp_enqueue_scripts', 'forcev2_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

function wptp_add_categories_to_attachments() { register_taxonomy_for_object_type( 'category', 'attachment' ); } add_action( 'init' , 'wptp_add_categories_to_attachments' );


/**
 * Disable Yoast SEO Schema Graph output
 */
add_filter('wpseo_json_ld_output', '__return_false');



//Numbers Only" Validation for phone number in contact form
add_filter('wpcf7_validate_tel*', 'custom_phone_validation_filter', 20, 2);
add_filter('wpcf7_validate_tel', 'custom_phone_validation_filter', 20, 2);

function custom_phone_validation_filter($result, $tag) {
  $name = $tag->name;

  if ($name == 'phone') {
    $value = isset($_POST[$name]) ? trim($_POST[$name]) : '';

    // 1. Strip out the mask characters: ( ) - and spaces
    $clean_value = preg_replace('/[()\- \s]/', '', $value);

    // 2. Check if the remaining string is numeric and exactly 10 digits
    if ($value != "" && !preg_match('/^[0-9]{10}$/', $clean_value)) {
      $result->invalidate($tag, "Please enter a valid 10-digit phone number.");
    }
  }

  return $result;
}

add_action('wp_footer', function () {
  ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          const phoneInput = document.querySelector('input[name="phone"]');

          if (phoneInput) {
              // Apply the mask: (999) 999-9999
              Inputmask({"mask": "(999) 999-9999"}).mask(phoneInput);

              // Optional: Show a message if they try to type letters
              phoneInput.addEventListener('keydown', function(e) {
                  // Allow backspace, tab, etc.
                  if (e.key.length === 1 && !/[0-9]/.test(e.key)) {
                      console.log("Digits only allowed");
                      // You could trigger a small toast/alert here if desired
                  }
              });
          }
      });
  </script>
  <?php
}, 100);