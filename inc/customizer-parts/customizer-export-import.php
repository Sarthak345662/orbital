<?php


final class Orbital_Export_Import_Core
{

	private static $core_options = array(
		'blogname',
		'blogdescription',
		'show_on_front',
		'page_on_front',
		'page_for_posts',
	);

	public static function init($wp_customize)
	{
		if (current_user_can('edit_theme_options')) {
			if (isset($_REQUEST['orbital-ei-export'])) {
				self::_export($wp_customize);
			}
			if (isset($_REQUEST['orbital-ei-import']) && isset($_FILES['orbital-ei-import-file'])) {
				self::_import($wp_customize);
			}
		}
	}

	public static function controls_enqueue_scripts()
	{
        // Register
		wp_register_style('orbital-ei-css', get_template_directory_uri() . '/assets/css/export-import.css', array(), '1.0.0');
		wp_register_script('orbital-ei-js', get_template_directory_uri() . '/assets/js/export-import.js', array( 'jquery' ), '1.0.0', true);

        // Localize
		wp_localize_script('orbital-ei-js', 'orbitaleil10n', array(
			'emptyImport'   => __('Please choose a file to import.', 'orbital')
		));

        // Config
		wp_localize_script('orbital-ei-js', 'orbitaleiConfig', array(
			'customizerURL'   => admin_url('customize.php'),
			'exportNonce'     => wp_create_nonce('orbital-ei-exporting')
		));

        // Enqueue
		wp_enqueue_style('orbital-ei-css');
		wp_enqueue_script('orbital-ei-js');
	}

	public static function register($wp_customize)
	{
		require get_template_directory() . '/inc/customizer-parts/ei/control.php';

		$wp_customize->add_section('orbital-ei-section', array(
			'title'    => __('Export/Import', 'orbital'),
			'priority' => 10000000
		));

		$wp_customize->add_setting('orbital-ei-setting', array(
			'default' => '',
			'type'    => 'none',
			'sanitize_callback' => '',
		));

		$wp_customize->add_control(new Orbital_Export_Import_Control(
			$wp_customize,
			'orbital-ei-setting',
			array(
				'section'   => 'orbital-ei-section',
				'priority'  => 1
			)
		));
	}

	private static function _export($wp_customize)
	{
		if (! wp_verify_nonce($_REQUEST['orbital-ei-export'], 'orbital-ei-exporting')) {
			return;
		}

		$theme      = get_stylesheet();
		$template   = get_template();
		$charset    = get_option('blog_charset');
		$mods       = get_theme_mods();
		$data       = array(
			'template'  => $template,
			'mods'    => $mods ? $mods : array(),
			'options'     => array()
		);

		$settings = $wp_customize->settings();

		foreach ($settings as $key => $setting) {
			if ('option' == $setting->type) {
				if ('widget_' === substr(strtolower($key), 0, 7)) {
					continue;
				}

				if ('sidebars_' === substr(strtolower($key), 0, 9)) {
					continue;
				}

				if (in_array($key, self::$core_options)) {
					continue;
				}

				$data['options'][ $key ] = $setting->value();
			}
		}

		$option_keys = apply_filters('Orbital_Export_Import_export_option_keys', array());

		foreach ($option_keys as $option_key) {
			$data['options'][ $option_key ] = get_option($option_key);
		}

		if (function_exists('wp_get_custom_css_post')) {
			$data['wp_css'] = wp_get_custom_css();
		}

		header('Content-disposition: attachment; filename=' . $theme . '-export.dat');
		header('Content-Type: application/octet-stream; charset=' . $charset);

		echo serialize($data);

		die();
	}


	private static function _import($wp_customize)
	{
		if (! wp_verify_nonce($_REQUEST['orbital-ei-import'], 'orbital-ei-importing')) {
			return;
		}

		if (! function_exists('wp_handle_upload')) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		}

		require get_template_directory() . '/inc/customizer-parts/ei/option.php';

		global $wp_customize;
		global $Orbital_Export_Import_error;

		$Orbital_Export_Import_error     = false;
		$template    = get_template();
		$overrides   = array( 'test_form' => false, 'test_type' => false, 'mimes' => array('dat' => 'text/plain') );
		$file        = wp_handle_upload($_FILES['orbital-ei-import-file'], $overrides);

		if (isset($file['error'])) {
			$Orbital_Export_Import_error = $file['error'];
			return;
		}
		if (! file_exists($file['file'])) {
			$Orbital_Export_Import_error = __('Error importing settings! Please try again.', 'orbital');
			return;
		}

		$raw  = wp_remote_get($file['url'], array('sslverify'   => false));
		$raw = wp_remote_retrieve_body($raw);
		$raw = str_replace('s:12:"orbital-lite"', 's:7:"orbital"', $raw);
		$data = @unserialize($raw);

		unlink($file['file']);

		if ('array' != gettype($data)) {
			$Orbital_Export_Import_error = __('Error importing settings! Please check that you uploaded a customizer export file.', 'orbital');
			return;
		}
		if (! isset($data['template']) || ! isset($data['mods'])) {
			$Orbital_Export_Import_error = __('Error importing settings! Please check that you uploaded a customizer export file.', 'orbital');
			return;
		}
		if ($data['template'] != $template) {
			$Orbital_Export_Import_error = __('Error importing settings! The settings you uploaded are not for the current theme.', 'orbital');
			return;
		}

		if (isset($_REQUEST['orbital-ei-import-images'])) {
			$data['mods'] = self::_import_images($data['mods']);
		}

		if (isset($data['options'])) {
			foreach ($data['options'] as $option_key => $option_value) {
				$option = new Orbital_Export_Import_Option($wp_customize, $option_key, array(
					'default'       => '',
					'type'          => 'option',
					'capability'    => 'edit_theme_options'
				));

				$option->import($option_value);
			}
		}

		if (function_exists('wp_update_custom_css_post') && isset($data['wp_css']) && '' !== $data['wp_css']) {
			wp_update_custom_css_post($data['wp_css']);
		}

		do_action('customize_save', $wp_customize);

		foreach ($data['mods'] as $key => $val) {
			do_action('customize_save_' . $key, $wp_customize);
			set_theme_mod($key, $val);
		}

		do_action('customize_save_after', $wp_customize);
	}

	private static function _import_images($mods)
	{
		foreach ($mods as $key => $val) {
			if (self::_is_image_url($val)) {
				$data = self::_sideload_image($val);

				if (! is_wp_error($data)) {
					$mods[ $key ] = $data->url;

					if (isset($mods[ $key . '_data' ])) {
						$mods[ $key . '_data' ] = $data;
						update_post_meta($data->attachment_id, '_wp_attachment_is_custom_header', get_stylesheet());
					}
				}
			}
		}

		return $mods;
	}


	private static function _sideload_image($file)
	{
		$data = new stdClass();

		if (! function_exists('media_handle_sideload')) {
			require_once(ABSPATH . 'wp-admin/includes/media.php');
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/image.php');
		}
		if (! empty($file)) {
			preg_match('/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches);
			$file_array = array();
			$file_array['name'] = basename($matches[0]);
			$file_array['tmp_name'] = download_url($file);

			if (is_wp_error($file_array['tmp_name'])) {
				return $file_array['tmp_name'];
			}

			$id = media_handle_sideload($file_array, 0);

			if (is_wp_error($id)) {
				@unlink($file_array['tmp_name']);
				return $id;
			}

			$meta                   = wp_get_attachment_metadata($id);
			$data->attachment_id    = $id;
			$data->url              = wp_get_attachment_url($id);
			$data->thumbnail_url    = wp_get_attachment_thumb_url($id);
			$data->height           = $meta['height'];
			$data->width            = $meta['width'];
		}

		return $data;
	}


	private static function _is_image_url($string = '')
	{
		if (is_string($string)) {
			if (preg_match('/\.(jpg|jpeg|png|gif)/i', $string)) {
				return true;
			}
		}

		return false;
	}
}


add_action('customize_controls_enqueue_scripts', 'Orbital_Export_Import_Core::controls_enqueue_scripts');
add_action('customize_register', 'Orbital_Export_Import_Core::init', 999999);
add_action('customize_register', 'Orbital_Export_Import_Core::register');
