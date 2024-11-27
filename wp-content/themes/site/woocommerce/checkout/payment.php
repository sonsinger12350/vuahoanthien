<?php

/**

 * Checkout Payment Section

 *

 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.

 *

 * HOWEVER, on occasion WooCommerce will need to update template files and you

 * (the theme developer) will need to copy the new files to your theme to

 * maintain compatibility. We try to do this as little as possible, but it does

 * happen. When this occurs the version of the template file will be bumped and

 * the readme will list any important changes.

 *

 * @see     https://docs.woocommerce.com/document/template-structure/

 * @package WooCommerce/Templates

 * @version 3.5.3

 */



defined( 'ABSPATH' ) || exit;



if ( WC()->cart->needs_payment() ) :

	$images = array(

		'icon-payment-method-cod.svg',

		'icon-payment-method-credit.svg'

	);

?>

<div id="payment" class="section">

  <h2 class="section-header">

    <span>Chọn hình thức thanh toán</span>

  </h2>

  <div class="checkout-payment-method">

	<?php

	if ( ! empty( $available_gateways ) ) {

		foreach ( $available_gateways as $i => $gateway ) {

		?>

	<div class="row">

		<div class="form-check">

			<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="form-check-input me-2" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

			<div class="form-check-label">

				<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">

					<?php if( 0 && isset($images[$i]) ):?>

					<img class="me-2" src="<?php site_the_assets();?>images/icons/payment/<?php echo $images[$i];?>">

					<?php endif;?>

					<?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> 

					<?php // echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>

				</label>

			</div>

			<?php if( $gateway->get_description()!='' ):?>

			<div class="form-check-description" style="display: none;">

				<?php echo nl2br( $gateway->get_description() );?>

			</div>

			<?php endif;?>

		</div>

	</div>

		<?php

		}

	}

	?>

  </div>

</div>

<?php endif; ?>