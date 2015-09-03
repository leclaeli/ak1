<?php
/**
 * The template for displaying all cpt_program custom post type.
 *
 * @package asapkids
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<div class="back-to-results"><a href="#">Back to results</a></div>
		<?php while ( have_posts() ) : the_post(); ?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( sprintf( '<h1 class="entry-title">', esc_url( get_permalink() ) ), '</h1>' ); ?>
				</header><!-- .entry-header -->
			
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
					
					<ul>
						<li>
							<?php 
								$org_id = get_field('prog_organization')[0];	
								echo get_the_title($org_id); 
							?>
						</li>
					</ul>
					
					<ul>
						<li>Ages: <?php echo get_field('prog_age_min'). ' - ' .get_field('prog_age_max'); ?></li>
						<li>Date(s): <?php echo date("m/d/y", strtotime(get_field('prog_date_start'))). ' - ' .date("m/d/y", strtotime(get_field('prog_date_end'))); ?></li>
						<li>Days: <?php echo implode( ", ", get_field('prog_days_offered')); ?></li>
						<li>Ongoing: <?php if(get_field('prog_ongoing')) { echo 'Yes'; } else { echo 'No'; } ?></li>
						<li>Level(s): <?php echo implode( ", ", get_field('prog_activity_level')); ?></li>
						<li>Cost: <?php echo money_format('$%i', get_field('prog_cost')); ?></li>
						<li>Location: <?php echo get_field('prog_location')['address']; ?></li>
					</ul>
					
				</div><!-- .entry-content -->
			</article><!-- #post-## -->

		<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>