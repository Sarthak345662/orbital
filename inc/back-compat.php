<?php
/**
 * Back Compatibility
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

add_action('after_switch_theme', 'orbital_switch_theme');
add_action('load-customize.php', 'orbital_customize');
add_action('template_redirect', 'orbital_preview');

function orbital_switch_theme()
{
	switch_theme(WP_DEFAULT_THEME);
	unset($_GET['activated']);
	add_action('admin_notices', 'orbital_upgrade_notice');
}

function orbital_upgrade_notice()
{
	$message = sprintf(__('Orbital requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'orbital'), $GLOBALS['wp_version']);
	printf('<div class="error"><p>%s</p></div>', $message);
}

function orbital_customize()
{
	wp_die(sprintf(__('Orbital requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'orbital'), $GLOBALS['wp_version']), '', array(
		'back_link' => true,
	));
}

function orbital_preview()
{
	if (isset($_GET['preview'])) {
		wp_die(sprintf(__('Orbital requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'orbital'), $GLOBALS['wp_version']));
	}
}
