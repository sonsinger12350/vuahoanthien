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



//$list = explode( '<!--BREAK-->', $term->description );
//$list = explode( '<!--BREAK-->', $term->description );



//var_dump($list_hot_brand);


?>

<div class="bg-light list-product-page hotsale-page">
    <div class="container">
        <?php // ?>
        <div class="row" id="top">
          <?php
            get_template_part( 'sidebar' );
          ?>

          <div class="col-12 col-lg-10">
            <div class="section-bg">

              <div class="bg-light d-flex flex-column p-3">

                <?php            

                  

                  wc_get_template_part( 'archive/product-filter' );

                  

                  wc_get_template_part( 'section/products' , array('layout'=> 'hot-sale', 'product_limited'=>40) );

                

                ?>          

              </div>
            </div>  

          </div>

        </div>
        <?php // ?>
    </div>    
<?php

    
    

    // get_template_part( 'parts/home/special', 'buy', array('no_button'=>1, 'product_limited'=>50) );



    // wc_get_template_part( 'hotsale/info' );



    // $lines = explode("\n", trim( $term->description ) );


    // get_template_part( 'woocommerce/hotsale/products', '', array(

    //     'title' => $lines[0],

    //     'desc' => $lines[1]

    // ));



    // //$brands = get_field('top_brands', $term->taxonomy . '_' . $term->term_id );
    // $list_hot_brand = get_field('list_hot_brand' , $term->taxonomy . '_' . $term->term_id );


    // if( $list_hot_brand == false ) {

    //     $list_hot_brand = get_terms(array(

    //         'number' => 1,

    //         'taxonomy' => 'product-brand',

    //     ));     

    // }

    

    // if( count($list_hot_brand)>0 ) {

    //     //$brand = $brands[0];

    //     //var_dump($brand);

    //     //$lines = explode("\n", trim( $list[1] ));

    //     foreach( $list_hot_brand as $brand_item ) {
    //         //var_dump($brand_item["brand_name"]->term_id);
            
    //         get_template_part( 'woocommerce/hotsale/products', '', array(

    //             'title' => $brand_item["brand_sub_title"], //'Khuyến mãi ngày hè',
    //             'desc' => $brand_item["brand_title"],
    //             'saleoff' => $brand_item["sale_value"],
    //             'cat' => $brand_item["brand_name"],
    //             'idname' => $brand_item["id_name"],

    //         ) );
    //     }



        

    // }



?>

<p class="d-none"><?php // var_dump( $top_brands );?></p>

</div>

<?php



get_footer();