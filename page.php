<?php
/**
 * The template for displaying all pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

get_header();

while (have_posts()) :
	the_post();

	get_template_part('template-parts/page/content', 'page');
endwhile;

get_footer();