<?php
/**
 * The template for displaying the add/edit student profile page.
 *
 * This is the template that displays the add/edit student profile page.
 *
 * @package asapkids
 */

acf_form_head();

get_header();

//default values to create new student custom post type
$post_id = 'new';
$verbage = 'Add Student';

//check if a post_id is passed in the url ("student=123")
if( isset($_GET['st']) ) {
	//check if the user that is currently logged in is the "owner" of the student record. 
	//If they are, allow student update, if not, fail silently and force it to add student.
	$user_of_student_profile = get_post_field( 'post_author', $_GET['st'] );
	$user_logged_in = get_current_user_id();
	if( $user_of_student_profile == $user_logged_in ) {	
		$post_id = $_GET['st'];
		$verbage = 'Update Student';
	}
}

$options = array(
	/* (string) Unique identifier for the form. Defaults to 'acf-form' */
	'id' => 'asapkids-student-form',
	
	/* (int|string) The post ID to load data from and save data to. Defaults to the current post ID. 
	Can also be set to 'new' to create a new post on submit */
	'post_id' => $post_id,
	
	/* (array) An array of post data used to create a post. See wp_insert_post for available parameters.
	The above 'post_id' setting must contain a value of 'new_post' */
	'field_groups'	=> array( 75 ),
	
	'form_attributes' => array('class' => 'asapkids_form '),
	
	'post_status' => 'private',
	
	'return' => home_url('manage-students/?updated=true'),
	
	/* (string) The text displayed on the submit button */
	'submit_value' => __($verbage, 'acf')	
); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="back-to-results"><a href="#">Back to results</a></div>
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<h1 class="entry-title"><?php echo $verbage; ?></h1>
						</header><!-- .entry-header -->
						
						<?php if( isset($_GET['updated']) ) { 
							$st_id = $_GET['st']; ?>
							<div class="asapkids-student-update">
								Your student has been updated. <a href="<?php echo home_url( '?s=&st=' . $st_id ); ?>">View results</a>
							</div>
						<?php } ?>						
						
						<div class="entry-content">
							<?php acf_form($options); ?>
						</div><!-- .entry-content -->
					</article>
				<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>