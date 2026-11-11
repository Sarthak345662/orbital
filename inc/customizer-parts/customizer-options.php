<?php

// GOOGLE ANALYTICS
function orbital_enqueue_analytics() {
    if (orbital_gdpr_show_cookies('analytics_ads')) {
        if (orbital_customize_option('orbital_seo_analytics')) {
            echo orbital_customize_option('orbital_seo_analytics');
        }
    }
}

function orbital_enqueue_adsense() {
    if (orbital_gdpr_show_cookies('analytics_ads')) {
        if (orbital_customize_option('orbital_seo_adsense')) {
            echo orbital_customize_option('orbital_seo_adsense');
        }
    }
}

//GOOGLE FONTS SCRIPT
function orbital_fonts_head() {
    ?>
    <style>
    <?php
    if (orbital_customize_option('orbital_typo_headings')) {
        $heading = explode(':', orbital_customize_option('orbital_typo_headings'));
        ?>
            h1,h2,h3,h4,h5,h6, .title {
                font-family: '<?php echo $heading[0]; ?>', sans-serif;
                font-weight: <?php echo $heading[1]; ?>;
            }
        <?php
    }
    if (orbital_customize_option('orbital_typo_body')) {
        $body = explode(':', orbital_customize_option('orbital_typo_body'));
        ?>
            body, .site-header {
                font-family: '<?php echo $body[0]; ?>' , sans-serif;
                font-weight: <?php echo $body[1]; ?>;
            }
        <?php
    }
    if (orbital_customize_option('orbital_typo_logo')) {
        $logo = explode(':', orbital_customize_option('orbital_typo_logo'));
        ?>
            .site-logo a {
                font-family: '<?php echo $logo[0]; ?>' , sans-serif;
                font-weight: <?php echo $logo[1]; ?>;
            }
        <?php
    }
    ?>
    </style>
    <?php
}

function orbital_customize_css() {

    $container = orbital_customize_option('orbital_layout_container') ? orbital_customize_option('orbital_layout_container') : 'inherit';
    $relation = orbital_customize_option('orbital_layout_relation') ? orbital_customize_option('orbital_layout_relation') : 0;
    $order = orbital_customize_option('orbital_layout_sidebar_order') ? orbital_customize_option('orbital_layout_sidebar_order') : 0;
    ?>
    <style>
        @media(min-width: 48rem){

            .container {
                width: <?php print $container; ?>rem;
            }

            .entry-content {
                max-width: <?php print 100 - $relation; ?>%;
                flex-basis: <?php print 100 - $relation; ?>%;
            }

            .entry-aside {
                max-width: <?php print $relation; ?>%;
                flex-basis: <?php print $relation; ?>%;
                order: <?php echo $order; ?>;
                -ms-flex-order: <?php echo $order; ?>;

            }

        }


    <?php if (orbital_customize_option('orbital_link_color')) : ?>
            a {
                color: <?php echo orbital_customize_option('orbital_link_color'); ?>;
            }

        <?php endif; ?>


    <?php if (orbital_customize_option('orbital_navbar_background')) : ?>
            .site-header {
                background-color: <?php echo orbital_customize_option('orbital_navbar_background'); ?>;
            }

        <?php endif; ?>

    <?php if (orbital_customize_option('orbital_navbar_link_color')) : ?>
            .site-header a {
                color: <?php echo get_theme_mod('orbital_navbar_link_color'); ?>;
            }

            @media(min-width: 1040px){
                .site-navbar .menu-item-has-children:after {
                    border-color: <?php echo get_theme_mod('orbital_navbar_link_color'); ?>;
                }
            }
    <?php endif; ?>


    </style>

        <?php
    }
    