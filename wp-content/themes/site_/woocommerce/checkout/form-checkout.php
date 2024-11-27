<?php

/**

 * Checkout Form

 *

 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.

 *

 * HOWEVER, on occasion WooCommerce will need to update template files and you

 * (the theme developer) will need to copy the new files to your theme to

 * maintain compatibility. We try to do this as little as possible, but it does

 * happen. When this occurs the version of the template file will be bumped and

 * the readme will list any important changes.

 *

 * @see https://docs.woocommerce.com/document/template-structure/

 * @package WooCommerce/Templates

 * @version 3.5.0

 */



if ( ! defined( 'ABSPATH' ) ) {

	exit;

}



// do_action( 'woocommerce_before_checkout_form', $checkout );



// If checkout registration is disabled and not logged in, the user cannot checkout.

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {

	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );

	return;

}



?>

<form name="checkout" method="post" class="checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

<div class="row mt-3">

	<div class="col-12 col-md-9">



		<?php if ( $checkout->get_checkout_fields() ) : ?>

		<div class="row">

			<div class="col-md-12 mb-3">
				<div class="section-bg">
					
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</div>	
			</div>

			<div class="col-md-12 mb-3">
				<div class="section-bg">
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					<div class="woocommerce-billing-info mt-2 pt-2 border-top" data-field="checkout_note_3">

						<?php the_field('checkout_note_3');?>

						<!-- VieHome Depot cam kết những thông tin cá nhân của Quý Khách chỉ được sử dụng để thanh toán đơn hàng, giao hàng và hoàn toàn bảo mật theo chính sách quyền riêng tư. -->

					</div>
				</div>	
			</div>

		</div>

		<?php endif; ?>

		<div class="row">
			<div class="col-md-12 mb-3">
				<div class="section-bg">
					<div id="order_review" class="woocommerce-checkout-review-order mb-3">
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					</div>
					<div class="row"> <!--  pb-4 -->

						<div class="col-12 col-md-9">

							<button type="submit" id="btn-checkout-now" class="btn btn-danger btn-checkout-now rounded w-50" id="place_order" data-value="Đặt hàng">Đặt hàng</button>

							<input type="hidden" name="woocommerce_checkout_place_order" value="Đặt hàng" />

							<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>

						</div>

					</div>
				</div>
			</div>
		</div>

	</div>

	

	<div class="col-12 col-md-3 col-sidebar"> <!-- py-5 -->
		<div class="section-bg">
			<?php wc_get_template( 'checkout/sidebar.php', array( 'checkout' => $checkout ) ); ?>
		</div>
	</div>

</div>





<?php if( 0 && $checkout->get_value('shipping_address')!='' ):?>

<div class="modal fade modal-checkout-shipping" id="customer_details" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

	<div class="modal-dialog" style="max-width: 800px;">

		<div class="modal-content">

			<div class="modal-header">

				<!-- <h5 class="modal-title border-0">Địa chỉ giao hàng</h5> -->

				<h5 class="modal-title border-0">Thông tin người nhận</h5>

				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

			</div>

			<div class="modal-body">

				<?php if ( $checkout->get_checkout_fields() ) : ?>

				<div class="row">

					<div class="col-md-12 d-none">

						<?php do_action( 'woocommerce_checkout_billing' ); ?>

					</div>

					<div class="col-md-12"> <!--  pt-4 -->

						<?php do_action( 'woocommerce_checkout_shipping' ); ?>

					</div>

				</div>

				<?php endif; ?>

			</div>

			<div class="modal-footer text-center">

				<button type="button" class="btn btn-primary btn-save" data-bs-dismiss="modal">Cập nhật</button>

			</div>

		</div>

	</div>

</div>

<?php endif; ?>

<script>
  document.getElementById("btn-checkout-now").addEventListener("click", function() {
    // Remove the storage item
    localStorage.removeItem('wps_cart_points');
    localStorage.removeItem('wps_cart_remain_points');

    // Optionally, you can display an alert to notify the user
    //alert("Cart points have been removed from storage.");
  });
</script>


</form>

<?php 



do_action( 'woocommerce_after_checkout_form', $checkout );