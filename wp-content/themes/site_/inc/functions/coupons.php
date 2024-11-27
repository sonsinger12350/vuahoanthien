<?php
/*
 * https://woocommerce.github.io/code-reference/classes/WC-Coupon.html
 */


function site_get_coupons( $params = array() )
{
    if( class_exists('WC_Coupon') == false ) return array();

    $key = http_build_query($params,'','&');
    
    $defaults = array(
        'type'      => '',
        'sort'      => '',
        'limit'     => -1,
    );
    
    $params = wp_parse_args( $params, $defaults );
    
    global $coupons;

    if( empty($coupons[$key]) ) {
        $coupons[$key] = array();

        $args = array(
            'post_type' => 'shop_coupon',
            'posts_per_page' => $params['limit']
        );
        
        if( $params['type'] == '' ) {
            $args['meta_query'] = array(
                array(
                    'key' => 'allow',
                    'value' => 'all',
                    // 'compare' => '=',
                )
            );
        }

        if( $params['sort'] == 'minimum_amount' ) {
            $args['meta_key'] = 'minimum_amount';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
        }

        $list = get_posts($args);

        if( $list ) {
            foreach( $list as $p ) {
                $coupons[$key][ $p->post_name ] = new WC_Coupon($p->post_name);
            }
        }
    }

    return $coupons[$key];
}

function site_coupon_register_post_type_args( $args = array(), $post_type = '' )
{
    if( $post_type == 'shop_coupon' ) {
        if( is_array($args['supports']) == false ) {
            $args['supports'] = array('thumbnail');
        } else if( in_array('thumbnail', $args['supports']) == false ) {
            $args['supports'][] = 'thumbnail';
        }
    }

    return $args;
}
add_filter( 'register_post_type_args', 'site_coupon_register_post_type_args', 10, 2 );

/*
 * includes/class-wc-coupon.php
 */
function site_coupon_wc_coupon_message( $msg, $msg_code, $wc_coupon ) {

    // WC_COUPON_SUCCESS = 200 
    if ( $msg_code == 200 ) {

    }

    // file_put_contents(ABSPATH. '/a_p.json', json_encode([ $msg, $msg_code, $wc_coupon ]) );

    return $msg;
}
// add_filter('woocommerce_coupon_message', 'site_coupon_wc_coupon_message', 10, 3);

/*
 * includes/class-wc-coupon.php
 */
function site_coupon_wc_coupon_error( $err, $err_code, $wc_coupon ) 
{    
    // E_WC_COUPON_INVALID_REMOVED = 101
    if( $err_code == 101 ) {
        $err = sprintf( __( 'Sorry, it seems the coupon "%s" is invalid - it has now been removed from your order.', 'woocommerce' ), esc_html( $wc_coupon->get_code() ) );
    }
    // E_WC_COUPON_NOT_EXIST = 105
    else if( 0 && $err_code == 105 ) {
        $err = 'Ma khong ton tai';
    }
    
    return $err;
}
add_filter('woocommerce_coupon_error', 'site_coupon_wc_coupon_error', 90, 3);

/*
 * includes/class-wc-cart.php
 */
function site_wc_apply_individual_use_coupon( $coupons_to_keep = array(), $the_coupon = false, $applied_coupons = array() ) 
{
    // chuc nang nay chay

    if( $the_coupon instanceof WC_Coupon && count($applied_coupons)>0 )
    {
        $coupons_to_keep = array();

        foreach( $applied_coupons as $applied_coupon ) {
            if( $applied_coupon != $the_coupon->get_code() ) {
                $coupons_to_keep[] = $applied_coupon;
            }
        }
    }

    return $coupons_to_keep;
}
// add_filter('woocommerce_apply_individual_use_coupon', 'site_wc_apply_individual_use_coupon', 10, 3);

function site_wc_applied_coupon( $coupon_code )
{
    // chuc nang nay chay

    $cart_coupons = WC()->cart->get_coupons();

    if( $cart_coupons>1 ) {
        foreach ( $cart_coupons as $code => $coupon ) {
            if( $coupon_code != $code ) {
                WC()->cart->remove_coupon( $coupon );
            }
        }
    }
}
// add_action('woocommerce_applied_coupon', 'site_wc_applied_coupon', 10, 2 );

/**
 * Redirect to cart after submit code success
 */
function site_wc_applied_coupon_redirect_to_cart()
{
    wp_redirect( wc_get_cart_url() );
    exit();
}
// add_action('woocommerce_applied_coupon', 'site_wc_applied_coupon_redirect_to_cart', 20);
// add_action('woocommerce_removed_coupon', 'site_wc_applied_coupon_redirect_to_cart', 20);

/**
 * Template
 */
function site_wc_coupon_discount( $coupon, $prev = '-' )
{
    $html = '';

    $amount = site_wc_coupon_discount_amount( $coupon );

    if( $amount > 0 )
    {
        $html = $prev . site_wc_price( $amount ) . '<span>đ</span>';
    }
    
    return $html;
}

function site_wc_coupon_discount_amount( $coupon )
{
    $amount = 0;

    if( $coupon instanceof WC_Coupon )
    {
        $amount = (int) WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
    }

    return $amount;
}

// Calculate Per Point Conversion
// Hook into order creation to calculate and update points
add_action('woocommerce_new_order', 'calculate_and_update_points');

function calculate_and_update_points($order_id) {
    $order = wc_get_order($order_id);
    $conversion_rate = 1; // Adjust this to your desired conversion rate (1 point = $1)

    // Calculate points earned based on the order's total amount and conversion rate
    $points_earned = (int) ($order->get_total() * $conversion_rate);

    // Get the user ID associated with the order
    $user_id = $order->get_user_id();

    // Update the user's points balance (assuming the points are stored in the user meta field)
    $current_points = (int) get_user_meta($user_id, 'points', true);
    $updated_points = $current_points + $points_earned;
    update_user_meta($user_id, 'points', $updated_points);
}