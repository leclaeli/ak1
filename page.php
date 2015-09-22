<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>