<main id="content" <?php post_class('site-main'); ?>>

	<?php do_action('orbital_before_page_header'); ?>

	<?php get_template_part('template-parts/header/header', 'default'); ?>

	<?php do_action('orbital_after_page_header'); ?>

	<div id="content-wrapper" class="container flex">
		<div class="entry-content">

			<?php do_action('orbital_before_page_content'); ?>

			<?php the_content(); ?>

			<?php wp_link_pages(array('next_or_number' => 'next')); ?>

			<?php do_action('orbital_after_page_content'); ?>

			<?php if (comments_open() || get_comments_number()) : ?>
				<footer class="entry-footer">

					<?php do_action('orbital_before_page_comments'); ?>

					<?php comments_template(); ?>

					<?php do_action('orbital_before_page_comments'); ?>

				</footer>

			<?php endif; ?>
		</div>

		<?php get_template_part('template-parts/widgets/widget', 'pilar'); ?>
	</div>

</main>

