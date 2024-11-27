<?php
/**
 * Template Name: Testing
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

die('<meta http-equiv="refresh" content="0; url='. home_url(). '">');

// 	0771234567@viehome.depot
$customer_id = (int) get_current_user_id();
if( $customer_id != 1750 ) {
  wp_redirect( home_url() );
  exit();
}

// $cart_discount = getCurrentLanguageCode() == 'vi' ? 'Điểm' : 'Point';
$cart_discount = __( 'Cart Discount', 'points-and-rewards-for-woocommerce' );
$my_coupons = WC()->cart->get_coupons();

foreach ( $my_coupons as $code => $coupon ){
  if( strtolower($code) == strtolower($cart_discount) ) {
    echo "$code <br>";

    WC()->cart->remove_coupon( $code );
    WC()->session->__unset( 'wps_cart_points' );
  }
}

var_dump( WC()->cart->get_coupons() );

die('');

// $result = site_sms_send( $phone = '0776628322', $code = '2929' );
// var_dump( $result );

// $customer = new WC_Customer( $customer_id );

// $customer = new stdClass();
// $customer->id = time();
// var_dump( $customer );

/*
$wps_settings = get_option('wps_wpr_order_total_settings');
var_dump( $wps_settings );

$wps_settings = get_option('wps_wpr_settings_gallery');
var_dump( $wps_settings );

$wps_settings = get_option('wps_wpr_coupons_gallery');
var_dump( $wps_settings );
*/

$order_total = 6300000;
$wps_wpr_coupon_conversion_points = 1;
$wps_wpr_coupon_conversion_price = 1000000;

$points_calculation = ceil( ( $order_total * $wps_wpr_coupon_conversion_points ) / $wps_wpr_coupon_conversion_price );

var_dump( $points_calculation );

$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
var_dump( $get_points );

$total_points = intval( $points_calculation + $get_points );
var_dump( $total_points );

die;

/*

global $wpdb;

$q = "
  SELECT `umeta_id`,`meta_key`,`meta_value`
  FROM `vhd_usermeta`
  WHERE `user_id` = %s 
  AND `meta_key` LIKE 'shipping_%_first_name'
  GROUP BY `umeta_id`
  ORDER BY `umeta_id`
";

// $q = $wpdb->prepare( $q, 1111111111);

// $items = $wpdb->get_results( $q );

// var_dump( $items );

// $addresses = site_wc_get_account_addresses( $customer_id );

// $keys = array_keys($addresses);

$address_name = sanitize_text_field( isset($_GET['address-name']) ? $_GET['address-name'] : '' );
if( $address_name!='' && preg_match('/^new_/i', $address_name) ) {
  $new = (int) str_replace( 'new_', '', $address_name );
}
var_dump( $new );
die;

$keys = [1];

var_dump( $keys );

$e = end( $keys );

var_dump( $e );

/*
$address = site__get( 'address' );
$fields = get_user_meta( $user_id );
foreach( $fields as $key => $value ) {
  if( preg_match( '/^'.$address.'_/i', $key) ) {
    echo "$key => $value <br>";
  }
} 
die;

array(
  'kimquoctien.com',
  'hita.com.vn',
  'tdm.vn',
);

$id = intval( isset( $_GET['id'] ) ? $_GET['id'] : 0 );

if( $id>0 ) {
    echo 'Product Sold Count: ' . site_wc_product_sold_count( $id ) . '<br>';
}
*/