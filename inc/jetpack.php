<?php
/**
 * Jetpack Compability
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

function orbital_jetpack_setup()
{
	add_theme_support('infinite-scroll', array(
		'container' => 'main',
		'render'    => 'orbital_infinite_scroll_render',
		'footer'    => 'page',
	));
	add_theme_support('jetpack-responsive-videos');
}


function orbital_infinite_scroll_render()
{
	while (have_posts()) {
		the_post();
		if (is_search()) {
			get_template_part('template-parts/content', 'search');
		} else {
			get_template_part('template-parts/content', get_post_format());
		}
	}
}
