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


$cat = get_queried_object();
if( isset($cat->taxonomy) && ( $cat->taxonomy == 'product_tag' || get_term_meta($cat->term_id, 'display_type', true) == 'both' ) )
{
  if( $cat->taxonomy == 'product_tag' ) {
    wc_get_template_part( 'archive/tag' );
  } else {
    wc_get_template_part( 'archive/hot-sale' );
  }
}
else
{
  wc_get_template_part( 'archive/default' );
}
