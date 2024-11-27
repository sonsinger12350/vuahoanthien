<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$max_value = apply_filters( 'woocommerce_quantity_input_max', '20', $product );
$min_value = apply_filters( 'woocommerce_quantity_input_min', '1', $product );
$step      = apply_filters( 'woocommerce_quantity_input_step', '1', $product );

?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<div class="d-flex flex-row flex-wrap">
		<div class="d-flex flex-row align-items-center row-amount">
		<span class="fw-bold">Số lượng</span>
		<div class="d-inline-flex quantity-input-group mx-2">
			<button type="button" class="btn btn-outline-dark rounded btn-decrease border fw-bold fs-4">-</button>
			<input type="number"
			class="form-control text-center fw-bold border border-end-0 border-start-0 rounded-0 quantity"
			placeholder="" value="1" name="quantity" inputmode="numeric"
			max="<?php echo $max_value;?>" min="<?php echo $min_value;?>" step="<?php echo $step;?>" />
			<button type="button" class="btn btn-outline-dark rounded btn-increase border fw-bold fs-4">+</button>
		</div>
		</div>
		<div class="d-flex row-btn flex-row flex-grow-1">
		<button class="btn btn-primary btn-submit rounded me-1 p-2 fw-bold flex-grow-1" type="button" name="simple" value="1"><i class="bi bi-cart"></i>
			Thêm vào giỏ hàng</button>
		<button class="btn btn-danger rounded ms-1 p-2 fw-bold flex-grow-1" type="submit" name="checkout" value="1"><i class="bi bi-handbag"></i>
			Mua ngay</button>
		</div>
	</div>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
