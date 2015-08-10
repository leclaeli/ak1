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
 * Enqueue scripts and styles.
 */
/*function asapkids_scripts() {
	wp_enqueue_style( 'asapkids-style', get_stylesheet_uri() );

	wp_enqueue_script( 'asapkids-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	
	wp_enqueue_script( 'asapkids-jquery-functions', get_template_directory_uri() . '/js/functions.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'asapkids_scripts' );*/

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
	if( is_admin() && !strpos($_SERVER["REQUEST_URI"], 'wp-admin/admin-ajax.php') ) {
		wp_redirect( home_url( '/sign-up/' ) );
        exit();
	}
}

// Display appropriate wordpress menu based on whether a user is logged in or not
function my_wp_nav_menu_args( $args ) {
	if( is_user_logged_in() && !current_user_can('manage_options')) {
	    $args['menu'] = 'logged-in';
	} else {
	    $args['menu'] = 'logged-out';
	}
    return $args;
}
add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );

// Attaching custom menu options to logged in user's menu
add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
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
  				$url = home_url('/') .'add-student/?st='. $id->ID;
  				if($student_name) {
  					$student_items.='<li><a href="'.$url.'">'.$student_name.'</a></li>';
  				}
  			}
  		}
        $items = '<li class="asapkids-profile-menu">'. $current_user->user_firstname . ' '. $current_user->user_lastname .'<ul>'. $student_items . $items .'<li><a href="'. wp_logout_url( home_url( '/' ) . 'sign-in') .'">Log Out</a></li></li></ul></ul>';
    }
    return $items;
}

/*
*  Elijah's Functions
*/

// Allows query vars to be added, removed, or changed prior to executing the query.
function asapkids_query_vars( $qvars ) {
    $qvars[] = 'ai'; //associated_interests
    $qvars[] = 'dow'; // dows of the week
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
        $this->location_title = get_the_title();
        $this->location_post_id = get_the_id();
        array_push($location_titles, $this->location_title);
        array_push($location_post_ids, $this->location_post_id);
    }
}

// Enqueue Scripts
function asapkids_scripts() {
	
	//if(is_search() && is_user_logged_in()) {
	if(is_user_logged_in()) {
 		$current_user = wp_get_current_user();
 		$user_address = get_user_meta($current_user->ID, 'address', true).', '.get_user_meta($current_user->ID, 'city', true).', '.get_user_meta($current_user->ID, 'state', true).' '.get_user_meta($current_user->ID, 'zip', true);
 	} else {
 		$user_address = 'Milwaukee, WI';
 	}
 	
	wp_enqueue_script( 'asapkids-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
    wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places', array(), false, true);    
    wp_enqueue_script( 'asapkids-jquery-functions', get_template_directory_uri() . '/js/functions.js', array(), '20130115', true );
    wp_enqueue_script('marker-with-label', get_stylesheet_directory_uri() . '/js/markerwithlabel_packed.js', array(), false, true);
    wp_enqueue_script('jquery-ui-datepicker');	 
    wp_enqueue_style('plugin_name-admin-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/smoothness/jquery-ui.css', false, false, false );
    wp_localize_script( 'asapkids-jquery-functions', 'ak_localize', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'distance' => ( get_query_var( 'di', 9999999 ) != 0 ? get_query_var( 'di' ) : 9999999 ), // distance
        'user_address' => get_query_var( 'addy' ),
    ) );
    
    //if(is_search()) {
    	wp_localize_script('asapkids-jquery-functions', 'get_user_location', array('address' => $user_address));
    //}
    //mmenu
   wp_enqueue_script('mmenu', get_stylesheet_directory_uri() . '/mmenu/jquery.mmenu.min.all.js', array( 'jquery'), false, true);
   wp_enqueue_style( 'asapkids-style', get_stylesheet_uri() );
   wp_enqueue_style( 'mmenu-css', get_stylesheet_directory_uri() . '/mmenu/jquery.mmenu.all.css', false, false, false );
   wp_enqueue_style( 'mmenu-iconbar', get_stylesheet_directory_uri() . '/mmenu/jquery.mmenu.iconbar.css', false, false, false );
   wp_enqueue_style( 'mmenu-widescreen', get_stylesheet_directory_uri() . '/mmenu/jquery.mmenu.widescreen.css', false, false, 'all and (min-width: 900px)' );
   
}
add_action('wp_enqueue_scripts', 'asapkids_scripts');

//get search to filter out non-programs posts and pages
/*function mySearchFilter($query) {
    if ($query->is_search) {
        $query->set('post_type', array('cpt_program', 'cpt_interest', 'cpt_organization'));    
    };
};
add_filter('pre_get_posts','mySearchFilter');*/

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
					<label for="asapkids_user_Login">Username:</label>
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
			
	        // get student meta data to pass in query
	        $args = array('post_type' => 'cpt_student', 'post_status' => 'private', 'author' => $user->ID);
	        $students = get_posts($args);
	        $st_ids = array();
	        foreach ( $students as $id ) {
	            array_push( $st_ids, $id->ID );
	        }
	        $st_di = get_field( 'student_distance', $st_ids[0] );
	        // $st_age = get_field( 'student_age', $st_ids[0] );
			
			$creds = array();
			$creds['user_login'] = $_POST['asapkids_user_login'];
			$creds['user_password'] = $_POST['asapkids_user_pass'];
			$user = wp_signon( $creds, false );
 			
			wp_redirect(home_url() . '/?s&st=' . $st_ids[0] . '&di=' . $st_di . '&age=' . asapkids_get_student_age( $st_ids[0] ) ); // could also redirect to page with links to children
            exit;
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
						wp_new_user_notification($new_user_id);
		 
						// log the new user in
		 				$creds = array();
						$creds['user_login'] = $user_email;
						$creds['user_password'] = $user_pass;
						$user = wp_signon( $creds, false );
		 				
		 				show_admin_bar( false );
		 				
						// send the newly created user to the home page after logging them in
						wp_redirect(home_url()); exit;							
					}					
				}				
			}
		}
	}
}
add_action('init', 'asapkids_add_new_member');

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
    if ( isset( $student_id ) ) {
    $birthday = get_field('student_date_of_birth', $student_id );
    $from = new DateTime($birthday);
    $to   = new DateTime('today');
    $age  = $from->diff($to)->y;
    } else {
        $age = '';
    }
    return $age;
}

// check for student experience
function asapkids_check_student_experience( $level ) {
    if ( get_query_var( 'st' ) ) {
        $st_exp = get_field( 'student_experience', $_GET['st'] ); // get student experience preferences
        if ( in_array( $level, $st_exp ) ) echo 'checked';
    }
}


function asapkids_post_save_acf_form() {
    global $current_user;
    get_currentuserinfo();
    // get student meta data to pass in query
    $args = array('post_type' => 'cpt_student', 'post_status' => 'private', 'author' => $current_user->ID);
    $students = get_posts($args);
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
        wp_redirect( add_query_arg( $arr_params, home_url( '/' ) ) );
        exit();
    }
}

add_action('acf/save_post', 'asapkids_post_save_acf_form', 20);