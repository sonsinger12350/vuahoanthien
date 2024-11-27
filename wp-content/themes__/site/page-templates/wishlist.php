<?php

/**

 * Template Name: WishList

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



get_header();



$products = array();



$items = site_wcwl_get_products();

foreach( $items as $item ) {

  $products[] = wc_get_product( $item->get_product_id() );

}

//$user_id = (int) get_current_user_id();

?>

<div class="container wishlist-inAccount">
  <?php get_template_part( 'woocommerce/section/breadcrumb' ); ?>
  <div class="row woocommerce">
    <?php if (is_user_logged_in()): ?>
      <?php do_action( 'woocommerce_account_navigation' );  ?>
    <?php endif ?>
  

    <div class="col-12 <?php echo (is_user_logged_in())?"col-lg-10":""; ?> col-account-info">
        <div class="section-bg">


            <div class="wishlist-page <?php echo (count($products)>4)?"list-long":""; ?>">

                  
                <h2 class="pb-2 pt-2 mg-top-0 section-header border-0"><span><?php the_title();?></span></h2>

                
                
                <?php //if (count($products)>0): ?>
                  <?php /* ?>
                  <div class="row product-list product-wishlist flex-nowrap flex-md-wrap">

                      <?php foreach( $products as $product ): site_setup_product_data( $product ); ?>

                      <div class="col-6 col-md-4 col-lg w-lg-20 mb-3">

                        <?php wc_get_template_part( 'archive/product', 'item' ); ?>

                      </div>

                      <?php endforeach; site_reset_product_data(); ?>
                  </div>
                  <?php */ ?>  
                  <?php
                  // Use the do_shortcode function to add the shortcode
                  echo do_shortcode('[yith_wcwl_wishlist]');
                  ?> 
                <?php //else: ?>  
                  <!-- <div class="desc"><?php //the_content(); ?></div>  -->
                <?php //endif; ?>
                 
                     
            
            </div>
       </div>
    </div>        
</div>

<?php



get_footer();