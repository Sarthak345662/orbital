<?php
/**
 * The header
 *
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */


$show_header = true;
if(is_singular( array( 'post', 'page' ))){
    $show_header = orbital_get_option_page('header');
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<a class="screen-reader-text" href="#content"><?php _e('Skip to content', 'orbital'); ?></a>

	<?php if($show_header){ ?>
		<?php get_template_part('template-parts/header/header', 'image'); ?>

		<?php get_template_part('template-parts/navbar/navbar', 'top'); ?>

		<?php
		if (orbital_customize_option('orbital_layout_search_navbar')) {
			get_template_part('template-parts/search/content-search', 'navbar');
		}
	} ?>