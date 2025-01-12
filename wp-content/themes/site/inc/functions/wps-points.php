<?php

add_action('woocommerce_new_order', 'site_wc_handle_new_order', 10, 1);

function site_wc_handle_new_order($order_id) {
    $cart_discount = __( 'Cart Discount', 'points-and-rewards-for-woocommerce' );
    $my_coupons = WC()->cart->get_coupons();
    $used_points = WC()->session->get('wps_cart_points_original');

    if (!empty($used_points)) {
        $current_user_id = get_current_user_id();
        $current_points = (int) get_user_meta( $current_user_id, 'wps_wpr_points', true );
        $new_points = max( 0, $current_points - $used_points );

        // Update new point
        update_user_meta( $current_user_id, 'wps_wpr_points', $new_points );
        update_post_meta( $order_id, 'points_used', $used_points );
    }

    WC()->session->__unset( 'wps_cart_points' );
    WC()->session->__unset( 'wps_cart_points_original' );

    foreach ( $my_coupons as $code => $coupon ){
        if( strtolower($code) == strtolower($cart_discount) ) {
            WC()->cart->remove_coupon( $code );
        }
    }
}

function site_wps_round_down_cart_total_value( $points_calculation, $order_total, $wps_wpr_coupon_conversion_points, $wps_wpr_coupon_conversion_price )
{
    global $site_wps_points_calculation;

    $site_wps_points_calculation = $points_calculation;

    return $points_calculation;
}
// add_filter('wps_round_down_cart_total_value', 'site_wps_round_down_cart_total_value', 20, 4 );

function site_wps_wpr_per_currency_points_on_subtotal( $order_total, $order )
{
    file_put_contents( ABSPATH. '/a_t.json', json_encode([$order_total, $order]) );
}
// add_filter('wps_wpr_per_currency_points_on_subtotal', 'site_wps_wpr_per_currency_points_on_subtotal', 10, 4 );

function site_wps_updated_user_meta( $meta_id, $object_id, $meta_key, $_meta_value )
{
    if( is_admin() || $meta_key != 'wps_wpr_points' ) return false;

    $object_id = absint( $object_id );
	if ( ! $object_id ) {
		return false;
	}

	$table = _get_meta_table( 'user' );
	if ( ! $table ) {
		return false;
	}

    global $wpdb, $site_wps_points_calculation;

    if( $object_id>0 && isset($site_wps_points_calculation) && $site_wps_points_calculation>0 ) {        
        $get_points = (int) get_user_meta( $object_id, 'wps_wpr_points', true );

        $total_points = intval( $site_wps_points_calculation + $get_points );

        $wpdb->update( $table, array(
            'meta_value' => $total_points,
        ), array(
            'umeta_id' => $meta_id
        ) );
        
        $site_wps_points_calculation = 0;
    }
}
// add_action('updated_user_meta', 'site_wps_updated_user_meta', 10, 4 );

function site_wps_wpr_allowed_user_roles_points_features( $check = false )
{
    if( preg_match('/points/i', $_SERVER['REQUEST_URI']) ) {
        site_wps_wpr_account_viewlog();
    }
    
    return true;
}
add_filter('wps_wpr_allowed_user_roles_points_features', 'site_wps_wpr_allowed_user_roles_points_features');

function site_wps_wpr_account_points() 
{
    $user_ID = get_current_user_ID();
    $user = new WP_User( $user_ID );

    /* Include the template file in the woocommerce template*/
    require get_theme_file_path( '/partials/wps-wpr-points-template.php' );
}

function site_wps_wpr_account_viewlog() {
    $user_ID = get_current_user_ID();
    $user    = new WP_User( $user_ID );
    
    require get_theme_file_path( '/partials/wps-wpr-points-log-template.php' );
}
// add_action('woocommerce_account_points_endpoint', 'site_wps_wpr_account_viewlog');

/*
add_action('init',function(){
    $user_ID = get_current_user_ID();
    if( $user_ID!=19 ) return;

    if( class_exists('Points_Rewards_For_Woocommerce') == false ) return;

    $wps = new Points_Rewards_For_Woocommerce();
    remove_action('woocommerce_account_points_endpoint', array( $wps, 'wps_wpr_account_points'), 10, 1 );
    // remove_action('woocommerce_account_view-log_endpoint', array( $wps, 'wps_wpr_account_viewlog'), 10, 1  );

    add_action('woocommerce_account_points_endpoint', 'site_wps_wpr_account_points');
    // add_action('woocommerce_account_view-log_endpoint', 'site_wps_wpr_account_viewlog');

}, 90 );
*/ 