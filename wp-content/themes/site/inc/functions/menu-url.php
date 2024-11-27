<?php

function site_shop_url()
{
    // return get_permalink( 6 );
    return get_permalink( wc_get_page_id( 'shop' ) );
}

function site_cart_url()
{
    return get_permalink( 7 );
}

function site_account_url()
{
    return get_permalink( 9 );
}

function site_login_url($redirect_link = '') {
    $base_url = get_permalink(179);

    // Check if a custom redirect link is provided
    if (!empty($redirect_link)) {
        // Encode the redirect link to make it URL-safe
        $encoded_redirect_link = urlencode($redirect_link);

        // Append the encoded redirect link as a query parameter
        $url_with_redirect = add_query_arg('redirect_link', $encoded_redirect_link, $base_url);

        return $url_with_redirect;
    }

    return $base_url;
}

function site_register_url()
{
    return get_permalink( 189 );
}

function site_wishlist_url()
{
    return get_permalink( 11 );
    //return get_permalink( 41502 );
}

function site_compare_url()
{
    return get_permalink( 366 );
}

function site_shop_search( $args = array() )
{
    // $cat = get_query_var('product_cat');

    // if( $cat && $cat != 'gia-soc-hom-nay' ) {
    //     $url = add_query_arg( $args );
    // } else {
    //     $url = add_query_arg( $args, site_shop_url() );
    // }
    
    return add_query_arg( $args, site_shop_url() );
}

function site_coupon_url()
{
    return get_permalink( 2419 );
}