<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

$get_addresses = site_wc_get_account_addresses( $customer_id );

$count = count($get_addresses);

$fields = $checkout->get_checkout_fields( 'shipping' );

$phone = $checkout->get_value( 'shipping_phone' );

?>
<div class="woocommerce-shipping-fields">
	<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>
	<p class="d-none" id="ship-to-different-address">
		<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
			<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="hidden" name="ship_to_different_address" value="1" /> 
			<span><?php esc_html_e( 'Ship to a different address?', 'woocommerce' ); ?></span>
		</label>
	</p>
	
	<h3 class="section-header">
		<span>Thông tin người nhận</span>
	</h3>
	
	<?php if( $count>0 ):?>
	<div class="shipping_address_list">
		<div class="mb-2"><b>Chọn địa chỉ<!-- người nhận hàng--></b></div>
		<?php

			$last = end( array_keys($get_addresses) );
			$new = intval( str_replace('shipping_', '', $last) ) + 1;

			foreach ( $get_addresses as $address => $address_title ) :
				if( preg_match('/billing/i', $address ) ) continue;

				$rows = site_wc_get_address_data( $address, $customer_id );
		?>
		<div class="form-check mb-2">
			<label class="form-check-label">
				<input class="form-check-input" type="radio" name="address-name" 
					value="<?php echo $address;?>" 
					data-name="<?php echo $rows['first_name'];?>" 
					data-address="<?php echo $rows['address_1'];?>"
					data-phone="<?php echo $rows['phone'];?>">
				<?php echo implode(' - ', $rows);?>
			</label>
		</div>
		<?php endforeach;?>
		<div class="form-check mb-2">
			<label class="form-check-label">
				<input class="form-check-input" type="radio" name="address-name" value="new_<?php echo $new;?>" >
				Thêm địa chỉ mới
			</label>
		</div>
	</div>
	<hr>
	<?php endif;?>
	
	<div class="shipping_address_fields<?php echo $count>1?' d-none-tmp':'';?>">
		
		<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>
				
		<div class="woocommerce-shipping-fields__field-wrapper">
			<?php		
			$labels = array(
				'shipping_first_name' => 'Tên người nhận hàng',
				// 'shipping_country' => 'Quốc gia',
				'shipping_address_1' => 'Địa chỉ nhận hàng',
				'shipping_phone' => 'Điện thoại nhận hàng',
			);
			
			foreach ( $fields as $key => $field ) 
			{
				$value = $checkout->get_value( $key );

				$cls = 'row mb-3 field-'. $key;
				if( preg_match('/country/i', $key ) ) {
					$cls .= ' d-none';
					$value = 'VN'; // Always is Vietname
				} else if( preg_match('/email/i', $key ) ) {
					$cls .= ' d-none';
					$field['required'] = false;
				}

				$field['class'][] 		=  $cls;
				$field['label_class'][] = 'col-md-3 form-label';
				$field['input_class'][] = 'form-control';
				$field['custom_attributes']['data-value'] = $value;
				$field['return'] = true;
				
				// $field['custom_attributes']['data-message'] = 'Vui lòng nhập '. $field['label'] . '!';
				// $field['custom_attributes']['required'] = 'required';
				
				$billing_key = str_replace( 'shipping', 'billing', $key );
				if( $value == '' ) {
					$value = $checkout->get_value( $billing_key );
				}

				if( isset($labels[$key]) ) {
					$value = '';
					$field['placeholder'] = '';
					$field['label'] = $labels[$key];
					$field['input_class'][] = 'input-required';
				}
				
				$html = woocommerce_form_field( $key, $field, $value );

				echo str_replace('woocommerce-input-wrapper', 'col-md-9 input-wrapper', $html);
			}
			?>
		</div>

		<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

	</div>

	<?php endif; ?>
</div>

