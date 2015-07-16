<?php
/**
 * The template for displaying search results pages.
 *
 * @package asapkids
 */

get_header(); ?>

<?php require_once( 'inc/asap-query.php' ); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
	
			<?php $query = new WP_Query( $args ); ?>
	
			<?php /* Start the Loop */ ?>
			
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					
					<?php 
						$current_user = wp_get_current_user();
						$user_id = $current_user->ID;
						$user_address = get_user_meta($user_id, 'address', true);					
						
						if($user_address) { 
							$address = str_replace(" ", "+", $user_address);
						
						    $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false");
						    $json = json_decode($json);
						
						    $user_lat 	= 	$json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
						    $user_lng 	= 	$json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
						    $latlong 	= 	$user_lat.','.$user_lng;
						    $map        =   explode(',' ,$latlong);
					        $user_lat   =   $map[0];
					        $user_lng   =   $map[1];
					        $lat = get_field('prog_location')['lat'];
							$lng = get_field('prog_location')['lng'];		
							
							if($lat && $lng) {
								$theta = $user_lng - $lng;
								$dist = sin(deg2rad($user_lat)) * sin(deg2rad($lat)) +  cos(deg2rad($user_lat)) * cos(deg2rad($lat)) * cos(deg2rad($theta));
								$dist = acos($dist);
								$dist = rad2deg($dist);
								$miles = $dist * 60 * 1.1515;
							}
						}   	
				
				
						$org_id = get_field('prog_organization')[0]->ID;		
						$age_start = get_field('prog_age_min');
						$age_end = get_field('prog_age_max');
						$date_start = get_field('prog_date_start');
						$date_end = get_field('prog_date_end');
						$cost = get_field('prog_cost');
						$location = get_field('prog_location');
						$level = implode(', ', get_field('prog_activity_level'));
						$days_offered = get_field( 'prog_days_offered' );
					?>	
					
					<div class="asapkids-search-result-container">
						
						<?php if ( has_post_thumbnail() ) { ?>
							<div class="asapkids-search-content-right">
								<?php the_post_thumbnail('medium'); ?>
							</div>
						<?php } ?>
						
						<div class="asapkids-search-content-left">
							<header class="entry-header">
								<?php the_title( sprintf( '<h1 class="entry-title"><i class="fa fa-trophy"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
							</header><!-- .entry-header -->
						
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
										<li><i class="fa fa-bullseye"></i>Age(s) 
										<?php 
											echo $age_start; 
											if( $age_append ) :
												echo $age_append;
											endif;
										?>
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
										
											<li><i class="fa fa-calendar"></i>
											<?php 
												echo date("m/d/y", strtotime($date_start)); 
												if( $date_append ) :
													echo $date_append;
												endif;
											?>
											</li>
									<?php endif; ?>
					
									<?php if( $cost ) : ?> 
										<li><i class="fa fa-money"></i><?php echo money_format('$%i', $cost); ?></li>
									<?php endif; ?>
									
									<?php if( $location && $user_address && $lat && $lng ) : ?>
										<li><i class="fa fa-map-marker"></i><?php echo round($miles, 2) . ' miles'; ?></li>
									<?php endif; ?>
									
									<?php if( $level ) : ?>
										<li><i class="fa fa-star-o"></i><?php echo $level; ?></li>
									<?php endif; ?>
									<li>Days: <?php echo implode( ", ", $days_offered ); ?> </li>
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

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>