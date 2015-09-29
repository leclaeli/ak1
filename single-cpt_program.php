<?php
/**
 * The template for displaying all cpt_program custom post type.
 *
 * @package asapkids
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php if (!empty($_SERVER["QUERY_STRING"])) { ?>
				<div class="back-to-results"><a href="<?php echo esc_url( home_url( '/?s' ) ); ?>">Back to Results</a></div>
			<?php } else { ?>
				<div class="back-to-results"><a href="<?php echo esc_url( home_url( '/?s' ) ); ?>">Go to Search</a></div>
			<?php } ?>
			
			<?php while ( have_posts() ) : the_post(); ?>
				
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php the_title( sprintf( '<h1 class="entry-title">', esc_url( get_permalink() ) ), '</h1>' ); ?>
					</header><!-- .entry-header -->
					
					<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { ADDTOANY_SHARE_SAVE_KIT(); } ?>
					
					<div class="entry-content">
						<?php
							/* translators: %s: Name of current post */
							the_content( sprintf(
								wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'asapkids' ), array( 'span' => array( 'class' => array() ) ) ),
								the_title( '<span class="screen-reader-text">"', '"</span>', false )
							) );
						?>
						
						<?php if ( has_post_thumbnail() ) { ?>
							<div class="asapkids-search-content-right">
								<?php the_post_thumbnail('medium'); ?>
							</div>
						<?php } ?>
						
						<ul class="organization">
							<li>
								<?php 
									$org_id = get_field('prog_organization')[0];	
									echo '<h3>';
									echo get_the_title($org_id); 
									echo '</h3>';
								?>
							</li>
						</ul>
						
						<ul>
							<li>Ages: <?php echo get_field('prog_age_min'). ' - ' .get_field('prog_age_max'); ?></li>
							<li>Date(s): <?php echo date("m/d/y", strtotime(get_field('prog_date_start'))). ' - ' .date("m/d/y", strtotime(get_field('prog_date_end'))); ?></li>
							<li>Days: <?php echo implode( ", ", get_field('prog_days_offered')); ?></li>
							<li>Ongoing: <?php if(get_field('prog_ongoing')) { echo 'Yes'; } else { echo 'No'; } ?></li>
							<?php if( get_field('prog_activity_level') ) : ?>
								<li>Level(s): <?php echo implode( ", ", get_field('prog_activity_level')); ?></li>
							<?php endif; ?>	
							<?php if( get_field('prog_cost') ) : ?>
								<li>Cost: <?php echo money_format('$%i', get_field('prog_cost')); ?></li>
							<?php endif; ?>
							<li>Location: 
							<?php if(get_field('prog_location')['address']) { 
								echo get_field('prog_location')['address']; 
							} else {
								echo 'Contact organization for location';
							} ?></li>
						</ul>
						
					</div><!-- .entry-content -->
				</article><!-- #post-## -->
	
			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>