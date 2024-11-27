<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
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
?>
<div class="woocommerce-billing-fields">
	<h3 class="section-header">
		<span>Thông tin người mua</span>
	</h3>

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<div class="woocommerce-billing-fields__field-wrapper"> <!-- pt-4 -->
		<?php
		$fields = $checkout->get_checkout_fields( 'billing' );

		$labels = array(
			'billing_first_name' => 'Tên người mua hàng',
			// 'billing_country' => 'Quốc gia',
			// 'billing_address_1' => 'Địa chỉ mua hàng',
			'billing_phone' => 'Điện thoại mua hàng',
		);

		$user = wp_get_current_user();

		$values = array(
			'billing_first_name' => isset($user->display_name) ? $user->display_name : '',
			'billing_phone' => site_check_phone_email($user->user_email) ? $user->user_login : '',
		);
		
		foreach ( $fields as $key => $field ) 
		{
			$value = $checkout->get_value( $key );

			$cls = 'row mb-3 field-'. $key;
			if( preg_match('/(country|address)/i', $key ) ) {
				$cls .= ' d-none';
				if( $value == '' ) {
					$value = 'VN'; // Always is Vietname
				}
			} else if( preg_match('/email/i', $key ) ) {
				$cls .= ' d-none';

				if( $value == '' ) {
					$value = site_domain_email( 'guest' );
				}
			}
			
			$field['class'][] 		=  $cls;
			$field['label_class'][] = 'col-md-3 form-label';
			$field['input_class'][] = 'form-control';
			$field['custom_attributes']['data-value'] = $value;
			$field['return'] = true;
			
			// $field['custom_attributes']['required'] = 'required';

			if( isset($labels[$key]) ) {
				$field['placeholder'] = '';
				$field['label'] = $labels[$key];
				$field['input_class'][] = 'input-required';
			}

			if( isset($values[$key]) ) {
				$value = $values[$key];
			}

			$field['custom_attributes']['data-message'] = 'Vui lòng nhập '. $field['label'] . '!';
			
			$html = woocommerce_form_field( $key, $field, $value );

			echo str_replace('woocommerce-input-wrapper', 'col-md-9 input-wrapper', $html);
		}
		?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>