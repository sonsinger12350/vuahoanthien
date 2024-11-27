<?php

/**

 * The Template for displaying product archives, including the main shop page which is a post type archive

 *

 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.

 *

 * HOWEVER, on occasion WooCommerce will need to update template files and you

 * (the theme developer) will need to copy the new files to your theme to

 * maintain compatibility. We try to do this as little as possible, but it does

 * happen. When this occurs the version of the template file will be bumped and

 * the readme will list any important changes.

 *

 * @see https://docs.woocommerce.com/document/template-structure/

 * @package WooCommerce/Templates

 * @version 3.4.0

 */



defined( 'ABSPATH' ) || exit;



// add_filter( 'loop_shop_per_page', 'return__10000', 30 );



get_header();



$term = get_queried_object();



$list = explode( '<!--BREAK-->', $term->description );



?>

<div class="bg-light list-product-page hotsale-page">

<?php

    

    get_template_part( 'parts/home/special', 'buy', array('no_button'=>1) );



    wc_get_template_part( 'hotsale/info' );



    $lines = explode("\n", trim( $list[0] ) );



    get_template_part( 'woocommerce/hotsale/products', '', array(

        'title' => $lines[0],

        'desc' => $lines[1]

    ));



    $brands = get_field('top_brands', $term->taxonomy . '_' . $term->term_id );



    if( $brands == false ) {

        $brands = get_terms(array(

            'number' => 1,

            'taxonomy' => 'product-brand',

        ));     

    }

    

    if( count($brands)>0 ) {

        $brand = $brands[0];



        $lines = explode("\n", trim( $list[1] ));



        get_template_part( 'woocommerce/hotsale/products', '', array(

            'title' => $lines[0], //'Khuyến mãi ngày hè',

            'desc' => str_replace('BRAND_NAME', $brand->name, isset($lines[1]) ? $lines[1] : '' ),

            'cat' => $brand

        ) );

    }



?>

<p class="d-none"><?php // var_dump( $top_brands );?></p>

</div>

<?php



get_footer();