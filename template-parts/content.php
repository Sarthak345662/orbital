<?php
$category = get_the_category();
?>
<article id="post-<?php the_ID(); ?>" class="entry-item">
	<header class="entry-header">
		<div class="entry-meta">
			<?php if (orbital_customize_option('orbital_loop_date')) : ?>
				<div class="entry-date">
					<p><?php echo get_the_date(); ?></p>
				</div>
			<?php endif; ?>
			<?php if (orbital_customize_option('orbital_loop_category')) : ?>
				<div class="entry-category">
					<p><?php echo orbital_the_category(); ?></p>
				</div>
			<?php endif; ?>
		</div>
		<a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark">
			<?php
			if (orbital_customize_option('orbital_loop_thumbnail')) {
				the_post_thumbnail('medium');
			}
			the_title('<h3>', '</h3>');
			?>
		</a>
	</header><!-- .entry-header -->
	<?php if (orbital_customize_option('orbital_loop_author')) : ?>
		<div class="archive-author">
			<p>Por <?php echo get_the_author(); ?></p>
		</div>
	<?php endif; ?>
	<?php if (orbital_customize_option('orbital_loop_excerpt')) : ?>
		<div class="archive-content">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->
	<?php endif; ?>
</article>
