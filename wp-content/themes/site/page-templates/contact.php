<?php

/**

 * Template Name: Contact

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

$field_address = get_field("address");
$field_phone = get_field("phone");
$field_mail = get_field("mail");
$field_facebook = get_field("facebook");
$field_facebooklink = get_field("facebook_link");
$field_link_google_map = get_field("link_google_map");

?>

<div class="contact-page">

  <div class="container">

    <h1 class="title"><?php the_title(); ?></h1>

    <div class="row product-list product-wishlist flex-nowrap flex-md-wrap">
        <div class="col-12 col-md-6">
          <div class="col-left">
            <div class="contact-list-item">
              <div class="contact-item">
                <i class="bi bi-house-fill"></i>
                <span><?php echo $field_address; ?></span>
              </div>
              <div class="contact-item">
                <i class="bi bi-telephone-fill"></i>
                <span><?php echo $field_phone; ?></span>
              </div>
              <div class="contact-item">
                <i class="bi bi-envelope-fill"></i>
                <span><?php echo $field_mail; ?></span>
              </div>
              <div class="contact-item">
                <i class="bi bi-facebook"></i>
                <span><a href="<?php echo $field_facebooklink; ?>" target="_blank"><?php echo $field_facebook; ?></a></span>
              </div>
            </div>
            <div class="contact-map">
              <?php echo $field_link_google_map; ?>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="col-right">
            <div class="desc">
              <?php
              //ad_breadcrumbs();
              
              // Start the Loop.
              while ( have_posts() ) : the_post();
              
                // Include the page content template.
                the_content();


                
              
              endwhile;
              
              ?>
            </div>
            <div class="form-box">
              <?php echo do_shortcode('[contact-form-7 id="2611" title="Contact form 1"]'); ?>
            </div>
          </div>
        </div>  
    </div>

  </div>

</div>

<?php



get_footer();