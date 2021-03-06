<?php
/**
 * asapkids functions and definitions
 *
 * @package asapkids
 */

if ( ! function_exists( 'asapkids_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function asapkids_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on asapkids, use a find and replace
	 * to change 'asapkids' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'asapkids', get_template_directory() . '/languages' );

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
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'asapkids' ),
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

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'asapkids_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // asapkids_setup
add_action( 'after_setup_theme', 'asapkids_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function asapkids_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'asapkids_content_width', 640 );
}
add_action( 'after_setup_theme', 'asapkids_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function asapkids_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'asapkids' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'asapkids_widgets_init' );

//Search redirect that fixed issue where searches performed on pages other than home or search displayed 404
function change_search_url_rewrite() {
    if ( !empty( $_GET['s'] ) && !is_search()) {
        wp_redirect( home_url('/search/') . urlencode(get_query_var('s')) );
        exit();
    }   
}
add_action( 'template_redirect', 'change_search_url_rewrite' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/** RDK Custom functions stuff **/
require_once('custom-post-types.php');

// Hide admin toolbar from front-end registrants and redirect front-end registrants that are trying to access the dashboard to their profile page
if ( is_user_logged_in() && !current_user_can('manage_options')) {
	add_filter('show_admin_bar', '__return_false');
	//checks if the page being requested is an administrative page, but needs to allow ajax calls
	if( is_admin() && (!strpos($_SERVER["REQUEST_URI"], 'wp-admin/admin-ajax.php') && !strpos($_SERVER["QUERY_STRING"], '&student=student')) ) {
		wp_redirect( home_url( '/sign-up/' ) );
        exit();
	}
}

// Display appropriate wordpress menu based on whether a user is logged in or not
function my_wp_nav_menu_args( $args ) {
	if( 'primary' == $args['theme_location'] ) {
		if( is_user_logged_in() && !current_user_can('manage_options')) {
		    $args['menu'] = 'logged-in';
		} else {
		    $args['menu'] = 'logged-out';
		}
	} else {
		$args['menu'] = 'asapkids-footer-menu';
	}
    return $args;
}
add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );

// Attaching custom menu options to logged in user's menu
/*add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {
    if (is_user_logged_in() && $args->theme_location == 'primary') {
    	global $current_user;
  		get_currentuserinfo();
  		$args = array('post_type' => 'cpt_student', 'post_status' => 'private', 'author' => $current_user->ID);
  		$students = get_posts($args);
  		$student_items = '';
  		
  		if($students) {
  			foreach($students as $id) {
  				$student_name = get_field('student_name', $id->ID);
  				$url = home_url('/') .'add-student/?student='. $id->ID;
  				if($student_name) {
  					$student_items.='<li><a href="'.$url.'">'.$student_name.'</a></li>';
  				}
  			}
  		}
        $items = '<li class="asapkids-profile-menu">'. $current_user->user_firstname . ' '. $current_user->user_lastname .'<ul>'. $student_items . $items .'<li><a href="'. wp_logout_url( home_url( '/' ) . 'sign-in') .'">Log Out</a></li></li></ul></ul>';
    }
    return $items;
}*/

// Attaching custom menu options to logged in user's menu
add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {
    if (is_user_logged_in() && $args->theme_location == 'primary') {
    	global $current_user;
  		get_currentuserinfo();
  		
        $items = '<li class="asapkids-profile-menu">'. $current_user->user_firstname . ' '. $current_user->user_lastname .'<ul>'. $items .'<li><a href="'. wp_logout_url( home_url( '/' ) . 'sign-in') .'">Log Out</a></li></li></ul></ul>';
    }
    return $items;
}

/*
*  Elijah's Functions
*/

// Allows query vars to be added, removed, or changed prior to executing the query.
function asapkids_query_vars( $qvars ) {
    $qvars[] = 'ai'; //associated_interests
    $qvars[] = 'dow'; // days of the week
    $qvars[] = 'st'; // student's id
    $qvars[] = 'age'; // min age
    $qvars[] = 'maa'; // max age
    $qvars[] = 'sd'; // start end
    $qvars[] = 'ed'; // end date
    $qvars[] = 'org'; // organization
    $qvars[] = 'addy'; // user's address
    $qvars[] = 'sr'; // sort results
    $qvars[] = 'pr'; // price
    $qvars[] = 'ex'; // experience
    $qvars[] = 'di'; // distance in miles
    return $qvars;
}
add_filter( 'query_vars', 'asapkids_query_vars' , 10, 1 );

// Location Class
class Location {
    function __construct() {
        $this->check_prog_location();
    }
    
    function check_prog_location() {
        global $location_titles, $location_post_ids;
        if( get_field('prog_location') ) {
            // create object and return it
            $this->my_location = get_field('prog_location');
            $this->has_loc = " pinned";
        } else {
            $this->has_loc = "";
            $this->my_location = "";
        }
    }
}

function get_user_address() {
    if(is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $user_address = get_user_meta($current_user->ID, 'address', true).', '.get_user_meta($current_user->ID, 'city', true).', '.get_user_meta($current_user->ID, 'state', true).' '.get_user_meta($current_user->ID, 'zip', true);
    } else {
        $user_address = 'Milwaukee, WI';
    }
    return $user_address;
}

// Enqueue Scripts
function asapkids_scripts() {
	wp_enqueue_script( 'asapkids-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
    wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places', array(), false, true);    
    wp_enqueue_script('slick-slider', get_stylesheet_directory_uri() . '/js/slick.js', array(), '20130115', true );
    wp_enqueue_script( 'asapkids-jquery-functions', get_template_directory_uri() . '/js/functions.js', array(), '20130115', true );
    wp_enqueue_script('marker-with-label', get_stylesheet_directory_uri() . '/js/markerwithlabel_packed.js', array(), false, true);
    wp_enqueue_script('jquery-ui-datepicker');	 
    wp_enqueue_style('plugin_name-admin-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/smoothness/jquery-ui.css', false, false, false );
    wp_localize_script( 'asapkids-jquery-functions', 'ak_localize', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'distance' => ( get_query_var( 'di', 9999999 ) != 0 ? get_query_var( 'di' ) : 9999999 ), // distance
        'user_address' => get_user_address(),
        'student_id' => get_query_var( 'st' ), //EL added 8/21
    ) );
    //wp_localize_script('asapkids-jquery-functions', 'get_user_location', array('address' => $user_address));
    wp_localize_script( 'asapkids-jquery-functions', 'filter_options', array('ajax_url' => admin_url( 'admin-ajax.php' ),) );
    
	//mmenu
	wp_enqueue_script('mmenu', get_stylesheet_directory_uri() . '/mmenu/jquery.mmenu.min.all.js', array( 'jquery'), false, true);
	wp_enqueue_style( 'asapkids-style', get_stylesheet_uri() );
	wp_enqueue_style( 'mmenu-css', get_stylesheet_directory_uri() . '/mmenu/jquery.mmenu.all.css', false, false, false );
	wp_enqueue_style( 'mmenu-iconbar', get_stylesheet_directory_uri() . '/mmenu/jquery.mmenu.iconbar.css', false, false, false );
	wp_enqueue_style( 'mmenu-widescreen', get_stylesheet_directory_uri() . '/mmenu/jquery.mmenu.widescreen.css', false, false, 'all and (min-width: 900px)' ); 
}
add_action('wp_enqueue_scripts', 'asapkids_scripts');

//AJAXified search filtering
add_action("wp_ajax_filter_results", "filter_results");
add_action("wp_ajax_nopriv_filter_results", "filter_results");
function filter_results() {
    
	$keyword = $_POST['s'];
	
    if(is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        // get students for current user and check against query
        $args = array('post_type' => 'cpt_student', 'post_status' => 'private', 'author' => $user_id, 'posts_per_page' => -1 );                   
        $students = get_posts($args);
        if(!empty($students)) {               
            $st_ids = array();
            foreach ( $students as $id ) {
                array_push( $st_ids, $id->ID );
            }
        }
    }	
	
    if ( isset( $_POST['st'] ) && $_POST['st'] != "" && is_user_logged_in() && in_array( $_POST['st'] , $st_ids ) ) {
        $st = $_POST['st'];
        $st_name = get_field( 'student_name', $st );
        $distance = get_field( 'student_distance', $st);
 		$experience = ( !empty( get_field( 'student_experience', $st ) ) ? get_field( 'student_experience', $st ) : array() );
        $interests = ( !empty( get_field( 'student_interests', $st ) ) ? get_field( 'student_interests', $st ) : array() );
        $daysofweek = ( !empty( get_field( 'student_days_desired', $st ) ) ? get_field( 'student_days_desired', $st ) : array() );
        $age = asapkids_get_student_age( $st );
        $address = get_user_address();
        // $price = $_POST['pr'];
    } else {
        $st_name = check_query_vars();
        $st = "";
        $distance = ( $_POST['di'] != 0 ? $_POST['di'] : 9999999 );
        $experience = ( !empty($_POST['ex']) ? $_POST['ex'] : array() );
        $interests = ( !empty($_POST['ai']) ? $_POST['ai'] : array() );
        $daysofweek = ( !empty($_POST['dow']) ? $_POST['dow'] : array() );
        $start_date = $_POST['sd'];
        $age  = $_POST['age'];
        $address= $_POST['addy'];
        $price = $_POST['pr'];
    }
    
    global $wpdb;
    // If you use a custom search form
    // $keyword = sanitize_text_field( $_POST['keyword'] );
    // If you use default WordPress search form
    //$keyword = get_search_query();
    $keyword = '%' . $wpdb->esc_like( $keyword ) . '%'; // Thanks Manny Fleurmond
    // Search in all custom fields
    
    $post_ids_meta = $wpdb->get_col( $wpdb->prepare( "
        SELECT DISTINCT post_id FROM {$wpdb->postmeta}
        WHERE meta_value LIKE '%s'
    ", $keyword ) );
    // Search in post_title and post_content
    $post_ids_post = $wpdb->get_col( $wpdb->prepare( "
        SELECT DISTINCT ID FROM {$wpdb->posts}
        WHERE post_title LIKE '%s'
        OR post_content LIKE '%s'
    ", $keyword, $keyword ) );
    $post_ids = array_merge( $post_ids_meta, $post_ids_post );   
    
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'cpt_program',
        'post__in' => $post_ids,
        'paged' => $paged,
        //'s' => $s,
        'meta_query' => array(
            'featured' => array(
                'key' => 'prog_featured', // {$wpdb->postmeta}
            ),
            'start_date' => array(
                'key' => 'prog_date_start',
            ),
            'ongoing' => array(
                'key' => 'prog_ongoing',
            ),
            array(
                'relation' => 'OR',
                array (
                    'key' => 'prog_date_start',
                    'value' => date('Ymd'),
                    'compare' => '>=',
                ),
                array (
                    'key' => 'prog_date_expires',
                    'value' => date('Ymd'),
                    'compare' => '>=',
                ),                
                array (
                    'key' => 'prog_ongoing',
                    'value' => true,
                    'compare' => '=',
                ),
            ),
        ),
    );

    if (!empty( $age )) {
        array_push($args['meta_query'],  array (
                'key' => 'prog_age_min',
                'value' => $age,
                'compare' => '<=',
                'type' => 'NUMERIC'
            ),
            array (
                'key' => 'prog_age_max',
                'value' => $age,
                'compare' => '>=',
                'type' => 'NUMERIC'
            ));
    }

    if (!empty( $interests )) {
        $i = 0;
        $ints['relation'] = 'OR';
        foreach ($interests as $interest) {
            $ints[$i] = array(
                'key' => 'associated_interests',
                'value' => '"' . $interest . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $ints);
    }

    if (!empty( $daysofweek )) {
        $i = 0;
        $days['relation'] = 'OR';
        foreach ( $daysofweek as $day ) {
            $days[$i] = array(
                'key' => 'prog_days_offered',
                'value' => '"' . $day . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $days );
    }

    if (!empty( $price )) {
        array_push($args['meta_query'], array (
            'key' => 'prog_cost',
            'value' => $price,
            'compare' => '<=',
            'type' => 'NUMERIC'
        ));
    }

    if ( !empty( $experience ) && !in_array( "Any", $experience ) ) {
        $i = 0;
        $exp_levels['relation'] = 'OR';
        foreach ( $experience as $exp_level ) {
            $exp_levels[$i] = array(
                'key' => 'prog_activity_level',
                'value' => '"' . $exp_level . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $exp_levels );
    }
    
    add_filter( 'posts_orderby', $func = function ( $orderby, $query ) {
        $start_date = date('Ymd');
        global $wpdb;
        $orderby = $wpdb->prepare(
            "
            CASE
                WHEN {$wpdb->postmeta}.meta_value THEN CONCAT('A', mt1.meta_value)
                WHEN mt1.meta_value >= %d THEN CONCAT('B', mt1.meta_value)
                WHEN mt2.meta_value AND mt1.meta_value THEN CONCAT('C', mt1.meta_value)
                WHEN mt2.meta_value THEN 'D'
                ELSE 'Es'
            END ASC
            "
            , $start_date
        );
        return $orderby;
    }, 10, 2 );
    $query = new WP_Query( $args );	

	include( 'inc/search-results.php' );
    
    $filter_array = array(
        'name' => $st_name,
        'st' => $st,
        'age' => $age,
        'di' => $distance,
        'ex' => $experience,
        'dow' => $daysofweek,
        'ai' => $interests,
        'addy' => $address,
    );
    echo '<div id="json_data">' . json_encode($filter_array) . '</div>';
    die();
}

function check_query_vars() {
    $qvar_array = array( 'ex', 'ai', 'dow', 'sd', 'age', 'pr' );
    foreach ( $qvar_array as $qvar_value ) {
        if ( !isset( $_POST[$qvar_value] ) ) {
            continue;
        }
        if ( !empty( $_POST[$qvar_value] || $_POST['di'] != 9999999 ) ) {
            $st_name = "Custom Search";
            break;
        } else {
            $st_name = "Student";
        }
    }
    return $st_name;
}

/**
 * ASAPK!DS Custom Registration/Login/Password Reset/Add Student
 */
// user registration login form
function asapkids_registration_form() {
	// check to make sure user registration is enabled
	$registration_enabled = get_option('users_can_register');

	// only show the registration form if allowed
	if($registration_enabled) {
		$output = asapkids_registration_form_fields();
	} else {
		$output = __('User registration is not enabled');
	}
	return $output;
}
add_shortcode('register_form', 'asapkids_registration_form');

// user login form
function asapkids_login_form() {
 
	if(!is_user_logged_in()) {
		$output = asapkids_login_form_fields();
	} else {
        $output = '<a href="' . home_url( "/manage-students" ) . '">Manage Students</a>';
    }
	return $output;
}
add_shortcode('login_form', 'asapkids_login_form');

// password reset form
function asapkids_pwreset_form() {
 
	$output = asapkids_pwreset_form_fields();
	
	return $output;
}
add_shortcode('pwreset_form', 'asapkids_pwreset_form');

function my_pre_save_post( $post_id ) {

    // check if this is to be a new post
    if( $post_id == 'new' ) {
	    // Create a new post
	    $post = array(
	        'post_type'  => 'cpt_student',
	        'post_title' => $_POST['fields']['field_55a96d32dbaf5'],
	        'post_status' => 'private'
	    );
	
	    // insert the post
	    $post_id = wp_insert_post( $post );
	}
	
    // return the new ID
    return $post_id;
}
add_filter('acf/pre_save_post' , 'my_pre_save_post' );

// registration form fields
function asapkids_registration_form_fields() {
 	
 	if(is_user_logged_in()) {
 		$current_user = wp_get_current_user();
 	} else {
 		$current_user = '';
 	}
 	
 	if($current_user) {
 		$user_id      = $current_user->ID;
 		$user_email    = $current_user->user_login;
 		$user_first   = $current_user->first_name;
 		$user_last    = $current_user->last_name;
		$user_address = get_user_meta($user_id, 'address', true);
		$user_city    = get_user_meta($user_id, 'city', true);
		$user_state   = get_user_meta($user_id, 'state', true);
		$user_zip     = get_user_meta($user_id, 'zip', true);
		$user_phone   = get_user_meta($user_id, 'phone', true);	
 	}
 	
	ob_start(); ?>	
 
		<?php 
		// show any error messages after form submission
		asapkids_show_error_messages(); ?>
 
		<form id="asapkids_registration_form" class="asapkids_form" action="" method="POST">
			<fieldset>

				<?php if($current_user) { ?>
					<p>
						<label for="asapkids_user_login"><?php _e('Email'); ?></label>
						<span class="grey"><?php echo $user_email; ?></span>
						<input type="hidden" name="asapkids_user_login" value="<?php echo $user_email; ?>">
					</p>
				<?php } else { ?>
					<p>
						<label for="asapkids_user_login"><?php _e('Email'); ?> <span class="required">*</span></label>
						<input name="asapkids_user_login" id="asapkids_user_login" class="required" type="email"<?php if($current_user) { echo ' value="'.$user_email.'"';} ?>/>
					</p>
				<?php } ?>

				<p>
					<label for="asapkids_user_first"><?php _e('First Name'); ?> <span class="required">*</span></label>
					<input name="asapkids_user_first" id="asapkids_user_first" type="text"<?php if($current_user) { echo ' value="'.$user_first.'"';} ?>/>
				</p>
				<p>
					<label for="asapkids_user_last"><?php _e('Last Name'); ?> <span class="required">*</span></label>
					<input name="asapkids_user_last" id="asapkids_user_last" type="text"<?php if($current_user) { echo ' value="'.$user_last.'"';} ?>/>
				</p>
				<p>
					<label for="asapkids_user_address"><?php _e('Address'); ?> <span class="required">*</span></label>
					<input name="asapkids_user_address" id="asapkids_user_address" type="text"<?php if($current_user) { echo ' value="'.$user_address.'"';} ?>/>
				</p>	
				<p>
					<label for="asapkids_user_city"><?php _e('City'); ?> <span class="required">*</span></label>
					<input name="asapkids_user_city" id="asapkids_user_city" type="text"<?php if($current_user) { echo ' value="'.$user_city.'"';} ?>/>
				</p>				
				<p>
					<label for="asapkids_user_state"><?php _e('State'); ?> <span class="required">*</span></label>
					<select name="asapkids_user_state" id="asapkids_user_state" size="1">
						<option value="AL"<?php if($current_user && $user_state == 'AL') { echo ' selected'; } ?>>AL</option>
						<option value="AK"<?php if($current_user && $user_state == 'AK') { echo ' selected'; } ?>>AK</option>
						<option value="AZ"<?php if($current_user && $user_state == 'AZ') { echo ' selected'; } ?>>AZ</option>
						<option value="AR"<?php if($current_user && $user_state == 'AR') { echo ' selected'; } ?>>AR</option>
						<option value="CA"<?php if($current_user && $user_state == 'CA') { echo ' selected'; } ?>>CA</option>
						<option value="CO"<?php if($current_user && $user_state == 'CO') { echo ' selected'; } ?>>CO</option>
						<option value="CT"<?php if($current_user && $user_state == 'CT') { echo ' selected'; } ?>>CT</option>
						<option value="DE"<?php if($current_user && $user_state == 'DE') { echo ' selected'; } ?>>DE</option>
						<option value="FL"<?php if($current_user && $user_state == 'FL') { echo ' selected'; } ?>>FL</option>
						<option value="GA"<?php if($current_user && $user_state == 'GA') { echo ' selected'; } ?>>GA</option>
						<option value="HI"<?php if($current_user && $user_state == 'HI') { echo ' selected'; } ?>>HI</option>
						<option value="ID"<?php if($current_user && $user_state == 'ID') { echo ' selected'; } ?>>ID</option>
						<option value="IL"<?php if($current_user && $user_state == 'IL') { echo ' selected'; } ?>>IL</option>
						<option value="IN"<?php if($current_user && $user_state == 'IN') { echo ' selected'; } ?>>IN</option>
						<option value="IA"<?php if($current_user && $user_state == 'IA') { echo ' selected'; } ?>>IA</option>
						<option value="KS"<?php if($current_user && $user_state == 'KS') { echo ' selected'; } ?>>KS</option>
						<option value="KY"<?php if($current_user && $user_state == 'KY') { echo ' selected'; } ?>>KY</option>
						<option value="LA"<?php if($current_user && $user_state == 'LA') { echo ' selected'; } ?>>LA</option>
						<option value="ME"<?php if($current_user && $user_state == 'ME') { echo ' selected'; } ?>>ME</option>
						<option value="MD"<?php if($current_user && $user_state == 'MD') { echo ' selected'; } ?>>MD</option>
						<option value="MA"<?php if($current_user && $user_state == 'MA') { echo ' selected'; } ?>>MA</option>
						<option value="MI"<?php if($current_user && $user_state == 'MI') { echo ' selected'; } ?>>MI</option>
						<option value="MN"<?php if($current_user && $user_state == 'MN') { echo ' selected'; } ?>>MN</option>
						<option value="MS"<?php if($current_user && $user_state == 'MS') { echo ' selected'; } ?>>MS</option>
						<option value="MO"<?php if($current_user && $user_state == 'MO') { echo ' selected'; } ?>>MO</option>
						<option value="MT"<?php if($current_user && $user_state == 'MT') { echo ' selected'; } ?>>MT</option>
						<option value="NE"<?php if($current_user && $user_state == 'NE') { echo ' selected'; } ?>>NE</option>
						<option value="NV"<?php if($current_user && $user_state == 'NV') { echo ' selected'; } ?>>NV</option>
						<option value="NH"<?php if($current_user && $user_state == 'NH') { echo ' selected'; } ?>>NH</option>
						<option value="NJ"<?php if($current_user && $user_state == 'NJ') { echo ' selected'; } ?>>NJ</option>
						<option value="NM"<?php if($current_user && $user_state == 'NM') { echo ' selected'; } ?>>NM</option>
						<option value="NY"<?php if($current_user && $user_state == 'NY') { echo ' selected'; } ?>>NY</option>
						<option value="NC"<?php if($current_user && $user_state == 'NC') { echo ' selected'; } ?>>NC</option>
						<option value="ND"<?php if($current_user && $user_state == 'ND') { echo ' selected'; } ?>>ND</option>
						<option value="OH"<?php if($current_user && $user_state == 'OH') { echo ' selected'; } ?>>OH</option>
						<option value="OK"<?php if($current_user && $user_state == 'OK') { echo ' selected'; } ?>>OK</option>
						<option value="OR"<?php if($current_user && $user_state == 'OR') { echo ' selected'; } ?>>OR</option>
						<option value="PA"<?php if($current_user && $user_state == 'PA') { echo ' selected'; } ?>>PA</option>
						<option value="RI"<?php if($current_user && $user_state == 'RI') { echo ' selected'; } ?>>RI</option>
						<option value="SC"<?php if($current_user && $user_state == 'SC') { echo ' selected'; } ?>>SC</option>
						<option value="SD"<?php if($current_user && $user_state == 'SD') { echo ' selected'; } ?>>SD</option>
						<option value="TN"<?php if($current_user && $user_state == 'TN') { echo ' selected'; } ?>>TN</option>
						<option value="TX"<?php if($current_user && $user_state == 'TX') { echo ' selected'; } ?>>TX</option>
						<option value="UT"<?php if($current_user && $user_state == 'UT') { echo ' selected'; } ?>>UT</option>
						<option value="VT"<?php if($current_user && $user_state == 'VT') { echo ' selected'; } ?>>VT</option>
						<option value="VA"<?php if($current_user && $user_state == 'VA') { echo ' selected'; } ?>>VA</option>
						<option value="WA"<?php if($current_user && $user_state == 'WA') { echo ' selected'; } ?>>WA</option>
						<option value="DC"<?php if($current_user && $user_state == 'DC') { echo ' selected'; } ?>>DC</option>
						<option value="WV"<?php if($current_user && $user_state == 'WV') { echo ' selected'; } ?>>WV</option>
						<option value="WI"<?php if(($current_user && $user_state == 'WI') || !$current_user) { echo ' selected'; } ?>>WI</option>
						<option value="WY"<?php if($current_user && $user_state == 'WY') { echo ' selected'; } ?>>WY</option>
					</select>
				</p>
				<p>
					<label for="asapkids_user_address"><?php _e('Zip'); ?> <span class="required">*</span></label>
					<input name="asapkids_user_zip" id="asapkids_user_zip" type="text"<?php if($current_user) { echo ' value="'.$user_zip.'"';} ?>/>
				</p>	
				<p>
					<label for="asapkids_user_phone"><?php _e('Phone'); ?></label>
					<input name="asapkids_user_phone" id="asapkids_user_phone" type="text"<?php if($current_user) { echo ' value="'.$user_phone.'"';} ?>/>
				</p>																					
				<?php if(!$current_user) { ?>
					<p>
						<label for="password"><?php _e('Password'); ?> <span class="required">*</span></label>
						<input name="asapkids_user_pass" id="password" class="required" type="password"/>
					</p>
					<p>
						<label for="password_again"><?php _e('Confirm Password'); ?> <span class="required">*</span></label>
						<input name="asapkids_user_pass_confirm" id="password_again" class="required" type="password"/>
					</p>
				<?php } ?>
				<p>
					<input type="hidden" name="asapkids_register_nonce" value="<?php echo wp_create_nonce('asapkids-register-nonce'); ?>"/>
					<input class="button" type="submit" value="<?php if($current_user) { _e('Update Profile'); } else { _e('Sign Up'); } ?>"/>
				</p>
			</fieldset>
		</form>
	<?php
	return ob_get_clean();
}

// login form fields
function asapkids_login_form_fields() {
 
	ob_start(); ?>
 
		<?php
		// show any error messages after form submission
		asapkids_show_error_messages(); ?>
 
		<form id="asapkids_login_form" class="asapkids_form" action="" method="POST">
			<fieldset>
				<p>
					<label for="asapkids_user_login">Email:</label>
					<input name="asapkids_user_login" id="asapkids_user_login" class="required" type="text"/>
				</p>
				<p>
					<label for="asapkids_user_pass">Password:</label>
					<input name="asapkids_user_pass" id="asapkids_user_pass" class="required" type="password"/>
				</p>
				<p>
					<input type="hidden" name="asapkids_login_nonce" value="<?php echo wp_create_nonce('asapkids-login-nonce'); ?>"/>
					<input class="button" id="asapkids_login_submit" type="submit" value="Sign In"/>
				</p>
			</fieldset>
		</form>
		
		<a href="<?php echo home_url('lost-password'); ?>" title="Lost Password">Lost Password</a>
	<?php
	return ob_get_clean();
}

// password reset form fields
function asapkids_pwreset_form_fields() {
 
	global $wpdb, $wp_hasher;
	
	// check if we're in reset form
	if( isset( $_POST['action'] ) && 'reset' == $_POST['action'] ) {
		$email = trim($_POST['user_login']);
		
		if( empty( $email ) ) {
			asapkids_errors()->add('username_empty', __('Please enter your email'));
		}
		if( ! is_email( $email )) {
			asapkids_errors()->add('email_invalid', __('Invalid email'));
		}
		if( ! email_exists( $email ) ) {
			asapkids_errors()->add('username_invalid', __('Email address could not be found'));
		}
		
		$errors = asapkids_errors()->get_error_messages();
	 
		// only generate email if there are no errors
		if(empty($errors)) {
			$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $email));
		    if ( empty($key) ) {
		        // Generate something random for a key...
		        $key = wp_generate_password(20, false);
		        do_action('retrieve_password_key', $email, $key);
	    
				if ( empty( $wp_hasher ) ) {
			        require_once ABSPATH . 'wp-includes/class-phpass.php';
			        $wp_hasher = new PasswordHash( 8, true );
			    }
			    $hashed = $wp_hasher->HashPassword( $key );	    
		        
		        // Now insert the new md5 key into the db
		        $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $email));
		    }
	    	    
		    $message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
		    $message .= home_url( '/' ) . "\r\n\r\n";
		    $message .= sprintf(__('Username: %s'), $email) . "\r\n\r\n";
		    $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
		    $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
		    $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($email), 'login') . ">\r\n";	    
		    
		    if ( is_multisite() ) {
		        $blogname = $GLOBALS['current_site']->site_name;
		    } else {
		        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
		        // we want to reverse this for the plain text arena of emails.
		        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			}
			
		    $title = sprintf( __('[%s] Password Reset'), $blogname );
		
		    $title = apply_filters('retrieve_password_title', $title);
		    $message = apply_filters('retrieve_password_message', $message, $key);
			
		    if ( $message && !wp_mail($email, $title, $message) ) {
		        wp_die( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...') );
		    } else {
		    	ob_start(); ?>
		    	
		    	<p>An email has been sent to <strong><?php echo $email; ?></strong>.</p>
		  		
		  		<?php return ob_get_clean();
		    }
		
		}
	} 
 
	ob_start(); ?>
	
	<?php 
		// show any error messages after form submission
		asapkids_show_error_messages(); ?>
 
	<!--html code-->
	<form class="asapkids_form" method="post">
		<fieldset>
			<p>Please enter your email address. You will receive a link to create a new password via email.</p>
			<p><label for="user_login">Email:</label>
				<?php $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : ''; ?>
				<input type="text" name="user_login" id="user_login" value="<?php echo $user_login; ?>" /></p>
			<p>
				<input type="hidden" name="action" value="reset" />
				<input type="submit" value="Get New Password" class="button" id="submit" />
			</p>
		</fieldset> 
	</form>
	
	<?php
	return ob_get_clean();
}

// logs a member in after submitting a form
function asapkids_login_member() {
	//if(isset($_POST['asapkids_user_login']) && wp_verify_nonce($_POST['asapkids_login_nonce'], 'asapkids-login-nonce')) {
	if(isset($_POST['asapkids_login_nonce']) && wp_verify_nonce($_POST['asapkids_login_nonce'], 'asapkids-login-nonce')) {
 		
		// this returns the user ID and other info from the user name
		$user = get_user_by('login', $_POST['asapkids_user_login']);

		if(!$user) {
			// if the user name doesn't exist
			asapkids_errors()->add('empty_username', __('Invalid username'));
		} else {
 
			if(!isset($_POST['asapkids_user_pass']) || $_POST['asapkids_user_pass'] == '') {
				// if no password was entered
				asapkids_errors()->add('empty_password', __('Please enter a password'));
			} else {
				// check the user's login with their password
				if(!wp_check_password($_POST['asapkids_user_pass'], $user->user_pass, $user->ID)) {
					// if the password is incorrect for the specified user
					asapkids_errors()->add('empty_password', __('Incorrect password'));
				}		
			}
		}	

		// retrieve all error messages
		$errors = asapkids_errors()->get_error_messages();
 
		// only log the user in if there are no errors
		if(empty($errors)) {
			$creds = array();
			$creds['user_login'] = $_POST['asapkids_user_login'];
			$creds['user_password'] = $_POST['asapkids_user_pass'];
			$user = wp_signon( $creds, false );
			
	        // get student meta data to pass in query
	        $args = array('post_type' => 'cpt_student', 'post_status' => 'private', 'author' => $user->ID, 'posts_per_page' => -1 );	               
	        $students = get_posts($args);
			
			if(!empty($students)) {               
		        $st_ids = array();
		        foreach ( $students as $id ) {
		            array_push( $st_ids, $id->ID );
		        }
				$st_di = get_field( 'student_distance', $st_ids[0] );
	            $st_ex = get_field( 'student_experience', $st_ids[0] );
	            $st_da = get_field( 'student_days_desired', $st_ids[0] );
	            $st_in = get_field( 'student_interests', $st_ids[0] );
	
	            $arr_params = array( 
	                'st' => $st_ids[0],
	                'di' => $st_di,
	                'age' => asapkids_get_student_age( $st_ids[0] ),
	                'ex' => $st_ex,
	                'dow' => $st_da,
	                'ai' => $st_in,
	                's' => get_search_query(),
	            );
	            
				if ( !is_admin() ) {
	                wp_redirect( home_url( '/manage-students' ) );    
	            	exit;
	            }	            
	        } else {
	        	if ( !is_admin() ) {
					wp_redirect( home_url( '/manage-students' ) );     
					exit;
	        	}	            
	          	            
	        }   
 			
		} 
	}
}
add_action('init', 'asapkids_login_member');

// register a new user
function asapkids_add_new_member() {
	//had to add this check that looks if there was a login error, for some reason this code runs even after a failed login attempt and fires a nonce error
	if(empty(asapkids_errors()->get_error_messages())) {
	  	if (isset( $_POST["asapkids_user_login"] ) && wp_verify_nonce($_POST['asapkids_register_nonce'], 'asapkids-register-nonce')) {	
			$user_email		= $_POST["asapkids_user_login"];	
			$user_first 	= $_POST["asapkids_user_first"];
			$user_last	 	= $_POST["asapkids_user_last"];
			$user_address   = $_POST["asapkids_user_address"];
			$user_city      = $_POST["asapkids_user_city"];
			$user_state     = $_POST["asapkids_user_state"];
			$user_zip       = $_POST["asapkids_user_zip"];
			$user_phone     = $_POST["asapkids_user_phone"];			
			
			if ( !is_user_logged_in() ) {
				$user_pass		= $_POST["asapkids_user_pass"];
				$pass_confirm 	= $_POST["asapkids_user_pass_confirm"];
				
				if($user_email == '') {
					// empty email
					asapkids_errors()->add('username_empty', __('Please enter an email'));
				} else {
					if(username_exists($user_email)) {
						// Email already registered
						asapkids_errors()->add('username_unavailable', __('Email already taken'));
					}
					if(!is_email($user_email)) {
						//invalid email
						asapkids_errors()->add('email_invalid', __('Invalid email'));
					}
					if(email_exists($user_email)) {
						//Email address already registered
						asapkids_errors()->add('email_used', __('Email already registered'));
					}			
					if(!validate_username($user_email)) {
						// invalid username
						asapkids_errors()->add('username_invalid', __('Invalid email'));
					}
				}
				
				if($user_pass == '') {
					// passwords do not match
					asapkids_errors()->add('password_empty', __('Please enter a password'));
				}
				if($user_pass != $pass_confirm) {
					// passwords do not match
					asapkids_errors()->add('password_mismatch', __('Passwords do not match'));
				}				
			} 
			
			if($user_first == '') {
				//First name is empty
				asapkids_errors()->add('first_empty', __('Please enter a first name'));
			}
			
			if($user_last == '') {
				//Last name is empty
				asapkids_errors()->add('last_empty', __('Please enter a last name'));
			}			
	 
			if($user_address == '') {
				//Address is empty
				asapkids_errors()->add('address_empty', __('Please enter an address'));
			}
			if($user_city == '') {
				//City is empty
				asapkids_errors()->add('city_empty', __('Please enter a city'));
			}		
			if($user_state == '') {
				//State is empty
				asapkids_errors()->add('state_empty', __('Please enter a state'));
			}		
			if($user_zip == '') {
				//Zip is empty
				asapkids_errors()->add('zip_empty', __('Please enter a zip'));
			}		
	 
			$errors = asapkids_errors()->get_error_messages();
	 
			// only create the user if there are no errors
			if(empty($errors)) {
	 
				if ( is_user_logged_in() && !current_user_can('manage_options')) {
					
					$user_id = get_current_user_id();
					
					$existing_user_id = wp_update_user(array(
							'ID'                => $user_id,
							'first_name'		=> $user_first,
							'last_name'			=> $user_last
						)
					);
					
					if($existing_user_id) {
						update_user_meta($existing_user_id, 'address', $user_address);
						update_user_meta($existing_user_id, 'city', $user_city);
						update_user_meta($existing_user_id, 'state', $user_state);
						update_user_meta($existing_user_id, 'zip', $user_zip);
						update_user_meta($existing_user_id, 'phone', $user_phone);
					}
				
				} else {
				
					$new_user_id = wp_insert_user(array(
							'user_login'		=> $user_email,
							'user_pass'	 		=> $user_pass,
							'user_email'		=> $user_email,
							'first_name'		=> $user_first,
							'last_name'			=> $user_last,
							'user_registered'	=> date('Y-m-d H:i:s'),
							'role'				=> 'subscriber'
						)
					);
					
					if($new_user_id) {
						update_user_meta($new_user_id, 'address', $user_address);
						update_user_meta($new_user_id, 'city', $user_city);
						update_user_meta($new_user_id, 'state', $user_state);
						update_user_meta($new_user_id, 'zip', $user_zip);
						update_user_meta($new_user_id, 'phone', $user_phone);
						
						// send an email to the admin alerting them of the registration
						wp_new_user_custom_notification($new_user_id);
		 
						// log the new user in
		 				$creds = array();
						$creds['user_login'] = $user_email;
						$creds['user_password'] = $user_pass;
						$user = wp_signon( $creds, false );
		 				
		 				show_admin_bar( false );
		 				
						// send the newly created user to the home page after logging them in
						wp_redirect(home_url('manage-students')); exit;							
					}					
				}				
			}
		}
	}
}
add_action('init', 'asapkids_add_new_member');

//Custom ASAPK!DS new user registration email notification function
function wp_new_user_custom_notification( $user_id ) {
    $user = new WP_User($user_id);

    $user_login = stripslashes($user->user_login);
    $user_email = stripslashes($user->user_email);

    $message  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "\r\n\r\n";
    $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
    $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

    @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);

    $message  = __("Thanks for taking the time to explore ASAPk!ds !") . "\r\n\r\n";
    $message .= __("There are still a few enhancements on the way, as well as Content-based Activities that will allow you to really start putting all the resources in our community to work for your student's needs and give our community a chance to showcase the hard work of community organizations.") . "\r\n\r\n";
    $message .= __("We are here to help, so please feel free to reach out to us if you have any questions or concerns. We're hard at work improving and growing the ASAPk!ds community, and any feedback is greatly appreciated.") . "\r\n\r\n";
	$message  .= __("Thanks,") . "\r\n\r\n";
	$message  .= __("ASAPk!ds Team") . "\r\n";
	$message  .= __("info@asapkids.org") . "\r\n";
	$message  .= __("414-367-6076");

    wp_mail($user_email, 'Welcome to ASAPk!ds', $message);
}

//Customize email name "from" value
add_filter( 'wp_mail_from_name', 'custom_wp_mail_from_name' );
function custom_wp_mail_from_name( $original_email_from ) {
	return 'ASAPk!ds';
}

//Customize email address "from" value 
add_filter( 'wp_mail_from', 'custom_wp_mail_from' );
function custom_wp_mail_from( $original_email_address ) {
	return 'info@asapkids.org';
}

// used for tracking error messages
function asapkids_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function asapkids_show_error_messages() {
	if($codes = asapkids_errors()->get_error_codes()) {
		echo '<div class="asapkids_errors">';
		    // Loop error codes and display errors
		   foreach($codes as $code){
		        $message = asapkids_errors()->get_error_message($code);
		        echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
		    }
		echo '</div>';
	}	
}

// get students age
function asapkids_get_student_age( $student_id ) {
    //if(isset($_GET['st'])) {
    if ( $student_id !== "" ) {
        $birthday = get_field('student_date_of_birth', $student_id );
        $from = new DateTime($birthday);
        $to   = new DateTime('today');
        $age  = $from->diff($to)->y;
    } else if ( isset( $_GET['age'] ) ) {
        $age = get_query_var( 'age' );
    } else {
        $age = '';
    }
    return $age;
}

// Redirect and load in student data - //EL added 8/21
function asapkids_post_save_acf_form() {
    if(!is_admin()) {
	    global $current_user;
	    get_currentuserinfo();
	    // get student meta data to pass in query
	    $args = array('post_type' => 'cpt_student', 'post_status' => 'private', 'author' => $current_user->ID, 'posts_per_page' => -1);
	    $students = get_posts($args);
	    $st_ids = array();
	    foreach ( $students as $id ) {
	        array_push( $st_ids, $id->ID );
	    }
	    if ( isset( $_GET['st'] ) ) {
	        $st_id = get_query_var( 'st' );
	    } else {
	        $st_id = $st_ids[0];
	    }
	    
	    $st_di = get_field( 'student_distance', $st_id );
	    $st_ex = get_field( 'student_experience', $st_id );
	    $st_da = get_field( 'student_days_desired', $st_id );
	    $st_in = get_field( 'student_interests', $st_id );
	    $arr_params = array( 
	        'st' => $st_id,
	        'di' => $st_di,
	        'age' => asapkids_get_student_age( $st_ids[0] ),
	        'ex' => $st_ex,
	        'dow' => $st_da,
	        'ai' => $st_in,
	        's' => get_search_query(),
	    );
	}    
    
    if ( !is_admin() && !is_page( 'add-student' ) ) {
        wp_redirect( add_query_arg( $arr_params, home_url( '/' ) ) );
        exit;
    } else if ( is_page( 'add-student' ) ) {
        wp_redirect( home_url( '/add-student/?updated=true&st=' . $st_id ) );
        exit;
    }
}
add_action('acf/save_post', 'asapkids_post_save_acf_form', 20);

//Add custom admin column headers for Students
add_filter('manage_cpt_student_posts_columns', 'asapkids_students_columns');
function asapkids_students_columns( $defaults ) {
    unset( $defaults['date'] );
    $defaults['title']  = 'Student Name';
    $defaults['author'] = 'User Name';    
    $defaults['email']  = 'User Email';
    $defaults['phone']  = 'User Phone';
    $defaults['date']   = "Date";
    return $defaults;
}

//Populate custom admin columns for Students
add_action( 'manage_cpt_student_posts_custom_column', 'asapkids_students_content', 10, 2 );
function asapkids_students_content( $column_name, $post_id ) {  
    $post = get_post($post_id);
    $author = $post->post_author;
    $user = get_user_by( 'id', $author );
    $user_id = $user->ID;
    
    if ($column_name == 'email') {
    	$user_email = $user->user_login;
      	echo  $user_email;
    }
    if ($column_name == 'phone') {
    	$phone = get_user_meta( $user_id, 'phone', true );
    	echo $phone;
    }
}

//Make custom admin columns sortable for Students
add_filter( 'manage_edit-cpt_student_sortable_columns', 'sorting_cpt_student_columns' );
function sorting_cpt_student_columns( $columns ) {
    $columns['author'] = 'author';
 
    return $columns;
}

//Add custom admin column headers for Programs
add_filter('manage_cpt_program_posts_columns', 'asapkids_programs_columns');
function asapkids_programs_columns( $defaults ) {
    unset( $defaults['date'] );
    $defaults['title']  = 'Program Name';
    $defaults['cpt_interest']  = 'Type';
    $defaults['prog_date_expires'] = 'Date Expires';    
    $defaults['date']   = "Date";
    return $defaults;
}

//Populate custom admin columns for Students
add_action( 'manage_cpt_program_posts_custom_column', 'asapkids_programs_content', 10, 2 );
function asapkids_programs_content( $column_name, $post_id ) {  
    if ($column_name == 'prog_date_expires') {
    	if ( strlen(get_post_meta( $post_id, 'prog_date_expires', true )) ) { 
        	$prog_date_expires = date('o/m/d', strtotime(get_post_meta($post_id, 'prog_date_expires', true)));
        	echo $prog_date_expires;
        } else {
        	echo 'N/A';
        }
    }
    
	if ($column_name == 'cpt_interest') {   
		$types = get_post_meta($post_id, 'associated_interests', true);
		
		if(!empty($types)) {
			$count = 0;
			$type_array = array();
			
			foreach ( $types as $id ) {
				$type_array[] = get_the_title($id);
		    }
		    
		    asort($type_array);
			$type_array = implode(', ', $type_array);
			
			echo $type_array;
		} else {
			echo 'N/A';
		}
	}	  
}

//Make custom admin columns sortable for Programs
add_filter( 'manage_edit-cpt_program_sortable_columns', 'sorting_cpt_program_columns' );
function sorting_cpt_program_columns( $columns ) {
    $columns['prog_date_expires'] = 'prog_date_expires';
    $columns['cpt_interest'] = 'cpt_interest';
 
    return $columns;
}

//Register footer menu, so we can have a distinct menu set to display in footer only
add_action( 'init', 'register_footer_menu' );
function register_footer_menu() {
  register_nav_menu('asapkids-footer-menu',__( 'Footer Menu' ));
}

//Allow registered users (subscribers) the ability to delete their student posts (which are set as private posts
add_action( 'admin_init', 'add_subscriber_delete_cap');
function add_subscriber_delete_cap() {
    $role = get_role( 'subscriber' );
	$role->add_cap('delete_private_posts');    
}