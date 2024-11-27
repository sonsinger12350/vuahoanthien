<?php
/**
 * Template Name: Lost Password
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

$redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : home_url();

$user_id = (int) get_current_user_id();
if( $user_id > 0 ) {
  wp_redirect( $redirect_to );
  exit();
}

global $no_nav, $no_coupon;

$no_nav = 1;
$no_coupon = 1;

get_header();

?>
<div class="container user-form d-flex align-items-center justify-content-center">
  <div class="row justify-content-center py-3">
    <img class="mb-3 user-form-logo" src="<?php site_the_assets();?>images/logo/VHT-new-logo.svg" />
    <h2 class="text-center">Đặt lại mật khẩu</h2>

    <div class="col-12 col-md-8 col-xl-6 col-xxl-6">
      <a class="btn text-primary px-0 mb-3" href="<?php echo home_url("/dang-nhap");?>">
        <i class="bi bi-chevron-left"></i> Quay lại
      </a>

      <?php do_action( 'woocommerce_before_lost_password_form' );?>

      <form method="post" class="mb-3 needs-validation lost-password-form" action="<?php the_permalink();?>">
        
        <div class="mb-3">
          <label for="phoneInput" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
          <input type="tel" name="user_phone" class="form-control" id="phoneInput" 
            minlength="10" maxlength="10" pattern="[0]{1}[0-9]{9}" required />
          <!-- <input type="tel" name="user_phone" class="form-control" id="phoneInput" 
            minlength="10" maxlength="10" pattern="^[0-9]*$" required /> -->
        </div>
        
        <button type="submit" class="btn btn-primary w-100" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></button>
        <?php wp_nonce_field( 'site-lost-password', 'site_lost_password' ); ?>
      </form>
      
      <p class="text-center">
        <small>Đặt lại mật khẩu của bạn sẽ đăng xuất bạn khỏi tất cả các thiết bị khác.</small>
      </p>

    </div>
  </div>
</div>
<!-- POPUP SUCCESS -->
<div class="modal fade modal-reset-password-success" id="resetpasswordSuccess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="header-top">
          <p class="row-icon"><img class="" src="<?php site_the_assets();?>images/icons/icon-key.png" /></p>
          <h5 class="modal-title border-0" id="discountCodeLabel">Reset mật khẩu mới thành công</h5>
        </div>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Mật khẩu mới sẽ được gửi về số điện thoại của quý khách.</p>
        <p>Vui lòng kiểm tra tin nhắn.</p>
      </div>
    </div>
  </div>
</div>
<!-- END POPUP SUCCESS -->
</main>
<?php 
get_template_part( 'parts/popup/noti' );

wp_footer(); ?>
</body>
</html>