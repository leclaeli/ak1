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

/**
 * Enqueue scripts and styles.
 */
function asapkids_scripts() {
	wp_enqueue_style( 'asapkids-style', get_stylesheet_uri() );

	wp_enqueue_script( 'asapkids-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'asapkids-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	
	wp_enqueue_script( 'asapkids-jquery-functions', get_template_directory_uri() . '/js/functions.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'asapkids_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/** RDK Custom functions stuff **/
require_once('custom-post-types.php');

// Hide admin toolbar from front-end registrants and redirect front-end registrants that are trying to access the dashboard to their profile page
if ( is_user_logged_in() && !current_user_can('manage_options')) {
	add_filter('show_admin_bar', '__return_false');
	if( is_admin() ) {
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
    $qvars[] = 'dow';
    $qvars[] = 'chid'; // child's id
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
function custom_js_script() {
    if ( !is_page_template( 'update-child.php' ) ) {
        wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places', array(), false, true);    
    }
    wp_enqueue_script('google-maps', get_stylesheet_directory_uri() . '/js/google-maps.js', array( 'jquery'), false, true);
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery', 'google-maps'), false, false);
    wp_enqueue_script('marker-with-label', get_stylesheet_directory_uri() . '/js/markerwithlabel_packed.js', array(), false, true);
    wp_enqueue_script('jquery-ui-datepicker');	 
    wp_enqueue_style('plugin_name-admin-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/smoothness/jquery-ui.css', false, false, false );
    wp_localize_script( 'custom-script', 'testing', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ) );
    wp_localize_script( 'google-maps', 'gmap', array(
        'distance' => ( get_query_var( 'di', 9999999 ) != 0 ? get_query_var( 'di' ) : 9999999 ) // distance
    ) );
    //mmenu
   wp_enqueue_script('mmenu', get_stylesheet_directory_uri() . '/js/jquery.mmenu.min.all.js', array( 'jquery'), false, true);
   wp_enqueue_style( 'mmenu-css', get_stylesheet_directory_uri() . '/js/jquery.mmenu.all.css', false, false, false );
   wp_enqueue_style( 'mmenu-iconbar', get_stylesheet_directory_uri() . '/js/jquery.mmenu.iconbar.css', false, false, false );
   wp_enqueue_style( 'mmenu-widescreen', get_stylesheet_directory_uri() . '/js/jquery.mmenu.widescreen.css', false, false, 'all and (min-width: 900px)' );
   
}
add_action('wp_enqueue_scripts', 'custom_js_script');

//get search to filter out non-programs posts and pages
/*function mySearchFilter($query) {
    if ($query->is_search) {
        $query->set('post_type', array('cpt_program', 'cpt_interest', 'cpt_organization'));    
    };
};
add_filter('pre_get_posts','mySearchFilter');*/

/**
 * ASAPK!DS Custom Registration/Login
 */
// user registration login form
function asapkids_registration_form() {
 
	// only show the registration form to non-logged-in members
	//if(!is_user_logged_in()) {
 
		// check to make sure user registration is enabled
		$registration_enabled = get_option('users_can_register');
 
		// only show the registration form if allowed
		if($registration_enabled) {
			$output = asapkids_registration_form_fields();
		} else {
			$output = __('User registration is not enabled');
		}
		return $output;
	//}
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
 		//$user_pass    = $current_user->user_pass;
 		//$user_email   = $current_user->user_email;
 		$user_first   = $current_user->first_name;
 		$user_last    = $current_user->last_name;
		$user_address = get_user_meta($user_id, 'address', true);
		$user_city    = get_user_meta($user_id, 'city', true);
		$user_state   = get_user_meta($user_id, 'state', true);
		$user_zip     = get_user_meta($user_id, 'zip', true);	
 	}
 	
	ob_start(); ?>	
 
		<?php 
		// show any error messages after form submission
		asapkids_show_error_messages(); ?>
 
		<form id="asapkids_registration_form" class="asapkids_form" action="" method="POST">
			<fieldset>
				<p>
					<label for="asapkids_user_login"><?php _e('Email'); ?></label>
					<input name="asapkids_user_login" id="asapkids_user_login" class="required" type="email"<?php if($current_user) { echo ' value="'.$user_email.'"';} ?>/>
				</p>
				<p>
					<label for="asapkids_user_first"><?php _e('First Name'); ?></label>
					<input name="asapkids_user_first" id="asapkids_user_first" type="text"<?php if($current_user) { echo ' value="'.$user_first.'"';} ?>/>
				</p>
				<p>
					<label for="asapkids_user_last"><?php _e('Last Name'); ?></label>
					<input name="asapkids_user_last" id="asapkids_user_last" type="text"<?php if($current_user) { echo ' value="'.$user_last.'"';} ?>/>
				</p>
				<p>
					<label for="asapkids_user_address"><?php _e('Address'); ?></label>
					<input name="asapkids_user_address" id="asapkids_user_address" type="text"<?php if($current_user) { echo ' value="'.$user_address.'"';} ?>/>
				</p>	
				<p>
					<label for="asapkids_user_city"><?php _e('City'); ?></label>
					<input name="asapkids_user_city" id="asapkids_user_city" type="text"<?php if($current_user) { echo ' value="'.$user_city.'"';} ?>/>
				</p>				
				<p>
					<label for="asapkids_user_state"><?php _e('State'); ?></label>
					<input name="asapkids_user_state" id="asapkids_user_state" type="text"<?php if($current_user) { echo ' value="'.$user_state.'"';} ?>/>
				</p>
				<p>
					<label for="asapkids_user_address"><?php _e('Zip'); ?></label>
					<input name="asapkids_user_zip" id="asapkids_user_zip" type="text"<?php if($current_user) { echo ' value="'.$user_zip.'"';} ?>/>
				</p>																		
				<?php if(!$current_user) { ?>
					<p>
						<label for="password"><?php _e('Password'); ?></label>
						<input name="asapkids_user_pass" id="password" class="required" type="password"/>
					</p>
					<p>
						<label for="password_again"><?php _e('Confirm Password'); ?></label>
						<input name="asapkids_user_pass_confirm" id="password_again" class="required" type="password"/>
					</p>
				<?php } ?>
				<p>
					<input type="hidden" name="asapkids_register_nonce" value="<?php echo wp_create_nonce('asapkids-register-nonce'); ?>"/>
					<input type="submit" value="<?php if($current_user) { _e('Update'); } else { _e('Sign Up'); } ?>"/>
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
					<input id="asapkids_login_submit" type="submit" value="Login"/>
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
 			
			wp_redirect(home_url()); exit;
		}
	}
}
add_action('init', 'asapkids_login_member');

// register a new user
function asapkids_add_new_member() {
	//had to add this check that looks if there was a login error, for some reason this code runs even after a failed login attempt and fires a nonce error
	if(empty(asapkids_errors()->get_error_messages())) {
	  	if (isset( $_POST["asapkids_user_login"] ) && wp_verify_nonce($_POST['asapkids_register_nonce'], 'asapkids-register-nonce')) {	
			
			if ( !is_user_logged_in() ) {
				$user_pass		= $_POST["asapkids_user_pass"];
				$pass_confirm 	= $_POST["asapkids_user_pass_confirm"];
				
				if(username_exists($user_email)) {
					// Username already registered
					asapkids_errors()->add('username_unavailable', __('Username already taken'));
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
					asapkids_errors()->add('username_invalid', __('Invalid username'));
				}
				if($user_email == '') {
					// empty username
					asapkids_errors()->add('username_empty', __('Please enter a username'));
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
			
			$user_email		= $_POST["asapkids_user_login"];	
			//$user_email		= $_POST["asapkids_user_email"];
			$user_first 	= $_POST["asapkids_user_first"];
			$user_last	 	= $_POST["asapkids_user_last"];
			$user_address   = $_POST["asapkids_user_address"];
			$user_city      = $_POST["asapkids_user_city"];
			$user_state     = $_POST["asapkids_user_state"];
			$user_zip       = $_POST["asapkids_user_zip"];
	 
			// this is required for username checks
			/* RDK had to comment out, this is deprecated
			require_once(ABSPATH . WPINC . '/registration.php');
	 		*/
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
	 
			// only create the user in if there are no errors
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
						
						// send an email to the admin alerting them of the registration
						wp_new_user_notification($new_user_id);
		 
						// log the new user in
						//wp_set_auth_cookie($user_login, $user_pass, true);
						//wp_set_current_user($new_user_id, $user_login);	
						//do_action('wp_login', $user_login);
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