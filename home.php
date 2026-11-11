<?php
/**
 * The template for displaying the lasts posts on the main page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

get_header(); ?>

<main id="content" class="site-main">

	<div id="content-wrapper" class="container flex">

		<div class="entry-content">

			<?php if (have_posts()) : ?>
				<?php do_action('orbital_before_page_home_content'); ?>

				<div class="flex flex-fluid">

					<?php
					$featured = 0;

					while (have_posts()) :
						the_post();

						if ($featured == 3) {
							do_action('orbital_after_featured_home');
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

				<?php do_action('orbital_after_page_home_content'); ?>

			<?php else :
				get_template_part('template-parts/none/content', 'none'); ?>

			<?php endif; ?>

			<?php wp_reset_query(); ?>

		</div>

		<?php get_template_part('template-parts/widgets/widget', 'page-home'); ?>
	</div>
</main>
<?php get_footer();
