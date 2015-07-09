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
				<ul id="accordion">
					<li><a class="fa fa-bars" href="#"></a>
					</li>
					<li><a class="fa fa-user" href="#"></a></li>
					<li><a class="fa fa-map-marker" href="#"><label>Distance:</label></a>
						<div class="collapsed">
							<select name="di" id="select-distance" style="width: 300px;">
								<option></option>
								<option value="1609.34">Within 1 Mile</option>
								<option value="3218.69">Within 2 Miles</option>
								<option value="8046.72">Within 5 Miles</option>
								<option value="16093.4">Within 10 Miles</option>
								<option value="32186.9">Within 20 Miles</option>
								<option value="">Any Distance</option>
							</select>
						</div>	
					</li>
					<li><a class="fa fa-birthday-cake" href="#"><label>Age:</label></a>
						<div class="collapsed">
							<input type="number" name="age" id="age" min="4" max="19" >
						</div>
					</li>
					<li><a class="fa fa-money" href="#"><label>Price:</label></a>
						<div class="collapsed">
							<select name="pr" id="select-price" style="width: 300px;">
								<option></option>
								<option value="25">$25 or Less</option>
								<option value="50">$50 or Less</option>
								<option value="100">$100 or Less</option>
								<option value="200">$200 or Less</option>
								<option value="">Any</option>
							</select>
						</div>
					</li>
					<li><a class="fa fa-calendar" href="#"><label for="datepicker">Date:</label></a>
						<div class="collapsed">
							<span>Programs that begin before or on the date selected.</span>
							<input type="text" id="datepicker" />
						</div>
					</li>
					<li><a class="fa fa-star-o" href="#"><label>Experience:</label></a>
						<div class="collapsed">
							<ul>
								<li><label for="exp1"><input id="exp1" type="checkbox" value="Beginner" name="ex[]">Beginner</label></li>
								<li><label for="exp2"><input id="exp2" type="checkbox" value="Intermediate" name="ex[]">Intermediate</label></li>
								<li><label for="exp3"><input id="exp3" type="checkbox" value="Advanced" name="ex[]">Advanced</label></li>
								<li><label for="exp4"><input id="exp4" type="checkbox" value="0" name="ex[]">Any or Not Applicable</label></li>
							</ul>
						</div>
					</li>
					<li><a class="fa fa-heart-o" href="#"><label>Interests:</label></a>
						<div class="collapsed">
							<select multiple name="ai[]" id="select-ai" style="width: 300px;">
								<?php 
								global $post; 
								$args = array( 'numberposts' => -1, 'post_type' => 'cpt_interest' ); 
								$posts = get_posts($args);
								foreach( $posts as $post ) : setup_postdata($post); ?>
									<option value="<? echo $post->ID; ?>"><?php the_title(); ?></option> 
								<?php endforeach; 
								wp_reset_postdata();
								?>
							</select>
						</div>
					</li>
					<li><input type="submit" value="Save Preferences"></li>
					<li class="menu-background"> </li>

				</ul>
				
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
						<li><a id="list-view" class="fa fa-th-list clicked" href="#"></a></li>
						<li><a id="map-view" class="fa fa-map-marker" href="#"></a></li>
					</ul>
				</div>
				<div class="asapkids-search-info-select">
					<form action="/wordpress/search-results/" method="get">
						<select name="sr" onchange="this.form.submit()">
							<option value="location">Location</option>
							<option value="date">Date</option>
							<option value="price">Price</option>
						</select>
					</form>
				</div>
			</div>
		<?php // } ?>
		<div id="content" class="site-content">