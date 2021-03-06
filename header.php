<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package asapkids
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<!-- FONTS -->
<script src="https://use.typekit.net/zfn7esn.js"></script>
<script>try{Typekit.load({ async: true });}catch(e){}</script>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php 
/*** RDK 2015072901: testing code to load student profiles as default filters ***/

if(is_user_logged_in()) {
	$user = wp_get_current_user();
	
	$args = array('post_type' => 'cpt_student', 'post_status' => 'private', 'author' => $user->ID, 'orderby' => 'title', 'order' => 'ASC');
	$students = get_posts($args);

    $distance = ( get_query_var( 'di', 9999999 ) != 0 ? get_query_var( 'di' ) : 9999999 ); // distance
    if ( !empty( $distance ) ) {
        update_field( 'field_558d91f3473f9', $distance, 236 );
    }
}
?>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'asapkids' ); ?></a>

	<div class="container-right">
		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo get_template_directory_uri().'/images/asapkids-logo.png'; ?>" title="ASAPK!DS" alt="ASAPK!DS"></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div><!-- .site-branding --><?php get_search_form(); ?><nav id="site-navigation" class="main-navigation" role="navigation">
				<!--<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">--><?php //esc_html_e( 'Primary Menu', 'asapkids' ); ?><!--</button>-->
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
			</nav><!-- #site-navigation -->
		</header><!-- #masthead -->
		
		<div id="content" class="site-content">