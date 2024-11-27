<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.2
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="container user-form d-flex align-items-center justify-content-center">
  <div class="row justify-content-center py-3">
    <img class="mb-3 user-form-logo" src="<?php site_the_assets();?>images/logo/VHT-new-logo.svg" />
    <h2 class="text-center">Quên mật khẩu</h2>

    <div class="col-12 col-md-8 col-xl-6 col-xxl-6">
      <a class="btn text-primary px-0 mb-3" href="<?php echo home_url();?>">
        <i class="bi bi-chevron-left"></i> Quay lại
      </a>

      <?php do_action( 'woocommerce_before_lost_password_form' );?>

      <form method="post" class="mb-3 needs-validation" novalidate disablednex >
        
        <!-- <div class="mb-3">
          <label for="phoneInput" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
          <input type="tel" name="user_login" class="form-control" id="phoneInput" 
            minlength="10" maxlength="10" pattern="^[0-9]*$" required />
        </div> -->

        <div class="mb-3">
          <label for="emailInput" class="form-label">Email</label>
          <input type="email" class="form-control" id="emailInput" name="user_login" autocapitalize="off"
            pattern="[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)" required>
        </div>
        
        <?php do_action( 'woocommerce_lostpassword_form' ); ?>

        <input type="hidden" name="wc_reset_password" value="true" />
        <button type="submit" class="btn btn-primary w-100" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></button>
        <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
      </form>

      <p class="text-center">
        <small>Đặt lại mật khẩu của bạn sẽ đăng xuất bạn khỏi tất cả các thiết bị khác.</small>
      </p>

      <?php do_action( 'woocommerce_after_lost_password_form' ); ?>
    </div>

  </div>
</div>