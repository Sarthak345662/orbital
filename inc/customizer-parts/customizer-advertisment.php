<?php

function orbital_add_header_xua()
{
	if (is_customize_preview()) {
		header('X-XSS-Protection: 0');
	}
}
add_action('send_headers', 'orbital_add_header_xua');

function orbital_advertisment_customizer($wp_customize)
{

	$wp_customize->add_panel('orbital_ads', array(
		'title' =>  __('Adsense and Analytics', 'orbital'),
		'description' => __('Remember the limitations and <a rel="nofollow" target="_blank" href="https://support.google.com/adsense/answer/48182">Google Adsense policies</a>.', 'orbital'),
		'priority' => 1007,
	));

	$wp_customize->add_section('position_options_analytics', array(
		'title' =>  __('Analytics and Adsense codes', 'orbital'),
		'panel' => 'orbital_ads',
	));

	$wp_customize->add_section('position_options_home', array(
		'title' =>  __('Home Ads', 'orbital'),
		'panel' => 'orbital_ads',
	));

	$wp_customize->add_section('position_options_single', array(
		'title' =>  __('Single Ads', 'orbital'),
		'panel' => 'orbital_ads',
	));

	$wp_customize->add_section('position_options_page', array(
		'title' =>  __('Page Ads', 'orbital'),
		'panel' => 'orbital_ads',
	));

	$wp_customize->add_section('position_options_archive', array(
		'title' =>  __('Archive Ads', 'orbital'),
		'panel' => 'orbital_ads',
	));

	$wp_customize->add_section('position_options_home_mobile', array(
		'title' =>  __('Home Mobile Ads', 'orbital'),
		'panel' => 'orbital_ads',
	));

	$wp_customize->add_section('position_options_single_mobile', array(
		'title' =>  __('Single Mobile Ads', 'orbital'),
		'panel' => 'orbital_ads',
	));

	$wp_customize->add_section('position_options_page_mobile', array(
		'title' =>  __('Page Mobile Ads', 'orbital'),
		'panel' => 'orbital_ads',
	));

	$wp_customize->add_section('position_options_archive_mobile', array(
		'title' =>  __('Archive Mobile Ads', 'orbital'),
		'panel' => 'orbital_ads',
	));


	$position_options = array(
		'orbital_advertisment_before_home' => __('Before Home', 'orbital'),
		'orbital_advertisment_after_featured_home' => __('After Featured Home', 'orbital'),
		'orbital_advertisment_after_home' => __('After Home', 'orbital'),
		'orbital_advertisment_before_home_mobile' => __('Before Home Mobile', 'orbital'),
		'orbital_advertisment_after_featured_home_mobile' => __('After Featured Home Mobile', 'orbital'),
		'orbital_advertisment_after_home_mobile' => __('After Home Mobile', 'orbital'),
		'orbital_advertisment_before_single_content' => __('Before Single Content', 'orbital'),
		'orbital_advertisment_middle_single_content' => __('Middle Single Content', 'orbital'),
		'orbital_advertisment_after_single_content' => __('After Single Content', 'orbital'),
		'orbital_advertisment_before_single_content_mobile' => __('Before Single Content Mobile', 'orbital'),
		'orbital_advertisment_middle_single_content_mobile' => __('Middle Single Content Mobile', 'orbital'),
		'orbital_advertisment_after_single_content_mobile' => __('After Single Content Mobile', 'orbital'),
		'orbital_advertisment_before_page_content' => __('Before Page Content', 'orbital'),
		'orbital_advertisment_middle_page_content' => __('Middle Page Content', 'orbital'),
		'orbital_advertisment_after_page_content' => __('After Page Content', 'orbital'),
		'orbital_advertisment_before_page_content_mobile' => __('Before Page Content Mobile', 'orbital'),
		'orbital_advertisment_middle_page_content_mobile' => __('Middle Page Content Mobile', 'orbital'),
		'orbital_advertisment_after_page_content_mobile' => __('After Page Content Mobile', 'orbital'),
		'orbital_advertisment_before_archive' => __('Before Archive', 'orbital'),
		'orbital_advertisment_after_featured_archive' => __('After Featured Archive', 'orbital'),
		'orbital_advertisment_after_archive' => __('After Archive', 'orbital'),
		'orbital_advertisment_after_description_archive' => __('After Description Archive', 'orbital'),
		'orbital_advertisment_before_archive_mobile' => __('Before Archive Mobile', 'orbital'),
		'orbital_advertisment_after_featured_archive_mobile' => __('After Featured Archive Mobile', 'orbital'),
		'orbital_advertisment_after_archive_mobile' => __('After Archive Mobile', 'orbital'),
		'orbital_advertisment_after_description_archive_mobile' => __('After Description Archive Mobile', 'orbital'),
	);

	foreach ($position_options as $position_option => $position_value) {
		$section_control = str_replace('_mobile', '', $position_option);

		if ($position_option == $section_control) {
			$wp_customize->add_section($section_control, array(
				'title' =>  $position_value,
				'panel' => 'orbital_ads',
			));
		}

		$wp_customize->add_setting($position_option . '_code', array(
			'default' => '',
			'transport' => 'refresh',
			'sanitize_callback' => '',

		));

		$wp_customize->add_setting($position_option . '_align', array(
			'default' => 'center',
			'transport' => 'refresh',
			'sanitize_callback' => '',
		));

		$wp_customize->add_setting($position_option . '_style', array(
			'default' => 'fluid',
			'transport' => 'refresh',
			'sanitize_callback' => '',
		));

		$wp_customize->add_control(new WP_Customize_Control($wp_customize, $position_option . '_code', array(
			'section' => $section_control,
			'label' => $position_value,
			'settings' => $position_option . '_code',
			'type' => 'textarea',
		)));

		$wp_customize->add_control(new WP_Customize_Control($wp_customize, $position_option . '_align', array(
			'section' => $section_control,
			'label' => 'Alignment',
			'settings' => $position_option . '_align',
			'type' => 'select',
			'choices' => array(
				'center' => 'Center',
				'left' => 'Left',
				'right' => 'Right',
			),
		)));

		$wp_customize->add_control(new WP_Customize_Control($wp_customize, $position_option . '_style', array(
			'section' => $section_control,
			'label' => 'Style',
			'settings' => $position_option . '_style',
			'type' => 'select',
			'choices' => array(
				'fluid' => 'Fluid (100%)',
				'small' => 'Small Rectangle (300 x 250)',
				'medium' => 'Medium Rectangle (336 x 280)',
				'large' => 'Large Rectangle (360 x 280)',
				'leaderboard' => 'Leaderboard (728 x 90)',
				'half-page' => 'Half Page (300 x 600)',
			),
		)));

		if (in_array($position_option, array(
			'orbital_advertisment_middle_single_content',
			'orbital_advertisment_middle_single_content_mobile',
			'orbital_advertisment_middle_page_content',
			'orbital_advertisment_middle_page_content_mobile'))) {
			$wp_customize->add_setting($position_option . '_middle_mode', array(
				'default' => 'unique',
				'transport' => 'refresh',
				'sanitize_callback' => '',
			));

			$wp_customize->add_setting($position_option . '_middle_tag', array(
				'default' => 'p',
				'transport' => 'refresh',
				'sanitize_callback' => '',
			));

			$wp_customize->add_setting($position_option . '_middle_number', array(
				'default' => 3,
				'transport' => 'refresh',
				'sanitize_callback' => '',
			));

			$wp_customize->add_control(new WP_Customize_Control($wp_customize, $position_option . '_middle_mode', array(
				'section' => $section_control,
				'label' => 'Middle Mode',
				'settings' => $position_option . '_middle_mode',
				'type' => 'select',
				'choices' => array(
					'unique' => 'Unique',
					'scroll' => 'Scroll',
				),
			)));

			$wp_customize->add_control(new WP_Customize_Control($wp_customize, $position_option . '_middle_tag', array(
				'section' => $section_control,
				'label' => 'Middle After Tag',
				'settings' => $position_option . '_middle_tag',
				'type' => 'select',
				'choices' => array(
					'p' => 'Paragraph',
					'h2' => 'Heading H2',
				),
			)));

			$wp_customize->add_control(new WP_Customize_Control($wp_customize, $position_option . '_middle_number', array(
				'section' => $section_control,
				'label' => 'Middle After Number',
				'settings' => $position_option . '_middle_number',
				'type' => 'number',
				'input_attrs' => array(
					'min' => 1,
				),
			)));
		}
	}
}
