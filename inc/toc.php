<?php

/*
 * Table of Content - Native post index
 */
 
add_action( 'create_index', 'auto_index' );

function auto_index(){
    ?>
    <style>
    <?php
    if (get_theme_mod('orbital_hide_index') && (get_theme_mod('orbital_user_hide_index'))): ?> 
        .post-index #index-table {
            display:none;
        }
    <?php endif; 

    $orbital_index_list    = get_theme_mod('orbital_index_list');
    if (!$orbital_index_list)
    {
        $orbital_index_list    = '1';
    }

    if ($orbital_index_list == 3): ?> 
        .post-index .bullet-li {
            display: none;
        }
    <?php endif;?>
    </style>
    <?php

	
	$orbital_index_options = get_theme_mod('orbital_index_options');
	
	if ( ! 	$orbital_index_options ) : $orbital_index_options = 1; endif;
	if ( 	$orbital_index_options == 1 ) : $orbital_show_headers = 'h2'; endif;
	if ( 	$orbital_index_options == 2 ) : $orbital_show_headers = 'h2,h3'; endif;
    if ( 	$orbital_index_options == 3 ) : $orbital_show_headers = 'h2,h3,h4'; endif;
    if ( 	$orbital_index_options == 4 ) : $orbital_show_headers = 'h2,h3,h4,h5'; endif;
    if ( 	$orbital_index_options == 5 ) : $orbital_show_headers = 'h2,h3,h4,h5,h6'; endif;
    
    $orbital_index_list = get_theme_mod('orbital_index_list'); 

    if ( ! $orbital_index_list ) : $orbital_index_list = 1; endif;

    $orbital_index_text = ( $orbital_index_list == 1  ) ? "true" : "false";

	if (!is_admin() && !wp_doing_ajax() && ( get_theme_mod('orbital_enable_' . get_post_type() ) ) ) : 

	?>
	
	<script type="text/javascript" id="auto-index" async defer>

        function indexContent(e) {
            var t = document.querySelector(e);
            table = document.getElementById("index-table");
            const n = e => e.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            var n_h = {
                "H2": 0,
                "H3": 0,
                "H4": 0,
                "H5": 0,
                "H6": 0,
            };
            for (var a = Array.from(t.querySelectorAll("<?php echo $orbital_show_headers; ?>")), r = 0, o = a.length; r < o; r++) {
                var l = a[r],
                    d = n(d = (d = a[r].textContent).replace(/ /g, "_"));
                l.setAttribute("id", d);
                var c = document.createElement("SPAN");
                var this_number = "";
                console.log("r", r);
                if("H2" == a[r].nodeName){
                    n_h["H2"] = n_h["H2"] + 1;
                    n_h["H3"] = 0;
                    n_h["H4"] = 0;
                    n_h["H5"] = 0;
                    n_h["H6"] = 0;
                    this_number = n_h["H2"];
                }
                if("H3" == a[r].nodeName){
                    c.setAttribute("class", "classh3");
                    n_h["H3"] = n_h["H3"] + 1;
                    n_h["H4"] = 0;
                    n_h["H5"] = 0;
                    n_h["H6"] = 0;
                    this_number = n_h["H2"] + "." + n_h["H3"];
                }
                if("H4" == a[r].nodeName){
                    c.setAttribute("class", "classh4");
                    n_h["H4"] = n_h["H4"] + 1;
                    n_h["H5"] = 0;
                    n_h["H6"] = 0;
                    this_number = n_h["H2"] + "." + n_h["H3"] + "." + n_h["H4"];
                }
                if("H5" == a[r].nodeName){
                    c.setAttribute("class", "classh5");
                    n_h["H5"] = n_h["H5"] + 1;
                    n_h["H6"] = 0;
                    this_number = n_h["H2"] + "." + n_h["H3"] + "." + n_h["H4"] + "." + n_h["H5"];
                }
                if("H6" == a[r].nodeName){
                    c.setAttribute("class", "classh6");
                    n_h["H6"] = n_h["H6"] + 1;
                    this_number = n_h["H2"] + "." + n_h["H3"] + "." + n_h["H4"] + "." + n_h["H5"] + "." + n_h["H6"];
                }
                var pre_text = (<?php echo $orbital_index_text; ?>)? this_number + ". " : "• ";

                var bullet = document.createElement("SPAN");
                bullet.setAttribute("class", "bullet-li");
                var bulletText = document.createTextNode( pre_text );

                var u = document.createElement("A"),
                    i = document.createTextNode(l.textContent);
                bullet.appendChild(bulletText), u.appendChild(bullet), u.appendChild(i), u.setAttribute("href", "#" + d), c.appendChild(u), 0 < i.length && table.appendChild(c);

            }
        }
        indexContent(".toc-content");		
	</script>
	
	<?php
	
	endif;
	
}


add_filter( 'the_content', 'orbital_add_index', 20 );

function orbital_add_index ( $content ) {
	
		
	if (  !is_admin() && !wp_doing_ajax() && ( get_theme_mod('orbital_enable_' .get_post_type() ) ) ) {		
		if(orbital_get_option_page('orbital_toc')){
            $has_shortcode = strpos($content, "<!--o_TOC-->") !== false;
            if( $has_shortcode){
                $pre_tag = "";
                $post_tag = "";
                $current_tag = "";
            }else{
                if(get_theme_mod('orbital_index_position') === '2'){
                    $pre_tag = "";
                    $post_tag = "</h2>";
                    $current_tag = "</h2>";
                }elseif(get_theme_mod('orbital_index_position') === '3'){
                    $pre_tag = "";
                    $post_tag = "";
                    $current_tag = "";
                }elseif(get_theme_mod('orbital_index_position') === '4'){
                    $pre_tag = "";
                    $post_tag = "";
                    $current_tag = "";
                }else{
                    $pre_tag = "<h2";
                    $post_tag = "";
                    $current_tag = "<h2";
                }
            }

            $orbital_index_text = get_theme_mod('orbital_index_text');

            $orbital_index_list = get_theme_mod('orbital_index_list'); 
                
            $hide_index = get_theme_mod('orbital_hide_index');

            $user_hide_index = get_theme_mod('orbital_user_hide_index');

            if ( ! $orbital_index_text ) : $orbital_index_text = __('Index', 'orbital'); endif;
            
            if ( ! $orbital_index_list ) : $orbital_index_list = 1; endif;

            if ( $hide_index ) : 
            
                $icon = '<label class="checkbox"><input type="checkbox"/ ><span class="check-table" ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="6 9 12 15 18 9" /></svg></span></label>';
        
            else: 
            
                $icon = '<label class="checkbox"><input type="checkbox"/ ><span class="check-table" ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="6 15 12 9 18 15" /></svg></span></label>';
        
            endif;
            
            if ( $user_hide_index ) :
            
                if ( $orbital_index_list == 1 ) :
        
                    $indice = $post_tag .'<div class="post-index" ><span>'.$orbital_index_text.'<span class="btn-show">'.$icon.'</span></span><div id="index-table"></div></div>'. $pre_tag;
            
                else:
                
                    $indice = $post_tag .'<div class="post-index"><span>'.$orbital_index_text.'<span class="btn-show">'.$icon.'</span></span><div id="index-table"></div></div>'. $pre_tag;

                endif;	
            
            else:
            
                if ( $orbital_index_list == 1 ) :
            
                    $indice = $post_tag . '<div class="post-index"><span>'.$orbital_index_text.'</span><ol id="index-table"></ol></div>' . $pre_tag;
            
                else:
            
                    $indice = $post_tag . '<div class="post-index"><span>'.$orbital_index_text.'</span><ul id="index-table"></ul></div>' . $pre_tag;
            
                endif;
            
            endif;

            if( $has_shortcode ){
                $content = preg_replace('/(<!--o_TOC-->)/i', $indice, $content, 1);
            }else{
                if(get_theme_mod('orbital_index_position') === '2'){
                    $content = preg_replace('/(<\/h2>)/i', $indice, $content, 1);
                }elseif(get_theme_mod('orbital_index_position') === '3'){
                    $content = $indice . $content;
                }elseif(get_theme_mod('orbital_index_position') === '4'){
                    $content = $content . $indice;
                }else{
                    $content = preg_replace('/(<h2)/i', $indice, $content, 1);
                }
            }
        }
		
	}
	
	return $content;

}

// function that runs when shortcode is called
function orbital_toc_func() { 
 
    // Things that you want to do. 
    $message = '<!--o_TOC-->'; 
     
    // Output needs to be return
    return $message;
    } 
    // register shortcode
add_shortcode('orbital_toc', 'orbital_toc_func', 1); 

?>