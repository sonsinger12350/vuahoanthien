<?php
/**
 * Template Name: Checkout
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

?>
<div class="bg-grey-lightest">
  <div class="container">
    <?php
      // Start the Loop.
      while ( have_posts() ) : the_post();
        
        WC_Shortcode_Checkout::output(array());

      endwhile;
    ?>
  </div>
</div>
<?php

get_footer();