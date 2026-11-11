<main id="content" <?php post_class('site-main'); ?>>

	<?php get_template_part('template-parts/header/header', 'default'); ?>

	<div id="content-wrapper" class="container">
		<div class="flex flex-fluid">
			<?php get_template_part('template-parts/widgets/widget', '404'); ?>
		</div>
	</div>
</main>