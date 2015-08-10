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
			$org_website = get_field( 'org_website' );
			$org_ages = get_field( 'org_ages' );
			$org_contact_name = get_field( 'org_contact_name' );
			$org_phone = get_field( 'org_phone' );
			$org_contact_email = get_field( 'org_contact_email' );
			$org_address = get_field( 'org_address' );
		?>
		<div>
			<ul>
				<?php if ( $org_website ) ?>
					<li> <?php echo '<a href="' . esc_url( $org_website ) . '">' . esc_html( $org_website ) . '</a>' ; ?> </li>
				<?php if ( $org_ages ) { ?>
					<li><?php echo esc_html( $org_ages ); ?></li>
				<?php } if ( $org_contact_name ) { ?>
					<li><?php echo esc_html( $org_contact_name ); ?></li>
				<?php } if ( $org_phone ) { ?>
					<li><?php echo esc_html( $org_phone ); ?> </li>
				<?php } if ( $org_contact_email ) { ?>
					<li><?php echo esc_html( $org_contact_email ); ?></li>
				<?php } ?>
			</ul>
		</div>

		<div>
			<h2>Programs</h2>
			<?php 
				$org_id = get_the_id();
				$prog_args = array(
					'post_type' => 'cpt_program',
					'meta_query' => array(
						array(
							'key' => 'prog_organization',
							'value' => '"' . $org_id . '"',
	                		'compare' => 'LIKE'
                		)
					),
				);

				$org_progs = get_posts( $prog_args );
				//setup_postdata( $org_progs );
				echo '<ul>';
				foreach ($org_progs as $post ) { ?>
					<li><a href="<?php esc_url( the_permalink() ); ?>"><?php esc_html( the_title() ); ?></a></li>
			<?php		
				}
				echo '</ul>';
				//wp_reset_postdata();
			?>
		</div>
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