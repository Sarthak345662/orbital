<?php

// Activar/Desactivar
// Redes Sociales
// Posts, Pages, Archives, Home, WooCommerce


function orbital_social_customizer($wp_customize)
{

	$social_networks = array(
		'facebook' => 'Facebook',
		'twitter' => 'Twitter',
		'google' => 'Google Plus',
		'tumblr' => 'Tumblr',
		'whatsapp' => 'WhatsApp',
		'email' => 'Email',
		'linkedin' => 'LinkedIn',
		'pinterest' => 'Pinterest',
		'vk' => 'Vk',
		'telegram' => 'Telegram',
	);

	$post_types = array(
		'post' => __('Posts', 'orbital'),
		'page' => __('Page', 'orbital'),
		'archive' => __('Archives', 'orbital'),
		'home' => __('Home', 'orbital'),
		'woocommerce' => __('WooCommerce', 'orbital'),
	);

    //SOCIAL PANEL

	$wp_customize->add_panel('social_panel', array(
		'title' => __('Social Settings', 'orbital'),
		'description' => __('Twitter counts need connect to <a target="_blank" href="http://newsharecounts.com/">http://newsharecounts.com/</a>', 'orbital'),
		'priority' => 1006,
	));

    //SOCIAL SECTIONS

	$wp_customize->add_section('orbital_social_fixed_bottom', array(
		'title' =>  __('Fixed Bottom', 'orbital'),
		'description' => __('Twitter counts need connect to <a target="_blank" href="http://newsharecounts.com/">http://newsharecounts.com/</a>', 'orbital'),
		'panel' => 'social_panel',
	));

	$wp_customize->add_section('orbital_social_fixed_side', array(
		'title' =>  __('Fixed Side', 'orbital'),
		'description' => __('Twitter counts need connect to <a target="_blank" href="http://newsharecounts.com/">http://newsharecounts.com/</a>', 'orbital'),
		'panel' => 'social_panel',
	));

	$wp_customize->add_section('orbital_social_before_content', array(
		'title' =>  __('Before Content', 'orbital'),
		'description' => __('Twitter counts need connect to <a target="_blank" href="http://newsharecounts.com/">http://newsharecounts.com/</a>', 'orbital'),
		'panel' => 'social_panel',
	));

	$wp_customize->add_section('orbital_social_after_content', array(
		'title' =>  __('After Content', 'orbital'),
		'description' => __('', 'orbital'),
		'panel' => 'social_panel',
	));


    // SOCIAL BOTTOM FIXED SETTINGS

	$wp_customize->add_setting('orbital_social_fixed_bottom_enable', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_fixed_bottom_social', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_fixed_bottom_post_types', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_fixed_bottom_count', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_fixed_bottom_devices', array(
		'default' => 'mobile',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

    // SOCIAL SIDE FIXED SETTINGS

	$wp_customize->add_setting('orbital_social_fixed_side_enable', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_fixed_side_social', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_fixed_side_post_types', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_fixed_side_count', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_fixed_side_devices', array(
		'default' => 'desktop',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

    // SOCIAL BEFORE CONTENT SETTINGS

	$wp_customize->add_setting('orbital_social_before_content_enable', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_before_content_social', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_before_content_post_types', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_before_content_count', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_before_content_devices', array(
		'default' => 'all',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

    // SOCIAL AFTER CONTENT SETTINGS

	$wp_customize->add_setting('orbital_social_after_content_enable', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_after_content_social', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_after_content_post_types', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_after_content_count', array(
		'default' => '',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));

	$wp_customize->add_setting('orbital_social_after_content_devices', array(
		'default' => 'all',
		'transport' => 'refresh',
		'sanitize_callback' => '',
	));


    // SOCIAL BOTTOM FIXED CONTROLS

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_social_fixed_bottom_enable', array(
		'section' => 'orbital_social_fixed_bottom',
		'label' => __('Enable Fixed Bottom Social', 'orbital'),
		'settings' => 'orbital_social_fixed_bottom_enable',
		'type' => 'checkbox',
	)));


	$wp_customize->add_control(new orbital_Customize_Misc_Control($wp_customize, 'orbital_social_fixed_bottom_social', array(
		'section'     => 'orbital_social_fixed_bottom',
		'type'        => 'multiselect',
		'label' => __('Select Social Networks', 'orbital'),
		'choices' => $social_networks,
	)));

	$wp_customize->add_control(new orbital_Customize_Misc_Control($wp_customize, 'orbital_social_fixed_bottom_post_types', array(
		'section'     => 'orbital_social_fixed_bottom',
		'type'        => 'multiselect',
		'label' => __('Post Types', 'orbital'),
		'choices' => $post_types,
	)));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_social_fixed_bottom_count', array(
		'section' => 'orbital_social_fixed_bottom',
		'label' => __('Enable Count', 'orbital'),
		'settings' => 'orbital_social_fixed_bottom_count',
		'type' => 'checkbox',
	)));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_social_fixed_bottom_devices', array(
		'section' => 'orbital_social_fixed_bottom',
		'label' => __('Device Selector', 'orbital'),
		'description' => __('If you have a cache plugin, maybe you should refresh cache.', 'orbital'),
		'settings' => 'orbital_social_fixed_bottom_devices',
		'type' => 'select',
		'choices' => array(
			'all' => __('All devices', 'orbital'),
			'mobile' => __('Only Mobile', 'orbital'),
			'desktop' => __('Only Desktop', 'orbital'),
		),

	)));

    // SOCIAL SIDE FIXED CONTROLS

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_social_fixed_side_enable', array(
		'section' => 'orbital_social_fixed_side',
		'label' => __('Enable Fixed Side Social', 'orbital'),
		'settings' => 'orbital_social_fixed_side_enable',
		'type' => 'checkbox',
	)));

	$wp_customize->add_control(new orbital_Customize_Misc_Control($wp_customize, 'orbital_social_fixed_side_social', array(
		'section'     => 'orbital_social_fixed_side',
		'type'        => 'multiselect',
		'label' => __('Select Social Networks', 'orbital'),
		'choices' => $social_networks,
	)));

	$wp_customize->add_control(new orbital_Customize_Misc_Control($wp_customize, 'orbital_social_fixed_side_post_types', array(
		'section'     => 'orbital_social_fixed_side',
		'type'        => 'multiselect',
		'label' => __('Post Types', 'orbital'),
		'choices' => $post_types,
	)));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_social_fixed_side_count', array(
		'section' => 'orbital_social_fixed_side',
		'label' => __('Enable Count', 'orbital'),
		'settings' => 'orbital_social_fixed_side_count',
		'type' => 'checkbox',
	)));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_social_fixed_side_devices', array(
		'section' => 'orbital_social_fixed_side',
		'label' => __('Device Selector', 'orbital'),
		'description' => __('If you have a cache plugin, maybe you should refresh cache.', 'orbital'),
		'settings' => 'orbital_social_fixed_side_devices',
		'type' => 'select',
		'choices' => array(
			'all' => __('All devices', 'orbital'),
			'mobile' => __('Only Mobile', 'orbital'),
			'desktop' => __('Only Desktop', 'orbital'),
		),

	)));

    // SOCIAL BEFORE CONTENT CONTROLS

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_social_before_content_enable', array(
		'section' => 'orbital_social_before_content',
		'label' => __('Enable Before Content Social', 'orbital'),
		'settings' => 'orbital_social_before_content_enable',
		'type' => 'checkbox',
	)));


	$wp_customize->add_control(new orbital_Customize_Misc_Control($wp_customize, 'orbital_social_before_content_social', array(
		'section'     => 'orbital_social_before_content',
		'type'        => 'multiselect',
		'label' => __('Select Social Networks', 'orbital'),
		'choices' => $social_networks,
	)));

	$wp_customize->add_control(new orbital_Customize_Misc_Control($wp_customize, 'orbital_social_before_content_post_types', array(
		'section'     => 'orbital_social_before_content',
		'type'        => 'multiselect',
		'label' => __('Post Types', 'orbital'),
		'choices' => $post_types,
	)));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_social_before_content_count', array(
		'section' => 'orbital_social_before_content',
		'label' => __('Enable Count', 'orbital'),
		'settings' => 'orbital_social_before_content_count',
		'type' => 'checkbox',
	)));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_social_before_content_devices', array(
		'section' => 'orbital_social_before_content',
		'label' => __('Device Selector', 'orbital'),
		'description' => __('If you have a cache plugin, maybe you should refresh cache.', 'orbital'),
		'settings' => 'orbital_social_before_content_devices',
		'type' => 'select',
		'choices' => array(
			'all' => __('All devices', 'orbital'),
			'mobile' => __('Only Mobile', 'orbital'),
			'desktop' => __('Only Desktop', 'orbital'),
		),

	)));

    // SOCIAL AFTER CONTENT CONTROLS

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_social_after_content_enable', array(
		'section' => 'orbital_social_after_content',
		'label' => __('Enable After Content Social', 'orbital'),
		'settings' => 'orbital_social_after_content_enable',
		'type' => 'checkbox',
	)));


	$wp_customize->add_control(new orbital_Customize_Misc_Control($wp_customize, 'orbital_social_after_content_social', array(
		'section'     => 'orbital_social_after_content',
		'type'        => 'multiselect',
		'label' => __('Select Social Networks', 'orbital'),
		'choices' => $social_networks,
	)));

	$wp_customize->add_control(new orbital_Customize_Misc_Control($wp_customize, 'orbital_social_after_content_post_types', array(
		'section'     => 'orbital_social_after_content',
		'type'        => 'multiselect',
		'label' => __('Post Types', 'orbital'),
		'choices' => $post_types,
	)));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_social_after_content_count', array(
		'section' => 'orbital_social_after_content',
		'label' => __('Enable Count', 'orbital'),
		'settings' => 'orbital_social_after_content_count',
		'type' => 'checkbox',
	)));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'orbital_social_after_content_devices', array(
		'section' => 'orbital_social_after_content',
		'label' => __('Device Selector', 'orbital'),
		'description' => __('If you have a cache plugin, maybe you should refresh cache.', 'orbital'),
		'settings' => 'orbital_social_after_content_devices',
		'type' => 'select',
		'choices' => array(
			'all' => __('All devices', 'orbital'),
			'mobile' => __('Only Mobile', 'orbital'),
			'desktop' => __('Only Desktop', 'orbital'),
		),

	)));
}
