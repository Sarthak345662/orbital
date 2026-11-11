<?php

/**
 * Customizer API
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */
/*
 *
 * text, checkbox, radio, select, textarea, dropdown-pages
 * email, url, number, hidden, and date
 *
 */

require get_template_directory() . '/inc/customizer-parts/customizer-sanitize.php';
require get_template_directory() . '/inc/customizer-parts/customizer-control.php';
require get_template_directory() . '/inc/customizer-parts/customizer-fonts.php';
require get_template_directory() . '/inc/customizer-parts/customizer-misc.php';
require get_template_directory() . '/inc/customizer-parts/customizer-options.php';
require get_template_directory() . '/inc/customizer-parts/customizer-advertisment.php';
require get_template_directory() . '/inc/customizer-parts/customizer-social.php';
require get_template_directory() . '/inc/customizer-parts/customizer-woocommerce.php';
require get_template_directory() . '/inc/customizer-parts/customizer-export-import.php';
require get_template_directory() . '/inc/customizer-parts/customizer-gdpr.php';
require get_template_directory() . '/inc/customizer-parts/customizer-performance.php';
require get_template_directory() . '/inc/customizer-parts/customizer-toc.php';

//Actions
add_action('customize_register', 'orbital_customizer');
add_action('customize_preview_init', 'orbital_customizer_live_preview');

//Options
add_action('wp_head', 'orbital_customize_css');
add_action('wp_head', 'orbital_fonts_head');
add_action('wp_head', 'orbital_enqueue_analytics');
add_action('wp_head', 'orbital_enqueue_adsense');

function orbital_customizer_live_preview() {
    wp_enqueue_script('orbital_customizer_js', get_template_directory_uri() . '/assets/js/customizer.js', array('jquery', 'customize-preview'), '20120187', true);
}

function orbital_customizer($wp_customize) {
    $arrayFonts = orbital_customizer_fonts();
    $sections = orbital_customizer_sections();
    $settings = orbital_customizer_settings();
    $controls = orbital_customizer_controls($arrayFonts);

    //Sections
    foreach ($sections as $section) {
        $wp_customize->add_section($section['name'], array(
            'title' => $section['title'],
            'description' => $section['description'],
            'priority' => $section['priority'],
        ));
    }

    //Settings
    foreach ($settings as $setting) {
        $wp_customize->add_setting($setting['name'], array(
            'default' => $setting['default'],
            'transport' => $setting['transport'],
                //'sanitize_callback' => $setting['sanitize_callback'],
        ));
    }

    //Controls
    foreach ($controls as $control) {
        $wp_customize->add_control(new WP_Customize_Control(
                        $wp_customize,
                        $control['setting'],
                        $control['info']
        ));
    }

    orbital_woocommerce_customizer($wp_customize);
    orbital_advertisment_customizer($wp_customize);
    orbital_social_customizer($wp_customize);
    orbital_gdpr_customizer($wp_customize);
    orbital_performance_customizer($wp_customize);
    orbital_content_table($wp_customize);
}

function orbital_customize_option($option, $default = null) {

    global $orbital_customizer_defaults;

    if (!is_null(get_theme_mod($option)) && get_theme_mod($option)) {
        return get_theme_mod($option);
    }

    if (isset($orbital_customizer_defaults[$option])) {
        return get_theme_mod($option, $orbital_customizer_defaults[$option]);
    }

    if (isset($default)) {
        return $default;
    }

    return get_theme_mod($option);
}

function orbital_delete_minified_files_after_save() {
    $cache_path = dirname(__FILE__) . '/../cache';
    if (is_dir($cache_path)) {
        $files = glob($cache_path . '/*');

        foreach ($files as $file) {
            @unlink($file);
        }
    }
}

add_action('customize_save_after', 'orbital_delete_minified_files_after_save');

