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
 * Implement the Custom Header feature.
 */
/*require get_template_directory() . '/inc/custom-header.php';*/

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
/*require get_template_directory() . '/inc/extras.php';*/

/**
 * Customizer additions.
 */
/*require get_template_directory() . '/inc/customizer.php';*/

/**
 * Load Jetpack compatibility file.
 */
/*require get_template_directory() . '/inc/jetpack.php';*/


/** RDK Custom functions stuff **/
require_once('custom-post-types.php');

// Hide admin toolbar from front-end registrants
if (!current_user_can('manage_options')) {
	add_filter('show_admin_bar', '__return_false');
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
            //$this->location_title = get_the_title();
            
            $this->has_loc = " pinned";
            // array_push( $locations[$i], get_the_title() );
            // var_dump($has_location);
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
    wp_enqueue_script('marker-with-label', get_stylesheet_directory_uri() . '/js/markerwithlabel.js', array(), false, true);
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('plugin_name-admin-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/smoothness/jquery-ui.css', false, false, false );
    wp_localize_script( 'custom-script', 'testing', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ) );
    wp_localize_script( 'google-maps', 'gmap', array(
        'distance' => ( get_query_var( 'di', 9999999 ) != 0 ? get_query_var( 'di' ) : 9999999 ) // distance
    ) );
}
add_action('wp_enqueue_scripts', 'custom_js_script');
