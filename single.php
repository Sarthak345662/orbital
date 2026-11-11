<?php
/**
 * The template for displaying all single posts
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
	get_template_part('template-parts/single/content', 'single');
endwhile;

get_footer();

