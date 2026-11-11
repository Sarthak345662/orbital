<?php
/**
 * Displays the menu icon and modal
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 2.6
 */

?>

<div class="menu-modal cover-modal header-footer-group" data-modal-target-string=".menu-modal">

	<div class="menu-modal-inner modal-inner">

		<div class="menu-wrapper section-inner">

			<div class="menu-top">

				<button class="toggle close-nav-toggle fill-children-current-color" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".menu-modal">
					<span class="toggle-text"><?php esc_html_e( 'Close Menu', 'orbital-go' ); ?></span>
					X
				</button><!-- .nav-toggle -->

				<?php

				$mobile_menu_location = '';

				if ( has_nav_menu( 'primary' ) ) {
					$mobile_menu_location = 'primary';
				} 
				?>

				<nav class="mobile-menu" aria-label="<?php echo esc_attr_x( 'Mobile', 'menu', 'orbital-go' ); ?>" role="navigation">

					<ul class="modal-menu reset-list-style">

					<?php
					if ( $mobile_menu_location ) {

						wp_nav_menu(
							array(
								'container'      => '',
								'items_wrap'     => '%3$s',
								'show_toggles'   => true,
								'theme_location' => $mobile_menu_location,
							)
						);

					} else {

						wp_list_pages(
							array(
								'match_menu_classes' => true,
								'show_toggles'       => true,
								'title_li'           => false,
								'walker'             => new Orbital_Menu_Walker_Page(),
							)
						);

					}
					?>

					</ul>

				</nav>

			</div><!-- .menu-top -->

		</div><!-- .menu-wrapper -->

	</div><!-- .menu-modal-inner -->

</div><!-- .menu-modal -->
