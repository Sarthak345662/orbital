
<?php do_action('orbital_before_navbar'); ?>

<header class="site-header<?php if (orbital_customize_option('orbital_layout_menu_orbital')) {
	echo ' with-header';
	} ?> <?php if (get_header_image()) {
		echo 'center-navbar';
	} ?>">
	<div class="container header-inner">
			<?php if (! get_header_image()) : ?>
				<div class="site-logo">

					<?php
					if (has_custom_logo()) {
						orbital_the_custom_logo();
					} else { ?>
						<a href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('name'); ?></a>
					<?php }  ?>

				</div>

			<?php endif; ?>
			<button class="toggle nav-toggle mobile-nav-toggle <?php if (orbital_customize_option('orbital_layout_menu_orbital')) {
					echo ' orbitalMenu-fixed';
				} ?>" data-toggle-target=".menu-modal"  data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
				<span class="toggle-inner">
					<a class="site-nav-trigger">
						<span></span>
					</a>
					<span class="toggle-text"><?php esc_html_e( 'Menu', 'orbital-go' ); ?></span>
				</span>
			</button><!-- .nav-toggle -->
			
			<div class="header-navigation-wrapper">

				<?php
				if ( has_nav_menu( 'primary' ) ) {
					?>
						<nav class="primary-menu-wrapper" aria-label="<?php echo esc_attr_x( 'Horizontal', 'menu', 'orbital-go' ); ?>" role="navigation">

							<ul class="primary-menu reset-list-style">

							<?php

								wp_nav_menu(
									array(
										'container'  => '',
										'items_wrap' => '%3$s',
										'theme_location' => 'primary',
									)
								);
							
							?>

							</ul>
							
						</nav><!-- .primary-menu-wrapper -->
					<?php
				}

				?>

			</div><!-- .header-navigation-wrapper -->
		
	</div>
</header>
<?php 
do_action('orbital_after_navbar'); 
get_template_part( 'template-parts/navbar/modal-menu' );