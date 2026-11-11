<?php

/**
 * Advertisment Functionality
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */
/*
 * Advertisment Hook Before Home
 */

function orbital_advertisment_before_home() {
    orbital_print_advertisment('orbital_advertisment_before_home', 'desktop');
}

function orbital_advertisment_before_home_mobile() {
    orbital_print_advertisment('orbital_advertisment_before_home_mobile', 'mobile');
}

/*
 * Advertisment Hook After Featured Home
 */

function orbital_advertisment_after_featured_home() {
    orbital_print_advertisment('orbital_advertisment_after_featured_home', 'desktop');
}

function orbital_advertisment_after_featured_home_mobile() {
    orbital_print_advertisment('orbital_advertisment_after_featured_home_mobile', 'mobile');
}

/*
 * Advertisment Hook After Home
 */

function orbital_advertisment_after_home() {
    orbital_print_advertisment('orbital_advertisment_after_home', 'desktop');
}

function orbital_advertisment_after_home_mobile() {
    orbital_print_advertisment('orbital_advertisment_after_home_mobile', 'mobile');
}

/*
 * Advertisment Hook Before Single Content
 */

function orbital_advertisment_before_single_content() {
    orbital_print_advertisment('orbital_advertisment_before_single_content', 'desktop');
}

function orbital_advertisment_before_single_content_mobile() {
    orbital_print_advertisment('orbital_advertisment_before_single_content_mobile', 'mobile');
}

/*
 * Advertisment Hook Middle Single Content
 */

function orbital_advertisment_middle_single_content($content) {
    return orbital_print_advertisment_middle_page('orbital_advertisment_middle_single_content', $content, 'desktop', 'post');
}

function orbital_advertisment_middle_single_content_mobile($content) {
    return orbital_print_advertisment_middle_page('orbital_advertisment_middle_single_content_mobile', $content, 'mobile', 'post');
}

/*
 * Advertisment Hook After Single Content
 */

function orbital_advertisment_after_single_content() {
    orbital_print_advertisment('orbital_advertisment_after_single_content', 'desktop');
}

function orbital_advertisment_after_single_content_mobile() {
    orbital_print_advertisment('orbital_advertisment_after_single_content_mobile', 'mobile');
}

/*
 * Advertisment Hook Before Page Content
 */

function orbital_advertisment_before_page_content() {
    orbital_print_advertisment('orbital_advertisment_before_page_content', 'desktop');
}

function orbital_advertisment_before_page_content_mobile() {
    orbital_print_advertisment('orbital_advertisment_before_page_content_mobile', 'mobile');
}

/*
 * Advertisment Hook Middle Page Content
 */

function orbital_advertisment_middle_page_content($content) {
    return orbital_print_advertisment_middle_page('orbital_advertisment_middle_page_content', $content, 'desktop', 'page');
}

function orbital_advertisment_middle_page_content_mobile($content) {
    return orbital_print_advertisment_middle_page('orbital_advertisment_middle_page_content_mobile', $content, 'mobile', 'page');
}

/*
 * Advertisment Hook After Page Content
 */

function orbital_advertisment_after_page_content() {
    orbital_print_advertisment('orbital_advertisment_after_page_content', 'desktop');
}

function orbital_advertisment_after_page_content_mobile() {
    orbital_print_advertisment('orbital_advertisment_after_page_content_mobile', 'mobile');
}

/*
 * Advertisment Hook Before Archive
 */

function orbital_advertisment_before_archive() {
    orbital_print_advertisment('orbital_advertisment_before_archive', 'desktop');
}

function orbital_advertisment_before_archive_mobile() {
    orbital_print_advertisment('orbital_advertisment_before_archive_mobile', 'mobile');
}

/*
 * Advertisment Hook After Featured Archive
 */

function orbital_advertisment_after_featured_archive() {
    orbital_print_advertisment('orbital_advertisment_after_featured_archive', 'desktop');
}

function orbital_advertisment_after_featured_archive_mobile() {
    orbital_print_advertisment('orbital_advertisment_after_featured_archive_mobile', 'mobile');
}

/*
 * Advertisment Hook After Archive
 */

function orbital_advertisment_after_archive() {
    orbital_print_advertisment('orbital_advertisment_after_archive', 'desktop');
}

function orbital_advertisment_after_archive_mobile() {
    orbital_print_advertisment('orbital_advertisment_after_archive_mobile', 'mobile');
}

/*
 * Advertisment Hook After Description Archive
 */

function orbital_advertisment_after_description_archive() {
    orbital_print_advertisment('orbital_advertisment_after_description_archive', 'desktop');
}

function orbital_advertisment_after_description_archive_mobile() {
    orbital_print_advertisment('orbital_advertisment_after_description_archive_mobile', 'mobile');
}

function orbital_middle_insert_advertisment($insertion, $part_id, $content, $code = 'p', $mode = 'unique') {
    $closing = '</' . $code . '>';
    $parts = explode($closing, $content);
    if ($mode == 'scroll') {
        foreach ($parts as $index => $part) {
            if ($part !== end($parts)) {
                if (trim($part)) {
                    $parts[$index] .= $closing;
                }

                if ($part_id) {
                    if (($index + 1) % $part_id == 0) {
                        $parts[$index] .= $insertion;
                    }
                }
            }
        }
    } else {
        foreach ($parts as $index => $part) {
            if (trim($part)) {
                $parts[$index] .= $closing;
            }

            if ($part_id == $index + 1) {
                $parts[$index] .= $insertion;
            }
        }
    }


    return implode('', $parts);
}

/*
 * Check if template can print advertisment
 */

if (!function_exists('orbital_check_advertisment_template')) :

    function orbital_check_advertisment_template($slug, $location = null) {
        $locationoption = orbital_customize_option($location);

        if (isset($location) && empty($locationoption)) {
            return true;
        }

        switch ($slug) {
            case 'no-ads':
                if (is_home() || is_front_page()) {
                    return false;
                }

                if ((is_page_template('templates/single-noads.php') || is_page_template('templates/page-noads.php')) || !orbital_get_option_page('advertisment')) {
                    return true;
                }


                break;

            default:
                return false;
                break;
        }
        return false;
    }

endif;

function orbital_print_advertisment($location, $device = 'desktop') {
    
    if (!orbital_gdpr_show_cookies('analytics_ads') || !orbital_get_option_page('advertisment')) {
        return;
    }
    ?>
    <div class="banner <?php echo $device ?>">
        <div class="<?php echo orbital_customize_option($location . '_align', 'center') ?> <?php echo orbital_customize_option($location . '_style', 'fluid') ?>">
            <?php echo orbital_customize_option($location . '_code') ?>
        </div>
    </div>
    <?php
}

function orbital_print_advertisment_middle_page($location, $content, $device = 'desktop', $post_type = 'post') {


    if (!orbital_gdpr_show_cookies('analytics_ads') || !orbital_get_option_page('advertisment') || is_admin()) {
        return $content;
    }

    $ad_code = '<div class="banner ' . $device . '"><div class="' . orbital_customize_option($location . '_align') . ' ' . orbital_customize_option($location . '_style') . '">
	' . orbital_customize_option($location . '_code') . '
	</div>
	</div>';


    if (is_singular($post_type)) {
        return orbital_middle_insert_advertisment(
                $ad_code,
                orbital_customize_option($location . '_middle_number', 3),
                $content,
                orbital_customize_option($location . '_middle_tag', 'p'),
                orbital_customize_option($location . '_middle_mode', 'unique')
        );
    }

    return $content;
}
