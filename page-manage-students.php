<?php
/**
 * The template for displaying the manage student profiles page.
 *
 * This is the template that displays the manage student profiles page.
 *
 * @package asapkids
 */

get_header(); 

$add_student = get_page_by_title( 'Add Student' );
$students = '';
$student_list = '';

if(is_user_logged_in()) {
	$current_user = wp_get_current_user();
	
	$args = array('post_type' => 'cpt_student', 'post_status' => 'private', 'author' => $current_user->ID, 'posts_per_page' => -1 );
	$students = get_posts($args);
	
	if($students) {
		foreach($students as $id) {
			$student_name = get_field('student_name', $id->ID);
			$url = home_url('/') .'add-student/?st='. $id->ID;
			$delete_url = get_delete_post_link($id->ID);

			if($student_name) {
				$student_list.='<li><a href="'.$url.'">Edit '.$student_name.'</a></li>';
				$student_list.='<li><a onclick="return confirm(\'Are you sure you want to delete this student?\')" href="'.$delete_url.'&student=student'.'">Delete '.$student_name.'</a></li>';
			}
		}
	}	
}
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="back-to-results"><a href="<?php echo esc_url( home_url( '/?s' ) ); ?>">Go to Search</a></div>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php while ( have_posts() ) : the_post(); ?>

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->
				
				<div class="entry-content">
					<?php if( isset($_GET['updated']) ) { ?>
						<div class="asapkids-student-update">
							Your student has been updated.
						</div>
					<?php } ?>
				
					<p>Get started by creating a student profile. This allows you to easily load program results that are catered specifically to the settings you create in  your student profile. Create as many as you like and easily switch between them in the orange filter menu.</p>
					<ul>
						<li>
							<a class="button" href="<?php echo get_page_link($add_student->ID); ?>" title="Add New Student">Add New Student</a>
						</li>
					</ul>
					
					<?php if($student_list) { ?>
						<ul>
							<?php echo $student_list; ?>
						</ul>
					<?php } ?>
				</div><!-- .entry-content -->
					
			<?php endwhile; // End of the loop. ?>
			
			</article>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>