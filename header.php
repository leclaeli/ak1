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

<script>
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocomplete;
var componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'long_name',
  postal_code: 'short_name'
};

function initialize() {
  // Create the autocomplete object, restricting the search
  // to geographical location types.
  autocomplete = new google.maps.places.Autocomplete(
	  /** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
	  { types: ['geocode'] });
  // When the user selects an address from the dropdown,
  // populate the address fields in the form.
  google.maps.event.addListener(autocomplete, 'place_changed', function() {
	//fillInAddress();
  });
}

// [START region_fillform]
function fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();

  for (var component in componentForm) {
	document.getElementById(component).value = '';
	document.getElementById(component).disabled = false;
  }

  // Get each component of the address from the place details
  // and fill the corresponding field on the form.
  for (var i = 0; i < place.address_components.length; i++) {
	var addressType = place.address_components[i].types[0];
	if (componentForm[addressType]) {
	  var val = place.address_components[i][componentForm[addressType]];
	  document.getElementById(addressType).value = val;
	}
  }
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
  if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(function(position) {
	  var geolocation = new google.maps.LatLng(
		  position.coords.latitude, position.coords.longitude);
	  var circle = new google.maps.Circle({
		center: geolocation,
		radius: position.coords.accuracy
	  });
	  autocomplete.setBounds(circle.getBounds());
	});
  }
}
// [END region_geolocation]

</script>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'asapkids' ); ?></a>

	


		<!-- <nav id="my-menu">
		   <ul>
			  <li class="Divider"><a href="/">Home</a></li>
			  <li><a href="/about/">About us</a>
				 <ul class="Vertical">
					<li class="Selected"><a href="/about/history/">History</a></li>
					<li><a href="/about/team/">The team</a></li>
					<li><a href="/about/address/">Our address</a></li>
				 </ul>
			  </li>
			  <li class="Spacer"><a href="/contact/">Contact</a></li>
		   </ul>
		</nav> -->
		



<!-- <form id="preferences"> -->
<form id="my-menu" class="filter-preferences">
	<ul>

		<li>
			<span>
				<img src="<?php echo get_template_directory_uri(); ?>/images/child.png" />
				Student
			</span>
			<ul>
				<select name="st" id="select-student">
					<option></option>
					<option value="student1">Student 1</option>
					<option value="student2">Student 2</option>
				</select>
			</ul>
		</li>

		<li>
			<span>
				<img src="<?php echo get_template_directory_uri(); ?>/images/location.png" />
				Location
			</span>
			<ul>
				<div id="locationField">
					<input id="autocomplete" placeholder="Enter your address" onFocus="geolocate()" type="text" name="addy"></input>
				</div>
				<li>
					<select name="di" id="select-distance">
						<option></option>
						<option value="1609.34">Within 1 Mile</option>
						<option value="3218.69">Within 2 Miles</option>
						<option value="8046.72">Within 5 Miles</option>
						<option value="16093.4">Within 10 Miles</option>
						<option value="32186.9">Within 20 Miles</option>
						<option value="">Any Distance</option>
					</select>
				</li>
			</ul>
		</li>

		<li>
			<span>
				<img src="<?php echo get_template_directory_uri(); ?>/images/birthdate.png" />
				Age
			</span>
			<ul>
				<li>
					<input type="number" name="age" id="age" min="4" max="19" >
				</li>
			</ul>
		</li>

		<li>
			<span>
			   <img src="<?php echo get_template_directory_uri(); ?>/images/price.png" />
				Price<br />
				<!-- <small>Garcia</small> -->
			</span>
			<ul>
			 	<li>
			 		<select name="pr" id="select-price">
                        <option></option>
                        <option value="25">$25 or Less</option>
                        <option value="50">$50 or Less</option>
                        <option value="100">$100 or Less</option>
                        <option value="200">$200 or Less</option>
                        <option value="">Any</option>
                    </select>
			 	</li>
			</ul>
		</li>

		<li>
			<span>
				<img src="<?php echo get_template_directory_uri(); ?>/images/date.png" />
				Date
			</span>
			<ul>
				<li>
					<span>I'm looking for programs that begin before:</span>
                    <input type="text" id="datepicker" placeholder="Select a date" />
				</li>
			</ul>
            <ul>
                <li><label for="dow2"><input id="dow2" type="checkbox" value="monday" name="dow[]">Monday</label></li>
                <li><label for="dow3"><input id="dow3" type="checkbox" value="tuesday" name="dow[]">Tuesday</label></li>
                <li><label for="dow4"><input id="dow4" type="checkbox" value="wednesday" name="dow[]">Wednesday</label></li>
                <li><label for="dow5"><input id="dow5" type="checkbox" value="thursday" name="dow[]">Thursday</label></li>
                <li><label for="dow6"><input id="dow6" type="checkbox" value="friday" name="dow[]">Friday</label></li>
                <li><label for="dow7"><input id="dow7" type="checkbox" value="saturday" name="dow[]">Saturday</label></li>
                <li><label for="dow1"><input id="dow1" type="checkbox" value="sunday" name="dow[]">Sunday</label></li>
            </ul>
			 	
		</li>

		<li>
			<span>
				<img src="<?php echo get_template_directory_uri(); ?>/images/experience.png" />
				Experience
			</span>
			<ul>
                <li><label for="exp1"><input id="exp1" type="checkbox" value="Beginner" name="ex[]">Beginner</label></li>
                <li><label for="exp2"><input id="exp2" type="checkbox" value="Intermediate" name="ex[]">Intermediate</label></li>
                <li><label for="exp3"><input id="exp3" type="checkbox" value="Advanced" name="ex[]">Advanced</label></li>
                <li><label for="exp4"><input id="exp4" type="checkbox" value="0" name="ex[]">Any or Not Applicable</label></li>
            </ul>
		</li>

		<li>
			<span>
				<img src="<?php echo get_template_directory_uri(); ?>/images/favorite.png" />
				Interests
			</span>
			<ul>
				<li>
					<select multiple name="ai[]" id="select-ai">
			            <?php 
			            global $post; 
			            $interest_args = array( 'numberposts' => -1, 'post_type' => 'cpt_interest', 'orderby' => 'title', 'order' => 'ASC' ); 
			            $interest_posts = get_posts($interest_args);
			            foreach( $interest_posts as $post ) : setup_postdata($post); ?>
			                <option value="<? echo $post->ID; ?>"><?php the_title(); ?></option> 
			            <?php endforeach; 
			            wp_reset_postdata();
			            ?>
		        	</select>
	        	</li>
	        </ul>
        </li>
        <li><input type="hidden" name="s" id="filter-search" value="<?php echo get_search_query(); ?>" /></li>
        <li><input id="view-results" type="submit" value="Apply Filters"></li>
   </ul>

</form>









	<div class="container-right">
		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div><!-- .site-branding --><?php get_search_form(); ?>
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'asapkids' ); ?></button>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
			</nav><!-- #site-navigation -->
		</header><!-- #masthead -->
		<?php if ( is_search() ) { ?>
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
				<a href="#my-menu">Open the menu</a>
				<!-- <div class="asapkids-search-info-select">
					<form>
						<select name="sr" onchange="this.form.submit()">
							<option value="location">Location</option>
							<option value="date">Date</option>
							<option value="price">Price</option>
						</select>
					</form>
				</div> -->
			</div>
		<?php } ?>
		<div id="content" class="site-content">

		<script>
		jQuery( document ).ready(function($) {
			initialize();
		});
		</script>