<header class="default-header">
	<div class="container">
		<?php if (is_single()) : ?>
			<?php do_action('orbital_before_single_title'); ?>

			<?php if (orbital_customize_option('orbital_posts_show_category')) : ?>
				<?php if (orbital_get_option_page('category')) : ?>
					<div class="category">
						<?php echo orbital_the_category_link(); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if (orbital_get_option_page('title')) : ?>
				<?php the_title('<h1 class="title">', '</h1>'); ?>
			<?php endif; ?>

			<?php if (orbital_get_option_page('meta')) : ?>
				<div class="meta">
					<?php orbital_posted_on(); ?>
				</div>
			<?php endif; ?>

			<?php orbital_subtitle(); ?>

			<?php do_action('orbital_after_single_title'); ?>


		<?php elseif (is_page()) : ?>
			<?php do_action('orbital_before_page_title'); ?>

			<?php the_title('<h1 class="title">', '</h1>'); ?>

			<?php orbital_subtitle(); ?>

			<?php do_action('orbital_after_page_title'); ?>

		<?php elseif (is_archive()) : ?>
			<?php do_action('orbital_before_archive_title'); ?>

			<h1 class="title"><?php single_term_title(); ?></h1>

			<?php do_action('orbital_after_archive_title'); ?>

		<?php elseif (is_front_page()) : ?>
			<h1 class="title"><?php bloginfo('name'); ?></h1>

		<?php elseif (is_home()) : ?>
			<?php single_post_title('<h1 class="title">', '</h1>'); ?>

		<?php elseif (is_search()) : ?>
			<h1 class="title"><?php printf(esc_html__('Search results for %s', 'orbital'), get_search_query()); ?></h1>

		<?php elseif (is_404()) : ?>
			<h1 class="title"><?php esc_html_e('404: Page Not Found', 'orbital'); ?></h1>

		<?php endif; ?>

	</div>

	<?php //orbital_get_contact_form(); ?>

</header>