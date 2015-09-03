<?php
/**
 * The template part used for displaying search results content in search.php
 *
 * @package asapkids
 */

?>
<div class="asapkids-loading"></div>
<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<!-- <div class="asapkids-loading"></div> -->
		<?php  
			$locations = array();
			$location_titles = array();
		    $location_post_ids = array();
		    $query_ids = array();
		?>

		<?php /* Start the Loop */ ?>
		<?php if($query->have_posts()) { ?>
		
			<?php while ( $query->have_posts() ) : $query->the_post();
				$loc = new Location(); // class in functions.php
                $prog_id = get_the_id();
                
                $title = get_the_title();
		        array_push($location_titles, $title);
		        array_push($location_post_ids, $prog_id);
        
                array_push( $locations, $loc->my_location );
                array_push( $query_ids, $prog_id );
                $pinned = $loc->has_loc;
                $classes = array(
                	'program-list',
                	$pinned,
                );
			?>			
			
				<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>  class="program-list<?php echo $loc->has_loc; ?>">
					
					<?php 	
						$org_id = get_field('prog_organization')[0];
						$org_name = get_the_title($org_id);		
						$age_start = get_field('prog_age_min');
						$age_end = get_field('prog_age_max');
						$date_start = get_field('prog_date_start');
						$date_end = get_field('prog_date_end');
						$cost = get_field('prog_cost');
						$location = get_field('prog_location');
						$level = get_field('prog_activity_level'); 
						$days_offered = get_field( 'prog_days_offered' );
						$featured_prog = get_field( 'prog_featured' );
						$class_name = 'one-hundred-percent';
						
						if($featured_prog) {
							$featured_title = '</a></h1><span class="featured-title"></span><span class="featured-text">Featured</span>';
						} else {
							$featured_title = '</a></h1>';
						}
					?>	
					
					<div class="asapkids-search-result-container">
						
						<?php if ( has_post_thumbnail() ) { $class_name = 'seventy-five-percent'; ?>
							<div class="asapkids-search-content-right">
								<?php the_post_thumbnail('medium'); ?>
							</div>
						<?php } ?>
						
						<div class="asapkids-search-content-left<?php echo ' '.$class_name; ?>">
							<header class="entry-header">
								<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), $featured_title ); ?>
							</header><!-- .entry-header -->
							<div class="organization-name">
								<?php echo '<a href="'.get_permalink($org_id).'" title="'.$org_name.'">'.$org_name.'</a>'; ?>
							</div>
							<div class="entry-summary">
								<?php the_excerpt(); ?>
								
								<ul>
									<?php 
										if( $age_start ) :
											
											$age_append = "";
											
											if( $age_end ) :
												if( $age_end !== $age_start ) :
													$age_append = " - ".$age_end;
												endif;
											endif; 
									?>
										<li><img src="<?php echo get_template_directory_uri().'/images/target.png'; ?>" width="40" height="40"><span>Age(s) 
										<?php 
											echo $age_start; 
											if( $age_append ) :
												echo $age_append;
											endif;
										?>
										</span>
										</li>
									<?php endif; ?>
				
									<?php 
										if( $date_start ) : 
											
											$date_append = "";
											
											if( $date_end ) :
												if( $date_end !== $date_start ) :
													$date_append = " - ".date("m/d/y", strtotime($date_end));
												endif;
											endif;
									?>
										
											<li><img src="<?php echo get_template_directory_uri().'/images/date-black.png'; ?>" width="40" height="40"><span>
											<?php 
												echo date("m/d/y", strtotime($date_start)); 
												if( $date_append ) :
													echo $date_append;
												endif;
											?>
											</span>
											</li>
									<?php endif; ?>
					
									<?php if( $cost ) : ?> 
										<li><img src="<?php echo get_template_directory_uri().'/images/price-black.png'; ?>" width="40" height="40"><span><?php echo money_format('$%i', $cost); ?></span></li>
									<?php endif; ?>
									
									<li><img src="<?php echo get_template_directory_uri().'/images/location-black.png'; ?>" width="40" height="40"><span class="distance <?php echo $loc->has_loc; ?>">Contact organization for location.</span></li>	
									
									<?php if( $level ) : ?>
										<li><img src="<?php echo get_template_directory_uri().'/images/experience-black.png'; ?>" width="40" height="40"><span><?php echo implode( ', ', $level); ?></span></li>
									<?php endif; ?>
									<!--<li>Days:--> <?php //echo implode( ", ", $days_offered ); ?> <!--</li>-->
																	
								</ul>
								
								<div class="asapkids-program-details-button"><a href="<?php echo esc_url(get_permalink()); ?>">See Event Details</a></div>
							</div><!-- .entry-summary -->
							
							<footer class="entry-footer">
								<?php asapkids_entry_footer(); ?>
							</footer><!-- .entry-footer -->
						</div>
						<div class="asapkids-clear"></div>
					</div>
					
				</article><!-- #post-## -->
	
			<?php 
				endwhile; 
				/* Restore original Post Data */
				wp_reset_query();
			?>		
		<?php } else { ?>
			<div class="asapkids-search-result-container">No programs were found based on your search criteria. Try switching your student profile or changing the filter values in the orange search menu to the left.</div>
		<?php } ?>
	</main><!-- #main -->
	
	<?php if($query->have_posts()) { ?>
		<div id="programs-map" class="acf-map box">
		    <?php
		    $i = 0;
	        foreach ( $locations as $location ) { 
	            if ( $location != NULL ) {?>
	            <div id="<?php echo $location_post_ids[$i]; ?>" class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>">
	                <?php 
	                	$org_id = get_field( 'prog_organization', $location_post_ids[$i] );
	                	$org_title = get_the_title( $org_id[0] );
	                ?>
	                <p class="program-name"><?php echo esc_html( $location_titles[$i] ); ?></p>
	                <p class="organization-name"><a href="<?php echo esc_url( get_permalink( $org_id[0] ) ); ?>"><?php echo esc_html( $org_title ); ?></a></p>
	                <p class="address"><?php echo esc_html( $location['address'] ); ?></p>
	            </div>
	            <?php 
	            }
	            $i++;
	         }
		    ?>
	    </div>	
	<?php } ?>
</section><!-- #primary -->
<div id="content-pane">
    <div id="outputDiv"></div>
</div>