<?php

//Lazy Images

function orbital_filter_gallery_img_atts($atts, $attachment, $size)
{

	//var_dump( get_intermediate_image_sizes() );
	$image = wp_get_attachment_image_src($attachment->ID, $size);
	$thumbnail = wp_get_attachment_image_src($attachment->ID, 'lazy-'. $size);


	$atts['class'] .= ' lazy';
	$atts['data-src'] = $image[0];
	$atts['src'] = $thumbnail[0];

	return $atts;
}


function orbital_disable_srcset($sources)
{
	return false;
}

function orbital_lazy_load_thumbnail_size()
{
	add_image_size('lazy-thumbnail', get_option("thumbnail_size_w") / 2, get_option("thumbnail_size_h") / 2);
	add_image_size('lazy-medium', get_option("medium_size_w") / 2, get_option("medium_size_h") / 2);
	add_image_size('lazy-large', get_option("large_size_w") / 2, get_option("large_size_h") / 2);
	add_image_size(
		'lazy-thumbnail-center',
		orbital_customize_option('orbital_loop_cluster_img_width', 390) / 2,
		orbital_customize_option('orbital_loop_cluster_img_height', 200) / 2,
		array( 'top', 'center' )
	);
	add_image_size('lazy-thumbnail-featured', 140, 168, array( 'center', 'center' ));
}


function orbital_lazy_content_images($content)
{

	libxml_use_internal_errors(true);

	if (! $content) {
		return;
	}

	$post = new DOMDocument();
	libxml_use_internal_errors(true);
	$post->strictErrorChecking = false;
	$post->preserveWhiteSpace  = false;
	$post->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
	libxml_clear_errors();

	$imgs = $post->getElementsByTagName('img');

	foreach ($imgs as $img) {
		if ($img->hasAttribute('data-src')) {
			continue;
		}

		$imgClass = $img->getAttribute('class');
		$src = $img->getAttribute('src');

		$match_class = preg_match('/^.*wp-image-(\d+).*$/', $imgClass, $attachment_id);
		$match_dimensions = preg_match('/^.*-(\d+)x(\d+)\..*$/', $src, $dimensions);
		if (! $match_class) {
			continue;
		}

		$image = wp_get_attachment_image_src($attachment_id[1], 'large');

		if ($match_dimensions) {
			$img->setAttribute('width', $dimensions[1]);
			$img->setAttribute('height', $dimensions[2]);

			if ($dimensions[1] >= 1024) {
				$image = wp_get_attachment_image_src($attachment_id[1], 'lazy-large');
			} elseif ($dimensions[1] >= 512) {
				$image = wp_get_attachment_image_src($attachment_id[1], 'lazy-medium');
			} else {
				$image = wp_get_attachment_image_src($attachment_id[1], 'lazy-thumbnail');
			}
		}

		$img->setAttribute('class', $imgClass . ' lazy');

		$img->setAttribute('src', $image[0]);
		$img->setAttribute('data-src', $src);
	};
	$content = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $post->saveHTML());
	return $content;
}

function orbital_lazy_load_insert()
{
	?>
	<script>

		(function(){


			let lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));
			let active = false;

			const lazyLoad = function() {
				if (active === false) {
					active = true;

					setTimeout(function() {
						lazyImages.forEach(function(lazyImage) {
							if ((lazyImage.getBoundingClientRect().top <= window.innerHeight + 30 && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== "none") {


								lazyImage.src = lazyImage.dataset.src;
								lazyImage.classList.remove("lazy");

								lazyImages = lazyImages.filter(function(image) {
									return image !== lazyImage;
								});

								if (lazyImages.length === 0) {
									document.removeEventListener("scroll", lazyLoad);
									window.removeEventListener("resize", lazyLoad);
									window.removeEventListener("orientationchange", lazyLoad);
								}
							}
						});

						active = false;
					}, 200);

				}
			};



			document.addEventListener("DOMContentLoaded", lazyLoad);
			document.addEventListener("scroll", lazyLoad);
			window.addEventListener("resize", lazyLoad);
			window.addEventListener("orientationchange", lazyLoad);

		})();


	</script>

	<?php
}




add_action('after_setup_theme', 'orbital_lazy_load_thumbnail_size');
add_filter('wp_get_attachment_image_attributes', 'orbital_filter_gallery_img_atts', 10, 3);
add_filter('wp_calculate_image_srcset', 'orbital_disable_srcset');
add_filter('the_content', 'orbital_lazy_content_images', 15);
add_action('wp_footer', 'orbital_lazy_load_insert');
