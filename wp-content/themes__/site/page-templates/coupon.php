<?php
/**
 * Template Name: Coupon
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

$coupons = site_get_coupons(array('sort'=>'minimum_amount'));


?>
<div class="coupons-page"> <!-- bg-grey-lightest -->
  <div class="container">
    <h4 class="pb-2 pt-2"><?php the_title();?></h4>
    <div class="row list-my-coupons mb-4">
      <?php 
      foreach( $coupons as $coupon ): 
        $data = $coupon->get_data(); 
        $img = get_the_post_thumbnail( $coupon->id, 'full', array( 'alt' => $coupon->get_code() ) );
        //var_dump($data["description"]);
      ?>
      <!-- <div class="col col-sm-6 mb-2" data-bs-toggle="modal" data-bs-target="#modal-save-coupon"> -->
      <div class="col col-sm-6 mb-2">
        <?php if( $img!='' ): ?>
        <div class="image" data-toggle="tooltip" data-placement="top" title="<?php echo $data["description"]; ?>">
          <?php echo $img;?>
          <small class="d-none"><?php echo $coupon->get_code();?></small>
        </div>
        <?php else: ?>
        <div class="row">
          <div class="col-3">
            <div class="d-flex flex-column align-items-center justify-content-center">
              <i class="bi bi-ticket-perforated"></i>
              <small><?php echo $coupon->get_code();?></small>
            </div>
          </div>
          <div class="col-9 border-0 border-dashed border-start">
            <div class="fs-5 fw-bold">Giảm <?php echo site_wc_price($data['amount']);?><sub>đ</sub></div>
            <div>Đơn hàng từ <?php echo site_wc_price($data['minimum_amount']);?><sub>đ</sub></div>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Popup Save Coupon Success -->
<!-- <div class="modal fade modal-auto-clear" id="modal-save-coupon" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="pt-4">
          <p class="stick-img"><img src="<?php //site_the_assets();?>images/icons/stick-green-small-icon.png" /></p>
          <p><strong>Lưu thành công</strong></p> 
          <p>Bạn có thể xem và sử dụng mã khuyến mãi ở trang Giỏ hàng.</p>
        </div>
      </div>
    </div>
  </div>
</div> -->

<?php

get_footer();