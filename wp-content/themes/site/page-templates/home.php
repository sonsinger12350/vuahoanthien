<?php
/**
 * Template Name: Home
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

get_template_part( 'parts/home/product-ad');
get_template_part( 'parts/home/special-buy');
get_template_part( 'parts/home/category-special');
get_template_part( 'parts/home/category-list');
get_template_part( 'parts/home/banner-sponsors');
get_template_part( 'parts/home/suggest');
get_template_part( 'parts/home/product-for-you');

?>
<div class="container">
<?php
    wc_get_template_part( 'section/blog' );
?>
</div>
<?php

//get_template_part( 'parts/home/top-search');
get_template_part( 'parts/home/top-search-products');
get_template_part( 'parts/home/trend-search');
?>
<div class="container">
    <?php wc_get_template_part( 'section/product-viewed' ); ?>
</div>    
<?php get_footer();
