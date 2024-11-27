<?php
/**
 * Template Name: Login
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

// $redirect_to = esc_url( isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : home_url() );

$redirect_link = esc_url( isset( $_REQUEST['redirect_link'] ) ? $_REQUEST['redirect_link'] : home_url() );
//$redirect_to = home_url();
$logout = sanitize_text_field( isset( $_REQUEST['logout'] ) ? $_REQUEST['logout'] : '' );

$user_id = (int) get_current_user_id();
if( $user_id > 0 ) {

  if( $logout!='' ) {
    wp_logout();
  } 

  wp_redirect( $redirect_link );
  exit();
}

global $no_nav, $no_coupon;

$no_nav = 1;
$no_coupon = 1;

get_header();

?>
<div class="container user-form d-flex align-items-center justify-content-center">
  <div class="row justify-content-center py-3">
    <img class="mb-3 user-form-logo" src="<?php site_the_assets();?>images/logo/VHT-new-logo.svg"/>
    <h2 class="text-center">Đăng nhập</h2>

    <div class="col-12 col-md-8 col-xl-6 col-xxl-6">
      <a class="btn text-primary px-0 mb-3" href="<?php echo home_url();?>">
        <i class="bi bi-chevron-left"></i> Quay lại
      </a>

      <form class="mb-3 needs-validation" novalidate disablednex name="loginform" id="loginform" method="post">

        <?php do_action( 'woocommerce_before_customer_login_form' ); ?>
        
        <div class="mb-3">
          <label for="phoneInput" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
          <input type="tel" name="username" class="form-control" id="phoneInput" 
            minlength="10" maxlength="10" pattern="^[0-9]*$" required />
        </div>

        <div class="mb-3">
          <label for="user_pass" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
          <div class="input-password">
            <input type="password" class="form-control" id="user_pass" name="password" autocomplete="off" required>
            <label show-password>
              <i class="bi bi-eye"></i>
            </label>
          </div>
        </div>

        <div class="mb-3 form-check">
          <label class="form-check-label" for="keepSignin">
            Giữ trạng thái đăng nhập
          </label>
          <input type="checkbox" name="rememberme" value="forever" checked class="form-check-input" id="keepSignin">
        </div>

        <button type="submit" class="btn btn-primary w-100" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">Đăng nhập</button>
        <input type="hidden" name="redirect_link" value="<?php echo $redirect_link;?>">
        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

        <?php do_action( 'woocommerce_after_customer_login_form' ); ?>
      </form>
      <p><a class="btn forget-pass" href="/quen-mat-khau/">Quên mật khẩu?</a></p>

      <p class="text-center form-divider">
        Bạn chưa có tài khoản?
      </p>

      <a href="<?php echo site_register_url()?>" class="btn btn-outline-primary w-100 mb-3">Đăng ký tài khoản mới</a>
      
      <p class="text-center">
        <small>
          Bằng việc chọn "Tạo tài khoản", bạn đã đồng ý với <a class="text-primary" href="https://vuahoanthien.com/dieu-khoan-su-dung/" target="_blank">Chính sách, quy định và yêu cầu của chúng tôi</a>.
        </small>
      </p>
    </div>

  </div>
</div>
<button class="btn btn-scroll-top"><i class="bi bi-arrow-up"></i></button>
<?php wp_footer(); ?>
</body>
</html>