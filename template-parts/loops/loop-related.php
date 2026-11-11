<article id="post-<?php the_ID(); ?>" class="entry-item <?php echo orbital_customize_option('orbital_loop_columns'); ?>">
	<a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark">
		<?php
		the_post_thumbnail('thumbnail');
		the_title('<h4 class="entry-title">', '</h4>');
		?>
	</a>
</article>
