<?php
if (orbital_check_social_template('orbital_social_before_content_post_types')) {
	return;
}

$devices = orbital_customize_option('orbital_social_before_content_devices') ? orbital_customize_option('orbital_social_before_content_devices') : 'all';
$social_networks = explode(',', orbital_customize_option('orbital_social_before_content_social'));
?>

<div class="entry-social <?php echo $devices; ?> <?php if (orbital_customize_option('orbital_social_before_content_count')) {
	echo 'social-count';
} ?>">
<?php foreach ($social_networks as $social_network) {
	echo '<a href="#" class="social social-'. $social_network .'"></a>';
}
?>
</div>