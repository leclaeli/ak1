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
	<div id="recently-added">
		<h3><a href="#" class="org">Community Youth Engagement Center</a> recently added <a href="#" class="activity">Girls Basketball</a></h3>
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
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam porta dui sed nibh elementum pharetra. Ut urna tortor, maximus non porta sit amet, sollicitudin ut tellus.</p>
	</div>

	<div class="column column2">
	<span class="circle">2</span>
	<img src="<?php echo get_template_directory_uri().'/images/orange-icons/curated-list.png';?>"/>
	<h4>Receive Curated Results</h4>
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam porta dui sed nibh elementum pharetra. Ut urna tortor, maximus non porta sit amet, sollicitudin ut tellus.</p>
	</div>

	<div class="column column3">
	<span class="circle">3</span>
	<img src="<?php echo get_template_directory_uri().'/images/orange-icons/sign-up.png';?>"/>
	<h4>Sign Your Students Up</h4>
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam porta dui sed nibh elementum pharetra. Ut urna tortor, maximus non porta sit amet, sollicitudin ut tellus.</p>
	</div>
</div> <!-- row -->

<div class="row two-columns">
	<h2>Available Activities</h2>
	<hr class="mini" align="center" />

	<div class="column column1">
		<ul>
			<li><img src="<?php echo get_template_directory_uri().'/images/orange-icons/sports.png';?>"/>Sports</li>
			<li><img src="<?php echo get_template_directory_uri().'/images/orange-icons/music.png';?>"/>Music</li>
			<li><img src="<?php echo get_template_directory_uri().'/images/orange-icons/art.png';?>"/>Art</li>
			<li><img src="<?php echo get_template_directory_uri().'/images/orange-icons/reading.png';?>"/>Reading</li>
			<li><img src="<?php echo get_template_directory_uri().'/images/orange-icons/math.png';?>"/>Math</li>
		</ul>
	</div>

	<div class="column column2">
		<ul>
			<li><img src="<?php echo get_template_directory_uri().'/images/orange-icons/outdoors.png';?>"/>Outdoors</li>
			<li><img src="<?php echo get_template_directory_uri().'/images/orange-icons/games.png';?>"/>Gaming</li>
			<li><img src="<?php echo get_template_directory_uri().'/images/orange-icons/cooking.png';?>"/>Cooking</li>
			<li><img src="<?php echo get_template_directory_uri().'/images/orange-icons/photography.png';?>"/>Photography</li>
			<li><img src="<?php echo get_template_directory_uri().'/images/orange-icons/science.png';?>"/>Science</li>
		</ul>
	</div>
</div><!-- row -->

</div><!-- information -->

<div id="newsletter" class="row">
	<h2>Sign up for our newsletter</h2>
	<hr class="mini" align="center" />
</div>

<?php get_footer(); ?>