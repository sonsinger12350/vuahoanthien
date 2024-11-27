<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

// echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) :
  
  $max_value = apply_filters( 'woocommerce_quantity_input_max', '20', $product );
  $min_value = apply_filters( 'woocommerce_quantity_input_min', '1', $product );
  $step      = apply_filters( 'woocommerce_quantity_input_step', '1', $product );

// Code for Rate Price 
$loai_sp = get_field("producttype");  

?>
<form class="cart product-detail-form" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype="multipart/form-data">
  <input type="hidden" name="add-to-cart" value="<?php echo $product->get_id();?>" />
  <div class="d-flex flex-row flex-wrap">
    <?php if ($loai_sp != "gach"): ?>
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
    <?php else: ?>
      <div class="d-flex flex-row align-items-center row-amount">
        <span class="fw-bold">Số lượng</span>
        <div class="d-inline-flex quantity-input-group mx-2">
          <input type="number" id="quantityProduct" class="form-control text-center fw-bold border border-end-0 border-start-0 rounded-0 quantity" placeholder="" value="" name="quantity" inputmode="numeric" max="<?php echo $max_value;?>" min="<?php echo $min_value;?>" step="<?php echo $step;?>" />
        </div>
      </div>     
    <?php endif ?>
    
    <div class="d-flex row-btn flex-row flex-grow-1">
      <button class="btn btn-primary btn-submit rounded me-1 p-2 fw-normal flex-grow-1" type="button" name="simple" value="1"><i class="bi bi-cart"></i>
        Thêm vào giỏ hàng</button>
      <button class="btn btn-danger rounded ms-1 p-2 fw-normal flex-grow-1" type="submit" name="checkout" value="1"><i class="bi bi-handbag"></i>
        Mua ngay</button>
    </div>
  </div>
</form>
<?php 

endif;