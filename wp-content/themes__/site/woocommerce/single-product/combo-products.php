<?php

$my_product = wc_get_product();
$combo_id = (int) get_field('combo_id');
if( $combo_id>0 ){
  $combo_products = get_field('combo_products', $combo_id);
  $combo = wc_get_product($combo_id);
} else {
  $combo_products = get_field('combo_products');
  // $combo_products = get_posts(array(
  //   'post_type' => 'product',
  //   'posts_per_page' => 5,
  // ));
  $combo = $my_product;
}

if( is_array($combo_products) && count($combo_products)>1 ):
  if( $combo_id>0 ) {
    $list = [array()];
    foreach( $combo_products as $i => $p ) {
      if( $p->ID == $my_product->get_id() ) {
        $list[0] = $p;
      } else {
        $list[] = $p;
      }
    }
    $combo_products = $list;
  }
?>
<div class="container mb-5 py-3">
  <h2 class="section-header">
    <span>Mua kèm deal sốc</span>
  </h2>
  <div class="row">
    <div class="col-10">
      <div class="row">
        <?php foreach( $combo_products as $i => $p ): 
          $product = wc_get_product( $p->ID );

          site_setup_product_data( $product );
        ?>
        <div class="col">
          <?php get_template_part( 'woocommerce/archive/product', 'item', array( 'no_footer' => 1 ) );?>
        </div>
        <?php if( $i == 0 ):?>
        <div class="col-1 m-auto text-center">
          <i class="bi bi-plus fs-1"></i>
        </div>
        <?php endif; ?>
        <?php endforeach; site_reset_product_data(); ?>
      </div>
    </div>
    <div class="col-2 border-start m-auto">
      <!-- <p><?php echo $combo->get_title() ?></p> -->
      <p>Giá combo: <b class="text-danger"><?php echo site_wc_price($combo->get_price());?>đ</b></p>
      <p>Tiết kiệm <b><?php site_wc_the_discount_percent( $combo )?>%</b></p>
      <p><a href="<?php echo $combo->add_to_cart_url();?>" class="btn btn-outline-primary rounded ms-1 p-2 fw-normal flex-grow-1">Mua Combo</a></p>
    </div>
  </div>
</div>
<?php

endif;