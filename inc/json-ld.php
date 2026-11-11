<?php
/**
 * Json LD for Rich Snippets, featured Snippets and Structure Data
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

/*
 * Print markup site
 */

if (! function_exists('orbital_markup_site')) :

	function orbital_markup_site()
	{

		$siteURL = home_url();
		$siteTitle = get_bloginfo('title');
		$siteDescription = get_bloginfo('description') ? get_bloginfo('description') : get_bloginfo('title');
		$logoID = get_theme_mod('custom_logo');
		$dataLogo = wp_get_attachment_image_src($logoID, 'full');
		$excerpt = wp_trim_words(get_the_excerpt());

		?>

		<script type="application/ld+json">
			{
				"@context": "http://schema.org",
				"@type": "WebSite",
				"name": "<?php print $siteTitle; ?>",
				"alternateName": "<?php print $siteDescription; ?>",
				"url": "<?php print $siteURL; ?>"
			}
		</script>

		<?php if (is_single()) : ?>
			<script type="application/ld+json">
				{
					"@context": "http://schema.org",
					"@type": "Article",
					"headline": "<?php print $excerpt; ?>",
					"mainEntityOfPage": {
					"@type": "WebPage",
					"@id": "<?php the_permalink(); ?>"
				},
				<?php if (has_post_thumbnail()) { ?>
					"image": {
					"@type": "ImageObject",
					"url": "<?php print get_the_post_thumbnail_url(); ?>",
					"height": <?php print get_option('large_size_h'); ?>,
					"width": <?php print get_option('large_size_w'); ?>
				},

			<?php } ?>

			"datePublished": "<?php the_date('Y-m-d'); ?>",
			"dateModified": "<?php the_modified_date('Y-m-d'); ?>",
			"author": {
			"@type": "Person",
			"name": "<?php the_author(); ?>"
		},
		"publisher": {
		"@type": "Organization",
		"name": "<?php print $siteTitle; ?>"

		<?php if (has_custom_logo()) { ?>
			,
			"logo": {
			"@type": "ImageObject",
			"url": "<?php print $dataLogo[0]; ?>"
		}

	<?php } ?>
}

<?php if (has_excerpt()) { ?>
	,
	"description": "<?php print $excerpt; ?>"
<?php } ?>
}
</script>

<?php endif; ?>

<?php
}

endif;
