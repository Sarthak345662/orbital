<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

get_header(); ?>

<main id="content" class="site-main">

	<?php get_template_part('template-parts/header/header', 'default'); ?>

	<div id="content-wrapper" class="container flex">

		<div class="entry-content">

			<?php if (! is_paged()) {
				orbital_category_top_description();
			} ?>

			<?php do_action('orbital_before_archive_loop'); ?>

			<?php if (have_posts()) : ?>
				<div class="flex flex-fluid">

					<?php
					$featured = 0;

					while (have_posts()) :
						the_post();

						if ($featured == 3) {
							do_action('orbital_after_featured_archive');
						}

						if ($featured < 3) {
							get_template_part('template-parts/loops/loop', 'featured');
						} else {
							get_template_part('template-parts/loops/loop', 'grid');
						}
						$featured++;
					endwhile;
					?>

				</div>

				<?php orbital_pagination(); ?>

				<?php do_action('orbital_before_archive_content'); ?>

				<?php if (! is_paged()) {
					the_archive_description('<div class="archive-description">', '</div>');
				} ?>

				<?php do_action('orbital_after_archive_content'); ?>

			<?php else :
				get_template_part('template-parts/none/content', 'none');  ?>

			<?php endif; ?>
		</div>

		<?php get_template_part('template-parts/widgets/widget', 'archives'); ?>
	</div>
</main>

<?php get_footer();
