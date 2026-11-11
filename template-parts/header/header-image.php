<?php if (get_header_image()) : ?>
	<?php $custom_header_sizes = apply_filters('custom_header_sizes', '(max-width: 709px) 85vw, (max-width: 909px) 81vw, (max-width: 1362px) 88vw, 1200px'); ?>

	<div class="header-image">
		<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
			<img src="<?php header_image(); ?>" width="<?php echo esc_attr(get_custom_header()->width); ?>" height="<?php echo esc_attr(get_custom_header()->height); ?>" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
		</a>
	</div>

<?php endif;
