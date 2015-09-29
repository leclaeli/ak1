<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package asapkids
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php 
			//Added this to change the page title to "Update Profile" instead of "Sign Up" when a logged in user is accessing the profile page
			if(is_page('sign-up') && (is_user_logged_in() && !current_user_can('manage_options'))) {
				echo '<h1 class="entry-title">Update Profile</h1>';
			} else {
				the_title( '<h1 class="entry-title">', '</h1>' ); 
			}
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'asapkids' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php edit_post_link( esc_html__( 'Edit', 'asapkids' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->