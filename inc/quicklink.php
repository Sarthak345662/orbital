<?php

if (! function_exists('orbital_quicklink_init')) :

	function orbital_quicklink_init()
	{

		if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
			return;
		}

		wp_enqueue_script('orbital-quicklink-js', get_template_directory_uri() . '/assets/js/quicklink-core.js', array(), '20190604', true);

		$urls = array_map('trim', preg_split('/\R/', orbital_customize_option('orbital_quicklink_default_urls')));
		if($urls !== [""] && is_array($urls) && count($urls) === 1 && strpos($urls[0], ' ')){
			$urls = explode(' ', $urls[0]);
		}
		$options = array(
			'el'        => '',
			'urls'      => $urls !== [""] ? $urls : array(),
			'timeout'   => 2000,
			'timeoutFn' => 'requestIdleCallback',
			'priority'  => false,
			'origins'   => array(
				wp_parse_url(home_url(), PHP_URL_HOST),
			),
			'ignores'   => array(
				preg_quote('feed=', '/'),
				preg_quote('/feed/', '/'),
				'^https?:\/\/[^\/]+' . preg_quote(wp_unslash($_SERVER['REQUEST_URI']), '/') . '(#.*)?$',
				'^' . preg_quote(admin_url(), '/'),
				'^' . preg_quote(site_url(), '/') . '[^?#]+\.php',
				preg_quote(wp_parse_url(content_url(), PHP_URL_PATH), '/'),
			),
		);

		$options = apply_filters('quicklink_options', $options);

		wp_add_inline_script('orbital-quicklink-js', sprintf('var quicklinkOptions = %s;', wp_json_encode($options)), 'before');
	}

endif;
add_action('wp_enqueue_scripts', 'orbital_quicklink_init');

if (! function_exists('orbital_quicklink_async')) :

	function orbital_quicklink_async($tag, $handle)
	{
		if ('orbital-quicklink-js' === $handle && false === strpos($tag, 'async')) {
			$tag = preg_replace(':(?=></script>):', ' async', $tag);
		}
		return $tag;
	}
	add_filter('script_loader_tag', 'orbital_quicklink_async', 10, 2);
endif;
