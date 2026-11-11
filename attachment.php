<?php
/**
 * The template for displaying post thumbnail
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

get_header();

while (have_posts()) :
	the_post();
	get_template_part('template-parts/single/content', 'image');
endwhile;

get_footer();
