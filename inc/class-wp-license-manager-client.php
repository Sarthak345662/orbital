<?php
/**
 * Update Theme System
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

if (! class_exists('Wp_License_Manager_Client')) {

	class Wp_License_Manager_Client
	{
		private $api_endpoint;
		private $product_id;
		private $product_name;
		private $type;
		private $text_domain;
		private $plugin_file;

		public function __construct(
			$product_id,
			$product_name,
			$api_url,
			$type = 'theme',
			$plugin_file = ''
		) {
			$this->product_id = $product_id;
			$this->product_name = $product_name;
			$this->api_endpoint = $api_url;
			$this->type = $type;
			$this->plugin_file = $plugin_file;


			if (is_admin()) {
				add_action('admin_menu', array( $this, 'add_license_settings_page' ));
				add_action('admin_init', array( $this, 'add_license_settings_fields' ));
				add_action('admin_notices', array( $this, 'show_admin_notices' ));

				if ($type == 'theme') {
					add_filter('pre_set_site_transient_update_themes', array( $this, 'check_for_update' ));
				} elseif ($type == 'plugin') {
					add_filter('pre_set_site_transient_update_plugins', array( $this, 'check_for_update' ));
					add_filter('plugins_api', array( $this, 'plugins_api_handler' ), 10, 3);
				}
			}

            //add_action( 'wp_head',  array( $this, 'check_licence_setup' ));
		}

		public function add_license_settings_page()
		{
			$title = sprintf(__('%s License', 'orbital'), $this->product_name);
			add_theme_page(
				$title,
				$title,
				'read',
				$this->get_settings_page_slug(),
				array( $this, 'render_licenses_menu' )
			);
		}

		public function add_license_settings_fields()
		{
			$settings_group_id = $this->product_id . '-license-settings-group';
			$settings_section_id = $this->product_id . '-license-settings-section';
			register_setting($settings_group_id, $this->get_settings_field_name());
			add_settings_section(
				$settings_section_id,
				__('License', 'orbital'),
				array( $this, 'render_settings_section' ),
				$settings_group_id
			);
			add_settings_field(
				$this->product_id . '-license-email',
				__('License e-mail address', 'orbital'),
				array( $this, 'render_email_settings_field' ),
				$settings_group_id,
				$settings_section_id
			);
			add_settings_field(
				$this->product_id . '-license-key',
				__('License key', 'orbital'),
				array( $this, 'render_license_key_settings_field' ),
				$settings_group_id,
				$settings_section_id
			);
		}

		public function render_settings_section()
		{
			_e('Insert your license information to enable updates.', 'orbital');
		}

		public function render_licenses_menu()
		{
			$title = sprintf(__('%s License', 'orbital'), $this->product_name);
			$settings_group_id = $this->product_id . '-license-settings-group';
			?>
			<div class="wrap">
				<form action='options.php' method='post'>
					<h2><?php echo $title; ?></h2>
					<?php
					settings_fields($settings_group_id);
					do_settings_sections($settings_group_id);
					submit_button();
					?>
				</form>

				<?php
				$license_info = $this->get_license_info();
				print_r($license_info);
				if ($this->is_api_error($license_info)) {
					print esc_html__('Status: Not activated', 'orbital');
				} else {
					print esc_html__('Status: Activated', 'orbital');
				}

				?>

			</div>
			<?php
		}

		public function render_email_settings_field()
		{
			$settings_field_name = $this->get_settings_field_name();
			$options = get_option($settings_field_name);
			?>
			<input type='text' name='<?php echo $settings_field_name; ?>[email]'
			value='<?php echo ($options)? $options['email'] : ""; ?>' class='regular-text'>
			<?php
		}

		public function render_license_key_settings_field()
		{
			$settings_field_name = $this->get_settings_field_name();
			$options = get_option($settings_field_name);
			?>
			<input type='text' name='<?php echo $settings_field_name; ?>[license_key]'
			value='<?php echo ($options)? $options['license_key']: ""; ?>' class='regular-text'>
			<?php
		}

		public function show_admin_notices()
		{
			if (! $this->get_license_key()) {
				$msg = __('Please enter your email and license key to enable updates to %s.', 'orbital');
				$msg = sprintf($msg, $this->product_name);
				?>
				<div class="update-nag">
					<p>
						<?php echo $msg; ?>
					</p>
					<p>
						<a href="<?php echo admin_url('themes.php?page=' . $this->get_settings_page_slug()); ?>">
							<?php _e('Complete the setup now.', 'orbital'); ?>
						</a>
					</p>
				</div>
				<?php
			}
		}

		public function check_for_update($transient)
		{
			if (empty($transient->checked)) {
				return $transient;
			}
			$info = $this->is_update_available();
			if ($info !== false) {
				if ($this->is_theme()) {
					$theme_data = wp_get_theme();
					$theme_slug = $theme_data->get_template();
					$transient->response[$theme_slug] = array(
						'new_version' => $info->version,
						'package'     => $info->package_url,
						'url'         => $info->description_url
					);
				} else {
					$plugin_slug = plugin_basename($this->plugin_file);
					$transient->response[$plugin_slug] = (object) array(
						'new_version' => $info->version,
						'package'     => $info->package_url,
						'slug'        => $plugin_slug
					);
				}
			}
			return $transient;
		}

		public function is_update_available()
		{
			$license_info = $this->get_license_info();
			if ($this->is_api_error($license_info)) {
				return false;
			}
			if (version_compare($license_info->version, $this->get_local_version(), '>')) {
				return $license_info;
			}
			return false;
		}

		public function get_license_info()
		{
			$options = get_option($this->get_settings_field_name());
			if (! isset($options['email']) || ! isset($options['license_key'])) {
				return false;
			}
			$info = $this->call_api(
				'info',
				array(
					'p' => $this->product_id,
					'e' => $options['email'],
					'l' => $options['license_key'],
					'w' => get_site_url(),
				)
			);

			return $info;
		}

		public function check_licence_setup()
		{
			$license_info = $this->get_license_info();
			if ($this->is_api_error($license_info)) {
				print esc_html__('Status: Not activated', 'orbital');
			} else {
				print esc_html__('Status: Activated', 'orbital');
			}
		}


		public function plugins_api_handler($res, $action, $args)
		{
			if ($action == 'plugin_information') {
				if (isset($args->slug) && $args->slug == plugin_basename($this->plugin_file)) {
					$info = $this->get_license_info();
					$res = (object) array(
						'name'          => isset($info->name) ? $info->name : '',
						'version'       => $info->version,
						'slug'          => $args->slug,
						'download_link' => $info->package_url,
						'tested'        => isset($info->tested) ? $info->tested : '',
						'requires'      => isset($info->requires) ? $info->requires : '',
						'last_updated'  => isset($info->last_updated) ? $info->last_updated : '',
						'homepage'      => isset($info->description_url) ? $info->description_url : '',
						'sections'      => array(
							'description' => $info->description,
						),
						'banners'       => array(
							'low'  => isset($info->banner_low) ? $info->banner_low : '',
							'high' => isset($info->banner_high) ? $info->banner_high : ''
						),
						'external'      => true
					);
                    // Add change log tab if the server sent it
					if (isset($info->changelog)) {
						$res['sections']['changelog'] = $info->changelog;
					}
					return $res;
				}
			}
			return false;
		}

		protected function get_settings_field_name()
		{
			return $this->product_id . '-license-settings';
		}

		protected function get_settings_page_slug()
		{
			return $this->product_id . '-licenses';
		}

		private function is_theme()
		{
			return $this->type == 'theme';
		}

		private function get_local_version()
		{
			if ($this->is_theme()) {
				$theme_data = wp_get_theme();
				return $theme_data->Version;
			} else {
				$plugin_data = get_plugin_data($this->plugin_file, false);
				return $plugin_data['Version'];
			}
		}

		private function get_license_key()
		{
			$license_email = ( defined('FOURBASE_LICENSE_EMAIL') ) ? FOURBASE_LICENSE_EMAIL : '';
			$license_key = ( defined('FOURBASE_LICENSE_KEY') ) ? FOURBASE_LICENSE_KEY : '';
			if (! $license_key || strlen($license_key) < 8) {
				$options = get_option($this->get_settings_field_name());
				if ($options
					&& isset($options['email'])
					&& isset($options['license_key'])
					&& strlen($options['email']) > 0
					&& strlen($options['license_key']) >= 8 ) {
					$license_email = $options['email'];
					$license_key = $options['license_key'];
				} else {
					$license_email = '';
					$license_key = '';
				}
			}

			if (strlen($license_email) > 0 && strlen($license_key) >= 8) {
				return array( 'key' => $license_key, 'email' => $license_email );
			}
			return false;
		}

		private function call_api($action, $params)
		{
			$url = $this->api_endpoint . '/' . $action;
			$url .= '?' . http_build_query($params);
			$response = wp_remote_get($url);
			if (is_wp_error($response)) {
				return false;
			}
			$response_body = wp_remote_retrieve_body($response);
			$result = json_decode($response_body);

			return $result;
		}

		private function is_api_error($response)
		{
			if ($response === false) {
				return true;
			}
			if (! is_object($response)) {
				return true;
			}
			if (isset($response->error)) {
				return true;
			}
			return false;
		}
	}

}
$licence_manager = new Wp_License_Manager_Client(
	'orbital',
	'Orbital',
	'https://orbital.romualdfons.com/api/license-manager/v1',
	'theme'
);
