<main id="content" <?php post_class('site-main'); ?>>

	<?php get_template_part('template-parts/header/header', 'default'); ?>

	<div id="content-wrapper" class="container flex no-sidebar">
		<div class="entry-content">
			<?php orbital_yoast_breadcrumbs(); ?>
			<?php do_action('orbital_before_page_content'); ?>
			<?php the_content(); ?>
			<?php wp_link_pages(array('next_or_number' => 'next')); ?>
			<?php do_action('orbital_after_page_content'); ?>

			<?php if (comments_open() || get_comments_number()) : ?>
				<footer class="entry-footer">
					<?php comments_template(); ?>
				</footer>
			<?php endif; ?>
		</div>
	</div>
</main>