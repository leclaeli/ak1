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
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/fonts/css/font-awesome.min.css">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'asapkids' ); ?></a>

	<div class="container-left">
		<div class="asapkids-menu">
			<form action="/wordpress/search-results/" method="get">
				<ul>
					<li><a class="fa fa-bars" href="#"></a>
					</li>
					<li><a class="fa fa-user" href="#"></a></li>
					<li><a class="fa fa-map-marker" href="#"></a></li>
					<li><a class="fa fa-birthday-cake" href="#"><label>Age:</label></a>
						<div>
							<input type="number" name="age" id="age" min="4" max="19" >
						</div>
					</li>
					<li><a class="fa fa-money" href="#"></a></li>
					<li><a class="fa fa-calendar" href="#"></a></li>
					<li><a class="fa fa-star-o" href="#"><label>Experience:</label></a>
						<ul>
							<li><label for="exp1"><input id="exp1" type="checkbox" value="Beginner" name="ex[]">Beginner</label></li>
							<li><label for="exp2"><input id="exp2" type="checkbox" value="Intermediate" name="ex[]">Intermediate</label></li>
							<li><label for="exp3"><input id="exp3" type="checkbox" value="Advanced" name="ex[]">Advanced</label></li>
							<li><label for="exp4"><input id="exp4" type="checkbox" value="0" name="ex[]">Any or Not Applicable</label></li>
						</ul>
					</li>
					<li><a class="fa fa-heart-o" href="#"></a></li>
					<li class="menu-background"> </li>				
				</ul>
				<input type="submit" value="Filter Results">
			</form>
		</div>
	</div><div class="container-right">
		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div><!-- .site-branding --><?php get_search_form(); ?><nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'asapkids' ); ?></button>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
			</nav><!-- #site-navigation -->
		</header><!-- #masthead -->
		<?php // if ( is_search() ) { ?>
			<div class="asapkids-search-info">
				<div class="asapkids-search-info-text">
					<?php printf( esc_html__( 'Showing %s Results for "%s"', 'asapkids' ), '<span class="total-results"></span>', '<span>' . get_search_query() . '</span>' ); ?>
				</div>
				<div class="asapkids-search-info-icons">
					<ul>
						<li><a class="fa fa-th-large" href="#"></a></li>
						<li><a id="list-view" class="fa fa-th-list" href="#"></a></li>
						<li><a id="map-view" class="fa fa-map-marker" href="#"></a></li>
					</ul>
				</div>
				<div class="asapkids-search-info-select">
					<select name="asapkids-sort">
						<option value="location">Location</option>
						<option value="date">Date</option>
						<option value="price">Price</option>
					</select>
				</div>
			</div>
		<?php // } ?>
		<div id="content" class="site-content">