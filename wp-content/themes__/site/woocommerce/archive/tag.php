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



get_header();



$term = get_queried_object();


// $args = array(
//     'post_type' => 'product',
//     'tax_query' => array(
//         array(
//             'taxonomy' => $term->taxonomy,
//             'field'    => 'term_id',
//             'terms'    => $term->term_id,
//         ),
//     ),
//     'posts_per_page' => 20,
// );

// $query = new WP_Query( $args );



?>

<div class="bg-light list-product-page brandproduct-page">

  <?php

    

    get_template_part( 'parts/home/special', 'buy', array('no_button'=>1) );



    wc_get_template_part( 'hotsale/info' );

  ?>

  <div class="container">

    <h2 class="section-header border-0">

      <span>Tìm kiếm theo:</span>

      <b class="fs-2 fw-800"><?php echo $term->name;?></b>

    </h2>

    <div class="product-list flex-nowrap flex-md-wrap">

      <?php

        if ( woocommerce_product_loop() && wc_get_loop_prop( 'total' ) ) :
          $counter = 0;

          while ( have_posts() ) : the_post();
              if ( $counter < 30 ) : 
      ?>    

            <div class="col-6 col-md-4 col-lg-2 mb-4 col-product-item">

              <?php 

                wc_get_template_part( 'archive/product', 'item' );

              ?>

            </div>

      <?php
            else :

                  break; // Break the loop after 30 products have been displayed

            endif;

            $counter++;

          endwhile;

        endif;

      ?>

    </div>

  </div>

</div>

<?php



get_footer();