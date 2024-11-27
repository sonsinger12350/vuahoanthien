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

$breadcrumbs = [];

$is_cat_page = true;

$cat = get_queried_object();
if( isset($cat->taxonomy) ) {
  $title = $cat->name;

  $items = site_wc_get_terms_to_root($cat);
  foreach( $items as $term ) {
    if( $term->term_id == $cat->term_id ) {
      break;
    }
    $breadcrumbs[] = array(
      'link' => get_term_link($term->term_id),
      'name' => $term->name
    );
  }
} else {
  // wp_redirect( home_url(), 301 );
  // exit();
  $title = 'Shop';
  $is_cat_page = false;
}

// single_term_title('',false)

site_body_class_add( 'brand-collections layout-cat-lv-2' );

get_header();

?>
<div class="bg-light">
  <div class="container">
    <?php

      get_template_part( 'woocommerce/section/breadcrumb', '', array( 
        'breadcrumbs' => $breadcrumbs, 
        'title' => $title
      ) );
      
      wc_get_template_part( 'section/banner-sponsors' );

      wc_get_template_part( 'section/product-highlight' );

      wc_get_template_part( 'section/brand-highlight' );
    
    ?>
    <div class="row">

      <?php
        get_template_part( 'sidebar' );
      ?>

      <div class="col-12 col-lg-10">
        <div class="bg-light d-flex flex-column p-3">
          <?php
            
            wc_get_template_part( 'archive/product-filter' );
            
            wc_get_template_part( 'section/products' );

            // wc_get_template_part( 'section/searched-viewed' );
            
            // wc_get_template_part( 'section/related-search' );

            // wc_get_template_part( 'section/search-feedback' );

          ?>
        </div>
      </div>
    </div>
    <?php

      if( $is_cat_page ) {
        wc_get_template_part( 'section/hot-deal-today' );

        get_template_part( 'woocommerce/section/suggest', '', array('top'=>'trend') );
      }

      wc_get_template_part( 'section/combo' );

      wc_get_template_part( 'section/blog' );

    ?>
  </div>
  <?php

    // if( $is_cat_page ) {
    //   wc_get_template_part( 'section/suggest' );
    // }

    wc_get_template_part( 'section/product-viewed' );

    // if( $is_cat_page ) {
    //   wc_get_template_part( 'section/explore-more' );
    // }

    get_template_part( 'parts/home/trend-search');
  
  ?>
</div>
<?php

get_footer();