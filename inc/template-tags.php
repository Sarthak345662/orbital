<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

/*
 * Print Pagination
 */

if (! function_exists('orbital_pagination')) :

	function orbital_pagination()
	{

		if (paginate_links()) {
			echo '<div class="pagination">'. paginate_links() .'</div>';
		}
	}

endif;


/*
 * Print Subtitle to Singular Elements
 */

if (! function_exists('orbital_subtitle')) :

	function orbital_subtitle()
	{

		if (! orbital_get_option_page('subtitle', false)) {
			return;
		}

		echo '<p class="subtitle">'. orbital_get_option_page('subtitle') . '</p>';
	}

endif;


/*
 * Return name of Primary Category of Post
 */

if (! function_exists('orbital_the_category')) :

	function orbital_the_category()
	{

		$categories = get_the_category();

		if (isset($categories[0])) {
			$category_name = $categories[0]->name;
			if (class_exists('WPSEO_Primary_Term')) {
				$wpseo_primary_term = new WPSEO_Primary_Term('category', get_the_id());
				if ($wpseo_primary_term->get_primary_term()) {
					$category_name = get_the_category_by_ID($wpseo_primary_term->get_primary_term());
				}
			}

			return $category_name;
		}
	}

endif;

/*
 * Tags
 */

if (! function_exists('orbital_the_tags')) :

	function orbital_the_tags()
	{
		the_tags();
	}

endif;


/*
 * Return id of Primary Category of Post
 */

if (! function_exists('orbital_the_category_id')) :

	function orbital_the_category_id()
	{

		$categories = get_the_category();
		$category_id = $categories[0]->term_id;

		if (class_exists('WPSEO_Primary_Term')) {
			$wpseo_primary_term = new WPSEO_Primary_Term('category', get_the_id());
			if ($wpseo_primary_term->get_primary_term()) {
				$category_id = $wpseo_primary_term->get_primary_term();
			}
		}

		return $category_id;
	}

endif;


/*
 * Return link of Primary Category of Post
 */

if (! function_exists('orbital_the_category_link')) :

	function orbital_the_category_link()
	{

		$category_name = orbital_the_category();
		$category_link = get_category_link(get_cat_ID($category_name));
		return '<a href="' . esc_url($category_link) .'">'. $category_name .'</a>';
	}

endif;


/*
 * Print related posts
 */

if (! function_exists('orbital_related_posts')) :

	function orbital_related_posts()
	{
		if (! orbital_get_option_page('related') || !orbital_customize_option('orbital_posts_default_related')) {
			return;
		}
		get_template_part('template-parts/single/content', 'related');
	}

endif;


/*
 * Print meta info
 */

if (! function_exists('orbital_posted_on')) :

	function orbital_posted_on()
	{

		$byline = sprintf(__('by %s', 'orbital'), '<span class="author">' . get_the_author() . '</span>');

		if (orbital_customize_option('orbital_loop_author')) {
			echo '<span class="byline"> ' . $byline . '</span>' ;
		}

		if (orbital_customize_option('orbital_loop_date')) {
			echo ' <span class="posted-on">' . get_the_date() . '</span>';
		}
	}

endif;


/*
 * Print Thumbnail Featured Image from a Singular element
 */

if (! function_exists('orbital_thumbnail_post')) :

	function orbital_thumbnail_post()
	{

		if (has_post_thumbnail() && orbital_get_option_page('thumbnail') && orbital_customize_option('orbital_posts_default_thumbnail')) { ?>
			<div class="post-thumbnail"><?php the_post_thumbnail('large'); ?></div>

			<?php
		}
	}

endif;


/*
 * Return Logo from customize
 */

if (! function_exists('orbital_customize_logo_html')) :

	function orbital_customize_logo_html()
	{

		$orbital_custom_logo = get_theme_mod('custom_logo');
		$html = sprintf(
			'<a href="%1$s" class="custom-logo-link">%2$s</a>',
			esc_url(home_url('/')),
			wp_get_attachment_image($orbital_custom_logo, 'full', false, array(
				'class'    => 'custom-logo',
			))
		);
		return $html;
	}

endif;


/*
 * Print Custom Logo
 */

if (! function_exists('orbital_the_custom_logo')) :

	function orbital_the_custom_logo()
	{
		if (function_exists('the_custom_logo')) {
			the_custom_logo();
		}
	}

endif;


/*
 * Print Background Style for Jumbotron Header
 */

if (! function_exists('orbital_the_custom_jumbotron')) :

	function orbital_the_custom_jumbotron()
	{
		if (is_home()) {
			echo '<style>.jumbotron {background-image: url(' . get_header_image() .'); }</style>';
		} elseif (is_front_page()) {
			echo '<style>.jumbotron {background-image: url(' . get_the_post_thumbnail_url(get_option('page_for_posts'), 'full') .');}</style>';
		} elseif (is_page() || is_single()) {
			if (has_post_thumbnail()) {
				echo '<style>
				.jumbotron, .group-image {
					background-image: url(' . get_the_post_thumbnail_url(get_the_ID(), 'full') .');
				}
				</style>';
			}
		}
	}

endif;


/*
 * Print Top Category description
 */

if (! function_exists('orbital_category_top_description')) :

	function orbital_category_top_description()
	{
		if(get_queried_object()){
			echo wpautop(get_term_meta(get_queried_object()->term_id, 'cat_extra_description', true));
		}
	}

endif;
