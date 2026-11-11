<?php
/**
 * Meta Box API Generator
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

/*
*
* text, checkbox, color, date, datetime, datetime-local
* email, media, month, number, password, radio, range,
* select, tel, textarea, time, url, week
*
*/

/*
 * Fields to Meta Box
 */

if (! function_exists('orbital_get_meta_fields')) :

	function orbital_get_meta_fields()
	{
		$doc = new DOMDocument();
		@$doc->loadHTMLFile(get_permalink($_GET["post"]). "?bypass_unload=yes");
		$xpath = new DOMXpath($doc);

		$assets_arr = array(
			array(
				"values" =>$xpath->query("//link[@rel='stylesheet']"),
				"name" => "style",
				"attribute" => "href",
				"file" => "css"
			),
			array(
				"values" =>$xpath->query("//script[@type='text/javascript']"),
				"name" => "script",
				"attribute" => "src",
				"file" => "js"
			)
		);


		$fields = array(
			array(
				'id' => 'custom-appearance',
				'label' => __('Modify appearance', 'orbital'),
				'type' => 'heading',
			),
			array(
				'id' => 'subtitle',
				'label' => __('Subtitle', 'orbital'),
				'type' => 'textarea',
				'default' => '',
			),
			array(
				'id' => 'custom-sidebar',
				'label' => __('Custom Sidebar', 'orbital'),
				'type' => 'select',
				'options' => orbital_dynamic_sidebar(),
			),
			array(
				'id' => 'custom-layout',
				'label' => __('Activate / Deactivate layout', 'orbital'),
				'type' => 'heading',
			),
			array(
				'id' => 'header',
				'label' => __('Header', 'orbital'),
				'type' => 'checkbox',
				'default' => '1',
			),
			array(
				'id' => 'footer',
				'label' => __('Footer', 'orbital'),
				'type' => 'checkbox',
				'default' => '1',
			),
			array(
				'id' => 'sidebar',
				'label' => __('Sidebar', 'orbital'),
				'type' => 'checkbox',
				'default' => '1',
			),
			array(
				'id' => 'separator-apperance-theme',
				'label' => '',
				'type' => 'separator',
			),
			array(
				'id' => 'title',
				'label' => __('Title', 'orbital'),
				'type' => 'checkbox',
				'default' => '1',
			),
			array(
				'id' => 'meta',
				'label' => __('Meta', 'orbital'),
				'type' => 'checkbox',
				'default' => '1',
			),
			array(
				'id' => 'category',
				'label' => __('Category', 'orbital'),
				'type' => 'checkbox',
				'default' => '1',
			),
			array(
				'id' => 'advertisment',
				'label' => __('Advertisment', 'orbital'),
				'type' => 'checkbox',
				'default' => '1',
			),
			array(
				'id' => 'thumbnail',
				'label' => __('Thumbnail', 'orbital'),
				'type' => 'checkbox',
				'default' => '1',
			),
			array(
				'id' => 'social',
				'label' => __('Social Shares', 'orbital'),
				'type' => 'checkbox',
				'default' => '1',
			),
			array(
				'id' => 'orbital_toc',
				'label' => __('Table of Content', 'orbital'),
				'type' => 'checkbox',
				'default' => '1',
			),
			array(
				'id' => 'related',
				'label' => __('Related Posts', 'orbital'),
				'type' => 'checkbox',
				'default' => '1',
			),
			array(
				'id' => 'separator-apperance-pilar',
				'label' => '',
				'type' => 'separator',
			),
			array(
				'id' => 'pilar',
				'label' => __('Pilar Page', 'orbital'),
				'type' => 'checkbox',
			),
			array(
				'id' => 'custom-code',
				'label' => __('Add Custom Code', 'orbital'),
				'type' => 'heading',
			),
			array(
				'id' => 'header-code',
				'label' => __('Add Header Code', 'orbital'),
				'type' => 'textarea',
				'default' => '',
			),
			array(
				'id' => 'footer-code',
				'label' => __('Add Footer Code', 'orbital'),
				'type' => 'textarea',
				'default' => '',
			),
			array(
				'id' => 'custom-css',
				'label' => '',
				'type' => 'separator',
			),
			array(
				'id' => 'force-gdpr',
				'label' => __('Force GDPR for code above', 'orbital'),
				'type' => 'checkbox',
				'default' => '0',
			),
			array(
				'id' => 'gdpr-code-type',
				'label' => __('GDPR type of code above', 'orbital'),
				'type' => 'select',
				'options' => array(
					'functional' => __('Functionality Cookies', 'orbital'),
					'performance' => __('Traceability and performance cookies', 'orbital'),
					'analytics_ads' => __('Tracking and advertising cookies', 'orbital'),
					'other' => __('Other cookies', 'orbital')
				)
			)
		);
		$file_opt_heading = array(
			'id' => 'custom-css',
			'label' => __('File Optimization', 'orbital'),
			'type' => 'heading',
		);
		$first_value = true;
		foreach ($assets_arr as $asset) {
			if(!$first_value){
				$file_opt_heading = array(
					'id' => 'separator-asset',
					'label' => '',
					'type' => 'separator',
				);
			}
			$first_value = true;
			if ($asset["values"]->length > 0) {
				$fields[] = $file_opt_heading;
				foreach ($asset["values"] as $single_value) {
					$url = false;
					$id = false;
					foreach ($single_value->attributes as $attribute) {
						if($attribute->name === $asset["attribute"]){
							$url = $attribute->nodeValue;
						}elseif($attribute->name === "id"){
							$id = $attribute->nodeValue;
						}
					}
					if($url && $id){
						$fields[] = array(
							'id' => $asset["file"]."_unload-".hash('ripemd160', $url),
							'label' => ($first_value)? "Deactivate " . $asset["file"] . " " . $asset["name"] : "",
							'sublabel' => $url,
							'value' => $id,
							'type' => 'multiple',
							'default' => '0',
						);
						$first_value = false;
					}
				}
				
			}
		}
		return $fields;
	}

endif;

/*
 * Select Type of template to Meta Box
 */

if (! function_exists('orbital_get_meta_screens')) :

	function orbital_get_meta_screens()
	{
		$screens = array(
			'post',
			'page',
		);
		return $screens;
	}

endif;

/*
 * Launch Meta Box
 */

if (! function_exists('orbital_add_meta_boxes')) :

	function orbital_add_meta_boxes()
	{

		$screens = orbital_get_meta_screens();
		foreach ($screens as $screen) {
			add_meta_box(
				'option-page',
				__('Option Page', 'orbital'),
				'orbital_add_meta_box_callback',
				$screen,
				'advanced',
				'high'
			);
		}
	}

endif;


/*
 * Meta Box Field Callback
 */

if (! function_exists('orbital_add_meta_box_callback')) :

	function orbital_add_meta_box_callback($post)
	{
		wp_nonce_field('option_page_data', 'option_page_nonce');
		orbital_generate_fields($post);
	}

endif;


/*
 * Javascript for Meta Box WP Editor
 */

if (! function_exists('orbital_admin_footer')) :

	function orbital_admin_footer()
	{
		?>
		<script>
			jQuery(document).ready(function($){
				if ( typeof wp.media !== 'undefined' ) {
					var _custom_media = true,
					_orig_send_attachment = wp.media.editor.send.attachment;
					$('.rational-metabox-media').click(function(e) {
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(this);
						var id = button.attr('id').replace('_button', '');
						_custom_media = true;
						wp.media.editor.send.attachment = function(props, attachment){
							if ( _custom_media ) {
								$("#"+id).val(attachment.url);
							} else {
								return _orig_send_attachment.apply( this, [props, attachment] );
							};
						}
						wp.media.editor.open(button);
						return false;
					});
					$('.add_media').on('click', function(){
						_custom_media = false;
					});
				}
			});
		</script>
		<?php
	}

endif;

/*
 * Generate Fields for Meta Box
 */ 

if (! function_exists('orbital_generate_fields')) :

	function orbital_generate_fields($post)
	{

		$output = '';
		$fields = orbital_get_meta_fields();
		foreach ($fields as $field) {
			if($field['type'] !== 'multiple'){
				$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
			}else{
				$label = $field['label'];
			}
			$db_value = get_post_meta($post->ID, 'option_page_' . $field['id'], true);

			if ($db_value === '') {
				if (isset($field['default'])) {
					$db_value = $field['default'];
				}
			}

			switch ($field['type']) {
				case 'heading':
					$input = false;
					break;
				case 'separator':
					$input = '<hr>';
					break;
				case 'checkbox':
				$input = sprintf(
					'<input %s id="%s" name="%s" type="checkbox" value="1">',
					$db_value === '1' ? 'checked' : '',
					$field['id'],
					$field['id']
				);
				break;
				case 'multiple':
				$input = sprintf(
					"<input %s id='%s' name='%s' type='checkbox' value='%s'> <label for='%s'> <b>%s</b><br>%s <b>(Click to Deactivate)</b></label>",
					$db_value === $field['value'] ? 'checked' : '',
					$field['id'],
					$field['id'],
					$field['value'],
					$field['id'],
					$field['value'],
					$field['sublabel']
				);
				break;
				case 'media':
				$input = sprintf(
					'<input class="regular-text" id="%s" name="%s" type="text" value="%s"> <input class="button rational-metabox-media" id="%s_button" name="%s_button" type="button" value="Upload" />',
					$field['id'],
					$field['id'],
					$db_value,
					$field['id'],
					$field['id']
				);
				break;
				case 'radio':
				$input = '<fieldset>';
				$input .= '<legend class="screen-reader-text">' . $field['label'] . '</legend>';
				$i = 0;
				foreach ($field['options'] as $key => $value) {
					$field_value = !is_numeric($key) ? $key : $value;
					$input .= sprintf(
						'<label><input %s id="%s" name="%s" type="radio" value="%s"> %s</label>%s',
						$db_value === $field_value ? 'checked' : '',
						$field['id'],
						$field['id'],
						$field_value,
						$value,
						$i < count($field['options']) - 1 ? '<br>' : ''
					);
					$i++;
				}
				$input .= '</fieldset>';
				break;
				case 'select':
				$input = sprintf(
					'<select id="%s" name="%s">',
					$field['id'],
					$field['id']
				);
				$input .= '<option value="">---</option>';
				foreach ($field['options'] as $key => $value) {
					$field_value = !is_numeric($key) ? $key : $value;
					$input .= sprintf(
						'<option %s value="%s">%s</option>',
						$db_value == $key ? 'selected' : '',
						$key,
						$value
					);
				}
				$input .= '</select>';
				break;
				case 'textarea':
				$input = sprintf(
					'<textarea class="large-text" id="%s" name="%s" rows="3">%s</textarea>',
					$field['id'],
					$field['id'],
					$db_value
				);
				break;
				default:
				$input = sprintf(
					'<input %s id="%s" name="%s" type="%s" value="%s">',
					$field['type'] !== 'color' ? 'class="regular-text"' : '',
					$field['id'],
					$field['id'],
					$field['type'],
					$db_value
				);
			}
			$output .= orbital_row_format($label, $input);
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

endif;


/*
 * Format to Meta Box Fields
 */

if (! function_exists('orbital_row_format')) :

	function orbital_row_format($label, $input)
	{
		return ($input)?
			sprintf('<tr><th scope="row">%s</th><td>%s</td></tr>', $label, $input)
			:
			"<tr><th colspan=\"2\" scope\"row\"><h3>$label</h3></th></tr>";
	}

endif;


/*
 * Save Meta Box data
 */

if (! function_exists('orbital_save_post')) :

	function orbital_save_post($post_id)
	{

		if (! isset($_POST['option_page_nonce'])) {
			return $post_id;
		}
		$nonce = $_POST['option_page_nonce'];
		if (!wp_verify_nonce($nonce, 'option_page_data')) {
			return $post_id;
		}
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
		$fields = orbital_get_meta_fields();
		foreach ($fields as $field) {
			 if (isset($_POST[ $field['id'] ])) {
				switch ($field['type']) {
					case 'email':
					$_POST[ $field['id'] ] = sanitize_email($_POST[ $field['id'] ]);
					break;
					case 'text':
					$_POST[ $field['id'] ] = sanitize_text_field($_POST[ $field['id'] ]);
					break;
				}
				update_post_meta($post_id, 'option_page_' . $field['id'], $_POST[ $field['id'] ]);
			} else if ($field['type'] === 'checkbox' || $field['type'] === 'multiple') {
				update_post_meta($post_id, 'option_page_' . $field['id'], '0');
			}
		}
	}

endif;

/*
 * Get Meta Post Options
 */

if (! function_exists('orbital_get_option_page')) :

	function orbital_get_option_page($option, $default = true)
	{
		if (get_post_meta(get_the_ID(), 'option_page_' . $option, false)) {
			return get_post_meta(get_the_ID(), 'option_page_' . $option, true);
		}
		return $default;
	}

endif;

if (! function_exists('orbital_dynamic_sidebar')) :

	function orbital_dynamic_sidebar()
	{

		global $wp_registered_sidebars;

		$widget_areas = array();


		foreach ($wp_registered_sidebars as $sidebar => $value) {
			$widget_areas[$sidebar] = $value['name'];
		}

		ksort($widget_areas);
		return $widget_areas;
	}

endif;
