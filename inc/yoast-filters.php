<?php
/**
 * Custom functionality for Yoast SEO Plugin
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

/*
 * Check if Yoast SEO plugin is installed
 */

if (! function_exists('orbital_check_yoast_seo')) :

	function orbital_check_yoast_seo()
	{

		if (defined('WPSEO_VERSION')) {
			return true;
		} else {
			return false;
		}
	}

endif;


/*
 * Check if singular has noindex option
 */

if (! function_exists('orbital_check_noindex')) :

	function orbital_check_noindex()
	{

		$post_meta_noindex = get_post_meta(get_the_ID(), '_yoast_wpseo_meta-robots-noindex', true);
		$global_meta_noindex = get_option('wpseo_titles', true);

		if (!empty($post_meta_noindex)) {
			switch ($post_meta_noindex) {
				case 2:
				return false;
				break;

				case 1:
				return true;
				break;

				default:
				return $global_meta_noindex['noindex-product'];
				break;
			}
		} else {
			return $global_meta_noindex['noindex-product'];
		}
		return false;
	}

endif;


/*
 * Add variable with post count on archives with Yoast SEO
 */

if (! function_exists('orbital_title_count')) :

	function orbital_title_count()
	{

		$title = '';

		if (is_category()) {
			$category = get_category(get_query_var('cat'));
			$count = $category->category_count;
			$title = $count;
		}

		if (is_tag()) {
			$tag = get_term_by('slug', get_query_var('tag'), 'post_tag');
			$count = $tag->count;
			$title = $count;
		}

		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			if (is_product_category()) {
				$category_product = get_term_by('slug', get_query_var('product_cat'), 'product_cat');
				$count = $category_product->count;
				$title = $count;
			}
		}

		return $title;
	}

endif;


/*
 * Generate variable %%orbital_tax_count%%
 */

if (! function_exists('orbital_title_replacements')) :

	function orbital_title_replacements()
	{
		wpseo_register_var_replacement('orbital_tax_count', 'orbital_title_count', 'advanced');
	}

endif;


/*
 * Add Breadcrumbs from Yoast SEO
 */

if (! function_exists('orbital_yoast_breadcrumbs')) :

	function orbital_yoast_breadcrumbs()
	{

		if (function_exists('yoast_breadcrumb')) {
			yoast_breadcrumb('<div class="breadcrumbs"><nav>', '</nav></div>');
		}
	}

endif;
