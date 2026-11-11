<?php
/**
 * Custom functionality for Native Gallery of Wordpress
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

/*
 * Replace native code from Gallery
 */

if (! function_exists('orbital_custom_gallery_shortcode')) :

	function orbital_custom_gallery_shortcode($attr)
	{

		$post = get_post();
		static $instance = 0;
		$instance++;

		if (! empty($attr['ids'])) {
			if (empty($attr['orderby'])) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		$output = apply_filters('post_gallery', '', $attr, $instance);

		if ($output != '') {
			return $output;
		}

		$atts = shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post ? $post->ID : 0,
			'itemtag'    => 'figure',
			'icontag'    => 'div',
			'captiontag' => 'figcaption',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => '',
			'link'       => '',
			'masonry'    => '',
			'titles'     => 0,
			'legend'     => 0,
			'wrapper' => 0,
		), $attr, 'gallery');

		$id = intval($atts['id']);

		if (! empty($atts['include'])) {
			$_attachments = get_posts(array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ));
			$attachments = array();
			foreach ($_attachments as $key => $val) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif (! empty($atts['exclude'])) {
			$attachments = get_children(array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ));
		} else {
			$attachments = get_children(array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ));
		}

		if (empty($attachments)) {
			return '';
		}

		if (is_feed()) {
			$output = "\n";
			foreach ($attachments as $att_id => $attachment) {
				$output .= wp_get_attachment_link($att_id, $atts['size'], true) . "\n";
			}
			return $output;
		}

		$itemtag = tag_escape($atts['itemtag']);
		$captiontag = tag_escape($atts['captiontag']);
		$icontag = tag_escape($atts['icontag']);
		$valid_tags = wp_kses_allowed_html('post');

		if (! isset($valid_tags[ $itemtag ])) {
			$itemtag = 'figure';
		}

		if (! isset($valid_tags[ $captiontag ])) {
			$captiontag = 'figcaption';
		}

		if (! isset($valid_tags[ $icontag ])) {
			$icontag = 'div';
		}

		$columns = intval($atts['columns']);
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		$float = is_rtl() ? 'right' : 'left';
		$masonry = $atts['masonry'] ? 'gallery-masonry' : '';
		$titles = $atts['titles'];
		$legend = $atts['legend'];
		$gallery_wrapper = $atts['wrapper'];
		$selector = "gallery-{$instance}";
		$size_class = sanitize_html_class($atts['size']);
		$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} {$masonry} gallery-columns-{$columns} gallery-size-{$size_class}'>";
		$output = apply_filters('gallery_style', $gallery_div);
		$i = 0;

		foreach ($attachments as $id => $attachment) {
			$attr = ( trim($attachment->post_excerpt) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
			if (get_post_meta($id, 'orbital_attachment_custom_link', true)) {
				$image_output = '<a href="'.get_post_meta($id, 'orbital_attachment_custom_link', true).'">'. wp_get_attachment_image($id, $atts['size'], false, $attr) . '</a>';
			} elseif (! empty($atts['link']) && 'file' === $atts['link']) {
				$image_output = wp_get_attachment_link($id, $atts['size'], false, false, false, $attr);
			} elseif (! empty($atts['link']) && 'none' === $atts['link']) {
				$image_output = wp_get_attachment_image($id, $atts['size'], false, $attr);
			} else {
				$image_output = wp_get_attachment_link($id, $atts['size'], false, $attr);
			}

			$image_meta  = wp_get_attachment_metadata($id);
			$orientation = '';

			if (isset($image_meta['height'], $image_meta['width'])) {
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
			}

			$output .= "<{$itemtag} class='gallery-item'>";

			if ($gallery_wrapper) {
				$output .= "<div class='gallery-wrapper'>";
			}

			$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
			$image_output
			</{$icontag}>";

			if (! $titles) {
				$output .="<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>";

				if (trim($attachment->post_title)) {
					if (get_post_meta($id, 'orbital_attachment_custom_link', true)) {
						$output .= "<a href='".get_post_meta($id, 'orbital_attachment_custom_link', true)."'><h3>" . wptexturize($attachment->post_title) . "</h3></a>";
					} else {
						$output .= "<h3>" . wptexturize($attachment->post_title) . "</h3>";
					}
				}

				if (trim($attachment->post_excerpt) && !$legend) {
					$output .= "<p>" . wptexturize($attachment->post_excerpt) . "</p>";
				}

				$output .= "</{$captiontag}>";
			}

			if ($gallery_wrapper) {
				$output .= "</div>";
			}

			$output .= "</{$itemtag}>";
		}

		$output .= "
		</div>\n";
		return $output;
	}

endif;


/*
 * Add Custom Link to Media Items
 */

if (! function_exists('orbital_attachment_custom_link')) :

	function orbital_attachment_custom_link($form_fields, $post)
	{

		$form_fields['orbital_attachment_custom_link'] = array(
			'label' => 'Gallery Link URL',
			'input' => 'url',
			'value' => get_post_meta($post->ID, 'orbital_attachment_custom_link', true),
		);

		return $form_fields;
	}

endif;


/*
 * Save Custom Link to Media Items
 */

if (! function_exists('orbital_attachment_custom_link_save')) :

	function orbital_attachment_custom_link_save($post, $attachment)
	{

		if (isset($attachment['orbital_attachment_custom_link'])) {
			update_post_meta($post['ID'], 'orbital_attachment_custom_link', $attachment['orbital_attachment_custom_link']);
		}

		return $post;
	}

endif;


/*
 * Add custom options to Native Gallery
 */

if (! function_exists('orbital_gallery_settings')) :

	function orbital_gallery_settings()
	{
		?>
		<style>
			#tmpl-custom-gallery-setting {
				display: none;
			}
			.collection-settings.gallery-settings #tmpl-custom-gallery-setting{
				display: block;
			}
		</style>
		<div id="tmpl-custom-gallery-setting">
			<label class="setting">
				<span><?php esc_html_e('Masonry', 'orbital') ?></span>
				<input type="checkbox" data-setting="masonry">
			</label>
			<label class="setting">
				<span><?php esc_html_e('Titles', 'orbital') ?></span>
				<input type="checkbox" data-setting="titles">
			</label>
			<label class="setting">
				<span><?php esc_html_e('Caption', 'orbital') ?></span>
				<input type="checkbox" data-setting="legend">
			</label>
			<label class="setting">
				<span><?php esc_html_e('Wrapper', 'orbital') ?></span>
				<input type="checkbox" data-setting="wrapper">
			</label>
		</div>
		<script>
			jQuery(document).ready(function()
			{
				_.extend(wp.media.gallery.defaults, {
					masonry: false,
					titles: true,
					legend: true,
					wrapper: false,
				});
				wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
					template: function(view){
						return wp.media.template('gallery-settings')(view)
						+ wp.media.template('custom-gallery-setting')(view);
					}
				});
			});
		</script>
		<?php
	}

endif;
