<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

return;
?>


<div class="section p-0">
  <h2 class="section-header">
    <!-- <span>Chọn hình thức giao hàng</span> -->
    <span>Thông tin giỏ hàng</span>
  </h2>
  <div class="container">
	
	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

	<?php // wc_cart_totals_shipping_html(); ?>

	<?php endif; ?>
	
	<?php 
	
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) { ?>
	<div class="row mb-3 border p-2 rounded">
		<div class="col-6 d-flex">
			<img class="h-100-px" src="<?php echo wp_get_attachment_image_url( $_product->get_image_id() );?>">
			<div>
				<p class="text-limit-1"><?php echo esc_html( $_product->get_name() );?></p>
				<div class="d-flex justify-content-between">
					<span>SL: <?php echo $cart_item['quantity'];?></span>
				</div>
			</div>
		</div>
		<div class="col-6 d-flex justify-content-between">
			<div class="fw-bold"><?php echo site_wc_price( $_product->get_price() * $cart_item['quantity'] ); ?> <sub>đ</sub></div>
		</div>
	</div>
	<?php
		}
	}
	?>
	</div>
</div>