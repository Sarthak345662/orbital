<?php
/**
 * Extra functions
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */
/*
 * Return and modify excerpt Lenght
 */

if (!function_exists('orbital_excerpt_length')) :

    function orbital_excerpt_length($length) {

        if (orbital_customize_option('orbital_loop_excerpt_lenght')) {
            return orbital_customize_option('orbital_loop_excerpt_lenght');
        } else {
            return 10;
        }
    }

endif;

/*
 * Print Excerpt More
 */

if (!function_exists('orbital_excerpt_more')) :

    function orbital_excerpt_more($more) {
        return ' <a class="entry-read-more" href="' . get_the_permalink() . '">' . orbital_customize_option('orbital_loop_read_more') . '</a>';
    }

endif;



/*
 * Future featured
 */

if (!function_exists('orbital_next_page')) :

    function orbital_next_page($buttons, $id) {

        if ('content' != $id) {
            return $buttons;
        }
        array_splice($buttons, 13, 0, 'wp_page');
        return $buttons;
    }

endif;


/*
 * Future featured
 */

if (!function_exists('orbital_page_layout')) :

    function orbital_page_layout($default = "") {

        if (orbital_get_option_page('layout')) {
            return orbital_get_option_page('layout');
        } else {
            return $default;
        }
    }

endif;

/*
 * Add Mime Types
 */

if (!function_exists('orbital_mime_types')) :

    function orbital_mime_types($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

endif;


/*
 * Check if sidebar is activated and add class nosidebar if is not
 */

if (!function_exists('orbital_check_sidebar_class')) :

    function orbital_check_sidebar_class($classes) {
        if (orbital_check_woocommerce()) {
            if (!is_active_sidebar('shop') && is_woocommerce()) {
                $classes[] = 'no-sidebar';
            } elseif (is_woocommerce()) {
                return $classes;
            }
        }
        if (
                !is_active_sidebar('pages') && is_page() || !is_active_sidebar('posts') && is_single() || !is_active_sidebar('archives') && is_archive() || !is_active_sidebar('page-home') && is_home() || !is_active_sidebar('pilar') && is_page_template('templates/page-pilar.php') || !orbital_get_option_page('sidebar')
        ) {
            $classes[] = 'no-sidebar';
        }
        return $classes;
    }

endif;

if (!function_exists('orbital_layout_menu_class')) :

    function orbital_layout_menu_class($classes) {
        if (orbital_customize_option('orbital_layout_menu_orbital')) {
            $classes[] = 'layout-menu-orbital';
        }
        return $classes;
    }

endif;

/*
 * Add Custom Excerpt to Pages
 */

if (!function_exists('orbital_excerpts_to_pages')) :

    function orbital_excerpts_to_pages() {
        add_post_type_support('page', 'excerpt');
    }

endif;


/*
 * Categorized Blog
 */

if (!function_exists('orbital_categorized_blog')) :

    function orbital_categorized_blog() {
        if (false === ( $all_the_cool_cats = get_transient('orbital_categories') )) {
            $all_the_cool_cats = get_categories(array(
                'fields' => 'ids',
                'hide_empty' => 1,
                'number' => 2,
            ));
            $all_the_cool_cats = count($all_the_cool_cats);
            set_transient('orbital_categories', $all_the_cool_cats);
        }
        if ($all_the_cool_cats > 1) {
            return true;
        } else {
            return false;
        }
    }

endif;


/*
 * Category Trasient Flusher
 */

if (!function_exists('orbital_category_transient_flusher')) :

    function orbital_category_transient_flusher() {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        delete_transient('orbital_categories');
    }

endif;

/*
 * Add custom Buttons to WP Editor WYSIWYG
 */

if (!function_exists('orbital_add_custom_buttons')) :

    function orbital_add_custom_buttons() {

        global $typenow;

        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        if (!in_array($typenow, array('page', 'post'))) {
            return;
        }

        if (get_user_option('rich_editing') == 'true') {
            add_filter("mce_external_plugins", "orbital_add_tinymce_plugin");
            add_filter('mce_buttons', 'orbital_register_custom_buttons');
        }
    }

endif;


/*
 * Register WP Editor Buttons
 */

if (!function_exists('orbital_add_tinymce_plugin')) :

    function orbital_add_tinymce_plugin($plugin_array) {
        $plugin_array['orbital_tc_button'] = get_template_directory_uri() . '/assets/js/admin.js';
        return $plugin_array;
    }

endif;


if (!function_exists('orbital_register_custom_buttons')) :

    function orbital_register_custom_buttons($buttons) {
        array_push($buttons, "orbital_tc_button");
        return $buttons;
    }

endif;


/*
 * Remove Hentry Class
 */

if (!function_exists('orbital_remove_hentry')) :

    function orbital_remove_hentry($classes) {
        if (is_singular()) {
            $classes = array_diff($classes, array('hentry'));
        }
        return $classes;
    }

endif;


/*
 * Add WP Editor Extra to Categories
 */

if (!function_exists('orbital_cat_description')) :

    function orbital_cat_description($tag) {
        $cat_extra_description = get_term_meta($tag->term_id, 'cat_extra_description', true);
        ?>
        <table class="form-table">
            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="description"><?php esc_html_e('Top Description', 'orbital'); ?></label>
                </th>
                <td><?php
                    $settings = array(
                        'wpautop' => true,
                        'media_buttons' => true,
                        'quicktags' => true,
                        'textarea_rows' => '10',
                        'textarea_name' => 'cat_extra_description',
                        'drag_drop_upload' => true
                    );
                    wp_editor(wp_kses_post($cat_extra_description, ENT_QUOTES, 'UTF-8'), 'cat_extra_description', $settings);
                    ?>
                    <br />
                    <span class="description"><?php _e('The description is not prominent by default; however, some themes may show it.', 'orbital'); ?></span>
                </td>
            </tr>
        </table><?php
    }

endif;


if (!function_exists('orbital_save_extra_category_fields')) :

    function orbital_save_extra_category_fields($term_id) {
        if (isset($_POST['cat_extra_description'])) {
            update_term_meta($_POST['tag_ID'], 'cat_extra_description', $_POST['cat_extra_description']);
        }
    }

endif;


/*
 * Modify default textarea comments
 */

if (!function_exists('orbital_comment_textarea')) :

    function orbital_comment_textarea($arg) {
        $arg['comment_field'] = '<textarea id="comment" name="comment" cols="45" rows="1" required></textarea>';
        return $arg;
    }

endif;

if (!function_exists('orbital_gdpr_enqueue_js')) :

    function orbital_gdpr_enqueue_js() {

        if (!orbital_customize_option('orbital_gdpr_general_active')) {
            return;
        }
        get_template_part('template-parts/other/content', 'gdpr');
    }

endif;



add_filter('wp_nav_menu_items', 'orbital_search_navbar', 99, 2);

function orbital_search_navbar($items, $args) {
    if (!orbital_customize_option('orbital_layout_search_navbar')) {
        return $items;
    }

    ob_start();
    ?>
    <a href="#" onclick="orbital_expand_navbar()" class="text-center"><svg class="svg-inline--fa fa-search fa-w-16 fa-sm" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg></a>
    <?php
    $searchform = ob_get_contents();
    ob_end_clean();

    $items .= '<li class="menu-item search-item">' . $searchform . '</li>';

    return $items;
}


function orbital_get_domain() {
    $sURL = site_url();
    $asParts = parse_url($sURL);

    if (!$asParts) {
        wp_die('ERROR: Path corrupt for parsing.');
    }
    $sScheme = $asParts['scheme'];
    $sHost = $asParts['host'];
    $sReturn = $sScheme . '://' . $sHost;

    return $sReturn;
}

if (!function_exists('orbital_post_meta_request_params')) :

    function orbital_post_meta_request_params($args, $request) {
        $args += array(
            'meta_key' => $request['meta_key'],
            'meta_value' => $request['meta_value'],
            'meta_query' => $request['meta_query'],
        );
        return $args;
    }

    add_filter('rest_post_query', 'orbital_post_meta_request_params', 99, 2);
    add_filter('rest_page_query', 'orbital_post_meta_request_params', 99, 2);
endif;

/*
 * Add custom header / footer code to singles 
 */

if (!function_exists('orbital_single_header_code')) :

    function orbital_single_header_code() {
        if (is_single()) {
            if(orbital_get_option_page('force-gdpr')){
                $code_type = orbital_get_option_page('gdpr-code-type');
                if ($code_type && !orbital_gdpr_show_cookies($code_type)) {
                    return;
                }
            }
			$header_code = orbital_get_option_page('header-code');
			if($header_code && is_string($header_code)){
				echo $header_code ;
			}
        }
    }

endif;

if (!function_exists('orbital_single_footer_code')) :

    function orbital_single_footer_code() {
        if (is_single()) {
            if(orbital_get_option_page('force-gdpr')){
                $code_type = orbital_get_option_page('gdpr-code-type');
                if ($code_type && !orbital_gdpr_show_cookies($code_type)) {
                    return;
                }
            }
			$footer_code = orbital_get_option_page('footer-code');
			if($footer_code && is_string($footer_code)){
				echo $footer_code ;
			}
        }
    }

endif;

if (!function_exists('orbital_preload_func')) :

    function orbital_preload_func() {
        if(orbital_customize_option('orbital_performance_preload_styles')){
        
            $mime_types = array(
                'svg'   => 'image/svg+xml',
                'ttf'   => 'font/ttf',
                'otf'   => 'font/otf',
                'woff'  => 'font/woff',
                'woff2' => 'font/woff2',
                'eot'   => 'application/vnd.ms-fontobject',
                'sfnt'  => 'font/sfnt'
            );

            $preloads = array(
                "font" =>  explode(" ", orbital_customize_option('orbital_performance_preload_fonts')),
                "style" => explode(" ", orbital_customize_option('orbital_performance_preload_styles')),
                "script" =>  explode(" ", orbital_customize_option('orbital_performance_preload_scripts')),
                "image" =>  explode(" ", orbital_customize_option('orbital_performance_preload_images')),
                "document" =>  explode(" ", orbital_customize_option('orbital_performance_preload_embed')),
            );
            if (is_array($preloads)) {
                foreach($preloads as $lineType => $urls) {
                    if (is_array($urls) && !empty($urls)) {
                        foreach($urls as $lineUrl) {
                            if($lineUrl){
                                $mime_type = "";

                                if(!empty($lineType) && $lineType == 'font') {
                                    $path_info = pathinfo($lineUrl);
                                    $mime_type = !empty($path_info['extension']) && isset($mime_types[$path_info['extension']]) ? $mime_types[$path_info['extension']] : "";
                                }
                                
                                echo "<link rel='preload' href='" . $lineUrl . "'" . (!empty($lineType) ? " as='" . $lineType . "'" : "") . (!empty($mime_type) ? " type='" . $mime_type . "'" : "") . (!empty($lineType) && $lineType == 'style' ? " onload=\"this.rel='stylesheet';this.removeAttribute('onload');\"" : "") . ">" . "\n";
                            }
                        }
                    }
                }
            }
        }
    }
endif;

if (!function_exists('orbital_preconnect_func')) :
    function orbital_preconnect_func() {
        if(orbital_customize_option('orbital_performance_preconnect')){
            $preconnects =  explode(" ",  orbital_customize_option('orbital_performance_preconnect'));
            if (is_array($preconnects)) {
                foreach($preconnects as $line) {
                    echo "<link rel='preconnect' href='" . $line . "' crossorigin>" . "\n";
                }
            }
        }
    }
endif;

if (!function_exists('orbital_prefetch_func')) :
    function orbital_prefetch_func() {
        if(orbital_customize_option('orbital_performance_prefetch')) {
            $prefetch =  explode(" ",  orbital_customize_option('orbital_performance_prefetch'));
            if (is_array($prefetch)) {
                foreach($prefetch as $url) {
                    echo "<link rel='dns-prefetch' href='" . $url . "'>" . "\n";
                }
            }
        }
    }
endif;

if (!function_exists('orbital_unload_js')) :
    function orbital_unload_js() {
        if(is_admin() || (isset($_GET["bypass_unload"]) && $_GET["bypass_unload"]==="yes")){
            return;
        }
        $meta = get_post_meta(get_the_ID());
        if (is_array($meta)) {
            foreach($meta as $key=>$val){
                $query_js = "option_page_js_unload";
                if (substr($key, 0, strlen($query_js)) === $query_js) {
                    if($val){
                        wp_deregister_script( substr($val[0], 0, -3) );
                        wp_dequeue_script( substr($val[0], 0, -3) );
                    }
                }
            }
        }
    }
endif;

if (!function_exists('orbital_unload_css')) :
    function orbital_unload_css() {
        if(is_admin() || (isset($_GET["bypass_unload"]) && $_GET["bypass_unload"]==="yes")){
            return;
        }
        $meta = get_post_meta(get_the_ID());
        if (is_array($meta)) {
            foreach($meta as $key=>$val){
                $query_css = "option_page_css_unload";
                if (substr($key, 0, strlen($query_css)) === $query_css) {
                    if($val){
                        wp_deregister_style( substr($val[0], 0, -4) );
                        wp_dequeue_style( substr($val[0], 0, -4) );
                    }
                }
            }
        }
    }
endif;