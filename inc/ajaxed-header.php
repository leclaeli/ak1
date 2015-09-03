<?php 
/*** RDK 2015072901: testing code to load student profiles as default filters ***/

if(is_user_logged_in()) {
	$user = wp_get_current_user();

	$args = array('post_type' => 'cpt_student', 'post_status' => 'private', 'author' => $user->ID, 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC');
	$students = get_posts($args);
}

if ( isset( $_POST['st'] ) && $_POST['st'] != "" ) {
	$st_id = $_POST['st'];
	$st_age = asapkids_get_student_age( $st_id );
	$st_di = get_field( 'student_distance', $st_id);
	$st_ex = get_field( 'student_experience', $st_id );
	$st_ins = get_field( 'student_interests', $st_id );
	$st_in = ( $st_ins ) ? $st_ins : array();
	$st_da = get_field( 'student_days_desired', $st_id );
	$st_name = get_field( 'student_name', $st_id );
} else {
	$st_id = "";
	$st_age = ( $_POST['age'] != "" ? $_POST['age'] : "" );
	$st_pr = ( $_POST['pr'] != "" ? $_POST['pr'] : "" );
	$st_di = ( $_POST['di'] != "" ? $_POST['di'] : null );
	$st_ex = ( $_POST['ex'] != "" ? $_POST['ex'] : array() );
	$st_in = ( $_POST['ai'] != "" ? $_POST['ai'] : array() );
	$st_da = ( $_POST['dow'] != "" ? $_POST['dow'] : array() );
	$st_name = "Custom Search";
}
?>

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
					<span class="asapkids-student-name"><?php echo $st_name; ?></span>
				</span>
				<ul>
					<?php if(is_user_logged_in()) { ?>
						<?php if ( count( $students ) > 0) { ?>
							<select name="st" id="select-student">
								<option value="" <?php if ( $st_id == "" ) echo "selected"; ?>> -- Select a Student -- </option>
								<?php
									//$post_st = $_GET['st'];
									foreach($students as $id) {
										$student_name = get_field('student_name', $id->ID);
										echo '<option value="'.$id->ID.'"' . ( $id->ID == $st_id ? "selected" : "" ) . '>'.get_field('student_name', $id->ID).'</option>';
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
						<input type="number" name="age" id="age" value="<?php echo $st_age; ?>">
					</li>
				</ul>
			</li>
	
			<li>
				<span>
					<img src="<?php echo get_template_directory_uri(); ?>/images/experience.png" />
					Experience
				</span>
				<ul>
					<li><label for="exp1"><input id="exp1" type="checkbox" value="Beginner" name="ex[]" <?php if ( in_array( "Beginner", $st_ex ) ) echo 'checked'; ?>>Beginner</label></li>
					<li><label for="exp2"><input id="exp2" type="checkbox" value="Intermediate" name="ex[]" <?php if ( in_array( "Intermediate", $st_ex ) ) echo 'checked'; ?>>Intermediate</label></li>
					<li><label for="exp3"><input id="exp3" type="checkbox" value="Advanced" name="ex[]" <?php if ( in_array( "Advanced", $st_ex ) ) echo 'checked'; ?>>Advanced</label></li>
					<li><label for="exp4"><input id="exp4" type="checkbox" value="Any" name="ex[]" <?php if ( in_array( "Any", $st_ex ) ) echo 'checked'; ?>>Any or Not Applicable</label></li>
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
									<div><label for="ai<?php echo _e( $i, 'asapkids' );  ?>"><input id="ai<?php echo _e( $i, 'asapkids' );  ?>" type="checkbox" value="<? echo $post->ID; ?>" name="ai[]"><?php the_title(); ?></label></div>
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
						<input name="sd" type="text" id="datepicker" placeholder="Select a date" />
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
							<option value="" <?php if ( $st_pr == '') echo 'selected'; ?>>Any Price</option>
							<option value="25">$25 or Less</option>
							<option value="50" <?php if ( $st_pr == '50' ) echo 'selected'; ?>>$50 or Less</option>
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