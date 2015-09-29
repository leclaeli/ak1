<?php
/*
Template Name: The Home Page
*/
?>

<?php get_header(); ?>

<div id="mast-header">
	<img src="<?php echo get_template_directory_uri().'/images/homepage-photo.jpg'; ?>"/>
	<div class="main-cta">
		<h1>Find the right activity today</h1>
		<?php get_search_form(); ?>
	</div>
</div>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div id="recently-featured">
			
		<?php $args = array(
			'post_type'	=> 'cpt_program',
		); 
		?>
		
		<?php $activity = new WP_Query($args);
			if ($activity->have_posts()) :
			while($activity->have_posts()) :
			$activity->the_post();
			$org_id = get_field('prog_organization')[0];
			$featured = get_field('prog_featured');

			if ($featured){ ?>
							
				<div>
				<h3><a href="#" class="org"><?php echo get_the_title($org_id); ?></a> featured <a href="<?php the_permalink();?>" class="activity"><?php the_title();?></a></h3>
				</div>

			<?php } ?>

		<?php endwhile; endif; wp_reset_postdata();?>
		
	</div>
<?php endwhile; endif; ?>

<div class="information">

<div class="row three-columns">
	<h2>How it works</h2>
	<hr class="mini" align="center" />

	<div class="column column1">
	<span class="circle">1</span>
	<img src="<?php echo get_template_directory_uri().'/images/orange-icons/create-profile.png';?>"/>
	<h4>Create Student Profiles</h4>
	<p><?php the_field('paragraph_1');?></p>
	</div>

	<div class="column column2">
	<span class="circle">2</span>
	<img src="<?php echo get_template_directory_uri().'/images/orange-icons/curated-list.png';?>"/>
	<h4>Receive Curated Results</h4>
	<p><?php the_field('paragraph_2');?></p>
	</div>

	<div class="column column3">
	<span class="circle">3</span>
	<img src="<?php echo get_template_directory_uri().'/images/orange-icons/sign-up.png';?>"/>
	<h4>Sign Your Students Up</h4>
	<p><?php the_field('paragraph_3');?></p>
	</div>
</div> <!-- row -->

</div><!-- information -->

<div id="newsletter" class="row">
	<h2>Sign up for our newsletter</h2>
	<hr class="mini" align="center" />
</div>

<?php get_footer(); ?>