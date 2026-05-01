<?php
/**
 * GrowME functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package GrowME
 */
// functions.php
require_once get_stylesheet_directory() . '/inc/projects/bootstrap.php';

if ( ! function_exists( 'growme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function growme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on GrowME, use a find and replace
		 * to change 'growme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'growme', get_template_directory() . '/languages' );

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
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'growme' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'growme_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'growme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function growme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'growme_content_width', 640 );
}
add_action( 'after_setup_theme', 'growme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function growme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'growme' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'growme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'growme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function growme_scripts() {
	wp_enqueue_style( 'growme-style', get_stylesheet_uri() );

	wp_enqueue_script( 'growme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'growme-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'growme_scripts' );

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
function woocommerce_after_shop_loop_item_title_short_description() {
	global $product;
	if ( ! $product->get_short_description() ) return;
	?>
	<div itemprop="description">
		<?php echo apply_filters( 'woocommerce_short_description', $product->get_short_description() ) ?>
	</div>
	<?php
}
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_after_shop_loop_item_title_short_description', 11);

function the_breadcrumb() {
	if (!is_home()) {
		echo '<a href="';
		echo get_option('home');
		echo '">';
		bloginfo('name');
		echo "</a>  ";
		if (is_category() || is_single()) {
			the_category('title_li=');
			if (is_single()) {
				echo "  ";
				the_title();
			}
		} elseif (is_page()) {
			echo the_title();
		}
	}
}

add_action('send_headers', function () {
    if (is_paged()) {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (preg_match('#^/resources/\\d+/?$#', $uri) || preg_match('#^/building-products/news/\\d+/?$#', $uri)) {
            header('X-Robots-Tag: noindex, follow', true);
        }
    }
});


add_filter( 'gform_required_legend', '__return_empty_string' );



function add_gform_tracking_script() {
?>
<!-- Gravity Form Submission Tracking for Google Ads -->
<script>
  jQuery(document).ready(function () {
    // Track seen forms to avoid duplicate pushes
    window._gfSeen = window._gfSeen || {};

    jQuery(document).on('gform_confirmation_loaded', function (event, formId) {
      // If already tracked, skip
      if (window._gfSeen[formId]) return;

      // Mark as tracked
      window._gfSeen[formId] = true;

      // Push to dataLayer
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({
        event: 'gform_confirmation_loaded',
        formId: formId,
      });

		let redirection = '/thank-you/'
		
		
		if(formId == 15|| formId == 11){
			redirection =  '/request-a-quote/thank-you/'
		}
		
		setTimeout(function () {
			window.location = redirection;
		}, 2500);
		
		
		
	
    });
  });
</script>
<?php
}
add_action('wp_footer', 'add_gform_tracking_script', 100);



/**
 * Seeder for Building Filters
 * Inserts Categories, Building Types, Sizes, Features, and Colour Library
 */
add_action('init', function() {
    if (get_option('building_filters_seeded')) return;

    /* -------------------------------
     * Categories
     * ------------------------------- */
    $categories = [
            'Lifestyle & Hobby',
            'Agricultural',
            'Equestrian',
            'Commercial (no BT)',
            'Residential / Homes'
    ];

    $category_ids = [];
    foreach ($categories as $cat) {
        $term = term_exists($cat, 'building_category');
        if (!$term) {
            $term = wp_insert_term($cat, 'building_category');
        }
        if (!is_wp_error($term)) {
            $category_ids[$cat] = is_array($term) ? $term['term_id'] : $term;
        }
    }

    /* -------------------------------
     * Building Types + Linked Categories
     * ------------------------------- */
    $building_types = [
            'Barn'              => ['Lifestyle & Hobby', 'Agricultural', 'Equestrian'],
            'Hangar'            => ['Agricultural'],
            'Hay Shed'          => ['Agricultural', 'Equestrian'],
            'Machine Shed'      => ['Agricultural'],
            'Riding Arena'      => ['Equestrian'],
            'Shop'              => ['Lifestyle & Hobby', 'Agricultural'],
            'Storage'           => ['Lifestyle & Hobby', 'Agricultural'],
            'Livestock Shelter' => ['Agricultural', 'Equestrian'],
            'Shouse & Barndo'   => ['Residential / Homes'],
            'Ranch Homes'       => ['Residential / Homes'],
            'Suites'            => ['Residential / Homes']
    ];

    foreach ($building_types as $type => $cats) {
        $term = term_exists($type, 'building_type');
        if (!$term) {
            $term = wp_insert_term($type, 'building_type');
        }
        if (!is_wp_error($term)) {
            $type_id = is_array($term) ? $term['term_id'] : $term;

            // Link to related categories
            $related_ids = [];
            foreach ($cats as $c) {
                if (isset($category_ids[$c])) {
                    $related_ids[] = $category_ids[$c];
                }
            }
            update_term_meta($type_id, 'related_categories', $related_ids);
        }
    }

    /* -------------------------------
     * Sizes
     * ------------------------------- */
    $sizes = [
            'Less than 2,500 sq ft',
            '2,500 - 6,000 sq ft',
            'Greater than 6,000 sq ft'
    ];
    foreach ($sizes as $size) {
        if (!term_exists($size, 'building_size')) {
            wp_insert_term($size, 'building_size');
        }
    }

    /* -------------------------------
     * Features
     * ------------------------------- */
    $features = [
            'Overhead Door',
            'Sliding Door',
            'Bi-fold Door',
            'Wainscoting',
            'Cupolas',
            'Timber accents',
            'Lean-to, Open',
            'Lean-to, Closed',
            'Porch',
            'Soffit',
            'Crows Peak'
    ];
    foreach ($features as $feat) {
        if (!term_exists($feat, 'building_feature')) {
            wp_insert_term($feat, 'building_feature');
        }
    }

    /* -------------------------------
     * Colour Library
     * ------------------------------- */
    $colours = [
            'White' => ['Bright White', 'White White', 'Bone White', 'Antique Linen'],
            'Grey'  => ['Stone Grey', 'Regent Grey', 'Gunmetal', 'Charcoal'],
            'Black' => ['Black', 'Iron Ore'],
            'Brown' => ['Tan', 'Wicker', 'Coffee Brown', 'Dark Brown', 'Sable', 'Buckskin'],
            'Red'   => ['Bright Red', 'Tile Red', 'Dark Red'],
            'Blue'  => ['Slate Blue', 'Heron Blue', 'Royal Blue', 'Bluebird'],
            'Green' => ['Melchers Green', 'Dark Sage'],
            'Galvalume' => [],
            'Premium Colours' => [
                    'Saddle','Frontier','Acorn','Gunstock','Espresso','Durango','Homestead','Autumn',
                    'Wagon Wood','Ponderosa','Canyon','Barnboard','Light Zinc','Dark Zinc','Aged Copper',
                    'Rustic Red','Light Pine','Knotty Pine','Heritage','Ashwood','Concrete','Corten',
                    'Kona Brown','Graphite','Burnished Slate','Onyx Black','Dark Brown','Dark Graphite'
            ]
    ];

    foreach ($colours as $parent_name => $children) {
        $parent = term_exists($parent_name, 'colour_library');
        if (!$parent) {
            $parent = wp_insert_term($parent_name, 'colour_library');
        }
        $parent_id = is_array($parent) ? $parent['term_id'] : $parent;

        foreach ($children as $child) {
            if (!term_exists($child, 'colour_library', $parent_id)) {
                wp_insert_term($child, 'colour_library', ['parent' => $parent_id]);
            }
        }
    }

    /* -------------------------------
     * Done
     * ------------------------------- */
    update_option('building_filters_seeded', 1);
});


