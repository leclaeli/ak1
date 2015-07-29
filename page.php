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
		
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // End of the loop. ?>

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
			    	echo '<div class="interest-container"><a class="interest-title">' . $category->name . '</a>';
			    	global $post; 
					$args = array( 
						'numberposts' => -1, 
						'post_type' => 'cpt_interest',
						'tax_query' => array(
							array(
								'taxonomy' => 'interest_type',
								'terms' => $category->cat_ID,
							),
						),
					);	
						// 'category' => $category->cat_ID ); 
					$posts = get_posts($args);
					echo '<ul class="hide-interests">';
					foreach( $posts as $post ) : setup_postdata($post); ?>
						<li><label for="ai"><input id="ai" type="checkbox" value="<? echo $post->ID; ?>" name="ai[]"><?php the_title(); ?></label></li>
					<?php endforeach;
					echo '</ul></div>';
					wp_reset_postdata();
			    }
			}
		?>
		<!-- <img src="' . get_template_directory_uri() . '/images/plus-sign.svg" /> -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
