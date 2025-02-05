<?php

function site_woocommerce_setup_theme()
{
    /* woocommerce templates */
    add_theme_support( 'woocommerce', array(
        // 'thumbnail_image_width' => 150,
        // 'single_image_width'    => 300,

        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 3,
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 4,
        ),
    ) );
    
    // add_theme_support( 'wc-product-gallery-zoom' );
    // add_theme_support( 'wc-product-gallery-lightbox' );
    // add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'site_woocommerce_setup_theme' );

/**
 * Change number of products that are displayed per page (shop page)
 */
function site_new_loop_shop_per_page( $cols ) {
    // $cols contains the current number of products per page based on the value stored on Options –> Reading
    // Return the number of products you wanna show per page.
    $cols = 24;

    $term = get_queried_object();

    if( isset($term->taxonomy) && $term->taxonomy == 'product_tag' ) {
        $cols = 10000;
    }
    
    return $cols;
}
add_filter( 'loop_shop_per_page', 'site_new_loop_shop_per_page', 20 );

/**
 * Hook: woocommerce_single_product_summary.
 *
 * @hooked woocommerce_template_single_title - 5
 * @hooked woocommerce_template_single_rating - 10
 * @hooked woocommerce_template_single_price - 10
 * @hooked woocommerce_template_single_excerpt - 20
 * @hooked woocommerce_template_single_add_to_cart - 30
 * @hooked woocommerce_template_single_meta - 40
 * @hooked woocommerce_template_single_sharing - 50
 * @hooked WC_Structured_Data::generate_product_data() - 60
 *
 * Hook: woocommerce_before_shop_loop.
 *
 * @hooked woocommerce_output_all_notices - 10
 * @hooked woocommerce_result_count - 20
 * @hooked woocommerce_catalog_ordering - 30
 */
function site_woocommerce_remove_actions()
{
    // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
    // remove_all_actions( 'woocommerce_single_product_summary' );

    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
}
// add_action('init','site_woocommerce_remove_actions', 99 );

remove_filter( 'the_title', 'wc_page_endpoint_title' );

// remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
// remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
// remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

function site_woocommerce_sidebars() 
{
    site_get_template_part( 'parts/section/left');
}
// add_action('woocommerce_sidebar','site_woocommerce_sidebars', 10, 2 );

function site_woocommerce_single_product_summary() 
{
    global $product, $post;
?>
    <div class="product-desc">
        <?php the_content(); ?>
    </div>
<?php
}
// add_action('woocommerce_single_product_summary','site_woocommerce_single_product_summary', 10, 2 );

function site_add_title_in_woocommerce_before_main_content() 
{
    global $product, $post;
    
    if( is_archive() ) {
        // echo '<h1>Sản phẩm</h1>';
        echo '<h1>';
            woocommerce_page_title();
        echo '</h1>';
    } else {
        the_title('<h1>','</h1>');
    }
}
// add_action('woocommerce_before_main_content','site_add_title_in_woocommerce_before_main_content', 10, 2 );

/**
 * Remove product data tabs
 */
// add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] );      	    // Remove the description tab
    unset( $tabs['reviews'] ); 			        // Remove the reviews tab
    unset( $tabs['additional_information'] );  	// Remove the additional information tab

    return $tabs;
}

/**
 * Change number of related products output
 */ 
function woo_related_products_limit() {
    global $product;

    $args['posts_per_page'] = 6;
    return $args;
}

// add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args', 20 );
function jk_related_products_args( $args ) {
    $args['posts_per_page'] = 6; // 4 related products
    $args['columns'] = 3; // arranged in 2 columns
    return $args;
}

/**
 * Remove menu my account
 */
function site_woocommerce_account_menu_items( $items ) 
{
    unset($items['downloads']); // Remove downloads item
    unset($items['customer-logout']);
    $items['wishlist'] = __('Wishlist', 'site');
    // $items['later'] = __('Buy Later Products', 'site');
    $items['watched'] = __('Watched Products', 'site');

    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'site_woocommerce_account_menu_items', 98 );

add_action( 'init', function() {
    // Repeat above line for more items ...
    add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'later', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'watched', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'point', EP_ROOT | EP_PAGES );
} );

function site_wc_endpoint_my_products()
{
    wc_get_template_part('myaccount/my-products' );
}
add_action( 'woocommerce_account_watched_endpoint', 'site_wc_endpoint_my_products' );
add_action( 'woocommerce_account_later_endpoint', 'site_wc_endpoint_my_products' );
add_action( 'woocommerce_account_wishlist_endpoint', 'site_wc_endpoint_my_products' );

function site_wc_endpoint_my_point()
{
    wc_get_template_part('myaccount/my-point' );
}
add_action( 'woocommerce_account_point_endpoint', 'site_wc_endpoint_my_point' );

/* 
 * https://woocommerce.wp-a2z.org/oik_api/wc_get_order_statuses/ 
 */
function site_wc_order_statuses( $statuses = array() ) 
{    
    // unset($statuses['wc-pending']);
    unset($statuses['wc-refunded']);
    unset($statuses['wc-failed']);
    unset($statuses['wc-on-hold']);
    unset($statuses['wc-checkout-draft']);
    // unset($statuses['wc-cancelled']);

    $new_statuses = [];

    foreach( $statuses as $key => $name ) {
        if( $key == 'wc-completed' ) {
            $new_statuses['wc-shipping'] = _x( 'Shipping', 'Order status', 'woocommerce' );
        }

        $new_statuses[$key] = $name;
    }

    $statuses = $new_statuses;
    
    return $statuses;
}
add_filter( 'wc_order_statuses' , 'site_wc_order_statuses' );

/*
 * https://neversettle.it/add-custom-order-action-woocommerce/
 */
function site_wc_order_actions( $actions = array() ) 
{
    $actions['wc-shipping'] = _x( 'Shipping', 'Order status', 'woocommerce' );

    return $actions;
}
// add_action( 'woocommerce_order_actions', 'site_wc_order_actions' );

/* https://stackoverflow.com/questions/49562272/add-custom-bulk-actions-to-admin-orders-list-in-woocommerce-3 */
// Adding to admin order list bulk dropdown a custom action 'custom_downloads'
function downloads_bulk_actions_edit_product( $actions ) {
    $actions['write_downloads'] = __( 'Download orders', 'woocommerce' );
    return $actions;
}
// add_filter( 'bulk_actions-edit-shop_order', 'downloads_bulk_actions_edit_product', 20, 1 );

function site_wc_address_fields()
{
    $list = array(
        'last_name',
        'company',
        // 'address_1',
        'address_2',
        'postcode',
        'city',
        // 'phone',
        // 'country',
    );

    return $list;
}

function site_wc_checkout_fields( $fields )
{
    // file_put_contents( ABSPATH . '/a_fields.json', json_encode($fields) );

    $list = site_wc_address_fields();
    
    foreach( ['billing', 'shipping'] as $name ) {
        foreach( $list as $value ) {
            unset($fields[$name][$name . '_' . $value ]);
        }
    }
    
    //Add Phone field;
    $fields['shipping']['shipping_phone'] = site_wc_get_phone_field();
    
    return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'site_wc_checkout_fields' );

function site_wc_default_address_fields( $fields )
{
    
    $list = site_wc_address_fields();
    
    foreach( $list as $value ) {
        unset($fields[$value]);
    }
    
    // Add phone field;
    $fields['phone'] = site_wc_get_phone_field();

    return $fields;
}
add_filter( 'woocommerce_default_address_fields' , 'site_wc_default_address_fields' );

function site_wc_get_phone_field()
{
    return array(
        'label' => 'Số điện thoại',
        'type' => 'tel',
        'required' => true,
        'class' => array(),
        'validate' => array( 'phone' ),
        'autocomplete' => 'tel',
        'priority' => 60,
    );
}

function site_wc_get_prices_static( $key = '' )
{
    $prices = array(
        '0-1'   => 'Dưới 1 triệu',
        '1-5'   => 'Từ 1 - 5 triệu',
        '5-10'  => 'Từ 5 - 10 triệu',
        '10-20' => 'Từ 10 - 20 triệu',
        '20-50' => 'Từ 20 - 50 triệu',
        '50'    => 'Trên 50 triệu',
    );

    if( $key!='' ) {
        if( isset($prices[$key]) ) {
            return $prices[$key];
        }

        return '';
    }
    
    return $prices;
}

function site_wc_get_sorts( $key = '' )
{
    $list = array(
        'date' => 'Mới nhất',
        // 'sale' => 'Khuyến mãi',
        'like' => 'Yêu thích',
        'discount' => 'Giảm giá nhiều',
        'buy' => 'Bán chạy',
        'view' => 'Xem nhiều',
        'price-up' => 'Giá tăng dần',
        'price-down' => 'Giá giảm dần',
    );
    
    if( $key!='' ) {
        if( isset($list[$key]) ) {
            return $list[$key];
        }

        return '';
    }
    
    return $list;
}

function site_wc_get_comment_sorts( $key = '' )
{
    $list = array(
        "newest" => 'Mới nhất',
        "oldest" => 'Cũ nhất',
        "highestRate" => 'Đánh giá cao nhất',
        "lowestRate" => 'Đánh giá thấp nhất',
        "photo" => 'Đánh giá có hình ảnh',
        // "mostHelpful" => 'Hữu ích nhất',
    );

    if( $key!='' ) {
        if( isset($list[$key]) ) {
            return $list[$key];
        }

        return '';
    }
    
    return $list;
}

function site_wc_get_parent_terms($term) 
{
    if ($term->parent > 0){
        $term = get_term_by("id", $term->parent, $term->taxonomy);

        return site_wc_get_parent_terms($term);
    }

    return $term->term_id;
}

function site_wc_get_term_level( $term, $level = 1 )
{
    if( isset($term->parent) && $term->parent > 0 )
    {
        $term = get_term_by("id", $term->parent, $term->taxonomy);
        
        return site_wc_get_term_level( $term, $level + 1 );
    }

    return $level;
}

// function site_wc_get_terms_to_root($term, $level = 0 )
// {
//     if( empty($term->term_id) ) {
//         return [];
//     }
    
//     $terms = array( $term );
//     while ($term->parent > 0){
//         $term = get_term_by("id", $term->parent, $term->taxonomy);
//         $terms[] = $term;
//     }
    
//     krsort($terms);

//     $list = array();
//     foreach( $terms as $term ){
//         $list[] = $term;
//     }

//     $level--;
//     if( $level>-1 && isset($list[$level]) ) {
//         return $list[$level];
//     }

//     return $list;
// }

function site_wc_get_terms_to_root($term, $level = 0)
{
    if(empty($term->term_id)) {
        return [];
    }
    
    // Initialize the terms array with the provided term, 
    // unless it's the one we want to exclude (ID = 100)
    $terms = array();
    if($term->term_id != 100) {
        $terms[] = $term;
    }
    
    while ($term->parent > 0) {
        $term = get_term_by("id", $term->parent, $term->taxonomy);
        // Exclude the term if its ID is 100
        if ($term->term_id == 100) {
            continue;
        }
        $terms[] = $term;
    }
    
    krsort($terms);

    $list = array();
    foreach($terms as $term) {
        $list[] = $term;
    }

    $level--;
    if($level > -1 && isset($list[$level])) {
        return $list[$level];
    }

    return $list;
}


function site_wc_get_discount_percent( $product )
{
    if( $product->get_sale_price() == 0 ) return 0;

    $product_type = get_post_meta($product->get_id(), 'producttype', true);
    if ($product_type === 'gach') {
        $heso_quydoi = get_post_meta($product->get_id(), 'heso_quydoi', true);
        
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        return round( ( $regular_price - $sale_price ) / $regular_price * 100, 0 );
        
    } else {
        return round( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() * 100, 0 );
    }    
}

function site_wc_the_discount_percent( $product )
{
    echo site_wc_get_discount_percent( $product );
}

function site_wc_get_stars_percent( $product = false )
{
    $rating = 0;
    
    if( is_numeric($product) ) 
    {
        $rating = $product;
    } 
    else if( $product instanceof WC_Product )
    {
        $rating = (float) $product->get_average_rating();
    }
    
    if( $rating>0 ) {
        $rating = round( $rating / 5 * 100, 0);
    }

    return $rating;
}

function site_wc_the_stars_percent( $product = false )
{
    echo site_wc_get_stars_percent( $product );
}

function site_setup_product_data( $item )
{
    global $product, $product_temp;

    if( is_object($product) && empty($product_temp) ) {
        $product_temp = $product;
    }
    
    $product = $item;
}

function site_reset_product_data()
{
    global $product, $product_temp;

    if( is_object($product_temp) ) {
        $product = $product_temp;
        unset($product_temp);
    }
}

// class-wc-query.php
function site_wc_product_query( $query ) 
{
    if( !$query->is_main_query() ) return;

    $meta = array();

    if ( is_search() ) {
        $sort = site__get( 'sort', 'discount' );
    } else {
       $sort = site__get( 'sort', 'discount' );
    }

    
    if ($sort != '') {
        $order = 'DESC';
        $orderby = $sort;

        if ($sort != 'date') {
            $sorts = explode('-', $sort);
            if ($sorts[0] == 'price' && isset($sorts[1])) {
                $sort = '_' . $sorts[0];

                if ($sorts[1] == 'up') {
                    $order = 'ASC';
                }
            } else if ($sort == 'sale') {
                $sort = '_sale_price';
                $order = 'ASC';
            } else if ($sort == 'discount') {
                $sort = 'sale_off';
            } else if ($sort == 'view') {
                $sort = 'view_count';
            } else if ($sort == 'buy') {
                $sort = 'total_sales';
            } else if ($sort == 'like') {
                $sort = 'virtual_like_number';
            }

            $orderby = 'meta_value_num';
            $query->set('meta_key', $sort);
        }

        // Set the primary orderby and order
        $query->set('orderby', $orderby);
        $query->set('order', $order);

        // Push products with price 0 or no price to the end when sorting by price
        if (strpos($sort, '_price') !== false) {
            if ($order == 'ASC') {
                $query->set('meta_query', array(
                    array(
                        'key'     => '_price',
                        'value'   => 0,
                        'compare' => '>',
                        'type'    => 'NUMERIC'
                    )
                ));
            }

            $query->set('orderby', array(
                'meta_value_num' => $order,
                'price_zero_last' => 'ASC'
            ));

            // $meta_query = $query->get('meta_query');
            // if (!is_array($meta_query)) {
            //     $meta_query = array();
            // }

            // $meta_query[] = array(
            //     'key' => '_price',
            //     'value' => 0,
            //     'compare' => '!=',
            //     'type' => 'NUMERIC'
            // );

            // $query->set('meta_query', $meta_query);
        }
    }

    $tax_query = array();

    // $brand = site__get( 'brand', '' );
    $brands = site__get( 'thuong-hieu', array() );
    if( is_array($brands) && count($brands) )
    {
        $tax_query[] = array(
            'taxonomy' => 'product-brand',
            'field'    => 'term_id',
            'terms'    => $brands,
        );
    }

    $cats = site__get( 'danh-muc', array() );
    if( is_array($cats) && count($cats) )
    {
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $cats,
        );
    }

    if( count($tax_query)>0 ) {
        if( count($tax_query)>1 ) {
            $tax_query['relation'] = 'AND';
        }

        $query->set( 'tax_query', $tax_query );
    }

    // $price = site__get( 'price', '' );
    $prices = site__get( 'khoang-gia', array() );
    if( is_array($prices) && count($prices) )
    {
        $price_meta = array( 'relation' => 'OR' );

        foreach( $prices as $price )
        {
            $from   = 0;
            $to     = 0;

            $prices = explode('-', $price);        
            if( count($prices) == 2 ) {
                $from   = $prices[0];
                $to     = $prices[1];
            } else if( $price == '1' ){
                $to = 1;
            } else if( $price == '50' ){
                $from = 50;
            }

            $vnd = 1000000;

            $sub_meta = array( 'relation' => 'AND' );
            
            if( $from>0 ) {
                $sub_meta[] =  array(
                    'key'     => '_price',
                    'compare' => '>=',
                    'value'   => $from * $vnd,
                    'type' => 'NUMERIC'
                );
            }

            if( $to>0 ) {
                $sub_meta[] =  array(
                    'key'     => '_price',
                    'compare' => '<=',
                    'value'   => $to * $vnd,
                    'type' => 'NUMERIC'
                );
            }

            if( count($sub_meta)>1 ) {
                $price_meta[] = $sub_meta;
            }
        }
        
        if( count($price_meta)>1 ) {
            $meta[] = $price_meta;
        }
    }

    $types = site__get( 'types', array() );
    if( is_array($types) && count($types) )
    {
        $meta[] =  array(
            'key'     => 'type',
            'compare' => 'IN',
            'value'   => $types,
        );
    }
    
    /*
    $cat = get_queried_object();
    if( $cat && is_object($cat) && isset($cat->taxonomy) ) {
        $fields = site_acf_get_fields( $cat->taxonomy . ':' . $cat->slug, $group = 1 );
        foreach( $fields as $i => $field ) {
            $key = $field['excerpt'];
            $value = site__get( $key, '' );
            if( $value != '' )
            {
                $meta[] =  array(
                                'key'     => $key . '_list',
                                'compare' => '=',
                                'value'   => $value,
                            );
            }
        }
    }
    */

    if( count($meta)>0 ) {
        if( count($meta)>1 ) {
            $meta['relation'] = 'AND';
        }
        $query->set( 'meta_query', $meta);
    }
	
	if( site__get('test', '') == 'abs' ) {
		file_put_contents( ABSPATH . '/test.txt', json_encode($query) );
	}
}
add_filter( 'woocommerce_product_query', 'site_wc_product_query' , 99, 2);

function site_product_viewed( $post_id = 0 ) {
    if ( $post_id == 0 ) {
        $post_id = get_the_ID();
    }

    $key = 'view_count';

    // Ensure correct retrieval and update of post meta
    $count = intval( get_post_meta( $post_id, $key, true ) );
    $count++;
    update_post_meta( $post_id, $key, $count );

    $user_id = get_current_user_id();

    if ( $user_id > 0 ) {
        $products = get_user_meta( $user_id, 'products', true );

        $products = !empty( $products ) ? explode(',', $products) : array();

        // Debug: Log the current products array and post ID
        // var_dump('Current products array: ' . print_r($products, true));
        // var_dump('Post ID: ' . $post_id);

        // Use strict comparison to ensure type consistency
        if ( !in_array( (string)$post_id, $products, true ) ) {
            $products[] = $post_id;

            // var_dump('Adding Post ID: ' . $post_id);

            $updated = update_user_meta( $user_id, 'products', implode( ',', $products ) );

            // if ( $updated === false ) {
            //     var_dump('Failed to update user meta for user ID ' . $user_id);
            // } else {
            //     var_dump('User products after: ' . implode( ',', $products ));
            // }
        } 
        // else {
        //     var_dump('Post ID already in products array.');
        // }
    } else {
        site_add_cookie_array( 'products', $post_id );
    }
}


function site_get_products_viewed()
{
    $products = array();

    $user_id = get_current_user_id();
    if( $user_id>0 ) {
        $products = explode(',', get_user_meta( $user_id, 'products', true ) );
        //$products = site_get_cookie_array('products');
    } else {
        $products = site_get_cookie_array('products');
    }
    
    return $products;
}

function site_the_product_brand( $post_id = 0, $key = 'name' )
{
    $brand = site_get_product_brand( $post_id, $key );

    if( is_object($brand) && isset($brand->$key) ) {
        echo $brand->$key;
    }
}

function site_get_product_brand( $post_id = 0 )
{
    if( $post_id == 0 ) {
        $post_id = get_the_ID();
    }

    $brands = get_the_terms( $post_id, 'product-brand' );

    if( isset($brands[0]) ) {
        return $brands[0];
    }

    return false;
}

/*
 * Return list taxonomies `product-brand` by category
 */
function site_the_category_brands( $category = array() )
{
    return site_the_category_terms( $category, 'product-brand' );
}

/*
 * Return list taxonomies by category
 */
function site_the_category_terms( $cat_names = array(), $taxonomy = '', $limit = 0 )
{
    $list = array();

    if( $taxonomy == '' ) {
        return $list;
    }

    $products = wc_get_products(array(
        'limit' => 30000,
        'category' => $cat_names
    ));

    foreach( $products as $product ) {
        $terms = get_the_terms( $product->get_id(), $taxonomy );

        if( $terms ) {
            foreach( $terms as $item ) {
                $list[ $item->term_id ] = $item;

                if( $limit>0 && count($list)>=$limit ) {
                    return $list;
                }
            }
        }
    }
    
    return $list;
}

/*
 * Return list taxonomies by taxonomies
 * 
 * Relate by products
 */
function site_get_terms_by_terms( $terms = array(), $taxonomy = '', $limit = -1 )
{
    $list = array();

    if( $taxonomy == '' || count($terms) == 0 ) {
        return $list;
    }

    $term_taxonomy = $terms[0]->taxonomy;
    $term_ids = array();

    foreach( $terms as $item ) {
        $term_ids[] = $item->term_id;
    }

    $products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => $term_taxonomy,
                'field'    => 'term_id',
                'terms'    => $term_ids
            )
        )
    ));

    foreach( $products as $p ) {
        $_terms = get_the_terms( $p->ID, $taxonomy );

        if( $_terms ) {
            foreach( $_terms as $item ) {
                $list[ $item->term_id ] = $item;

                if( $limit>0 && count($list)>=$limit ) {
                    return $list;
                }
            }
        }
    }
    
    return $list;
}

function site_get_terms_level_2_by_terms( $terms = array(), $taxonomy = '', $limit = -1 ) {
     $list = array();

    if( $taxonomy == '' || count($terms) == 0 ) {
        return $list;
    }

    $term_taxonomy = $terms[0]->taxonomy;
    $term_ids = array();

    foreach( $terms as $item ) {
        $term_ids[] = $item->term_id;
    }

    $products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => $term_taxonomy,
                'field'    => 'term_id',
                'terms'    => $term_ids
            )
        )
    ));

    foreach( $products as $p ) {
        $_terms = get_the_terms( $p->ID, $taxonomy );

        if( $_terms ) {
            foreach( $_terms as $item ) {
                // Exclude terms with the slug 'gia-soc-hom-nay'
                if ($item->slug === 'gia-soc-hom-nay') {
                    continue;
                }
                
                // Check if the category has a parent
                if ( $item->parent != 0 ) {
                    $item->level = 1; // Set initial level to 1

                    // Loop to find the category's level
                    while ( $item->parent != 0 ) {
                        $item = get_term( $item->parent, $taxonomy );
                        $item->level++;
                    }
                } else {
                    $item->level = 0; // No parent, so level is 0
                }

                $list[ $item->term_id ] = $item;

                if( $limit > 0 && count($list) >= $limit ) {
                    return $list;
                }
            }
        }
    }
    
    return $list;
}


function site_wc_redirect_to_checkout() 
{
    if( isset($_REQUEST['simple']) ) {
        $product = wc_get_product( $_REQUEST['add-to-cart'] );
        wp_redirect( $product->get_permalink() );
        exit();
    }
    
    if( isset($_REQUEST['checkout']) ) {
        wp_redirect( wc_get_checkout_url() );
        exit();
    }

    if( isset($_REQUEST['remove_coupon']) ) {
        wp_redirect( wc_get_cart_url() );
        exit();
    }

    return false;
}
add_filter('wc_add_to_cart_message_html', 'site_wc_redirect_to_checkout' );

function site_wc_add_message( $message ) 
{
    return '';
}
add_filter( 'woocommerce_add_message', 'site_wc_add_message' );

function site_wc_add_error( $message ) 
{
    if( preg_match('/(wishlist|thích|shipping)/i', $message) ) {
        return '';
    }
    
    return str_replace( 'Mục ', '', $message );
}
add_filter( 'woocommerce_add_error', 'site_wc_add_error' );

function site_wc_registration_auth_new_customer( $check, $new_customer_id )
{
    if( $new_customer_id>0 ) {

        $meta_fields = array(
            'billing_email',
            'billing_phone',
            'customer_type'
        );
        
        foreach( $meta_fields as $key ) {
            $value = sanitize_text_field( isset($_REQUEST[$key]) ? $_REQUEST[$key] : '' );
            if( $value!='' ) {
                update_user_meta( $new_customer_id, $key, $value );
            }
        }

        $fields = array( 
            'display_name',
            'last_name',
            'first_name',
        );
        
        $data = array(
            'ID' => $new_customer_id
        );

        $display_name = sanitize_text_field( isset($_REQUEST['display_name']) ? $_REQUEST['display_name'] : '' );
        if( $display_name!='' ) {
            $data['display_name'] = $display_name;

            $list   = explode(' ', $display_name);
            $n      = count($list) - 1;

            if( $n>0 ) {
                $data['last_name'] = $list[$n];
                unset($list[$n]);
            }

            $data['first_name'] = implode(' ', $list);
        }
        
        if( count($data)>1 ) {
            wp_update_user( $data );
        }
    }

    return $check;
}
add_filter('woocommerce_registration_auth_new_customer', 'site_wc_registration_auth_new_customer', 10, 2 );

function site_wc_user_customer_types() 
{
    $list = array(
        1 => 'Cá nhân',
        2 => 'Nhà thầu',
    );

    return $list;
}

// user-edit.php
function site_wc_user_contactmethods( $methods = array(), $user ) 
{
    $fields = array( 
        'phone'
    );

    foreach( $fields as $key ) {
        $methods[ $key ] = ucwords($key);
        $user->$key = get_user_meta( $user->ID, $key, true );
    }

    return $methods;
}
// add_filter('user_contactmethods', 'site_wc_user_contactmethods', 10, 2 );

// price
function site_wc_price( $price = 0 )
{
    if( !is_numeric($price) ) return '';

    return number_format( $price, 0, '.', ',' );
}

add_filter( 'woocommerce_registration_error_email_exists', function( $html ) {
    return 'Số điện thoại đã được đăng ký rồi. Vui lòng đăng nhập!';
} );

function site_wc_save_meta_sale_off( $post_id = 0, $post = false, $update = true ) 
{
    // Only set for post_type = product!
    if ( 'product' !== $post->post_type ) {
        return;
    }
    
    // Add new
    if( $update == false ) {
        update_post_meta( $post_id, 'view_count', 0 );
        update_post_meta( $post_id, 'like', 0 );
        
        $fields = array(
            'type' 
        );
    
        foreach( $fields as $key ) {
            $value = (int) get_post_meta( $post_id, $key, true );
            update_post_meta( $post_id, $key, $value );
        }
    }

    $sale_price = (int) get_post_meta( $post_id, '_sale_price', true );
    $regular_price = (int) get_post_meta( $post_id, '_regular_price', true );
    $sale_off = 0;

    if( $sale_price > 0 && $regular_price > $sale_price ) 
    {
        $sale_off = round( ( $regular_price - $sale_price ) / $regular_price * 100 , 0);
    }
    
    update_post_meta( $post_id, 'sale_off', $sale_off );
}
add_action( 'save_post', 'site_wc_save_meta_sale_off', 10, 3 );

function site_wc_fb_share_url( $product = false )
{
    // instanceof WP_Product

    if( $product == false ) return '';

    $params = array(
        'u' => $product->get_permalink(),
    );
    
	return site_fb_share_url( $params );
}

function site_wc_pagi_links()
{
    $links = [];

    add_filter('previous_posts_link_attributes', function(){ return 'class="prev page-link"'; });
    add_filter('next_posts_link_attributes', function(){ return 'class="next page-link"'; });

    $next_link = get_previous_posts_link( '<span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>' );
    $prev_link = get_next_posts_link( '<span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>' );

    if ( $prev_link ) {
        $links[] = $prev_link;
    }
    
    if ( $next_link ) {
        $links[] = $next_link;
    }

    return $links;
}

/**
 * Submiting Data
 */
function site_wc_submiting()
{
    /*
     * Load data prodoct
     */
    $ajax = sanitize_text_field( isset($_POST['ajax']) ? $_POST['ajax'] : '' );
    if( $ajax > 100000 )
    {
        wc_get_template_part( 'section/products' );

        exit();
    }

    if( get_query_var('product') ) {
        $quantity = intval( isset($_POST['change_quantity']) ? $_POST['change_quantity'] : -1 );
        if( $quantity > -1 )
        {
            $cart_count = site_wc_cart_update_item_quantity( get_the_ID(), $quantity );

            die( $cart_count );
            exit();
        }
    }

    $address = strtolower( site__get( 'remove_address' ) );
    if( $address != '' && preg_match('/shipping/i', $address) )
    {
        $m = 'error';

        $customer_id = get_current_user_id();
        $i = (int) str_replace('shipping_', '', $address);
        if( $customer_id > 0 && $i > 0 )
        {
            $fields = get_user_meta( $customer_id );
            $c = 0;

            foreach( $fields as $key => $value ) {
                if( preg_match( '/^'.$address.'_/i', $key) ) {
                    $c++;
                    delete_user_meta( $customer_id, $key );
                }
            }

            $m = $c>0 ? 'success' : 'no-data';
        }
        
        wp_safe_redirect( add_query_arg( array( 'remove' => $m ), wc_get_endpoint_url( 'edit-address' ) ) );
        exit;
    }

    $product_id = intval( isset($_GET['add-to-cart']) ? $_GET['add-to-cart'] : 0 );
    if( $product_id > 0 )
    {
        //Add product to WooCommerce cart.
        if( isset($_GET['js-add']) ) {
            die('Add to cart successfull!');
        } else {
            $uri = explode('?', $_SERVER['REQUEST_URI']);

            wp_safe_redirect( esc_url( $uri[0] ) );
        }

        exit();
    }
}
add_action('wp', 'site_wc_submiting', 1);

function site_wc_cart_update_item_quantity( $product_id, $quantity )
{
    $cart = WC()->cart;

    $cart_count = 0;
    
    $add = true;

    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        if( $cart_item['product_id'] == $product_id ) {
            $add = false;
            if( $quantity>0 ) {
                $cart->set_quantity($cart_item_key, $quantity);

                $cart_count += $quantity;
            } else {
                $cart->remove_cart_item( $cart_item_key );
            }
        } else {
            $cart_count += (int) $cart_item['quantity'];
        }
    }

    if( $add ) {
        $cart->add_to_cart( $product_id, $quantity );

        $cart_count += $quantity;
    }

    return $cart_count;
}

function site_domain_email( $name = '' )
{
    return ( $name!='' ? $name .'@' : '' ) . 'vuahoanthien.com';
}

function site_check_phone_email( $email )
{
    return strpos( $email, site_domain_email() )>-1;
}

function site_wc_save_account_details( $user_id = 0 ) 
{
    // Check action update email
    $billing_email = sanitize_text_field( isset( $_POST['billing_email'] ) ? $_POST['billing_email'] : '' );
    if( $billing_email!='' ) {
        update_user_meta( $user_id, 'billing_email', $billing_email );
    }

    wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
    exit;
}
add_action( 'woocommerce_save_account_details', 'site_wc_save_account_details', 10, 1 );

function site_wc_disabling_wp_mail( $args )
{
    if( site_check_phone_email( $args['to'] ) ) {
        unset( $args['to'] );
    }

    return $args;
}
add_filter('wp_mail','site_wc_disabling_wp_mail', 10, 1);

function site_wc_cart_get_total()
{
    $total = (int) str_replace([".", ' '], '', strip_tags(WC()->cart->get_cart_total()));
    
    return $total;
}

function site_wc_get_account_formatted_address( $address = '' )
{
    if( $address != '' || strpos($address, '_')>-1 ) {
        $data = array();

        $fields = array(
            'first_name',
            'address_1',
            // 'country',
            // 'state',
            // 'phone',
        );
        
        $customer_id = get_current_user_id();

        foreach( $fields as $name ) {
            $data[] = get_user_meta($customer_id, $address . '_' . $name,  true);
        }
        
        return implode("<br/>", $data );
    }

    return wc_get_account_formatted_address( $address );
}

function site_wc_get_account_addresses( $customer_id = 0 )
{
    $addresses = array(
		// 'shipping' => __( 'Shipping address', 'woocommerce' ),
		// 'billing' => __( 'Billing address', 'woocommerce' ),
	);
    
    // $addresses = array();
    
    /*
    $i = 0;
    while( $i++<1000 ) {
        $address = $prefix . $i;

        $value = get_user_meta($customer_id, $address . '_first_name',  true);
        if( $value != '' ) {
            $addresses[ $address ] = __( 'Shipping address', 'woocommerce' );
        }
    }
    */
    
    global $wpdb;

    $prefix = 'shipping_';

    $q = "
        SELECT `umeta_id`,`meta_key`,`meta_value`
        FROM `vhd_usermeta`
        WHERE `user_id` = %s 
        AND `meta_key` LIKE 'shipping_%_first_name'
        GROUP BY `umeta_id`
        ORDER BY `meta_key`
    ";

    $items = $wpdb->get_results( $wpdb->prepare( $q, $customer_id) );

    foreach( $items as $item ) {
        $i = (int) str_replace($prefix, '', $item->meta_key );

        if( $i>0 ) {
            $addresses[ $prefix . $i ] = __( 'Shipping address', 'woocommerce' );
        }
    }

    return apply_filters( 'woocommerce_my_account_get_addresses', $addresses, $customer_id );
}

function site_wc_get_address_fields()
{
    return array(
        'first_name',
        'address_1',
        'phone',
    );
}

function site_wc_get_address_data( $address = '', $customer_id = 0 )
{
    $data = array();

    $fields = site_wc_get_address_fields();

    foreach( $fields as $name ) {
        $data[ $name ] = get_user_meta($customer_id, $address . '_' . $name,  true);
    }
    
    return $data;
}

function site_wc_product_sold_count( $product_id ) 
{
    global $wpdb, $site_product_sold;

    if( empty($site_product_sold) ) {
        $site_product_sold = [];
    } else if( isset($site_product_sold[ $product_id ]) ) {
        return $site_product_sold[ $product_id ];
    }

    $prefix = $wpdb->prefix . 'wc_';

    return $site_product_sold[ $product_id ] = (int) $wpdb->get_var( "
        SELECT SUM(a.`product_qty`)
        FROM `{$prefix}order_product_lookup` AS a
        JOIN `{$prefix}order_stats` AS b ON b.order_id = a.order_id
        WHERE a.`product_id` = $product_id 
        AND b.`status` = 'wc-completed'
    ");
}

function site_wc_checkout_order_processed( $order_id = 0, $posted_data = array(), $order = false )
{
    $customer_id = (int) get_current_user_id();

    if( $customer_id > 0 ) {
        $address_name = sanitize_text_field( isset($_POST['address-name']) ? $_POST['address-name'] : '' );

        if( $address_name!='' && preg_match('/^new/i', $address_name) ) {
            $new = (int) str_replace( 'new_', '', $address_name );

            if( $new == 0 ) return;

            $address = 'shipping_';
            
            foreach( site_wc_get_address_fields() as $name ) {
                $value = sanitize_text_field( isset($_POST[$address . $name]) ? $_POST[$address . $name] : '' );
                update_user_meta($customer_id, $address . $new . '_' . $name,  $value);
            }
        }
    }
    
    $billing_phone = sanitize_text_field( isset($_POST['billing_phone']) ? $_POST['billing_phone'] : '' );

    if( $billing_phone!='' ) {
        site_sms_send( $billing_phone, '%23'.kiotVietOrderId($order_id) );
    }
}
add_action('woocommerce_checkout_order_processed', 'site_wc_checkout_order_processed', 10, 3 );

/**
 * My Account > Orders template.
 *
 * @param int $current_page Current page number.
 */
function woocommerce_account_orders( $current_page ) {
    $current_page    = empty( $current_page ) ? 1 : absint( $current_page );

    /*
    $customer_orders = wc_get_orders(apply_filters(
        'woocommerce_my_account_my_orders_query',
        array(
            'customer' => get_current_user_id(),
            'page'     => 2,
            'paginate' => true,
            'limit' => -1
        )
    ));
    */

    global $wpdb;

    $statuses = array_keys( wc_get_order_statuses() );

    $query = "
        SELECT * FROM `{$wpdb->prefix}wc_order_stats` 
        WHERE `customer_id` = ( SELECT `customer_id` FROM `{$wpdb->prefix}wc_customer_lookup` WHERE `user_id` = %s ) 
        AND `status` IN ( '". implode("','", $statuses) ."' )
        LIMIT 0,30000
    ";
    
    $query = $wpdb->prepare( $query, get_current_user_id() );

    // file_put_contents(ABSPATH. '/a_p.json', $query );

    $items = $wpdb->get_results( $query );

    $orders = array();
    foreach( $items as $item ) {
        $orders[] = $item->order_id;
    }
    
    $customer_orders = (object) array(
        'items'     => $items,
        'total'     => count($orders),
        'orders'    => $orders
    );
    
    wc_get_template(
        'myaccount/orders.php',
        array(
            'current_page'    => absint( $current_page ),
            'customer_orders' => $customer_orders,
            'has_orders'      => 0 < $customer_orders->total,
        )
    );
}

function site_wc_send_sms_reset_password( $user_login, $key )
{
	$message = 'Ma don hang cua Quy Khach la 1234. Cam on Quy Khach da mua hang tai VuaHoanThien.com. Chi tiet LH 0813008839.';

	// if( $number!='' ) {
	// 	$message = str_replace('1234', $number, $message );
	// }
	
	$message = str_replace(' ','+', $message );
	
	file_put_contents( ABSPATH . '/a_t.txt', $user_login . ':' . $key );
	
    // return site_sms_api( $user_login, $message );
}
// add_action( 'retrieve_password_key', 'site_wc_send_sms_reset_password', 10, 2 );


/**
 * Modify the sale price for a specific product if it has the custom field "producttype" set to "gach".
 *
 * @param string $sale_price The original sale price.
 * @param WC_Product $product The product object.
 * @return string The updated sale price.
 */
function custom_modify_sale_price($price, $product) {
    // Check if the product has the custom field "producttype" set to "gach"
    $product_type = get_post_meta($product->get_id(), 'producttype', true);
    if ($product_type === 'gach') {
        //echo "yeah";
        $heso_quydoi = get_post_meta($product->get_id(), 'heso_quydoi', true);
        $regular_price = $product->get_regular_price();
        //$sale_price = $product->get_sale_price();
        // Calculate and set the new sale price based on some custom logic
        $old_price = $price;
        $new_sale_price = $price * $heso_quydoi; // Replace this with your actual calculated price

        // Create an associative array or an object to store both old and new prices
        $prices = array(
            'old' => $old_price,
            'new' => $new_sale_price,
        );

        // Add the variable to the product object for use in the template
        $product->custom_prices = $prices;

        //var_dump($product->custom_prices);

        return $new_sale_price;
    }

    return $price;
}
add_filter('woocommerce_product_get_price', 'custom_modify_sale_price', 10, 2);

function get_product_image_url() {
    global $product;

    if (has_post_thumbnail($product->get_id())) {
        $image_url = get_the_post_thumbnail_url($product->get_id(), 'full');
        return $image_url;
    }

    return '';
}

function add_og_image_meta_tag() {
    if (is_singular('product')) {
        $product_image_url = get_product_image_url();
        if (!empty($product_image_url)) {
            echo '<meta property="og:image" content="' . esc_url($product_image_url) . '" />';
        }
    }
}
add_action('wp_head', 'add_og_image_meta_tag');

function get_point_used_by_user($user_id) {
    global $wpdb;

    $order_ids = $wpdb->get_var("SELECT GROUP_CONCAT(DISTINCT post_id) 
        FROM {$wpdb->prefix}postmeta
        WHERE meta_key = '_customer_user' AND meta_value = $user_id
    ");

    if (empty($order_ids)) return 0;

    $points_used = $wpdb->get_var("SELECT SUM(meta_value)
        FROM {$wpdb->prefix}postmeta
        WHERE meta_key = 'points_used' AND post_id IN ($order_ids)
    ");

    return $points_used ?? 0;
}