<?php
if (orbital_check_social_template('orbital_social_fixed_bottom_post_types')) {
	return;
}

$devices = orbital_customize_option('orbital_social_fixed_bottom_devices') ? orbital_customize_option('orbital_social_fixed_bottom_devices') : 'mobile';
$social_networks = explode(',', orbital_customize_option('orbital_social_fixed_bottom_social'));
?>

<div class="entry-social social-sticky <?php echo $devices; ?> social-bottom social-center  <?php if (orbital_customize_option('orbital_social_fixed_bottom_count')) {
	echo 'social-count';
} ?>">

<?php foreach ($social_networks as $social_network) {
	echo '<a href="#" class="social social-'. $social_network .'"></a>';
}
?>

</div>