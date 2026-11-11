<!DOCTYPE html>
<html amp>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
	<title><?php wp_title(); ?></title>

	<!-- YOAST SEO -->
	<?php do_action( 'wpseo_head' ); ?>

	<!-- SCRIPTS -->
	<?php get_template_part( 'template-parts/amp/common/amp', 'scripts' ); ?>
	
	<!-- STYLE -->
	<?php get_template_part( 'template-parts/amp/common/amp', 'styles' ); ?>
</head>
<body>

	<?php //orbital_enqueue_scripts_amp(); ?>