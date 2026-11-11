<?php
/**
 * The footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */
$show_footer = true;
if(is_singular( array( 'post', 'page' ))){
    $show_footer = orbital_get_option_page('footer');
}
do_action('orbital_before_footer');
?>
<?php if($show_footer){ ?>

	<footer class="site-footer">
		<div class="container">
			<?php get_template_part('template-parts/footer/footer', 'widgets'); ?>
			<?php get_template_part('template-parts/footer/footer', 'credits'); ?>
		</div>
	</footer>
<?php } ?>
<?php do_action('orbital_after_footer'); ?>

<!-- Site Overlay -->
<div class="site-overlay"></div>

<?php wp_footer(); ?>
<?php 
if (!is_admin() && !wp_doing_ajax() && ( get_theme_mod('orbital_enable_' . get_post_type() ) ) ) :
	if(orbital_get_option_page('orbital_toc')){
		do_action('create_index');
	}
endif;?>
</body>
</html>