<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;


?>
<div class="container user-form d-flex align-items-center justify-content-center">
  <div class="row justify-content-center py-3">
    <img class="mb-3 user-form-logo" src="<?php site_the_assets();?>images/logo/VHT-new-logo.svg" />
    <h2 class="text-center">Tạo mật khẩu mới</h2>

    <div class="col-12 col-md-8 col-xl-6 col-xxl-6">
      <a class="btn text-primary px-0 mb-3" href="<?php echo home_url();?>">
        <i class="bi bi-chevron-left"></i> Quay lại
      </a>

      <?php do_action( 'woocommerce_before_reset_password_form' );?>

      <form method="post" class="mb-3 needs-validation" novalidate disablednex >

        <div class="mb-3"><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce' ) ); ?></div><?php // @codingStandardsIgnoreLine ?>

        <div class="mb-3">
          <label class="form-label">Mật khẩu mới</label>
          <div class="input-group input-password">
            <input type="password" class="form-control" name="password_1" id="newPassword" autocomplete="off"
              minlength="6" maxlength="70"
              required>
              <!-- <input type="password" class="form-control" name="password_1" id="newPassword" autocomplete="off"
              minlength="6" maxlength="70"
              pattern="(?=.{8,})((?=.*\d)(?=.*[a-z])(?=.*[A-Z])|(?=.*\d)(?=.*[a-zA-Z])(?=.*[\W_])|(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_])).*"
              required> -->
            <label show-password>
              <i class="bi bi-eye"></i>
            </label>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Xác nhận mật khẩu</label>
          <div class="input-group input-password">
            <input type="password" class="form-control" name="password_2" id="confirmPassword"
              autocomplete="off" minlength="6" maxlength="70"
              
              required>
            <label show-password>
              <i class="bi bi-eye"></i>
            </label>
          </div>
        </div>
        <!-- pattern="(?=.{8,})((?=.*\d)(?=.*[a-z])(?=.*[A-Z])|(?=.*\d)(?=.*[a-zA-Z])(?=.*[\W_])|(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_])).*" -->
        <input type="hidden" name="reset_key" value="<?php echo isset($args['key']) ? esc_attr( $args['key'] ) : ''; ?>" />
        <input type="hidden" name="reset_login" value="<?php echo isset($args['login']) ? esc_attr( $args['login'] ) : ''; ?>" />

        <?php do_action( 'woocommerce_resetpassword_form' ); ?>

        <input type="hidden" name="wc_reset_password" value="true" />
        <button type="submit" class="btn btn-primary w-100" value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>"><?php esc_html_e( 'Save', 'woocommerce' ); ?></button>

        <?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

      </form>

      <p class="text-center">
        <small>Đặt lại mật khẩu của bạn sẽ đăng xuất bạn khỏi tất cả các thiết bị khác.</small>
      </p>

      <?php do_action( 'woocommerce_after_reset_password_form' ); ?>
    </div>

  </div>
</div>