<?php
/**
 * Social Shares
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

/*
 * Print Content Social
 */

if (! function_exists('orbital_social_share_content')) :

	function orbital_social_share_content()
	{
		get_template_part('template-parts/social/content', 'social');
	}

endif;

/*
 * Print Content Social Fixed Bottom
 */

if (! function_exists('orbital_social_share_fixed_bottom')) :

	function orbital_social_share_fixed_bottom()
	{
		if (! orbital_customize_option('orbital_social_fixed_bottom_enable')) {
			return;
		}

		if (is_singular() && ! orbital_get_option_page('social')) {
			return;
		}

		get_template_part('template-parts/social/content', 'social-fixed-bottom');
	}

endif;

/*
 * Print Content Social Fixed Side
 */

if (! function_exists('orbital_social_share_fixed_side')) :

	function orbital_social_share_fixed_side()
	{
		if (! orbital_customize_option('orbital_social_fixed_side_enable')) {
			return;
		}

		if (is_singular() && ! orbital_get_option_page('social')) {
			return;
		}

		get_template_part('template-parts/social/content', 'social-fixed-side');
	}

endif;

/*
 * Print Content Social Before Content
 */

if (! function_exists('orbital_social_share_before_content')) :

	function orbital_social_share_before_content()
	{
		if (! orbital_customize_option('orbital_social_before_content_enable')) {
			return;
		}

		if (is_singular() && ! orbital_get_option_page('social')) {
			return;
		}

		get_template_part('template-parts/social/content', 'social-before-content');
	}

endif;

/*
 * Print Content Social After Content
 */

if (! function_exists('orbital_social_share_after_content')) :

	function orbital_social_share_after_content()
	{
		if (! orbital_customize_option('orbital_social_after_content_enable')) {
			return;
		}

		if (is_singular() && ! orbital_get_option_page('social')) {
			return;
		}

		get_template_part('template-parts/social/content', 'social-after-content');
	}

endif;


/*
 * Check Social Share
 */

if (! function_exists('orbital_check_social_template')) :

	function orbital_check_social_template($position)
	{

		if (is_single() && strpos(orbital_customize_option($position), 'post') !== false) {
			return false;
		}

		if ((is_home() || is_front_page()) && strpos(orbital_customize_option($position), 'home') !== false) {
			return false;
		}

		if ((is_page() && !is_front_page()) && strpos(orbital_customize_option($position), 'page') !== false) {
			return false;
		}

		if (is_archive() && strpos(orbital_customize_option($position), 'archive') !== false) {
			return false;
		}

		return true;
	}

endif;
