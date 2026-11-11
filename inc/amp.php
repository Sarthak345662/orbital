<?php

define('AMP_QUERY_VAR', apply_filters('amp_query_var', 'amp'));
add_rewrite_endpoint(AMP_QUERY_VAR, EP_PERMALINK | EP_PAGES | EP_ROOT | EP_ALL_ARCHIVES);
add_filter('template_include', 'orbital_amp_init', 99);
flush_rewrite_rules();

function orbital_amp_init($template)
{

	if (get_query_var(AMP_QUERY_VAR, false) !== false) {
		if (is_single()) {
			$template = get_template_directory() .  '/template-parts/amp/amp-single.php';
		}

		if (is_page()) {
			$template = get_template_directory() .  '/template-parts/amp/amp-page.php';
		}

		if (is_archive()) {
			$template = get_template_directory() .  '/template-parts/amp/amp-archive.php';
		}

		if (is_home()) {
			$template = get_template_directory() .  '/template-parts/amp/amp-home.php';
		}
	}

	return $template;
}

//Activar/Desactivar AMP
//Elegir que plantillas tienen AMP Single/Pages/Archives/WooCommerce/Home
//Flush URLS Solo una vez
//Analytics
//Añadir Yoast SEO action


function orbital_amp_content($content)
{
	$converter = new AmpConverter();
	$ampHtml = $converter->convert($content);
	$amp = new AMP(apply_filters('the_content', $ampHtml));

	$amp->convertedHTML();
}


class AMP
{

	private $html;

	public function __construct($htmlContent)
	{
		$this->html = $htmlContent;
	}


	public function convertedHTML()
	{
		echo $this->ampify();
	}

	private function replaceTagsMain()
	{

		$this->html = str_ireplace(
			['<html', 'https:', 'http:', '<img', '<video', '/video>', '<audio', '/audio>'],
			['<html amp', '', '', '<amp-img', '<amp-video', '/amp-video>', '<amp-audio', '/amp-audio>'],
			$this->html
		);
	}

	private function replaceYoutubeIframe()
	{

		$this->html = preg_replace('/<iframe(.*)(src=\"\/\/www\.youtube\.com\/embed)(.*)"(.*)><\/iframe>/', '<amp-youtube data-videoid="$3"  layout="responsive"></amp-youtube>', $this->html);
	}


	private function replaceBodyContent()
	{
		$this->html = preg_replace('/<amp-img(.*?)\/?>/', '<amp-img height="500" width="281" layout="responsive"$1></amp-img>', $this->html);
	}

	public static function scriptEnqueueAMP($dom)
	{
        //
	}

	private static function checkYouTubeIframe($dom)
	{

		return true;
	}

	protected function ampify()
	{
		$this->replaceTagsMain();
		$this->replaceYoutubeIframe();
		$this->replaceBodyContent();
		return $this->html;
	}
}

function change_video_markup_to_amp($output, $atts, $video, $post_id, $library)
{

    /*
    change output only on 'post' post type, you might wanna get
    rid of this if you want to change video markup everywhere
    on your site
    */
    if (! 'post' === get_post_type($post_id)) {
    	return $output;
    }

    /*
    get video data, you can also check other $atts array
    keys for different video formats, by default you'll find:
    'mp4', 'm4v', 'webm', 'ogv', 'flv'.
    */
    $video_url = ! empty($atts['mp4']) ? $atts['mp4'] : '';
    $height    = ! empty($atts['height']) ? $atts['height'] : '';
    $width     = ! empty($atts['width']) ? $atts['width'] : '';

    // return default shortcode output if no video url is found
    if (empty($video_url)) {
    	return $output;
    }

    // now put the amp markup together
    $amp_output = sprintf('<amp-video controls width="%1$d" height="%2$d" layout="responsive"><source src="%3$s" /></amp-video>', absint($width), absint($height), esc_url($video_url));

    return $amp_output;
}

add_filter('wp_video_shortcode', 'change_video_markup_to_amp', 10, 5);
