<?php
if (orbital_check_woocommerce()) {
	if (is_cart()) {
		$class = 'current-menu-item';
	} else {
		$class = '';
	}
	?>

	<ul id="site-header-cart" class="site-header-cart menu">

		<li class="<?php echo esc_attr($class); ?>">
			<?php orbital_cart_link(); ?>
		</li>
		<li>
			<?php the_widget('WC_Widget_Cart', 'title='); ?>
		</li>
	</ul>

	<?php
}