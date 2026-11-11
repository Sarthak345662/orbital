<?php
if (! is_active_sidebar('page-home') || ! orbital_get_option_page('sidebar')) {
	return;
}

$custom_sidebar = orbital_get_option_page('custom-sidebar', 'page-home');

if ($custom_sidebar == '') {
	$custom_sidebar = 'page-home';
}

$sticky = orbital_customize_option('orbital_layout_sidebar_sticky') ? 'sticky' : '';
?>
<aside id="secondary" class="widget-area entry-aside">
	<div class="widget-area-wrapper <?php echo $sticky ?>">
		<?php dynamic_sidebar($custom_sidebar); ?>
	</div>
</aside><!-- #secondary -->