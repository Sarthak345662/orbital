<article id="post-<?php the_ID(); ?>" class="entry-item <?php echo orbital_customize_option('orbital_loop_columns'); ?>">
	<?php if (orbital_customize_option('orbital_loop_date')) : ?>
		<div class="entry-date">
			<p><?php echo get_the_date(); ?></p>
		</div>
	<?php endif; ?>
	<?php if (orbital_customize_option('orbital_loop_category') && ! is_archive()) : ?>
		<div class="entry-category">
			<p><?php echo orbital_the_category_link(); ?></p>
		</div>
	<?php endif; ?>
	<header class="entry-header">
		<a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark">
			<?php
			if (has_post_thumbnail() && orbital_customize_option('orbital_loop_thumbnail')) {
				the_post_thumbnail('thumbnail-center');
			}
			the_title('<h3 class="entry-title">', '</h3>');
			?>
		</a>
	</header><!-- .entry-header -->
	<div class="entry-meta">

		<?php if (orbital_customize_option('orbital_loop_author')) : ?>
			<div class="entry-author">
				<p><?php the_author(); ?></p>
			</div>
		<?php endif; ?>
		<?php if (orbital_customize_option('orbital_loop_excerpt')) : ?>
			<div class="entry-excerpt">
				<?php the_excerpt(); ?>
			</div><!-- .entry-content -->
		<?php endif; ?>
	</div>
</article>
