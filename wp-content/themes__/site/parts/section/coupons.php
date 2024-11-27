<?php





$page_on_front = (int) get_option( 'page_on_front', 0 );

$coupon_banner = (int) get_field('coupon_banner', $page_on_front);
$coupon_banner_mb = (int) get_field('coupon_banner_mobile', $page_on_front);



$img = wp_get_attachment_image_url( $coupon_banner, $size = 'full' );
$img_mb = wp_get_attachment_image_url( $coupon_banner_mb, $size = 'full' );

$page_template = basename( get_page_template() );


if( $img!='' && $page_template != 'coupon.php'):

?>

<div class="p-3 mb-2 bg-light my-coupons">

  <div class="container">

    <a href="<?php echo site_coupon_url();?>">

      <picture>
          <source media="(max-width:767px)" srcset="<?php echo $img_mb; ?>">
          <source media="(min-width:768px)" srcset="<?php echo $img; ?>">
          
          <img src="<?php echo $img; ?>" class="w-100" alt="" />

        </picture>
      <?php //echo $img;?>

    </a>

  </div>

</div>

<?php

endif;