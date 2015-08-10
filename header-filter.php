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

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php 
/*** RDK 2015072901: testing code to load student profiles as default filters ***/

if(is_user_logged_in()) {
	$user = wp_get_current_user();
	$args = array('post_type' => 'cpt_student', 'post_status' => 'private', 'author' => $user->ID, 'orderby' => 'title', 'order' => 'ASC');
	$students = get_posts($args);
    
    //$distance = ( get_query_var( 'di', 9999999 ) != 0 ? get_query_var( 'di' ) : 9999999 ); // distance
    // if ( !empty( $distance ) ) {
    //     update_field( 'field_558d91f3473f9', $distance, 236 );
    // }
}

if ( isset( $_GET['st'] ) ) {
    $st_id = $_GET['st'];
    $st_di = get_field( 'student_distance', $st_id);
    $st_ex = get_field( 'student_experience', $st_id );
    $st_ins = get_field( 'student_interests', $st_id );
    //$st_in = ( get_field( 'student_interests', $st_id ) ? get_field( 'student_interests', $st_id ) : array() );
    $st_in = ( $st_ins ) ? $st_ins : array();
    $st_da = get_field( 'student_days_desired', $st_id );
    $st_name = get_field( 'student_name', $st_id );
} else {
    $st_di = null;
    $st_ex = array();
    $st_in = array();
    $st_da = array();
    $st_name = "Student";
}
?>

<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'asapkids' ); ?></a>

	<form id="my-menu" class="filter-preferences">
		<ul>
			<li class="dark-orange">
				<span id="hamburger-span">
                    <a href="#" id="hamburger"><span></span></a>
				</span>
			</li>

			<li>
				<span>
					<img src="<?php echo get_template_directory_uri(); ?>/images/student.png" />
					<?php echo $st_name; ?>
				</span>
				<ul>
					<?php if(is_user_logged_in()) { ?>
                        <?php if ( count( $students ) > 0) { ?>
    						<select name="st" id="select-student">
    							<?php
    								foreach($students as $id) {
    									$student_name = get_field('student_name', $id->ID);
    									echo '<option value="'.$id->ID.'">'.get_field('student_name', $id->ID).'</option>';
    								}
    							?>
    						</select>
    					<?php } else {
    						echo 'No student profiles exist, want to <a href="'.home_url('/add-student').'">create one</a>?';
    					} 
                    } else {
                        echo '<a href="'.home_url('/sign-in').'">Sign In</a> or <a href="'.home_url('/sign-up').'">Sign Up</a> for a new account.';
                    } ?>
				</ul>
			</li>

            <!-- <li class="darkest-orange select-student"></li> -->
	
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
						<select name="di" id="select-distance" autocomplete="off">
							<option <?php if ( $st_di == '9999999') echo 'selected'; ?> value="9999999">Any Distance</option>
							<option <?php if ( $st_di == '1609.34') echo 'selected'; ?> value="1609.34">Within 1 Mile</option>
							<option <?php if ( $st_di == '3218.69') echo 'selected'; ?> value="3218.69">Within 2 Miles</option>
							<option <?php if ( $st_di == '8046.72') echo 'selected'; ?> value="8046.72">Within 5 Miles</option>
							<option <?php if ( $st_di == '16093.4') echo 'selected'; ?> value="16093.4">Within 10 Miles</option>
							<option <?php if ( $st_di == '32186.9') echo 'selected'; ?> value="32186.9">Within 20 Miles</option>
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
						<input type="number" name="age" id="age" value="<?php echo asapkids_get_student_age( $_GET['st'] ); ?>">
					</li>
				</ul>
			</li>
	
			<li>
				<span>
					<img src="<?php echo get_template_directory_uri(); ?>/images/experience.png" />
					Experience
				</span>
				<ul>
	                <li><label for="exp1"><input id="exp1" type="checkbox" value="beg" name="ex[]" <?php if ( in_array( "Beginner", $st_ex ) ) echo 'checked'; ?>>Beginner</label></li>
	                <li><label for="exp2"><input id="exp2" type="checkbox" value="int" name="ex[]" <?php if ( in_array( "Intermediate", $st_ex ) ) echo 'checked'; ?>>Intermediate</label></li>
	                <li><label for="exp3"><input id="exp3" type="checkbox" value="adv" name="ex[]" <?php if ( in_array( "Advanced", $st_ex ) ) echo 'checked'; ?>>Advanced</label></li>
	                <li><label for="exp4"><input id="exp4" type="checkbox" value="" name="ex[]" <?php if ( in_array( "Any", $st_ex ) ) echo 'checked'; ?>>Any or Not Applicable</label></li>
	            </ul>
			</li>
	
			<li>
				<span>
					<img src="<?php echo get_template_directory_uri(); ?>/images/favorite.png" />
					Interests
				</span>

				<div>
                    <?php
                        $customPostTaxonomies = get_object_taxonomies('cpt_interest');

                        if(count($customPostTaxonomies) > 0)
                        {
                            foreach($customPostTaxonomies as $tax)
                            {
                                $int_cat_args = array(
                                    'orderby' => 'name',
                                    'show_count' => 0,
                                    'pad_counts' => 0,
                                    'hierarchical' => 1,
                                    'taxonomy' => $tax,
                                    'title_li' => '',
                                    'hide_empty' => true
                                );
                                $categories = get_categories( $int_cat_args );
                            }
                            foreach ( $categories as $category ) {

                                /*$has_interest_args = array(
                                    'post_type' = 'cpt_program',
                                    'meta_query' => array(
                                        'key' => 'associated_interests',
                                        'value' => '"' . $category->cat_ID . '"',
                                        'compare' => 'LIKE'
                                    )
                                )
                                $has_interest = get_posts( $has_interest_args );*/

                                echo '<div class="interest-container"><a class="interest-title">' . $category->name . '</a>';
                                global $post; 
                                $interest_args = array( 
                                    'numberposts' => -1, 
                                    'post_type' => 'cpt_interest',
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'interest_type',
                                            'terms' => $category->cat_ID,
                                        ),
                                    ),
                                    'orderby' => 'title',
                                    'order' => 'ASC'
                                );  
                                    // 'category' => $category->cat_ID ); 
                                $interests_posts = get_posts($interest_args);
                                echo '<div class="hide-interests">';
                                $i = 1;
                                foreach( $interests_posts as $post ) : setup_postdata($post); ?>
                                    <div>
                                        <label for="ai<?php echo _e( $i, 'asapkids' );  ?>">
                                            <input id="ai<?php echo _e( $i, 'asapkids' );  ?>" type="checkbox" value="<? echo $post->ID; ?>" name="ai[]" <?php if ( in_array( $post->ID, $st_in ) ) echo "checked"; ?>><?php the_title(); ?>
                                        </label>
                                    </div>
                                <?php $i++; ?>
                                <?php endforeach;
                                echo '</div></div>';
                                wp_reset_postdata();
                            }
                        }
                    ?>
	        	</div>
		    </li>

            <li class="dark-orange"><h4 class="menu-divider">Additional Filters</h4></li>

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
                
                <?php //update_field( 'field_553fcaeb840ce', array( $dow ), $_GET['st'] ); ?>

                <ul>
                    <li><label for="dow2"><input id="dow2" type="checkbox" value="mon" name="dow[]" <?php if ( in_array( "mon", $st_da ) ) echo 'checked' ?>>Monday</label></li>
                    <li><label for="dow3"><input id="dow3" type="checkbox" value="tues" name="dow[]" <?php if ( in_array( "tues", $st_da ) ) echo 'checked' ?>>Tuesday</label></li>
                    <li><label for="dow4"><input id="dow4" type="checkbox" value="wed" name="dow[]" <?php if ( in_array( "wed", $st_da ) ) echo 'checked' ?>>Wednesday</label></li>
                    <li><label for="dow5"><input id="dow5" type="checkbox" value="thur" name="dow[]" <?php if ( in_array( "thur", $st_da ) ) echo 'checked' ?>>Thursday</label></li>
                    <li><label for="dow6"><input id="dow6" type="checkbox" value="fri" name="dow[]" <?php if ( in_array( "fri", $st_da ) ) echo 'checked' ?>>Friday</label></li>
                    <li><label for="dow7"><input id="dow7" type="checkbox" value="sat" name="dow[]" <?php if ( in_array( "sat", $st_da ) ) echo 'checked' ?>>Saturday</label></li>
                    <li><label for="dow1"><input id="dow1" type="checkbox" value="sun" name="dow[]" <?php if ( in_array( "sun", $st_da ) ) echo 'checked' ?>>Sunday</label></li>
                </ul>
                
            </li>

            <li>
                <span>
                   <img src="<?php echo get_template_directory_uri(); ?>/images/price.png" />
                    Price
                </span>
                <ul>
                    <li>
                        <select name="pr" id="select-price">
                            <option value="">Any Price</option>
                            <option value="25">$25 or Less</option>
                            <option value="50">$50 or Less</option>
                            <option value="100">$100 or Less</option>
                            <option value="200">$200 or Less</option>
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
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo get_template_directory_uri().'/images/asapkids-logo.png'; ?>" title="ASAPK!DS" alt="ASAPK!DS"></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div><!-- .site-branding --><?php get_search_form(); ?><nav id="site-navigation" class="main-navigation" role="navigation">
				<!--<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">--><?php //esc_html_e( 'Primary Menu', 'asapkids' ); ?><!--</button>-->
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
						<!--<li><a class="fa fa-th-large" href="#"></a></li>-->
						<li><a id="list-view" class="clicked" href="#"><img src="<?php echo get_template_directory_uri().'/images/list-view.png'; ?>" width="40" height="40" title="List View" alt="List View"></a></li>
						<li><a id="map-view" href="#"><img src="<?php echo get_template_directory_uri().'/images/map-view.png'; ?>" width="40" height="40" title="Map View" alt="Map View"></a></li>
					</ul>
				</div>
				<!--<div class="asapkids-search-info-select">
					<select name="asapkids-sort">
						<option value="location">Location</option>
						<option value="date">Date</option>
						<option value="price">Price</option>
					</select>
				</div>-->
			</div>
		<?php } ?>
		
		<div id="content" class="site-content">