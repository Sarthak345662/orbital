<?php
//orbitals Woocommerce Actions
add_action('after_setup_theme', 'orbital_woocommerce_support');

//orbitals Woocommerce Filters
//add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
add_filter('woocommerce_enqueue_styles', '__return_empty_array');
add_action('init', 'orbital_product_cat_register_meta');
add_action('product_cat_add_form_fields', 'orbital_product_cat_add_details_meta');
add_action('product_cat_edit_form_fields', 'orbital_product_cat_edit_details_meta');
add_action('create_product_cat', 'orbital_product_cat_details_meta_save');
add_action('edit_product_cat', 'orbital_product_cat_details_meta_save');
add_action('woocommerce_before_shop_loop', 'orbital_product_cat_display_details_meta');
add_action('woocommerce_before_shop_loop', 'orbital_home_shop_excerpt');
add_action('woocommerce_before_shop_loop', 'orbital_home_shop_sections');
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
add_action('woocommerce_before_shop_loop_item', 'orbital_open_link_product_loop', 10);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 0);
//add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 0 );
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 20);
add_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 10);
add_filter('woocommerce_product_tabs', 'woo_remove_product_tabs', 98);
add_filter('woocommerce_output_related_products_args', 'orbital_related_products');
add_filter('woocommerce_get_availability', 'orbital_get_availability', 1, 2);


//orbitals Woocommerce Functions
function orbital_woocommerce_support()
{
	add_theme_support('woocommerce');
}

function orbital_check_woocommerce()
{

	if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
		return true;
	} else {
		return false;
	}
}


function orbital_product_cat_register_meta()
{
	register_meta('term', 'details', 'orbital_sanitize_details');
}

function orbital_sanitize_details($details)
{
	return wp_kses_post($details);
}

function orbital_product_cat_add_details_meta()
{
	wp_nonce_field(basename(__FILE__), 'orbital_product_cat_details_nonce');
	?>
	<div class="form-field">
		<label for="orbital-product-cat-details"><?php esc_html_e('Details', 'orbital'); ?></label>
		<textarea name="orbital-product-cat-details" id="orbital-product-cat-details" rows="5" cols="40"></textarea>
		<p class="description"><?php esc_html_e('Detailed category info to appear below the product list', 'orbital'); ?></p>
	</div>
	<?php
}

function orbital_product_cat_edit_details_meta($term)
{
	$product_cat_details = get_term_meta($term->term_id, 'details', true);
	if (! $product_cat_details) {
		$product_cat_details = '';
	}
	$settings = array( 'textarea_name' => 'orbital-product-cat-details' );
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="orbital-product-cat-details"><?php esc_html_e('Details', 'orbital'); ?></label></th>
		<td>
			<?php wp_nonce_field(basename(__FILE__), 'orbital_product_cat_details_nonce'); ?>
			<?php wp_editor(orbital_sanitize_details($product_cat_details), 'product_cat_details', $settings); ?>
			<p class="description"><?php esc_html_e('Detailed category info to appear below the product list', 'orbital'); ?></p>
		</td>
	</tr>
	<?php
}

function orbital_product_cat_details_meta_save($term_id)
{
	if (! isset($_POST['orbital_product_cat_details_nonce']) || ! wp_verify_nonce($_POST['orbital_product_cat_details_nonce'], basename(__FILE__))) {
		return;
	}
	$old_details = get_term_meta($term_id, 'details', true);
	$new_details = isset($_POST['orbital-product-cat-details']) ? $_POST['orbital-product-cat-details'] : '';
	if ($old_details && '' === $new_details) {
		delete_term_meta($term_id, 'details');
	} else if ($old_details !== $new_details) {
		update_term_meta(
			$term_id,
			'details',
			orbital_sanitize_details($new_details)
		);
	}
}

function orbital_product_cat_display_details_meta()
{
	if (! is_tax('product_cat') || is_paged()) {
		return;
	}
	$t_id = get_queried_object()->term_id;
	$details = get_term_meta($t_id, 'details', true);
	if ('' !== $details) {
		?>
		<div class="product-cat-details">
			<?php echo apply_filters('the_content', wp_kses_post($details)); ?>
		</div>
		<?php
	}
}

function orbital_home_shop_sections()
{
	echo apply_filters('the_content', wp_kses_post(orbital_home_shop_section()));
}

function orbital_home_shop_excerpt()
{
	if (!is_shop() || is_paged()) {
		return;
	}
	echo '<p>' . get_post_meta(get_option('woocommerce_shop_page_id'), 'option_page_subtitle', true) . '</p>';
}

function orbital_home_shop_section()
{
	if (!is_shop() || is_paged()) {
		return;
	}
	$number_of_sections = 4;
	$output = '';
	for ($i=1; $i <= $number_of_sections; $i++) {
		if (orbital_customize_option('orbital_home_section_category_' . $i)) {
			$category = orbital_customize_option('orbital_home_section_category_' . $i);
			$category_info = get_term($category, 'product_cat');
			$order = orbital_customize_option('orbital_home_section_order_' . $i);
			$products = orbital_customize_option('orbital_home_section_number_products_' . $i);
			$args = array(
				'post_type'              => 'product',
				'tax_query'     => array(
					array(
						'taxonomy'  => 'product_cat',
						'field'     => 'slug',
						'terms'     => $category_info->slug
					)
				),
				'posts_per_page'         => $products,
			);
			$query = new WP_Query($args);
			if ($query->have_posts()) { ?>
				<div class="home-shop-section">
					<div class="products">
						<h3><a href="<?php echo get_term_link($category_info->slug, 'product_cat') ?>"><?php echo $category_info->name; ?></a></h2>
							<div class="flex flex-fluid">
								<?php
								while ($query->have_posts()) :
									$query->the_post();
									wc_get_template_part('content', 'product');
								endwhile;
								?>
							</div>
						</div>
					</div>
					<?php
				}
				wp_reset_postdata();
			}
		}
	}

	function orbital_open_link_product_loop()
	{
		global $product;
		$nofollow = '';
		$link = '';
		if (orbital_check_noindex()) {
			$nofollow = 'rel="nofollow"';
		}
		if ($product->is_type("external")) {
			$link = esc_url($product->get_product_url());
			$nofollow = 'rel="nofollow" target="_blank"';
		} else {
			$link = get_the_permalink();
		}


		echo '<a '. $nofollow .' href="' . $link . '" class="woocommerce-LoopProduct-link">';
	}

	function woo_remove_product_tabs($tabs)
	{
unset($tabs['additional_information']);   // Remove the additional information tab
return $tabs;
}

function orbital_related_products($args)
{
	$args['posts_per_page'] = 4;
	$args['columns'] = 4;
	return $args;
}

function orbital_get_availability($availability, $_product)
{
	if ($_product->is_in_stock()) {
		$availability['availability'] = __('In Stock', 'orbital');
	}
	return $availability;
}

function woocommerce_header_add_to_cart_fragment($fragments)
{
	global $woocommerce;

	ob_start();

	?>
	<a class="wcmenucart-contents" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'orbital'); ?>"><svg class="shopping-cart" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" viewBox="0 0 448 512"><path fill="currentColor" d="M352 160v-32C352 57.42 294.579 0 224 0 153.42 0 96 57.42 96 128v32H0v272c0 44.183 35.817 80 80 80h288c44.183 0 80-35.817 80-80V160h-96zm-192-32c0-35.29 28.71-64 64-64s64 28.71 64 64v32H160v-32zm160 120c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24zm-192 0c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24z"/></svg><span class="shopping-cart-elements"><?php echo $woocommerce->cart->cart_contents_count;?></span></a>
	<?php

	$fragments['a.wcmenucart-contents'] = ob_get_clean();

	return $fragments;
}


if (! function_exists('woocommerce_template_loop_category_title')) {
	function woocommerce_template_loop_category_title($category)
	{
		?>
		<h3>
			<?php
			echo $category->name;
			if ($category->count > 0) {
				echo apply_filters('woocommerce_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category);
			}
			?>
		</h3>
		<?php
	}
}

function orbital_woocommerce_breadcrumb()
{

	if (!orbital_customize_option('orbital_woocommerce_breadcrumb_active')) {
		remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
	}
}
add_action('init', 'orbital_woocommerce_breadcrumb');




function orbital_woocommerce_breadcrumb_align()
{

	return array(
		'wrap_before' => '<nav class="woocommerce-breadcrumb  ' . orbital_customize_option('orbital_woocommerce_breadcrumb_align', 'left') .'" itemprop="breadcrumb">',
		'delimiter'   => ' &#47; ',
		'wrap_after'  => '</nav>',
		'before'      => '',
		'after'       => '',
		'home'        => _x('Home', 'breadcrumb', 'woocommerce'),
	);
}
add_filter('woocommerce_breadcrumb_defaults', 'orbital_woocommerce_breadcrumb_align');


if (! function_exists('orbital_cart_link_fragment')) {

	function orbital_cart_link_fragment($fragments)
	{
		global $woocommerce;

		ob_start();
		orbital_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		ob_start();
		$fragments['a.footer-cart-contents'] = ob_get_clean();

		return $fragments;
	}
}


if (! function_exists('orbital_cart_link')) {

	function orbital_cart_link()
	{
		?>
		<a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'orbital'); ?>">
			<?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?>
			<span class="count">
				<?php echo wp_kses_data(sprintf(_n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'orbital'), WC()->cart->get_cart_contents_count()));?>

			</span>
		</a>
		<?php
	}
}

if (! function_exists('orbital_header_cart')) {

	function orbital_header_cart($menu)
	{
		ob_start();
		get_template_part('template-parts/other/widget', 'cart');
		$widget = ob_get_contents();
		ob_end_clean();

		$menu .= '<li class="menu-item">' . $widget . '</li>';

		return $menu;
	}
}




if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.3', '>=')) {
	add_filter('woocommerce_add_to_cart_fragments', 'orbital_cart_link_fragment');
} else {
	add_filter('add_to_cart_fragments', 'orbital_cart_link_fragment');
}

add_action('wp_nav_menu_items', 'orbital_header_cart', 60);
remove_action('wp_nav_menu_items', 'orbital_header_cart', 60);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
