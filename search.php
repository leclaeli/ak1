<?php
/**
 * The template for displaying search results pages.
 *
 * @package asapkids
 */

get_header('filter'); ?>

<?php require_once( 'inc/asap-query.php' ); ?>

<?php require_once( 'inc/search-results.php' ); ?>

<?php get_footer(); ?>