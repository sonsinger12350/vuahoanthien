<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

// $customer = new WC_Customer( $customer_id );

/*
if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Billing address', 'woocommerce' ),
			'shipping' => __( 'Shipping address', 'woocommerce' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'woocommerce' ),
		),
		$customer_id
	);
}
*/

$get_addresses = site_wc_get_account_addresses( $customer_id );

$new = 1;
/*
foreach ( $get_addresses as $name => $address_title ) {
	if( $address_title == 'new' ) {
		$new = (int) str_replace('shipping_', '', $name);

		unset($get_addresses[$name]);
	}
}
*/

if( count($get_addresses)>0 ) {
	$last = end( array_keys($get_addresses) );
	$new = intval( str_replace('shipping_', '', $last) ) + 1;
}

?>
<h2 class="mb-4 section-header border-0 mg-top-0"><span>Sổ địa chỉ</span></h2>
<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping_' . $new ) ); ?>" class="card card-body w-100 border flex-row justify-content-center align-items-center py-2 mb-3">
	<i class="bi bi-plus fs-3 me-3"></i> <b class="text-primary">Thêm địa chỉ nhận hàng mới</b>
</a> 
<?php
foreach ( $get_addresses as $name => $address_title ) : 
	$first = $address_title;
	$address = '';
	$rows = explode("<br/>", site_wc_get_account_formatted_address( $name ) );
	if( count($rows)>1 ) {
		$first = $rows[0];
		unset($rows[0]);
		$address = implode(' ',$rows);
	}

	$phone = get_user_meta($customer_id, $name . '_phone',  true);

	$i = (int) str_replace('shipping_', '', $name);
?>
<div class="card card-address flex-lg-row border-top mb-3" title="<?php echo esc_html( $address_title ); ?>">
	<div class="card-body">
		<p>
			<span class="uppercase me-3"><?php echo esc_html( $first . ' - ' . $phone ); ?></span>
			<!-- <small class="text-success"><i class="bi bi-check-circle"></i> Địa chỉ mặc định</small> -->
			<small class="text-success">Địa chỉ <?php echo ($i > 0 ? 'nhận' : 'mua' ) .' hàng';?></small>
		</p>
		<ul class="list-unstyled mb-lg-0">
			<li><span class="text-grey">Địa chỉ:</span> <?php echo $address ? strip_tags( $address ) : esc_html_e( 'You have not set up this type of address yet.', 'woocommerce' ); ?></li>
			<!-- <li><span class="text-grey">Điện thoại:</span> 0987897987</li> -->
		</ul>
	</div>
	<div class="card-footer text-left bg-white border-0">
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="btn btn-sm text-primary"><i class="bi bi-pencil-square"></i>
 <?php echo $address ? esc_html__( 'Edit', 'woocommerce' ) : esc_html__( 'Add', 'woocommerce' ); ?></a>
		<?php if( $i>0 ):?>
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) . '?remove_address=' . esc_attr($name) ); ?>" class="btn btn-sm text-danger" ><i class="bi bi-trash3"></i>
 Xóa</a>
		<?php endif;?>
	</div>
</div>
<?php endforeach; ?>
