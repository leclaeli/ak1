<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package asapkids
 */

?>

		</div><!-- #content -->
	</div><!-- .container-right -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="footer-left">
			<div class="asapkids-social-title">Follow Us:</div>
			<ul>
				<li><a target="_blank" href="https://www.facebook.com/asapkids.org"><img src="<?php echo get_template_directory_uri().'/images/icon-facebook.png'; ?>" width="16" height="16" alt="Facebook" title="Facebook"></a></li>
				<li><a target="_blank" href="https://twitter.com/ASAPkids"><img src="<?php echo get_template_directory_uri().'/images/icon-twitter.png'; ?>" width="16" height="16" alt="Twitter" title="Twitter"></a></li>
				<li><a target="_blank" href="https://instagram.com/asapkids.milwaukee"><img src="<?php echo get_template_directory_uri().'/images/icon-instagram.png'; ?>" width="16" height="16" alt="Instagram" title="Instagram"></a></li>
			</ul> 
		</div>
		<div class="footer-right">
			<div class="footer-site-nav">
				<?php wp_nav_menu( array('theme_location' => 'asapkids-footer-menu' )); ?>
			</div>
			<div class="site-info">
				Copyright 2015 ASAPK!DS
			</div><!-- .site-info -->
		</div><!-- .footer-right -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>