<?php

function orbital_performance_customizer($wp_customize)
{
	
	global $orbital_customizer_defaults;

	$wp_customize->add_panel('orbital_performances', array(
		'title' =>  __('Performance Settings', 'orbital'),
		'description' => __('Remember to test each function separately and check the website for errors.', 'orbital'),
		'priority' => 1004,
	));

	$wp_customize->add_section('orbital_performance_preload_fonts', array(
		'title' =>  __('Preload Fonts', 'orbital'),
		'panel' => 'orbital_performances',
	));
	$wp_customize->add_setting('orbital_performance_preload_fonts', array(
		'name' => 'orbital_performance_preload_fonts',
		'default' => $orbital_customizer_defaults['orbital_performance_preload_fonts'],
		'transport' => 'refresh',
		'sanitize_callback' => 'orbital_sanitize_nohtml',
	));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_performance_preload_fonts', array(
		'setting' => 'orbital_performance_preload_fonts',
		'label' => __('“Preload” the location of your resources', 'orbital'),
		'section' => 'orbital_performance_preload_fonts',
		'type' => 'textarea',
		'input_attrs' => array(
			'placeholder' => __("One per line \nEx. https://example.com/font.woff2", 'orbital'),
		)
	)));

	$wp_customize->add_section('orbital_performance_preload_styles', array(
		'title' =>  __('Preload Styles (CSS)', 'orbital'),
		'panel' => 'orbital_performances',
	));
	$wp_customize->add_setting('orbital_performance_preload_styles', array(
		'name' => 'orbital_performance_preload_styles',
		'default' => $orbital_customizer_defaults['orbital_performance_preload_styles'],
		'transport' => 'refresh',
		'sanitize_callback' => 'orbital_sanitize_nohtml',
	));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_performance_preload_styles', array(
		'setting' => 'orbital_performance_preload_styles',
		'label' => __('“Preload” the location of your resources', 'orbital'),
		'section' => 'orbital_performance_preload_styles',
		'type' => 'textarea',
		'input_attrs' => array(
			'placeholder' => __("One per line \nEx. https://example.com/style.css", 'orbital'),
		)
	)));

	$wp_customize->add_section('orbital_performance_preload_scripts', array(
		'title' =>  __('Preload Scripts (JS)', 'orbital'),
		'panel' => 'orbital_performances',
	));
	$wp_customize->add_setting('orbital_performance_preload_scripts', array(
		'name' => 'orbital_performance_preload_scripts',
		'default' => $orbital_customizer_defaults['orbital_performance_preload_scripts'],
		'transport' => 'refresh',
		'sanitize_callback' => 'orbital_sanitize_nohtml',
	));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_performance_preload_scripts', array(
		'setting' => 'orbital_performance_preload_scripts',
		'label' => __('“Preload” the location of your resources', 'orbital'),
		'section' => 'orbital_performance_preload_scripts',
		'type' => 'textarea',
		'input_attrs' => array(
			'placeholder' => __("One per line \nEx. https://example.com/script.css", 'orbital'),
		)
	)));

	$wp_customize->add_section('orbital_performance_preload_images', array(
		'title' =>  __('Preload Images', 'orbital'),
		'panel' => 'orbital_performances',
	));
	$wp_customize->add_setting('orbital_performance_preload_images', array(
		'name' => 'orbital_performance_preload_images',
		'default' => $orbital_customizer_defaults['orbital_performance_preload_images'],
		'transport' => 'refresh',
		'sanitize_callback' => 'orbital_sanitize_nohtml',
	));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_performance_preload_images', array(
		'setting' => 'orbital_performance_preload_images',
		'label' => __('“Preload” the location of your resources', 'orbital'),
		'section' => 'orbital_performance_preload_images',
		'type' => 'textarea',
		'input_attrs' => array(
			'placeholder' => __("One per line \nEx. https://example.com/image.jpg", 'orbital'),
		)
	)));

	$wp_customize->add_section('orbital_performance_preload_embed', array(
		'title' =>  __('Preload Embed (iframes)', 'orbital'),
		'panel' => 'orbital_performances',
	));
	$wp_customize->add_setting('orbital_performance_preload_embed', array(
		'name' => 'orbital_performance_preload_embed',
		'default' => $orbital_customizer_defaults['orbital_performance_preload_embed'],
		'transport' => 'refresh',
		'sanitize_callback' => 'orbital_sanitize_nohtml',
	));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_performance_preload_embed', array(
		'setting' => 'orbital_performance_preload_embed',
		'label' => __('“Preload” the location of your resources', 'orbital'),
		'section' => 'orbital_performance_preload_embed',
		'type' => 'textarea',
		'input_attrs' => array(
			'placeholder' => __("One per line \nEx. https://example.com/videoembed/", 'orbital'),
		)
	)));


	$wp_customize->add_section('orbital_performance_preconnect', array(
		'title' =>  __('Preconnect (URL)', 'orbital'),
		'panel' => 'orbital_performances',
	));
	$wp_customize->add_setting('orbital_performance_preconnect', array(
		'name' => 'orbital_performance_preconnect',
		'default' => $orbital_customizer_defaults['orbital_performance_preconnect'],
		'transport' => 'refresh',
		'sanitize_callback' => 'orbital_sanitize_nohtml',
	));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_performance_preconnect', array(
		'setting' => 'orbital_performance_preconnect',
		'label' => __('“Preconnect” the location of your resources', 'orbital'),
		'section' => 'orbital_performance_preconnect',
		'type' => 'textarea',
		'input_attrs' => array(
			'placeholder' => __("One url per line\nEx. https://example.com/font.woff2", 'orbital'),
		),
		
	)));

	$wp_customize->add_section('orbital_performance_prefetch', array(
		'title' =>  __('Prefetch (Domain)', 'orbital'),
		'panel' => 'orbital_performances',
	));
	$wp_customize->add_setting('orbital_performance_prefetch', array(
		'name' => 'orbital_performance_prefetch',
		'default' => $orbital_customizer_defaults['orbital_performance_prefetch'],
		'transport' => 'refresh',
		'sanitize_callback' => 'orbital_sanitize_nohtml',
	));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_performance_prefetch', array(
		'setting' => 'orbital_performance_prefetch',
		'label' => __('“Prefetch” the location of your resources', 'orbital'),
		'section' => 'orbital_performance_prefetch',
		'type' => 'textarea',
		'input_attrs' => array(
			'placeholder' => __("One domain per line. Format //example.com \nEx. //fonts.googleapis.com", 'orbital'),
		),
	)));

	$wp_customize->add_section('orbital_performance_rendering', array(
		'title' =>  __('Fix Render Blocking', 'orbital'),
		'panel' => 'orbital_performances',
	));

	$wp_customize->add_setting('orbital_performance_render_blocking_css', array(
		'name' => 'orbital_performance_render_blocking_css',
		'default' => $orbital_customizer_defaults['orbital_performance_render_blocking_css'],
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_performance_render_blocking_css', array(
		'setting' => 'orbital_performance_render_blocking_css',
		'label' => __('Fix Render Blocking CSS', 'orbital'),
		'section' => 'orbital_performance_rendering',
		'type' => 'checkbox',
	)));

	$wp_customize->add_setting('orbital_performance_render_blocking_js', array(
		'name' => 'orbital_performance_render_blocking_js',
		'default' => $orbital_customizer_defaults['orbital_performance_render_blocking_js'],
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_performance_render_blocking_js', array(
		'setting' => 'orbital_performance_render_blocking_js',
		'label' => __('Fix Render Blocking JS', 'orbital'),
		'section' => 'orbital_performance_rendering',
		'type' => 'checkbox',
	)));

	$wp_customize->add_setting('orbital_performance_render_blocking_jquery', array(
		'name' => 'orbital_performance_render_blocking_jquery',
		'default' => $orbital_customizer_defaults['orbital_performance_render_blocking_jquery'],
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_performance_render_blocking_jquery', array(
		'setting' => 'orbital_performance_render_blocking_jquery',
		'label' => __('Exclude jQuery on Fix Render Blocking', 'orbital'),
		'section' => 'orbital_performance_rendering',
		'type' => 'checkbox',
	)));	

	$wp_customize->add_setting('orbital_performance_lazy_load', array(
		'name' => 'orbital_performance_lazy_load',
		'default' => $orbital_customizer_defaults['orbital_performance_lazy_load'],
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_performance_lazy_load', array(
		'setting' => 'orbital_performance_lazy_load',
		'label' => __('Lazy Load (Experimental)', 'orbital'),
		'section' => 'orbital_performance_rendering',
		'description' => __('It is necessary to regenerate all miniatures', 'orbital'),
		'type' => 'checkbox',
	)));

	$wp_customize->add_section('orbital_performance_quicklink', array(
		'title' =>  __('Quick Link', 'orbital'),
		'panel' => 'orbital_performances',
	));

	$wp_customize->add_setting('orbital_quicklink_active', array(
		'name' => 'orbital_quicklink_active',
		'default' => $orbital_customizer_defaults['orbital_quicklink_active'],
		'transport' => 'refresh',
		'sanitize_callback' => 'orbital_sanitize_checkbox',
	));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_quicklink_active', array(
		'setting' => 'orbital_quicklink_active',
		'label' => __('Enable Quicklink', 'orbital'),
		//'description' => __('', 'orbital'),
		'section' => 'orbital_performance_quicklink',
		'type' => 'checkbox',
	)));

	$wp_customize->add_setting('orbital_quicklink_default_urls', array(
		'name' => 'orbital_quicklink_default_urls',
		'default' => $orbital_customizer_defaults['orbital_quicklink_default_urls'],
		'transport' => 'refresh',
		'sanitize_callback' => 'orbital_sanitize_nohtml',
	));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_quicklink_default_urls', array(
		'setting' => 'orbital_quicklink_default_urls',
		'label' => __('Default Prefetch URLs', 'orbital'),
		'section' => 'orbital_performance_quicklink',
		'type' => 'textarea',
		'input_attrs' => array(
			'placeholder' => __('One per line', 'orbital'),
		),
	)));
	
}
