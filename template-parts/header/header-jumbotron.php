<header class="jumbotron">
	<div class="container">
		<?php if (is_singular()) : ?>
			<?php the_title('<h1 class="title">', '</h1>'); ?>

			<?php orbital_subtitle(); ?>

			<?php if (orbital_get_option_page('button_header_text')) : ?>
				<a href="<?php echo orbital_get_option_page('button_header_url'); ?>" class="btn btn-red btn-lg"><?php echo orbital_get_option_page('button_header_text'); ?></a>

			<?php endif ?>

		<?php elseif (is_archive()) : ?>
			<h1 class="title"><?php single_term_title(); ?></h1>

		<?php elseif (is_front_page()) : ?>
			<h1 class="title"><?php bloginfo('name'); ?></h1>
			<p class="subtitle"><?php bloginfo('description'); ?></p>

		<?php elseif (is_home()) : ?>
			<?php single_post_title('<h1 class="title">', '</h1>'); ?>
			<?php orbital_subtitle(); ?>

		<?php elseif (is_search()) : ?>
			<h1 class="title"><?php printf(esc_html__('Search results for %s', 'orbital'), get_search_query()); ?></h1>

		<?php elseif (is_404()) : ?>
			<h1 class="title"><?php esc_html_e('404: Page Not Found', 'orbital'); ?></h1>

		<?php endif; ?>

	</div>

	<?php //orbital_get_contact_form(); ?>

</header>