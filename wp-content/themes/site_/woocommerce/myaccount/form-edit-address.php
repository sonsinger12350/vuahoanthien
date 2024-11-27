<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;


function site_address_auto_fill_email()
{
?>
<script>
(function($){
	var email = $('.useEmailInput input').val();
	$('#emailInput').on('change',function(){
		var value = $(this).val();
		if( value == '' ) {
			value = email;
		}

		$('.useEmailInput input').val( value );
	});
})(jQuery);
</script>
<?php
}
add_action('wp_footer', 'site_address_auto_fill_email', 99 );

// $page_title = ( 'billing' === $load_address ) ? esc_html__( 'Billing address', 'woocommerce' ) : esc_html__( 'Shipping address', 'woocommerce' );

// $a = explode('_', $load_address);
$page_title = esc_html__( 'Shipping address', 'woocommerce' );

do_action( 'woocommerce_before_edit_account_address_form' ); ?>

<?php if ( ! $load_address ) : ?>
	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>

<h2 class="mb-4 section-header border-0 mg-top-0"><span><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ); ?></span></h2><?php // @codingStandardsIgnoreLine ?>
<div class="card card-body">
	<form method="post" class="mb-5 needs-validation col-12 col-lg-8 my-address-form" novalidate disablednext>

		<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

		<?php

		$labels = array(
			$load_address . '_first_name' => 'Tên người nhận',
			$load_address . '_address_1' => 'Địa chỉ nhận hàng',
			$load_address . '_phone' => 'Điện thoại nhận hàng',
		);

		foreach ( $address as $key => $field ) {

			$cls = 'row mb-3 field-'. $key;
			$field['label_class'] = ['col-12 col-md-3 col-lg-4 col-form-label'];
			$field['input_class'] = ['form-control'];
			$field['return'] 	= true;
			
			if( preg_match('/country/i', $key ) ) {
				$cls .= ' d-none';
				$field['value'] = 'VN'; // Always is Vietname
			} else if( preg_match('/email/i', $key ) && site_check_phone_email($field['value']) ) {

				/*
				$field_2 = $field;
				$field_2['class'] = [ $cls . '_phone' ];
				$field_2['name'] = $key_2 = 'emailInput';
				$field_2['required'] = false;

				$html = woocommerce_form_field( $key_2, $field_2, wc_get_post_data_by_key( $key_2, '' ) );
				echo str_replace('woocommerce-input-wrapper', 'col-12 col-md-9 col-lg-8', $html);
				*/
				
				$cls .= ' d-none useEmailInput';
			}
			
			if( isset($labels[$key]) ) {
				$field['placeholder'] = $field['label'] = $labels[$key];
			}

			$field['class'] = [$cls];
			$html = woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );

			echo str_replace('woocommerce-input-wrapper', 'col-12 col-md-9 col-lg-8', $html);
		}
		?>
		<div class="row">
			<div class="col-12 col-md-9 offset-md-3 col-lg-8 offset-lg-4">
				<button type="submit" class="btn btn-primary px-4" name="save_address" value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>"><?php esc_html_e( 'Save address', 'woocommerce' ); ?></button>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="btn px-4">Quay lại</a>
				<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce', $referer = false ); ?>
				<input type="hidden" name="action" value="edit_address" />
				<input type="hidden" name="redirect_to" value="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>">
			</div>
		</div>
	</form>
</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
