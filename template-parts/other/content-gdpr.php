<?php
if (!function_exists('orbital_get_scrit_tags')) :

    function orbital_get_scrit_tags($script) {
        $attArr = explode(' ', $script);
        $attributes = array();
        foreach ($attArr as $attr) {
            $keyValue = explode("=", $attr);
            if (empty($keyValue[0])) {
                continue;
            }
            if (isset($keyValue[1])) {
                $attributes[$keyValue[0]] = str_replace('"', '', $keyValue[1]) ;
            } else {
                if (strtolower($keyValue[0]) == 'async' || strtolower($keyValue[0]) == 'defer') {
                    $attributes[$keyValue[0]] = 'true';
                } else {
                    $attributes[$keyValue[0]] = '';
                }
            }
        }
        return $attributes;
    }

endif;


if (!function_exists('orbital_clear_scripts')) :

    function orbital_clear_scripts($scripts) {
        $scripts = str_replace(array(PHP_EOL, "\n", "\r"), '', $scripts);
        $scripts = str_replace("'", "\"", $scripts);
        preg_match_all('#<script(.*?)>(.*?)<\/script>#is', $scripts, $matches);

        $ret = array(
            'scripts' => array(),
            'tags' => array(),
        );

        if (!$matches[2]) {
            $ret['scripts'] = (array) $scripts;
            $ret['tags'][] = array();
            return $ret;
        }

        foreach ($matches[2] as $key => $script) {

            $ret['scripts'][] = $script;
            $ret['tags'][] = orbital_get_scrit_tags($matches[1][$key]);
        }
        return $ret;
    }

endif;

$description = orbital_customize_option('orbital_gdpr_general_message', 'orbital') . '<a class="c_link link" href="' . orbital_customize_option('orbital_gdpr_general_link') . '">' . orbital_customize_option('orbital_gdpr_general_text_link') . '</a>';
$code_necessary = orbital_clear_scripts(orbital_customize_option('orbital_gdpr_strictly_necessary_code'));
$code_functional = orbital_clear_scripts(orbital_customize_option('orbital_gdpr_functional_code'));
$code_performance = orbital_clear_scripts(orbital_customize_option('orbital_gdpr_performance_code'));
$code_analytics = orbital_clear_scripts(orbital_customize_option('orbital_seo_analytics'));
$code_adsense = orbital_clear_scripts(orbital_customize_option('orbital_seo_adsense'));
$code_other = orbital_clear_scripts(orbital_customize_option('orbital_gdpr_other_code'));



?>
<div id="cookies-wrapper"></div><div class="cc__change_settings cc_cs_<?php echo orbital_customize_option('orbital_gdpr_general_change_position') ?>" >  
    <a href="javascript:void(0);" aria-label="View cookie settings" data-cc="c-settings"><?php echo orbital_customize_option('orbital_gdpr_general_btn_settings'); ?></a>
</div>
<script>

    window.addEventListener("load", function () {

    var cc = initCookieConsent();
    
    function clearCookie(name, domain, path){
	try {
	    function Get_Cookie( check_name ) {
	            // first we'll split this cookie up into name/value pairs
	            // note: document.cookie only returns name=value, not the other components
	            var a_all_cookies = document.cookie.split(';'),
	            	a_temp_cookie = '',
	        		cookie_name = '',
	            	cookie_value = '',
		            b_cookie_found = false;
	    
	            for ( i = 0; i < a_all_cookies.length; i++ ) {
                    // now we'll split apart each name=value pair
                    a_temp_cookie = a_all_cookies[i].split( '=' );
    
                    // and trim left/right whitespace while we're at it
                    cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');
    
                    // if the extracted name matches passed check_name
                    if ( cookie_name == check_name ) {
                        b_cookie_found = true;
                        // we need to handle case where cookie has no value but exists (no = sign, that is):
                        if ( a_temp_cookie.length > 1 ) {
                            cookie_value = unescape( a_temp_cookie[1].replace(/^\s+|\s+$/g, '') );
                        }
                        // note that in cases where cookie is initialized but no value, null is returned
                        return cookie_value;
                        break;
                    }
                    a_temp_cookie = null;
                    cookie_name = '';
	            }
	            if ( !b_cookie_found ) {
	              return null;
	            }
	        }
	        if (Get_Cookie(name)) {
                var domain = domain || document.domain;
                var path = path || "/";
                document.cookie = name + "=; expires=" + new Date + "; domain=" + domain + "; path=" + path;
	        }
	}
	catch(err) {}    
};
    
    
    function deleteCookies() {
        var cookies = document.cookie.split(";");

        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i];
            var eqPos = cookie.indexOf("=");
            var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
            if (cookie.indexOf("cc_cookie") == -1) {
                document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;";
                document.cookie = name.trim() + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;";
                clearCookie(name.trim(), window.location.hostname, '/');
                clearCookie(name.trim(), '.'+window.location.hostname, '/');
                clearCookie(name, window.location.hostname, '/');
                clearCookie(name, '.'+window.location.hostname, '/');
            }
        }
    }
    
    function deleteOnlyGaCookies() {
        var cookies = document.cookie.split(";");

        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i];
            var eqPos = cookie.indexOf("=");
            var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
            if (cookie.indexOf("ga") > -1) {
                document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;";
                document.cookie = name.trim() + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;";
                clearCookie(name.trim(), window.location.hostname, '/');
                clearCookie(name.trim(), '.' + window.location.hostname, '/');
                clearCookie(name, window.location.hostname, '/');
                clearCookie(name, '.' + window.location.hostname, '/');
            }
        }
    }
    
    
    
    function addCookies() {
<?php foreach ($code_necessary['scripts'] as $k => $c) : ?>
        var script = document.createElement("script");
        script.innerHTML = '<?php echo $c ?>';
        document.head.append(script);
<?php endforeach; ?>

<?php if (orbital_customize_option('orbital_gdpr_functional_active')) : ?>
        if (cc.allowedCategory('functional')) {
    <?php foreach ($code_functional['scripts'] as $k => $c) : ?>
            var script = document.createElement("script");
        <?php foreach ($code_functional['tags'][$k] as $key => $value) : ?>
                script.setAttribute("<?php echo $key; ?>", "<?php echo $value; ?>");
        <?php endforeach; ?>
            script.innerHTML = '<?php echo $c ?>';
            document.head.append(script);
    <?php endforeach; ?>
        }
<?php endif; ?>

<?php if (orbital_customize_option('orbital_gdpr_performance_active')) : ?>
        if (cc.allowedCategory('performance')) {
    <?php foreach ($code_performance['scripts'] as $k => $c) : ?>
            var script = document.createElement("script");
        <?php foreach ($code_performance['tags'][$k] as $key => $value) : ?>
                script.setAttribute("<?php echo $key; ?>", "<?php echo $value; ?>");
        <?php endforeach; ?>
            script.innerHTML = '<?php echo $c ?>';
            document.head.append(script);
    <?php endforeach; ?>
        }
<?php endif; ?>

<?php if (orbital_customize_option('orbital_gdpr_analytics_ads_active')) : ?>
        if (cc.allowedCategory('analytics_ads')) {
    <?php foreach ($code_analytics['scripts'] as $k => $c) : ?>
            var script = document.createElement("script");
        <?php foreach ($code_analytics['tags'][$k] as $key => $value) : ?>
                script.setAttribute("<?php echo $key; ?>", "<?php echo $value; ?>");
        <?php endforeach; ?>
            script.innerHTML = '<?php echo $c ?>';
            document.head.append(script);
    <?php endforeach; ?>
    <?php foreach ($code_adsense['scripts'] as $k => $c) : ?>
            var script = document.createElement("script");
        <?php foreach ($code_adsense['tags'][$k] as $key => $value) : ?>
                script.setAttribute("<?php echo $key; ?>", "<?php echo $value; ?>");
        <?php endforeach; ?>
            script.innerHTML = '<?php echo $c ?>';
            document.head.append(script);
    <?php endforeach; ?>
        }
<?php endif; ?>

<?php if (orbital_customize_option('orbital_gdpr_other_active')) : ?>
        if (cc.allowedCategory('other')) {
    <?php foreach ($code_other['scripts'] as $k => $c) : ?>
            var script = document.createElement("script");
        <?php foreach ($code_other['tags'][$k] as $key => $value) : ?>
                script.setAttribute("<?php echo $key; ?>", "<?php echo $value; ?>");
        <?php endforeach; ?>
            script.innerHTML = '<?php echo $c ?>';
            document.head.append(script);
    <?php endforeach; ?>
        }

<?php endif; ?>

    }

// run plugin with config object
    cc.run({
    autorun: true,
            delay: 1000,
            current_lang: 'en',
            autoclear_cookies: true,
            cookie_expiration: 365,
            autoload_css: false,
            onAccept: function (cookie) {
<?php if (!isset($_COOKIE['cc_cookie'])) : ?>
                addCookies();
<?php endif ?>
           
                const mediaQuery = window.matchMedia('(max-width: 768px)')
                var hasSocialBottom = document.getElementsByClassName('social-bottom');
                if (mediaQuery.matches && hasSocialBottom.length > 0) {
                    
                   var bottom = hasSocialBottom[0].offsetHeight;
                     document.getElementsByClassName('cc__change_settings')[0].style.bottom = (bottom-1) + "px";
                    
                }
                    document.getElementsByClassName('cc__change_settings')[0].style.visibility = "visible";
                
            },
            onChange: function (cookie) {
               deleteCookies();
               
               addCookies();
<?php if (orbital_gdp_has_ads()) : ?>
                setTimeout(function(){ window.location.reload(true); }, 500);
<?php endif ?>
                
            },
            languages: {
            'es': {
            consent_modal: {
            title: "",
                    description: '<?php echo $description; ?>',
                    primary_btn: {
                    text: '<?php echo orbital_customize_option('orbital_gdpr_general_btn_accept'); ?>',
                            role: 'accept_all'				//'accept_selected' or 'accept_all'
                    },
                    secondary_btn: {
                    text: '<?php echo orbital_customize_option('orbital_gdpr_general_btn_settings'); ?>',
                            role: 'settings'				//'settings' or 'accept_necessary'
                    }
            },
                    settings_modal: {
                    title: '<?php echo orbital_customize_option('orbital_gdpr_general_popup_title'); ?>',
                            save_settings_btn: "<?php echo orbital_customize_option('orbital_gdpr_general_popup_btn'); ?>",
                            accept_all_btn: "",
                            cookie_table_headers: [],
                            blocks: [
                            {
                            title: "",
                                    description: '<?php echo orbital_customize_option('orbital_gdpr_general_message', 'orbital'); ?>'
                            },
                            {
                            title: "<?php echo orbital_customize_option('orbital_gdpr_strictly_necessary_text'); ?>",
                                    description: "HOLA <?php echo orbital_customize_option('orbital_gdpr_strictly_necessary_desc'); ?>",
                                    toggle: {
                                    value: 'strictly_necessary',
                                            enabled: true,
                                            readonly: true
                                    }
                            },
<?php if (orbital_customize_option('orbital_gdpr_functional_active')) : ?>
                                {
                                title: "<?php echo orbital_customize_option('orbital_gdpr_functional_text'); ?>",
                                        description: '<?php echo orbital_customize_option('orbital_gdpr_functional_desc'); ?>',
                                        toggle: {
                                        value: 'functional',
                                                enabled: false,
                                                readonly: false
                                        }
                                },
<?php endif; ?>

<?php if (orbital_customize_option('orbital_gdpr_performance_active')) : ?>
                                {
                                title: "<?php echo orbital_customize_option('orbital_gdpr_performance_text'); ?>",
                                        description: '<?php echo orbital_customize_option('orbital_gdpr_performance_desc'); ?>',
                                        toggle: {
                                        value: 'performance',
                                                enabled: false,
                                                readonly: false
                                        }
                                },
<?php endif; ?>

<?php if (orbital_customize_option('orbital_gdpr_analytics_ads_active')) : ?>
                                {
                                title: "<?php echo orbital_customize_option('orbital_gdpr_analytics_ads_text'); ?>",
                                        description: '<?php echo orbital_customize_option('orbital_gdpr_analytics_ads_desc'); ?>',
                                        toggle: {
                                        value: 'analytics_ads',
                                                enabled: false,
                                                readonly: false
                                        }
                                },
<?php endif; ?>

<?php if (orbital_customize_option('orbital_gdpr_other_active')) : ?>
                                {
                                title: "<?php echo orbital_customize_option('orbital_gdpr_other_text'); ?>",
                                        description: '<?php echo orbital_customize_option('orbital_gdpr_other_desc'); ?>',
                                        toggle: {
                                        value: 'other',
                                                enabled: false,
                                                readonly: false
                                        }
                                },
<?php endif; ?>
                            ]
                    }
            }
            }
    });
    cc.show(0);
    
    if (!cc.allowedCategory('analytics_ads')) {
        deleteOnlyGaCookies();
    }
    
    
<?php if (orbital_customize_option('orbital_gdpr_general_mandatory')) : ?>
        document.documentElement.className += ' force--consent';
<?php endif ?>
<?php if (orbital_customize_option('orbital_gdpr_general_position') == 'middle') : ?>
        document.documentElement.className += ' position--middle';
<?php endif ?>
    });

</script>