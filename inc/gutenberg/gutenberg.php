<?php

function orbital_cluster_initialize()
{

	if (function_exists('register_block_type')) {
		add_action('init', 'orbital_register_clusters_block');
	}
}
add_action('init', 'orbital_cluster_initialize');

function orbital_register_clusters_block()
{
	register_block_type('orbital/clusters', array(
		'editor_script' => 'orbital-clusters-block-editor',
		'editor_style'  => 'orbital-clusters-block-editor',
	));
}

function orbital_extra_orbital_clusters_scripts()
{
	wp_enqueue_script(
		'react-transition-group',
		get_template_directory_uri() . '/inc/gutenberg/assets/js/vendor/react-transition-group.js',
		array( 'wp-blocks', 'wp-element' ),
		'2.2.1'
	);
	global $pagenow;

	$widget_editor = ( $pagenow === 'widgets.php' )?'wp-edit-widgets' :'wp-editor';
	wp_register_script(
		'orbital-clusters-block-editor',
		get_template_directory_uri() . '/inc/gutenberg/assets/js/clusters-block.js',
		array( 'wp-blocks', 'wp-element', 'react-transition-group', $widget_editor ),
		'1.1.0'
	);

	wp_enqueue_script('orbital-clusters-block-editor');

	wp_enqueue_style(
		'orbital-clusters-block-editor',
		get_template_directory_uri() . '/inc/gutenberg/assets/css/gutenberg-clusters-block.css',
		array( 'wp-edit-blocks' ),
		'1.1.0'
	);
}
add_action('enqueue_block_editor_assets', 'orbital_extra_orbital_clusters_scripts');


if (! function_exists('orbital_gutenberg_categories')) :

	function orbital_gutenberg_categories($categories, $post)
	{

		$orbital_category = array(
			'slug' => 'orbital',
			'title' => __('Orbital', 'orbital'),
		);
		array_unshift($categories, $orbital_category);
		return $categories;
	}

	add_filter('block_categories', 'orbital_gutenberg_categories', 10, 2);
endif;

require get_template_directory() . '/inc/gutenberg/parts/cluster.php';
