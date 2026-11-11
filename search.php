<?php
/**
* The template for displaying search query
*
* @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
*
* @package WordPress
* @subpackage Orbital
* @since 1.0
*/

get_header(); ?>

<main id="content" class="site-main">

	<?php if (have_posts()) : ?>
		<?php get_template_part('template-parts/header/header', 'default'); ?>

		<div class="container">
			<div class="flex flex-fluid">
				<?php
				while (have_posts()) :
					the_post();

					get_template_part('template-parts/loops/loop', 'grid');
				endwhile;
				?>
			</div>

			<?php orbital_pagination(); ?>

		</div>

	<?php else :
		get_template_part('template-parts/none/content', 'none');
	endif; ?>

</main>

<?php get_footer();
