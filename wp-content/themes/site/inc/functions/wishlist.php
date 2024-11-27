<?php

function site_add_to_wishlist_url( $product_id ) 
{
    // if( get_current_user_id() == 0 ) {
    //     return '#modal-noti';
    // }
    
    $args = array(
        // 'base_url' => get_permalink( $product_id ) 
    );
    
    return YITH_WCWL()->get_add_to_wishlist_url( $product_id, $args ); 
}

function site_check_action_add_to_wishlist()
{
    if( isset($_GET['add_to_wishlist']) && get_current_user_id() == 0 ) {
        unset($_GET['add_to_wishlist']);

        wp_redirect( site_login_url() );
        exit();
    }
}
// add_action('wp', 'site_check_action_add_to_wishlist', 1);

function site_remove_from_wishlist_url( $product = false, $base_url = '' )
{
    if( is_numeric($product) ) {
        $product = wc_get_product( $product );
    }

    if( $base_url == '' ) {
        $base_url = $product->get_permalink();
    }
    
    return wp_nonce_url( add_query_arg( 'remove_from_wishlist', $product->get_id(), $base_url ), 'remove_from_wishlist' ); 
}

function site_wcwl_count( $product_id = false ) 
{
    $count = 0;

    $items = site_wcwl_get_products();
    foreach( $items as $item ) {
        if( $product_id == $item->get_product_id() ) {
            $count++;
        }
    }
    
    // $count              = yith_wcwl_count_add_to_wishlist( $product_id );
    // $current_user_count = $count ? YITH_WCWL_Wishlist_Factory::get_times_current_user_added_count( $product_id ) : 0;

    return $count;
}

function site_wcwl_get_products( $field = 'all' )
{
    global $site_wcwl_items, $site_wcwl_ids;

    if( empty($site_wcwl_items) ) {
        $wishlist = YITH_WCWL::get_instance();

        $site_wcwl_items = $wishlist->get_products( array('wishlist_id' => 'all' ) );
    }
    
    if( $field == 'id' ) {
        if( empty($site_wcwl_ids) ) {
            $site_wcwl_ids = array();

            if( $site_wcwl_items ) {
                foreach( $site_wcwl_items as $item ) {
                    $site_wcwl_ids[] = $item->get_product_id();
                }
            }
        }

        return $site_wcwl_ids;
    }

    return $site_wcwl_items;
}

/*
 * Update like count to product
 */
function site_yith_wcwl_update_like_count( $prod_id = 0 )
{
    if( $prod_id > 0 ) {
        global $wpdb;
        
        $tb = $wpdb->prefix . 'yith_wcwl';

        $count = (int) $wpdb->get_var( "SELECT count(*) FROM `$tb` WHERE `prod_id`='$prod_id' " );

        update_post_meta($prod_id, 'like', $count);
    }
}
add_action( 'yith_wcwl_added_to_wishlist', 'site_yith_wcwl_update_like_count' );
add_action( 'yith_wcwl_removed_from_wishlist', 'site_yith_wcwl_update_like_count' );