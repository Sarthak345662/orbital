<?php
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', 'orbital_woocommerce_template_loop_product_title', 10);

function orbital_woocommerce_template_loop_product_title()
{
	echo '<h3 class="woocommerce-loop-product__title">' . get_the_title() . '</h3>';
}


add_filter('woocommerce_get_price_html', 'orbital_woocommerce_price_html', 100, 2);
function orbital_woocommerce_price_html($price, $product)
{
	$price = str_replace('<ins>', '', $price);
	$price = str_replace('</ins>', '', $price);
	return $price;
}

//remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');

// remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
// add_action('woocommerce_checkout_payment', 'woocommerce_checkout_payment', 20);



add_filter('the_content', 'woo_title_order_received', 10, 2);
function woo_title_order_received($content)
{
	if (function_exists('is_order_received_page') &&  is_order_received_page()) {
		$content = "[woocommerce_checkout]";
	}
	return $content;
}

remove_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 10);
add_action('woocommerce_cart_is_empty', 'custom_empty_cart_message', 10);

function custom_empty_cart_message()
{
	$html  = '<h2 class="cart-empty">';
	$html .= wp_kses_post(apply_filters('wc_empty_cart_message', __('Your cart is currently empty.', 'woocommerce')));
	echo $html . '</h2>';
}
