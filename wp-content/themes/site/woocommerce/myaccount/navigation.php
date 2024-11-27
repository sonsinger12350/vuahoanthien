<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$currentURL = esc_url( home_url( $_SERVER['REQUEST_URI'] ) );
// esc_url( wc_get_endpoint_url( 'edit-address', $name ) );
// global $post;
// if ( $post ) {
//   echo $current_page_link = get_permalink( $post->ID );
//     // Use the $current_page_link variable as needed
// }

$account_page_id = get_option('woocommerce_myaccount_page_id');
$account_page_url = get_permalink($account_page_id);

$wishlist_url = home_url( 'san-pham-yeu-thich-cua-ban' );

?>
<div class="d-none d-lg-block col-lg-2 col-sidebar-product">
  <div class="section-bg">
    <ul class="nav navbar-nav">
      <li class="nav-item">
        <a class="nav-link px-0 d-flex align-items-center <?php echo ($currentURL == $account_page_url) ? 'active' : ''; ?>" href="<?php echo esc_url( site_account_url() ); ?>">
          <i class="bi bi-person-fill me-2"></i> Thông tin tài khoản
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link px-0 d-flex align-items-center <?php echo is_wc_endpoint_url( 'orders' ) ? 'active' : ''; ?>" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ));?>">
          <i class="bi bi-calendar3-range-fill me-2"></i> Quản lý đơn hàng
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link px-0 d-flex align-items-center <?php echo is_wc_endpoint_url( 'edit-address' ) ? 'active' : ''; ?>" href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ));?>">
          <i class="bi bi-geo-alt-fill me-2"></i> Sổ địa chỉ
        </a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link px-0 d-flex align-items-center" href="<?php //echo esc_url( wc_get_account_endpoint_url( 'payment-method' ));?>">
          <i class="bi bi-credit-card-2-front-fill me-2"></i> Thông tin thanh toán
        </a>
      </li> 
      <li class="nav-item">
        <a class="nav-link px-0 d-flex align-items-center <?php //echo is_wc_endpoint_url( 'watched' ) ? 'active' : ''; ?>" href="<?php echo esc_url( wc_get_account_endpoint_url( 'watched' ));?>">
          <i class="bi bi-eye-fill me-2"></i> Sản phẩm bạn đã xem
        </a>
      </li>-->
      <li class="nav-item">
        <a class="nav-link px-0 d-flex align-items-center <?php //echo is_wc_endpoint_url( 'wishlist' ) ? 'active' : ''; ?>" href="<?php echo $wishlist_url;//esc_url( wc_get_account_endpoint_url( 'wishlist' ) ); ?>">
          <i class="bi bi-heart-fill me-2"></i> Sản phẩm yêu thích
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link px-0 d-flex align-items-center <?php echo is_wc_endpoint_url( 'points' ) ? 'active' : ''; ?>" href="<?php echo esc_url( wc_get_account_endpoint_url( 'points' ) ); ?>">
        <i class="bi bi-star-fill me-2"></i> Điểm tích lũy
        </a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link px-0 d-flex align-items-center" href="<?php //echo site_account_url();?>later/">
          <i class="bi bi-cart-fill me-2"></i> Sản phẩm mua sau
        </a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link px-0 d-flex align-items-center <?= ($currentURL == wc_get_account_endpoint_url('kho-qua-tang')) ? 'active' : ''; ?>" href="<?= wc_get_account_endpoint_url('kho-qua-tang') ?>">
        <i class="bi bi-gift-fill me-2"></i> Kho quà tặng
        </a>
      </li>
    </ul>
  </div>
</div>
<?php
/*
do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation">
	<ul class="list-unstyled">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); */ ?>
