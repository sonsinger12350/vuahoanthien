<?php

if( get_current_user_id() == 0 ) 
{
  $redirect_to = isset($_SERVER['REDIRECT_URL']) ? urlencode( $_SERVER['REDIRECT_URL'] ) : '';
  
  wp_redirect( site_login_url() .'?redirect_to=' . $redirect_to );
  exit();
}

get_header();

?>
<div class="container">
  <?php
    // Start the Loop.
    while ( have_posts() ) : the_post();

      get_template_part( 'woocommerce/section/breadcrumb' );
      
      ob_start();

      the_content();

      echo str_replace('<div class="woocommerce">', '<div class="row woocommerce">', ob_get_clean() );
      
    endwhile;
  ?>
</div>
<?php

get_footer();