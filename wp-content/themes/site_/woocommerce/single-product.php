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

site_product_viewed( get_the_ID() );

site_body_class_add( 'product-detail' );

get_header();

$terms = get_the_terms( get_the_ID(), 'product_cat' );

//var_dump($terms);
// wc_get_product_term_ids( get_the_ID(), 'product_cat' );
//echo $terms[0]->name;

if($terms[0]->term_id == 100) {
  $items = site_wc_get_terms_to_root($terms[1]);  
} else {
  $items = site_wc_get_terms_to_root($terms[0]);
}
//var_dump($items);

$breadcrumbs = [];

foreach( $items as $term ) {
  $breadcrumbs[] = array(
    'link' => get_term_link($term->term_id),
    'name' => $term->name
  );
}

?>
<style>
/* .btn-favorite:hover a {
  display: none !important;
}
.btn-favorite:hover a:nth-child(2) {
  display: inline !important;
} */
.explore-more .btn-viewmore .text-2,
.explore-more .btn-viewmore[aria-expanded="true"] .text-1{
  display: none;
}
.explore-more .btn-viewmore[aria-expanded="true"] .text-2{
  display: inline-block;
}
.form-suggest-text .btn-text{
  display: inline-block;
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 5px 10px;
  margin: 0 10px 10px 0;
  cursor: pointer;
}
.form-suggest-text .btn-text.active{
  background-color: var(--color-primary);
  color: #fff;
}
.single_variation_wrap .woocommerce-variation-price,
.variations .reset_variations {
  display: none;
}
</style>
<div class="bg-light">
    <div class="container">
  <?php
    get_template_part( 'woocommerce/section/breadcrumb', '', array( 'breadcrumbs' => $breadcrumbs, 'title' => false ) ); ?>
    </div>  
  <?php 
    while ( have_posts() ) : the_post(); ?>
       <div class="container">
       <?php  wc_get_template_part( 'single-product/summary' ); ?>
       </div>
     <?php
      wc_get_template_part( 'single-product/bundled-products' );

      wc_get_template_part( 'single-product/combo-products' );
      ?>
      <div class="section">
        <div class="container">
        <?php
        wc_get_template_part( 'single-product/info' );
        ?>
        </div>
      </div>  
        <?php        
          get_template_part( 'woocommerce/section/related-products', '', array( 'living' => 'space' ) );
        ?>
        <?php        
          wc_get_template_part( 'section/related-products' );
        ?>
      
      <div class="container">
        <?php wc_get_template_part( 'section/product-viewed' ); ?>
      </div> 

      <?php

        wc_get_template_part( 'section/product-brand' ); ?>

       

      <?php  
        wc_get_template_part( 'section/related-search' );
      
      ?>

      
      
      <?php
      
      wc_get_template_part( 'single-product/review' );
    
    endwhile; // end of the loop.
    
    // get_template_part( 'woocommerce/section/explore-more', '', array( 'term' => $terms[0] ) );
      
  ?>
</div>
<?php

get_footer();