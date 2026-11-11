<?php

$path = get_template_directory() . '/vendor';
require_once $path . '/minify/src/Minify.php';
require_once $path . '/minify/src/CSS.php';
require_once $path . '/minify/src/JS.php';
require_once $path . '/minify/src/Exception.php';
require_once $path . '/minify/src/Exceptions/BasicException.php';
require_once $path . '/minify/src/Exceptions/FileImportException.php';
require_once $path . '/minify/src/Exceptions/IOException.php';
require_once $path . '/path-converter/src/ConverterInterface.php';
require_once $path . '/path-converter/src/Converter.php';

use MatthiasMullie\Minify;

class OrbitalMinifier {

    private $cachePath = '../cache/';
    private $cssMinifier;
    private $jsMinifier;
    public $cssFile;
    public $jsFile;
    private $hasJS = false;
    private $hasCss = false;

    public function __construct() {
        $this->cssMinifier = new Minify\CSS();
        $this->jsMinifier = new Minify\JS();
        $this->cssFile = 'orbital.min.css';
        $this->jsFile = 'orbital.min.js';

        /** Configure woff and ttf imports * */
        $extensions = $this->cssMinifier->getImportExtensions();
        $extensions['ttf'] = 'data:application/x-font-ttf';
        $this->cssMinifier->setImportExtensions($extensions);
    }

    public function setCachePath($cachePath): self {
        $this->cachePath = $cachePath;
        return $this;
    }

    public function getCachePath() {
        return $this->cachePath;
    }

    public function enqueueCss($css, $cached = false) {

        $this->hasCss = true;
        if (empty($css)) {
            return false;
        }

        if (filter_var($css, FILTER_VALIDATE_URL)) {
            if ($cached) {
                return true;
            }
            if (strpos($css, get_site_url()) !== false) {
                $css = str_replace(get_site_url(), ABSPATH, $css);
                $this->cssMinifier->add($css);
            } else {
                $content = file_get_contents($css);
                $this->cssMinifier->add($content);
            }

            return true;
        } else if (file_exists(ABSPATH . $css)) {
            if ($cached) {
                return true;
            }
            $content = file_get_contents(ABSPATH . $css);
            $this->cssMinifier->add(ABSPATH . $css);
            return true;
        } else {
            return false;
        }

        return true;
    }

    public function enqueueJs($js, $cached = false) {

        $this->hasJS = true;
        if (empty($js)) {
            return false;
        }

        if (filter_var($js, FILTER_VALIDATE_URL)) {

            if ($cached) {
                return true;
            }
            if (strpos($js, get_site_url()) !== false) {
                $js = str_replace(get_site_url(), ABSPATH, $js);
                $this->jsMinifier->add($js);
            } else {
                $content = file_get_contents($js);
                $this->jsMinifier->add($content);
            }

            return true;
        } else if (file_exists(ABSPATH . $js)) {
            if ($cached) {
                return true;
            }
            $content = file_get_contents(ABSPATH . $js);
            $this->jsMinifier->add(ABSPATH . $js);
            return true;
        } else {
            $this->jsMinifier->add($js);
            return true;
        }

        return true;
    }

    public function run() {

        if (!is_dir($this->cachePath)) {
            wp_mkdir_p($this->cachePath);
        }

        if ($this->hasCss) {
            $this->cssMinifier->minify($this->cachePath . $this->cssFile);
        }
        if ($this->hasJS) {
            $this->jsMinifier->minify($this->cachePath . $this->jsFile);
        }
    }

    public function getMinifiedCSS() {

        return $this->cssMinifier->minify();
    }

    public function getMinifiedJS() {
        return $this->jsMinifier->minify();
    }

}

function orbital_dequeue_style($style_name) {
    global $wp_styles;
    wp_dequeue_style($style_name);
    $wp_styles->done[] = $style_name;
    if (isset($wp_styles->groups[$style_name])) {
        unset($wp_styles->groups[$style_name]);
    }
}

function orbital_minify_css($filename = 'orbital.min.css') {
    if (is_admin() || is_customize_preview()) {
        return;
    }
    $minifier = new OrbitalMinifier();
    $minifier->setCachePath(get_template_directory() . '/cache/');
    $minifier->cssFile = $filename;

    $cssCacheFile = $minifier->getCachePath() . $minifier->cssFile;
    if (!orbital_customize_option('orbital_performance_render_blocking_css')) {
        @unlink($cssCacheFile);
        return;
    }

    $cached = false;
    if (file_exists($cssCacheFile)) {
        $diff = time() - filemtime($cssCacheFile);
        if ($diff < 3600) { //1h CACHE
            $cached = true;
        }
    }


    global $wp_styles;

    $front_css = array_diff($wp_styles->queue, array('admin-bar', 'yoast-seo-adminbar', 'woocommerce-inline'));
    $dependencies = array();
    foreach ($wp_styles->registered as $asset) {
        if (in_array($asset->handle, $front_css)) {
            foreach ($asset->deps as $dep) {
                $dependencies = array_merge($dependencies, get_script_dependencies($dep));
            }
        }
    }
    $dependencies = array_unique($dependencies);
    //load dependencies  
    foreach ($wp_styles->registered as $asset) {
        if (in_array($asset->handle, $dependencies)) {
            if ($minifier->enqueueCss($asset->src, $cached)) {
                orbital_dequeue_style($asset->handle);
                if (isset($asset->extra['after'])) {
                    $minifier->enqueueCss($asset->extra['after'], $cached);
                }
            }
        }
    }


    foreach ($front_css as $style) {
        if ($minifier->enqueueCss($wp_styles->registered[$style]->src, $cached)) {
            orbital_dequeue_style($style);
        }

        if (isset($wp_styles->registered[$style]->extra['after'])) {
            $minifier->enqueueCss($wp_styles->registered[$style]->extra['after'], $cached);
        }
    }
    if (!$cached) {
        $minifier->run();
    }
    //wp_enqueue_style('orbital-cached-css', str_replace(get_template_directory(), get_template_directory_uri(), $cssCacheFile));
    echo '<style>' . file_get_contents($cssCacheFile) . '</style>';
}

function orbital_minify_head_css() {
    orbital_minify_css('orbital.min.css');
}

add_action('wp_print_styles', 'orbital_minify_head_css', 100);

function get_script_dependencies($script_name) {
    global $wp_scripts;

    $dependencies = array();
    foreach ($wp_scripts->registered as $asset) {
        if ($asset->handle == $script_name) {
            foreach ($asset->deps as $dep) {
                $dependencies = array_merge($dependencies, get_script_dependencies($dep));
            }
        }
    }
    $dependencies[] = $script_name;
    return $dependencies;
}

function orbital_minify_js() {

    if (is_admin() || is_customize_preview()) {
        return;
    }

    $minifier = new OrbitalMinifier();
    $minifier->setCachePath(get_template_directory() . '/cache/');

    $jsCacheFile = $minifier->getCachePath() . $minifier->jsFile;
    if (!orbital_customize_option('orbital_performance_render_blocking_js')) {
        @unlink($jsCacheFile);
        return;
    }

    //BUG
    if (file_exists($jsCacheFile) && !orbital_customize_option('orbital_performance_render_blocking_jquery')) {
        $file_content = file_get_contents($jsCacheFile);
        if (strpos($file_content, 'jquery.org/license') === false) {
            @unlink($jsCacheFile);
        }
    }


    $cached = false;
    if (file_exists($jsCacheFile)) {
        $diff = time() - filemtime($jsCacheFile);
        if ($diff < 3600) { //1h CACHE
            $cached = true;
        }
    }
    global $wp_scripts;


    $dependencies = array();
    foreach ($wp_scripts->registered as $asset) {
        if (in_array($asset->handle, $wp_scripts->queue)) {
            foreach ($asset->deps as $dep) {
                $dependencies = array_merge($dependencies, get_script_dependencies($dep));
            }
        }
    }
    $dependencies = array_unique($dependencies);


    //unload jquery dependencies
    if (orbital_customize_option('orbital_performance_render_blocking_jquery') == 1) {
        if (($key = array_search('jquery', $dependencies)) !== false) {
            unset($dependencies[$key]);
        }
        if (($key = array_search('jquery-core', $dependencies)) !== false) {
            unset($dependencies[$key]);
        }
        if (($key = array_search('jquery-migrate', $dependencies)) !== false) {
            unset($dependencies[$key]);
        }
    }

    //load dependencies  
    foreach ($wp_scripts->registered as $asset) {
        if (in_array($asset->handle, $dependencies)) {
            if ($minifier->enqueueJs($asset->src, $cached)) {
                wp_dequeue_script($asset->handle);
                $wp_scripts->done[] = $asset->handle;
            }
        }
    }

    //load scripts
    foreach ($wp_scripts->registered as $asset) {
        if (in_array($asset->handle, $wp_scripts->queue)) {
            if ($minifier->enqueueJs($asset->src, $cached)) {
                wp_dequeue_script($asset->handle);
                $wp_scripts->done[] = $asset->handle;
            }

            if (isset($asset->extra['data'])) {
                if ($minifier->enqueueJs($asset->extra['data'], $cached)) {
                    
                }
            }
        }
    }

    if (!$cached) {
        $minifier->run();
    }
    if (orbital_customize_option('orbital_performance_render_blocking_jquery') == 1) {
        wp_enqueue_script('jquery');
    }
    wp_enqueue_script('orbital-cached-js', str_replace(get_template_directory(), get_template_directory_uri(), $jsCacheFile . "#orbital-async"), array(), false, true);
}

function orbital_async_scripts($url) {

    if (is_admin() || is_customize_preview() || !orbital_customize_option('orbital_performance_render_blocking_js' || strpos($url, '#orbital-async') === false)) {
        return $url;
    }

    return str_replace('#orbital-async', '', $url) . "' async='async";
}

add_filter('clean_url', 'orbital_async_scripts', 99, 1);

add_action('wp_print_scripts', 'orbital_minify_js', 100);
