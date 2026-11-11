<?php
$links = get_query_var('links');
$excerpt = get_query_var('excerpt');
$target = get_query_var('target');
$nofollow = '';
$openlink = '';

if ($links == 'nofollow') {
	$nofollow = $links;
}

if ($target == '_blank') {
	$openlink = 'target="_blank"';
}

?>
<article class="entry-item <?php echo orbital_customize_option('orbital_cluster_columns'); ?>">
	<?php if (orbital_customize_option('orbital_loop_date')) { ?>
		<div class="entry-date">
			<p><?php echo get_the_date(); ?></p>
		</div>
	<?php } ?>
	<?php if (orbital_customize_option('orbital_loop_category') && ! is_archive()) { ?>
		<div class="entry-category">
			<p><?php echo orbital_the_category_link(); ?></p>
		</div>
	<?php } ?>
	<header class="entry-header">
		<a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark <?php echo $nofollow; ?>" <?php echo $openlink; ?>>
			<?php
			if (has_post_thumbnail() && orbital_customize_option('orbital_loop_thumbnail')) {
				the_post_thumbnail('thumbnail-center', ['class' => 'lazy']);
			}
			the_title('<h3 class="entry-title">', '</h3>');
			?>
		</a>
	</header>
	<div class="entry-meta">

		<?php if (orbital_customize_option('orbital_loop_author')) { ?>
			<div class="entry-author">
				<p><?php the_author(); ?></p>
			</div>
		<?php } ?>

		<?php if (orbital_customize_option('orbital_loop_excerpt')) { ?>
			<div class="entry-excerpt">
				<?php
				if ($excerpt == 'default') {
					the_excerpt();
				} elseif ($excerpt == 'yoast') {
					echo get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true);
				}
				?>
			</div>
		<?php } ?>
	</div>
</article>